<?php defined('SYSPATH') or die('No direct script access.');

/**
* @package    ParkResident/Setup
 * @category   Base
 * @author     Artonit
 * @copyright  (c) 2025 Artonit Team
 * @license    http://artonit/ru 
 
 */
 
 
/*
03.05.2025 
Wizard - контроллера для настройки интеграции

*/


class Controller_Wizard extends Controller_Template { // класс описывает въезды и вызды (ворота) для парковочных площадок
	
	
	public $template = 'template';
	
	
	
	public function before()
	{
			
			parent::before();
			$session = Session::instance();
	
	}
	
	
	public function action_index()
	{
		
		
		$content = View::factory('setup/wizard', array(
			
				
		));
        $this->template->content = $content;
		
	}
	
	
	/** 3.05.2025 Добавление категории доступа в БД СКУД. Название категории берется как название парковочной площадки.
	*/
	public function action_addAccessname ()
	{
		//echo Debug::vars('53', $_POST);//exit;
		$post=Validation::factory($_POST);
		$post->rule('name', 'not_empty')
				->rule('name', 'Model_wizard::checkAccessNameIsPresent')
				;
				if($post->check())
				{
					$sql='INSERT INTO ACCESSNAME (ID_DB,NAME) VALUES (1,\''.Arr::get($_POST, 'name').'\')';
					//echo Debug::vars('45', $sql);exit;
					Log::instance()->add(Log::NOTICE, $sql);
					Model::factory('Parkdb')->makeQuery(iconv('UTF-8','windows-1251',$sql));
					
					
				} else {
					//echo Debug::vars('62 err', $post);exit;
					
				}
		$this->redirect('wizard');
       
		
	}
	
	
	
	
	
} 
