<?php defined('SYSPATH') OR die('No direct access allowed.');

//abstract class Controller_Template extends Kohana_Controller_Template {
	
//abstract class Model extends Kohana_Model {}	
abstract class Model extends Kohana_Model
{
	public $session;
	public $user;
	/**
	 * Loads Session and configuration options.
	 *
	 * @param   array  $config  Config Options
	 * @return  void
	 */
	public function __construct()
	{
		//if (!Auth::instance()->logged_in()) $this->redirect('login'); 
		$this->session = Session::instance();
		$this->user=new User;
		

	}
	
}
	

