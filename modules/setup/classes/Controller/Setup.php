<?php defined('SYSPATH') or die('No direct script access.');

/**
* @package    ParkResident/Setup
 * @category   Base
 * @author     Artonit
 * @copyright  (c) 2025 Artonit Team
 * @license    http://artonit/ru 
 
 */
 

/** 3.05.2025 
* Setup - контроллер для автоматизации настройки парковочной системы
* @package    ParkResident/controller
 * @category   Base
 * @author     Artonit
 * @copyright  (c) 2025 Artonit Team
 * @license    http://artonit/ru 
 
 */
 



class Controller_Setup extends Controller_Template { // класс описывает въезды и вызды (ворота) для парковочных площадок
	
	
	public $template = 'template';
	
	
	public function before()
	{
			
			parent::before();
			$session = Session::instance();
	
	}
	
	
	
	/**3.05.2025 Добавление категорий доступа в СКУД.
	* каждая парковочная площадка добавляется в СКУД как категорию доступа с таким же названием. 
	*/
	public function action_addAccessname()
	{
		
		echo Debug::vars('65', $_POST);exit;
		$this->redirect('checkdb');
		
	}
	
	
	
} 
