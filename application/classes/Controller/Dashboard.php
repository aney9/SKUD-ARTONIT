<?php defined('SYSPATH') or die('No direct script access.');
/*
22.06.2023 
newcrm

*/

class Controller_Dashboard extends Controller_Template {

   
	 public function before() {
        parent::before();
     	
    }
	public function action_index()
	{	
		$t1=microtime(1);
		
		//Проверка авторизации
		$this->session->set('mode', 'home_page');
	
		$content = View::factory('dashboard')
			->bind('user', $this->user);
		//echo Debug::vars('68', $content);	exit;
		
		$this->template->content = $content;
		//echo View::factory('profiler/stats');
		
	}

	public function action_log()// просмотр лог-файлы
	{
		$_SESSION['menu_active']='log';
		$res1=Model::Factory('Log')->getList();
		$res2=Model::Factory('Log')->getListCompare();
		
		$content=View::factory('Log', array(
			'list1'=> $res1,
			'list2'=> $res2,
			));
		$this->template->content = $content;
	}
	
	public function action_sendFile ()//передача данных пользователю
	{
		$file=Arr::get($_GET, 'name');	
		//echo Debug::vars('58', $file); exit;
		$content = Model::Factory('Log')->send_file($file);
		$this->template->content = $content;
	}
	
	
    
	public function ErrMess ($err=false)
	{
		$content = View::factory('errorpage');
		$this->template->content = $content;
	}
	

	

	
}
