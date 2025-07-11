<?php defined('SYSPATH') OR die('No direct script access.');

class HTTP_Exception_404 extends Kohana_HTTP_Exception_404 {
 
 /*    public $template = 'template';
	
	public function before()
	{
		parent::before();
	} */
	
	public function get_response()
    {
        $response = Response::factory();
 
        $view = View::factory('errors/404');
        
        // We're inside an instance of Exception here, all the normal stuff is available.
		
        $view->message = $this->getMessage();
 
        $response->body($view->render());
		
		return $response;
 
	//$this->redirect('/');
    }
 
}