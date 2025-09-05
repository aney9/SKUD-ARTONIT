<?php defined('SYSPATH') or die('No direct access allowed.');
return array(

		'view_settings'=>true,// показывать ли пункт Настройка в хидере?
		'iphost'=>'192.168.56.1',// тут надо указать IP адрес сервера СКУД
		//'iphost'=>'127.0.0.1:8080',// тут надо указать IP адрес сервера СКУД
		'siteurl'=>'crm2',// используется при формировании <bas в view/template 
		

	'version' => array(
		'major' => '4',
		'minor' => '3'
		),
		
	'use_acl'=>false,//использовать ли роли. Если указан false, то у всех авторизованных пользователей будет роль role_default. Если 'use_acl'=>true, то роль берется из Auth
	'role_default'=>'admin',//роль для авторизованных пользовалей если 'use_acl'=>false
	
	/* максимальное количество получаемых идентификаторов для вывода на страницу
	*большое значение может привести к торможению вывода списка контактов на экран.
	*/
	'table_view_max_contact'=>'1000000',
	
	/**20.11.2024 Включение - отключение строки состояния в нижней части экрана
	*
	*/
	'bottom_status_table'=>false,
	
	
	//набор левого меню, которое надо показывать по левому краю.
	//для разных объектов этот набор меню может быть разным.
	'configLeftMenu'=>array(
			'home',
			'org',
			'contact',
			'identity',
			'fastreg',
			'Reports',
			'passoffice',
			'monitor',
			'acl',
			'doors',
			),
	//содержимое главной страницы
	'module'=>array(
		'org'=>true,
		'contact'=>true,
		'card'=>true,
		'guest'=>true,
		'event'=>false,
		'queue'=>false,
		'user'=>false,
		'stat'=>false,
		'devices'=>false,
		'doors'=>false,
		),
	
		
	
);