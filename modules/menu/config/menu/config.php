<?php defined('SYSPATH' OR die('No direct access allowed.'));
	
 return array(	 
	'build'=>2,
	'builddate'=>'6.07.2025',
 
 //вывод меню с учетом прав пользователя
	'configMenu'=>array(
			'home'=>array(),
			'org'=>array(Menu::MANCARD),
			'contact'=>array(Menu::MANCARD),
			'identity'=>array(Menu::MANCARD),
			'fastreg'=>array(Menu::NONE),
			'Reports'=>array(Menu::REPORT),
			'passoffice'=>array(Menu::NONE),
			'order'=>array(Menu::GUEST),
			'monitor'=>array(Menu::MANCARD, Menu::MONITOR),
			'acl'=>array(Menu::INTEGRATOR),
			'doors'=>array(Menu::DOOR)
		),
	//вывод меню без учета прав пользователя (т.к. указатели пусты)
	'_configMenu'=>array(
			'home'=>array(),
			'org'=>array(),
			'contact'=>array(),
			'identity'=>array(),
			'fastreg'=>array(),
			'Reports'=>array(),
			'passoffice'=>array(),
			'monitor'=>array(),
			'acl'=>array(),
			'doors'=>array()
			),
);