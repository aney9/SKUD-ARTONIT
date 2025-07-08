<?php defined('SYSPATH') or die('No direct script access.');
/*20.11.2024 Этот файл является основой всех контроллеров.
* сюда в раздел befor надо добавить проверку авторизации. Если неуспешно - то переход на ввод логина
* из других контроллеров авторизацию можно будет убрать.
*/

//class Controller_Template extends Controller_Template {
abstract class Controller_Template extends Kohana_Controller_Template {

	     public $template = 'template';
		 public $session;
		 public $user;
		 public $arrAlert;
		 public $listsize=100;
	
	 
    public function before() {
        parent::before();
     	
		//Для тестирования необходимо раскоментировать строку.		
		//Auth::instance()->force_login(19144);// авторизация как бюро пропусков Щербинка
		//Auth::instance()->force_login(1);//авторизация как Админ
		if (!Auth::instance()->logged_in()) $this->redirect('login'); 
		$this->session = Session::instance();
		$this->user=new User;
		include Kohana::find_file('classes/controller','check_db_connect');
		
		
    }
	
}

