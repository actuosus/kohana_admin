<?php defined('SYSPATH') OR die('No direct access allowed.');

class Auth_Controller extends Template_Controller {
	
	public $template = 'login';
	public $auto_render = TRUE;
	
	public function __construct()
	{
		parent::__construct();
		
		// Load sessions, to support logins
		$this->session = Session::instance();
		
		if(Auth::instance()->logged_in())
		{
			// Set the current user
			$this->user = $_SESSION['auth_user'];
		}
	}
	
	public function index()
	{
		$view = new View('admin/login');
		$this->template->content = '';
	}
	
	public function login()
	{
		$this->template->title = 'Login';
		$this->template->toolbar = '';
		$this->template->navigation = '';
		$this->template->content = View::factory('login')
			->bind('post', $post)
			->bind('errors', $errors);

		// Different type of accounts in AD
		define ('ADLDAP_NORMAL_ACCOUNT', 805306368);
		define ('ADLDAP_WORKSTATION_TRUST', 805306369);
		define ('ADLDAP_INTERDOMAIN_TRUST', 805306370);
		define ('ADLDAP_SECURITY_GLOBAL_GROUP', 268435456);
		define ('ADLDAP_DISTRIBUTION_GROUP', 268435457);
		define ('ADLDAP_SECURITY_LOCAL_GROUP', 536870912);
		define ('ADLDAP_DISTRIBUTION_LOCAL_GROUP', 536870913);
		
		$post = Validation::factory($_POST)
			->pre_filter('trim')
			->add_rules('username', 'required', 'length[4,127]')
			->add_rules('password', 'required');

		if ($post->validate())
		{
			$user = ORM::factory('user', $post['username']);
			
			if ( ! $user->loaded)
			{
				$ldap_host = "ldap://ferrum.at.webstein.ru/";
				$ldaprdn  = 'webstein_admin@at.webstein.ru';
				$ldappass = 'websteinpass';
				$ldap_bind = ldap_connect($ldap_host) OR die('First fuck');
				//set some ldap options for talking to AD
				ldap_set_option($ldap_bind, LDAP_OPT_PROTOCOL_VERSION, 3);
				ldap_set_option($ldap_bind, LDAP_OPT_REFERRALS, 0);
				$bind_state = ldap_bind($ldap_bind, $ldaprdn, $ldappass) OR die('Fuck');
				// $filter = 'sAMAccountName=actuosus,cn=Users,dc=at,dc=webstein,dc=at';

				if ($bind_state)
				{
					$search = $post['username'];
					$filter = "(&(objectClass=user)(sAMAccountType=". ADLDAP_NORMAL_ACCOUNT .")(objectCategory=person)(cn=".$search."))";
					$username = $post['username'];
					$filter = "samaccountname=".$username;
					$fields = array("samaccountname","mail","memberof","department","displayname","telephonenumber","primarygroupid");
					$dn = 'CN=Users,DC=at,DC=webstein,DC=ru';
					// 
					$search_results = ldap_search($ldap_bind, $dn, $filter, $fields);
					$entries = ldap_get_entries($ldap_bind, $search_results);
					
					if ($entries['count'] == 1)
					{
						$entries = $entries[0];
						$user = ORM::factory('user', 0);
						$user->username = $username;
						if (array_key_exists('displayname', $entries))
							$user->name = $entries['displayname'][0];
						
						$user->password = $post['password'];
						if (array_key_exists('mail', $entries))
							$user->email = $entries['mail'][0];
						
						
						//Getting roles
						$login = ORM::factory('role')->where('name','login')->find();
						$admin = ORM::factory('role')->where('name','admin')->find();
						
						//Settiong up roles
						$user->roles = array($login->id, $admin->id);
						
						$user->save();
						
						$this->user = $user;
						Auth::instance()->login($user->username, $post['password']);
						url::redirect('auth/logged_in');
					}
				}
				else {
					// The user could not be located
					$post->add_error('username', 'not_found');
				}
			}
			elseif (Auth::instance()->login($user->username, $post['password']))
			{
				// Successful login
				url::redirect('auth/logged_in');
			}
			else
			{
				// Incorrect password
				$post->add_error('password', 'incorrect');
			}
		}
		$errors = $post->errors('form_login');
	}
	
	public function logout()
	{
		Auth::instance()->logout();

		url::redirect('auth/login');
	}
	
	public function logged_in()
	{
		if(!is_object($this->user))
		{
			url::redirect('auth/login');
		}
		else
		{
			url::redirect('admin/');
		}
	}
} 