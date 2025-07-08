<?php
//	echo Debug::vars('32', $_SESSION, $_COOKIE);
//	echo Debug::vars('3', Auth::instance()->logged_in('admin'));
//	echo Debug::vars('3', Auth::instance()->logged_in('aa'));
//echo Kohana::version();
//echo phpinfo(INFO_GENERAL);
?>
<div id="header">
	<div id="logo"><img src="images/logo2.png" alt="logo"></div>
	

	<div id="search">
		<?php 
		echo  __('system.version'). ConfigType::getCityCrmVer().' ';
		if(Arr::get(Session::instance()->get('auth_user_crm'), 'ID_PEP') == 1) echo HTML::anchor('settings/mainManual', HTML::image('images/shortcut/setting.png', array('width'=>20, 'title'=>'Настройка')));
		
		echo ' | '. HTML::anchor('guide', __('Справка'));
		echo ' | '. HTML::anchor('logout', __('logout'));
		 ?>
	</div>

	<div id="account_info">
		<img src="images/icon_online.png" alt="Online" class="mid_align">
		<?php 
			$huser=Session::instance()->get('auth_user_crm');
			$userAdmin=new Contact(Arr::get($huser, 'ID_PEP'));
			
			echo __('welcome') . ', <strong><i>' . iconv('CP1251', 'UTF-8', 
				$userAdmin->name. ' '
				.$userAdmin->patronymic. ' '
				.$userAdmin->surname)
				. '</i></strong>'; ?> 
	</div>
	<div>
	
				
	</div>

	
</div>

