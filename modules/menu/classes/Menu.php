<?php defined('SYSPATH') OR die('No direct script access.');

class Menu extends Kohana_Menu {
	//битовые маски модулей
	const DOOR=0;
	const CONFIG=2;
	const MANCARD=4;
	const REPORT=5;
	const MONITOR=6;
	const INTEGRATOR=8;
	const GUEST=13;
	const NONE=31;//не выводить пункт меню (запретить)
	

}