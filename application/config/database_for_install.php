<?php defined('SYSPATH') OR die('No direct access allowed.');

return array
(

	'fb' => array(
		'type'			=> 'pdo',
		'connection'	=> array(
			'dsn'		=> 'odbc:SDUO',
			'username'	=> 'SYSDBA',
			'password'	=> 'temp',
			//'password'	=> 'masterkey',
			'charset'   => 'windows-1251',
			)
		),
		
		//тут хранятся общие настройки crm2: название листа в браузере, порядок удаления контактов
		'cdb' => array(
		'type'       => 'pdo',
		'connection' => array(
       		'dsn'        => 'sqlite:'.APPPATH .'\\Config\\config.sqlite',
			'persistent' => FALSE,
		)),
		
		
		//тут хранятся настройки, связанные с контактами: обязательные поля
		'config_cards' => array(
		'type'       => 'pdo',
		'connection' => array(
       		'dsn'        => 'sqlite:'.APPPATH .'\\config_contacts.sqlite',
			'persistent' => FALSE,
		)),
		
		'pocfg' => array(// сокращение от passofficeconfig
		'type'       => 'pdo',
		'connection' => array(
       		'dsn'        => 'sqlite:'.APPPATH .'\\Config\\passofficeconfig.sqlite',
			'persistent' => FALSE,
		)),
		
		//Ресурсы и права на них
		'aclcfg' => array(// 
		'type'       => 'pdo',
		'connection' => array(
       		'dsn'        => 'sqlite:'.APPPATH .'\\Config\\acl_config.sqlite',
			'persistent' => FALSE,
    )),
	


);

