<?php defined('SYSPATH') OR die('No direct script access.');

return array(

   'cookie' => array(
        'name' => 'cookie_name_crm2',
        'encrypted' => false,
        'lifetime' => 43200,
    ),
	  'native' => array(
        'name' => 'session_name_crm2',
        'lifetime' => 43200,
		),
		'default'=>'native',

);
