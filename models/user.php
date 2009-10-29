<?php

class User_Model extends Auth_User_Model {
	protected $has_and_belongs_to_many = array('roles');
	
	public $verbose_name = 'Пользователь';
	public $verbose_plural = 'Пользователи';
	
	public $admin = array(
		'username' => array('label'=>'Имя пользователя', 'type'=>'char_field'),
		'password' => array('label'=>'Пароль', 'type'=>'char_field'),
		'email' => array('label'=>'Эл. почта', 'type'=>'char_field'),
		'logins' => array('label'=>'Количество входов', 'type'=>'integer'),
		'last_login' => array('label'=>'Дата последнего входа', 'type'=>'date'),
		'name' => array('label'=>'Название', 'type'=>'char_field'),
		'window_title' => array('label'=>'Заголовок окна', 'type'=>'char_field'),
		'document_title' => array('label'=>'Заголовок документа', 'type'=>'char_field'),
		'navigation_title' => array('label'=>'Название в навигации', 'type'=>'char_field'),
		'sort_order' => array('label'=>'Порядок сортировки', 'type'=>'integer'),
		'in_navigation' => array('label'=>'Показывать в навигации', 'type'=>'boolean'),
		'robots' => array('label'=>'Правила индексации роботами', 'type'=>'char_field'),
		'author' => array('label'=>'Автор страницы', 'type'=>'char_field'),
		'copyright' => array('label'=>'Авторское право', 'type'=>'char_field'),
		'keywords' => array('label'=>'Ключевые слова', 'type'=>'text'),
		'description' => array('label'=>'Описание', 'type'=>'text'),
		'bookmark' => array('label'=>'Название для закладки', 'type'=>'char_field'),
		'picture' => array('label'=>'Минифото', 'type'=>'file', 'upload_to'=>'users'),
	);
	
	public function __toString()
	{
		return (string) $this->name.' '.$this->surname;
	}
	
} // End User Model

?>