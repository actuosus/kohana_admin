<?php

class Role_Model extends Auth_Role_Model {
	protected $has_and_belongs_to_many = array('users');
	
	public $verbose_name = 'Группа';
	public $verbose_plural = 'Группы';
	public $admin = array(
		'name' => array('label'=>'Тип', 'type'=>'char_field'),
		'description' => array('label'=>'Описание', 'type'=>'char_field'),
		'full_name' => array('label'=>'Полное название', 'type'=>'char_field')
	);
	
	public function __toString()
	{
		if ( !empty($this->full_name) )
		{
			return (string) $this->full_name;
		}
		else
		{
			return (string) $this->name;
		}
	}
	
} // End Role Model

?>