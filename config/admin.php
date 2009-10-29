<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * @package Admin
 * 
 * Administration interface modelu configuration
 * 
 * @author Arthur Chafonov
 */


/*
 * Navigation panel filled by this models, so models need to exist.
 * Order is important for rendering.
 * First child of array is group delimiter.
*/
$config['navigation'] = array(
	'Access' => array( // Group delimiter
		'user', // Model name
		'role',
	),
);

/*
 * Dependent modules
*/
$config['modules'] = array(
	'auth'
);

?>