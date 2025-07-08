<?php defined('SYSPATH') OR die('No direct access allowed.');

return array
(
	
	'config_module' => array(
		'type'       => 'pdo',
		'connection' => array(
       		'dsn'        => 'sqlite:'.MODPATH .'\\<modules>\\Config\\<config_file_name>.sqlite',
			'persistent' => FALSE,
    )),

	

	
);

