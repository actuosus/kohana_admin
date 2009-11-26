<?php defined('SYSPATH') OR die('No direct access allowed.');

class form_generator_Core
{
	/**
	 * Useful for multiple object at one form
	 *
	 * @param string $fields_array 
	 * @param string $format 
	 * @return array 
	 * @author Arthur Chafonov
	 */
	
	public static function deserialize($fields_array, $format)
	{
		$output = array();
		foreach($fields_array as $key => $value)
		{
			preg_match($format, $key, $matcher);
			if ( count($matcher) == 4 )
			{
				$model = $matcher[1];
				$property_name = $matcher[2];
				$id = $matcher[3];
				
				if ( array_key_exists($model, $output) )
				{
					if ( array_key_exists($id, $output[$model]) )
					{
						$output[$model][$id][$property_name] = $value;
					}
					else
					{
						$output[$model][$id] = array($property_name => $value);
					}
				}
				else {
					$output[$model] = array($id => array($property_name => $value));
				}
			}
		}
		return (array) $output;
	}
	
	public static function rows($item)
	{
		// print Kohana::debug($item);
		$output = '';
		foreach($item->as_array() as $column_name => $column_value)
		{
			if ($column_name === 'id') continue;
			$output .= '<li class="row">';
			$output .= form::label($column_name, self::label($item, $column_name) );
			
			if (empty($item->table_columns[$column_name]['null']))
				$required = 'required';
			else
				$required = NULL;
			// print_r($item->table_columns[$column_name]);
			switch($item->table_columns[$column_name]['type']) {
				case 'boolean':
					if ( $column_value )
						$checked = TRUE;
					else
						$checked = FALSE;
					$output .= form::checkbox($column_name, $column_value, $checked, 'class="checkbox '.$required.'"');
					break;
				case 'string':
					if (empty($item->table_columns[$column_name]['length'])) {
						if (empty($item->table_columns[$column_name]['format'])) {
							$output .= form::textarea($column_name, $column_value);
						}
						else {
							$output .= form::input($column_name, $column_value, 'class="date '.$required.'"');
						}
					
					}
					else if (array_key_exists('binary', $item->table_columns[$column_name]))
					{
						if ( $column_value )
							$checked = TRUE;
						else
							$checked = FALSE;
						$output .= form::checkbox($column_name, $column_value, $checked, 'class="checkbox '.$required.'"');
					}
					else {
						if (property_exists($item, 'admin') && array_key_exists($column_name, $item->admin) && $item->admin[$column_name]['type'] === 'file')
						{
							$attributes = array('name' => $column_name);
							$output .= form::upload($attributes, $column_value);
							$file_name = basename($column_value);
							
							$upload_to = '/';
							if ( array_key_exists('upload_to', $item->admin[$column_name]) )
							{
								$upload_to = '/'.$item->admin[$column_name]['upload_to'].'/';
							}
							
							$link = '/'.Kohana::config('upload.relative_path').$upload_to.$file_name;
							$full_file_path = rtrim(DOCROOT, '/').$column_value;
							$file_type = explode('/', file::mime($full_file_path));
							$file_type = $file_type[0];
							// print Kohana::debug($link);
							if ($file_type == 'image')
							{
								$image_source = $link;
								$file_data = pathinfo($full_file_path);
								$thumb = $file_data['dirname'].'/'.$file_data['filename'].'_small.'.$file_data['extension'];
								if (file_exists($thumb)) {
									$image_source = '/'.Kohana::config('upload.relative_path').$upload_to.$file_data['filename'].'_small.'.$file_data['extension'];
								}
								
								$output .= '<div class="picture-container">'. html::anchor($link, html::image(array('src'=>$image_source, 'width'=>100)), array('target'=>'_blank')). '</div>';
							}
							else
							{
								$output .= html::anchor($link, $file_name, array('target'=>'_blank'));
							}
						}
						else
						{
							$output .= form::input($column_name, $column_value, 'class="'.$required.'" size="'.$item->table_columns[$column_name]['length'].'" maxlength="'.$item->table_columns[$column_name]['length'].'"');
							$output .= '<div class="hint">Maximum length is '. $item->table_columns[$column_name]['length'] .'.</div>';
						}
					}
					break;
				case 'int':
					if (array_key_exists('max', $item->table_columns[$column_name]) AND $item->table_columns[$column_name]['max'] == 127) {
						if ( $column_value )
							$checked = TRUE;
						else
							$checked = FALSE;
						$output .= form::checkbox($column_name, $column_value, $checked, 'class="checkbox '.$required.'"');
						break;
					}
					$belongs_to = FALSE;
					$model_name = '';
					$selection = array();
					foreach ( array_values($item->belongs_to) as $model_name )
					{
						if ( $model_name.'_id' == $column_name )
						{
							$belongs_to = TRUE;
						}
					}
					if ( $belongs_to )
					{
						preg_match('/(\w+)_id/', $column_name, $matcher);
						$model_name = $matcher[1];
						$selection = array();
						$current_model = ORM::factory($model_name);
						{
							$objects = $current_model->find_all();
							foreach ($objects as $object)
							{
								$selection[$object->id] = (string) $object;
							}
						}
						
						// Check if we have has_one relation
						if (property_exists('has_one', $current_model) AND in_array($model_name, $current_model->has_one))
						{
							// Select is not multiple
							$output .= form::dropdown($column_name, $selection, $column_value);
						}
						else
						{
							$output .= form::dropdown($column_name, $selection, array($column_value));
						}
					}
					else if ( property_exists($item, 'children') )
					{
						preg_match('/(\w+)_id/', $column_name, $matcher);
						$model_name = $matcher[1];
						$objects = ORM::factory($item->object_name)->find_all();
						$selection = array();
						{
							$objects = ORM::factory($model_name)->find_all();
							foreach ($objects as $object)
							{
								if ($object->id == $column_value)
								{
									continue;
								}
								$selection[$object->id] = (string) $object;
							}
						}
						$output .= form::dropdown($column_name, $selection, array($column_value));
					}
					else if ( $column_name == 'parent_id' )
					{
						$objects = ORM::factory($item->object_name)->find_all();
						{
							$objects = ORM::factory($item->object_name)->find_all();
							foreach ($objects as $object)
							{
								// Don't allow to set parent to self
								// to prevent infinite loop
								if ($item->id == $object->id)
								{
									continue;
								}
								$selection[$object->id] = (string) $object;
							}
						}
						$output .= form::dropdown($column_name, $selection, array($column_value));
					}
					else
					{
						$output .= form::input($column_name, $column_value, 'class="'.$required.'"');
					}
					
					break;
				case 'float':
					$output .= form::input($column_name, sprintf('%F', $column_value), 'class="'.$required.'"');
					break;
			}
			
			$output .= '</li>';
		}
		
		return $output;
	}
	
	public static function related_rows($model_name, $related)
	{
		$item = ORM::factory(inflector::singular($model_name));
		$label = NULL;
		if (property_exists('verbose_plural', $item)) {
			$label = $item->verbose_plural;
		}
		$output = '<li class="row related many">';
		$output .= form::label($item->object_plural, self::label($item, $item->object_name, $label));
		
		$selection = array();
		{
			$objects = ORM::factory($item->object_name)->find_all();
			foreach ($objects as $object)
			{
				$selection[$object->id] = (string) $object;
			}
		}
		$related_ids = array();
		foreach ($related->{$item->object_plural} as $related_item)
		{
			array_push($related_ids, $related_item->id);
		}
		$output .= form::dropdown( array('name' => $item->object_plural.'[]', 'multiple' => 'multiple', 'size' => 4), $selection, $related_ids, 'id="'.$item->object_plural.'"');
		
		$output .= '</li>';
		
		return $output;
	}
	
	public static function related_one2one($model_name, $related)
	{
		$item = ORM::factory(inflector::singular($model_name));
		$label = NULL;
		if (property_exists('verbose_name', $item)) {
			$label = $item->verbose_name;
		}
		$output = '<li class="row related one">';
		$output .= form::label($item->object_name, self::label($item, $item->object_name, $label));
		
		$selection = array();
		{
			$objects = ORM::factory($item->object_name)->find_all();
			foreach ($objects as $object)
			{
				$selection[$object->id] = (string) $object;
			}
		}
		
		$objects = ORM::factory($item->object_name)->find_all();
		{
			$objects = ORM::factory($item->object_name)->find_all();
			foreach ($objects as $object)
			{
				// Don't allow to set parent to self
				// to prevent infinite loop
				if ($item->id == $object->id)
				{
					continue;
				}
				$selection[$object->id] = (string) $object;
			}
		}
		$output .= form::dropdown(array('name' => $item->object_name.'[]', 'size' => 4), $selection, $related->{$item->object_name}->id, 'id="'.$item->object_plural.'"');
		
		$output .= '</li>';
		
		return $output;
	}
	
	public function label($item, $column_name, $text = NULL)
	{
		if( property_exists($item, 'labels') AND array_key_exists($column_name, $item->labels))
		{
				$label_text = $item->labels[$column_name];
		}
		elseif (property_exists($item, 'admin') AND array_key_exists($column_name, $item->admin))
		{
			$label_text = $item->admin[$column_name]['label'];
		}
		elseif (!empty($text))
		{
			$label_text = preg_replace('/_/', ' ', ucfirst($text));
		}
		else
		{
			$label_text = preg_replace('/_/', ' ', ucfirst($column_name));
		}
		$label_text .= ':';
		return $label_text;
	}
}