<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Admin Controller class
 *
 * @package admin
 * @author Arthur Chafonov
 * @version 0.1.9
 */



class Admin_Controller extends Template_Controller {
	
	public $template = 'admin';
	
	public function __construct()
	{
		parent::__construct();
		$this->session = Session::instance();
		$authentic = new Auth;
		if (!$authentic->logged_in('admin'))
		{
			// redirect from the login page back to this page
			$this->session->set("requested_url","/".url::current());
			url::redirect('/auth/login/');
		}
		else
		{
			//now you have access to user information stored in the database
			$this->user = $authentic->get_user();
		}
		
		$this->template->title = $this->template->document_title = 'Site Admin';
	}
	
	public function __call($method, $args)
	{
		$this->auto_render = FALSE;
		
		if ( array_key_exists(0, $args) ){
			$action = $args[0];
		}
		else {
			$action = 'view';
		}
		if ( array_key_exists(1, $args) ){
			$id = $args[1];
		}
		else {
			$id = 0;
		}
		
		
		$model_name = inflector::singular($method);
		
		if ( $this->input->post('action') ) {
			$action = $this->input->post('action');
		}
		
		
		if ($action == 'multiple') {
			$this->save_multiple_from_files($model_name);
			try
			{
				$list_view = new View('admin/'.$method);
			}
			catch (Kohana_Exception $e)
			{
				$list_view = new View('admin/content');
			}
			$view = $list_view;
			$item = ORM::factory($model_name);
			$items = $item->find_all();
			$view->set('item', $item);
			$view->set('items', $items);
			$this->template->content = $view->render(TRUE);
			return;
		}
		
		if ($id){
			$item = ORM::factory($model_name, $id);
			try
			{
				$list_view = new View('admin/'.$method);
			}
			catch (Kohana_Exception $e)
			{
				$list_view = new View('admin/content');
			}
			switch($action){
				case 'edit':
					$view = new View('admin/form');
					$view->set('item', $item);
					break;
				case 'save':
					$this->save($model_name, $id);
					
					$items = ORM::factory($model_name)->find_all();
					
					$list_view->set('item', $item);
					$list_view->set('items', $items);
					$view = $list_view;
					break;
				case 'delete':
					$item = ORM::factory($model_name, $id);
					$item->delete();
					
					$item = ORM::factory($model_name);
					$items = $item->find_all();
					$list_view->set('item', $item);
					$list_view->set('items', $items);
					$view = $list_view;
					break;
			}
		}
		else {
			try
			{
				$view = new View('admin/'.$method);
			}
			catch (Kohana_Exception $e)
			{
				$view = new View('admin/content');
			}
			switch($action)
			{
				case 'save':
					$this->create($model_name);
					
					$item = ORM::factory($model_name);
					$items = $item->find_all();
					$view->set('item', $item);
					$view->set('items', $items);
					break;
				case 'add':
					$view = new View('admin/form');
					$item = ORM::factory($model_name);
					$view->set('item', $item);
					break;
				default:
					$item = ORM::factory($model_name);
					$items = $item->find_all();
					$view->set('item', $item);
					$view->set('items', $items);
			}
		}
		$this->template->content = $view->render(TRUE);
	}
	
	public function index()
	{
		$this->template->toolbar = '';
		$this->cache = Cache::instance();
		$this->template->navigation = $navigation = $this->cache->get('navigation');
		if (! $navigation )
		{
			$this->template->navigation = new View('admin/navigation');
			$this->cache->set('navigation', $this->template->navigation);
		}
		// $this->template->navigation = new View('admin/navigation');
		$this->template->content = new View('admin/content', array('item'=> NULL, 'items'=>NULL));
	}
	
	public function phpinfo()
	{
		$this->auto_render = FALSE;
		phpinfo();
	}
	
	public function login($role="")
	{
		$this->session = Session::instance();
		$authentic = new Auth;
		
		if (Auth::instance()->logged_in($role))
		{
			//return to page where login was called
			url::redirect($this->session->get("requested_url"));
		}
		else
		{
			if (Auth::instance()->logged_in()){
			    $this->template->title="No Access";
				$view = new View('admin/noaccess');
			    $this->template->content = $view->render(TRUE);
			}else{
			    $this->template->title="Please Login";
				$view = new View('admin/login');
			    $this->template->content= $view->render(TRUE);
			}
		}
		$form = $_POST;
		if($form)
		{
			// Load the user
			$user = ORM::factory('user', $form['username']);
			// orm user object or $form['username'] could be used
			Auth::instance()->login($user, $form['password']);
			url::redirect('/admin/');
		}
	}
	
	public function editor()
	{
		$this->auto_render = FALSE;
		
		$uri = $_SERVER['HTTP_REFERER'];
		$uri = trim(substr($uri, 7), '/');
		
		$result = array(
			'message'=> print_r($this->input->post(), TRUE),
			'error'=>0
		);
		
		$fields_to_deserialize = $this->input->post();
		
		$models = FormHelper::deserialize($fields_to_deserialize, '/^(\w+)-(\w+)-(\d+)$/');
		foreach($models as $model => $ids)
		{
			foreach($ids as $id => $fields)
			{
				$object = ORM::factory($model)->find($id);
				
				if ($object->loaded)
				{
					foreach($fields as $field => $value)
					{
						$object->$field = $value;
					}
					$object->save();
					
					if ($object->saved)
					{
						$result['message'] = 'Successefully updated.';
						$result['error'] = FALSE;
					}
					else
					{
						$result['message'] = $this->input->post();
						$result['error'] = TRUE;
					}
				}
				else
				{
					$result['message'] = $this->input->post();
					$result['error'] = TRUE;
				}
			}
		}
		
		header('Content-type:text/javascript; charser=UTF-8');
		print json_encode( array('result'=> $result ) );
	}
	
	public function media()
	{
		if (isset($this->profiler)) $this->profiler->disable();
		
		// Get the filename
		$file = MODPATH.'admin/views/media/'.Router::$segments[2].'/'.Router::$segments[3];
		
		$ext = strrchr($file, '.');
		
		if ($ext !== FALSE)
		{
			$file = substr($file, 0, -strlen($ext));
			$ext = substr($ext, 1);
		}
		
		// Disable auto-rendering
		$this->auto_render = FALSE;
		
		print file_get_contents($file.'.'.$ext);
	}
	
	/*
		Creating
	*/
	public function create($model_name)
	{
		$post = $this->input->post();
		$item = ORM::factory($model_name);
		
		$file_field_names = array();
		foreach( $item->as_array() as $field_name => $field_value )
		{
			if ( $field_name == 'id' )
				continue;
			if ( array_key_exists('binary', $item->table_columns[$field_name]))
			{
				if ( array_key_exists($field_name, $post) )
				{
					$item->$field_name = TRUE;
				}
				else
				{
					$item->$field_name = FALSE;
				}
			}
			else if (property_exists($item, 'admin') && array_key_exists($field_name, $item->admin) && $item->admin[$field_name]['type'] == 'file')
			{
				array_push($file_field_names, $field_name);
				continue;
			}
			else
			{
				if (array_key_exists($field_name, $post))
				{
					$item->$field_name = $post[$field_name];
				}
			}
		}
		
		$this->save_files($item);
		$item->save();
		$this->save_related($item, $post);
	}
	
	public function save($model_name, $id)
	{
		$post = $this->input->post();
		$item = ORM::factory($model_name, $id);
		
		$file_field_names = array();
		foreach( $item->as_array() as $field_name => $field_value )
		{
			if ( $field_name == 'id' )
				continue;
			if ( array_key_exists('binary', $item->table_columns[$field_name]))
			{
				if ( array_key_exists($field_name, $post) )
				{
					$item->$field_name = TRUE;
				}
				else
				{
					$item->$field_name = FALSE;
				}
			}
			else if (property_exists($item, 'admin') && array_key_exists($field_name, $item->admin) && $item->admin[$field_name]['type'] == 'file')
			{
				array_push($file_field_names, $field_name);
				continue;
			}
			else
			{
				if (array_key_exists($field_name, $post))
				{
					$item->$field_name = $post[$field_name];
				}
			}
		}
		
		$this->save_files($item);
		$this->save_related($item, $post);
		$item->save();
	}
	
	public function save_related($item, $post)
	{
		foreach($item->has_and_belongs_to_many as $related_model_name)
		{
			if (array_key_exists($related_model_name, $post))
			{
				$item->$related_model_name = $post[$related_model_name];
			}
			unset($post[$related_model_name]);
		}
		
		// foreach($item->has_many as $related_model_name)
		// {
		// 	if (array_key_exists($related_model_name, $post))
		// 	{
		// 		
		// 		print Kohana::debug($item->$related_model_name);
		// 		$item->$related_model_name = $post[$related_model_name];
		// 	}
		// 	unset($post[$related_model_name]);
		// }
	}
	
	public function save_multiple_from_files($model_name)
	{
		// $files = Validation::factory($_FILES);
		// print Kohana::debug($_FILES);
		$files = $this->prepare_files_array($_FILES['files']);
		// exit();
		foreach($files as $file)
		{
			// print $file['name'];
			$item = ORM::factory($model_name);
			$upload_to = NULL;
			if ( array_key_exists('upload_to', $item->admin['file']) )
			{
				$upload_to_path = $item->admin['file']['upload_to'];
				$upload_to = Kohana::config('upload.directory').'/'.$item->admin['file']['upload_to'];
			}
			
			$item->file = upload::save($file, NULL, $upload_to);
			$file_data = pathinfo($item->file);
			$item->title = $file_data['filename'];
			if (!empty($upload_to_path))
			{
				$upload_to = $upload_to_path.'/';
			}
			$item->file = '/'.Kohana::config('upload.relative_path').'/'.$upload_to.basename($item->file);
			$item->save();
			// print Kohana::debug($item);
		}
	}
	
	private function prepare_files_array($files) {
		$files_array = array();
		foreach($files['name'] as $idx => $data) {
			$file = array(
				'name' => $data,
				'type' => $files['type'][$idx],
				'tmp_name' => $files['tmp_name'][$idx],
				'error' => $files['error'][$idx],
				'size' => $files['size'][$idx],
			);
			array_push($files_array, $file);
		}
		return $files_array;
	}
	
	public function save_files($item)
	{
		$files = Validation::factory($_FILES);
		// print_r($_FILES);
		foreach($_FILES as $field_name => $file_data)
		{
			$files->add_rules($field_name, 'upload::required');
		}
		
		if ($files->validate())
		{
			foreach($_FILES as $field_name => $file_data)
			{
				if (property_exists($item, 'admin') && array_key_exists($field_name, $item->admin) && $item->admin[$field_name]['type'] == 'file')
				{
					// print Kohana::debug($file_data);
					$upload_to = NULL;
					if ( array_key_exists('upload_to',$item->admin[$field_name]) )
					{
						$upload_to_path = $item->admin[$field_name]['upload_to'];
						$upload_to = Kohana::config('upload.directory').'/'.$item->admin[$field_name]['upload_to'];
					}
					$item->$field_name = upload::save($file_data, NULL, $upload_to);
					// print $upload_to;
					if (!empty($upload_to_path))
					{
						$upload_to = $upload_to_path.'/';
					}
					$item->$field_name = '/'.Kohana::config('upload.relative_path').'/'.$upload_to.basename($item->$field_name);
					// print 'Saving file '. $item->$field_name;
				}
			}
		}
	}
	
	public function uploader()
	{
		$this->auto_render = FALSE;
		$error = "";
		$msg = "";
		$fileElementName = 'attach-file';
		// print Kohana::debug($_FILES[$fileElementName]);
		if(!empty($_FILES[$fileElementName]['error']))
		{
			switch($_FILES[$fileElementName]['error'])
			{

				case '1':
					$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
					break;
				case '2':
					$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
					break;
				case '3':
					$error = 'The uploaded file was only partially uploaded';
					break;
				case '4':
					$error = 'No file was uploaded.';
					break;

				case '6':
					$error = 'Missing a temporary folder';
					break;
				case '7':
					$error = 'Failed to write file to disk';
					break;
				case '8':
					$error = 'File upload stopped by extension';
					break;
				case '999':
				default:
					$error = 'No error code avaiable';
			}
		}elseif(empty($_FILES['attach-file']['tmp_name']) || $_FILES['attach-file']['tmp_name'] == 'none')
		{
			$error = 'No file was uploaded..';
		}else 
		{
			// Kohana::config('upload.directory');
			
			$msg = '/'.Kohana::config('upload.relative_path').'/'.basename(upload::save($_FILES[$fileElementName]));
				// $msg .= " File Name: " . $_FILES['attach-file']['name'] . ", ";
				// $msg .= " File Size: " . @filesize($_FILES['attach-file']['tmp_name']);
				//for security reason, we force to remove all uploaded file
				// @unlink($_FILES['fileToUpload']);		
		}		
		echo "{";
		echo				"error: '" . $error . "',\n";
		echo				"msg: '" . $msg . "'\n";
		echo "}";
	}
}