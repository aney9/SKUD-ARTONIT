

<a href="javascript:" id="show_menu">&nbsp;</a>
<div id="left_menu">
	<a href="javascript:;" id="hide_menu">&nbsp;</a>
	<ul id="main_menu">
	
	<?php 
		
		//$configMenu= Kohana::$config->load('config_newcrm')->get('configLeftMenu');// получаю массив меню из указанного в конфигурации файла
		$configMenu= MenuModuleUser::factory();// получаю массив меню для авторизованного пользователя.
		
		$fullMenu='leftside';// указание на файл с полным списком меню + указание на файл view. Файл со списком должен находится в C:\xampp\htdocs\crm2\modules\menu\config\menu\<$fullMenu>.php. 
		//$fileView='dashboardbar';// указание на файл с полным списком меню + указание на файл view. Файл со списком должен находится в C:\xampp\htdocs\crm2\modules\menu\config\menu\<$fullMenu>.php. 
		//$fileView='navbar';// указание на файл с полным списком меню + указание на файл view. Файл со списком должен находится в C:\xampp\htdocs\crm2\modules\menu\config\menu\<$fullMenu>.php. 
	
		
		//$fullMenu - имя файла конфигурации. В этом файле должен быть массив:элемент 'view' содержит путь к форме вывода, 'items' - все возможные пункты меню.
		//$currentMenu = MenuUser::factory($fullMenu, $configMenu, $fileView);//работаю с классом, который фильтрует основной набор меню.
		$currentMenu = MenuUser::factory($fullMenu, $configMenu);//работаю с классом, который фильтрует основной набор меню.
		//echo Debug::vars('22', $currentMenu);exit;
		echo new Menu($currentMenu);
		
	?>	
	</ul>

	<br class="clear">
	<div id="calendar"></div>

</div>