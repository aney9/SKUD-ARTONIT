<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Class User - информация о текущем авторизованном пользователей.
 * 
 * 
 */
Class User {

	public $id_pep;
	public $id_org;
	public $id_orgctrl;
	public $id_devgroup;
	public $login;
	public $role;
	public $flag;
	
	
	
	public function __construct($default = array())
	{
		
		$_config = Kohana::$config->load('auth');
		$_session = Session::instance($_config['session_type']);
		$ddd = $_session->get($_config['session_key'], $default);
		
		$this->id_pep=Arr::get($ddd, 'ID_PEP');
		$this->id_org=Arr::get($ddd, 'ID_ORG');
		$this->id_orgctrl=Arr::get($ddd, 'ID_ORGCTRL');
		$this->id_devgroup=Arr::get($ddd, 'ID_DEVGROUP');
		$this->login=Arr::get($ddd, 'LOGIN');
		$this->role=Arr::get($ddd, 'ROLE');
		$this->flag=Arr::get($ddd, 'FLAG');
	}

	/*10.11.2024 Список организаций, которыми может управлять текущий авторизованный пользователь.
	
	*/
	public function getChildOrg()
	{
		
		$sql='select  id_org from organization_getchild (1, '.$this->id_orgctrl.')';
		try {
			$query = DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'))
				->as_array()
				;
				
				
		} catch (Exception $e) {
			Log::instance()->add(Log::DEBUG, $e->getMessage());//логирование ошибки в файл
		}	
		
		return $query;
	}

} // End User
