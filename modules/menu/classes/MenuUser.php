<?php defined('SYSPATH') OR die('No direct script access.');

class MenuUser extends Kohana_Menu {
	
	/**
	 *06.10.2024
	 * Instantiate a new menu с учетом нужного шаблона
	 *@config_file - файл с полным перечнем меню, в т.ч. с указателем на view
	 *@_file - массив с пунктами меню, которые необходимо вывести на экран
	 *$_fileView - название файла view
	 * т.о, на экран будут выведены не все пункты меню, а только указанные.
	 *по сути дела, весь этот метод нужен только для того, чтобы изменить  путь к view, если он указан в параметрах factory
	 *метод "заглядывает" в файл config_file, проверяет там наличие 'view', и если его там нет, то берет view по умолчанию  const DEFAULT_VIEW = 'default';
	 */
	const VIEWPATH = 'views\templates\menu\bootstrap';//в этой папке находятся файлы view для меню
	
	public static function factory($config_file = 'simple', $_file = 'simple', $_fileView=null)
	{
		// Load menu config
		$menu_config = self::_get_menu_config($config_file);
        
		// Auto-detect view path when no view file given
		if (Arr::get($menu_config, 'view') === NULL) {
			$view_file = Kohana::find_file('views/'.self::VIEWS_DIR, $config_file)? $config_file : self::DEFAULT_VIEW;
			$menu_config['view'] = self::VIEWS_DIR.DIRECTORY_SEPARATOR.$view_file;
		}
		//\modules\menu\views\templates\menu\bootstrap
	//echo Debug::vars('23', Kohana::find_file('views\templates\menu\bootstrap', $_fileView));exit;
		$currentMenu=array();//тут будет формироваться текущее меню
		if(Kohana::find_file('views\templates\menu\bootstrap', $_fileView) === FALSE)
		{
			$currentMenu['view']=Arr::get($menu_config, 'view');
			
		} else {
			$currentMenu['view']='templates/menu/bootstrap/'.$_fileView;
			
		}
		//foreach($configMenu as $key){
		foreach($_file as $key){
			
			$currentMenu['items'][]=Arr::get(Arr::get($menu_config, 'items'), $key);

		}			
		return $currentMenu;
	}
	

}
