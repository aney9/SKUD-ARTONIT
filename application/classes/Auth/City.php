<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Авторизиция пользователя по базе данных СКУД 

 *
 *
 * 
 * Этот файл сформировал Бухаров А.В. 12 авг 2017 г. Пароль и логин пользователя берутся из БД СКУД.
 Класс File переименовал в City 25.06.2023 г.
 */
class Auth_City extends Auth {
	
	// User list
	protected $_users;
	
	
	
	
	/**
	 * Logs a user in.
	 *
	 * @param   string   $username  Username
	 * @param   string   $password  Password
	 * @param   boolean  $remember  Enable autologin (not supported)
	 * @return  boolean
	 */
	protected function _login($username, $password, $remember)
	{
		
		$sql='select p.id_pep, p.id_org, p.surname, p.name, p.patronymic, p.tabnum, p.login, p.flag, coalesce(p.id_orgctrl, p.id_org) as id_orgctrl , p.id_devgroup  from people p
			where p.login=\''.$username.'\'
			and p.pswd=\''.$password.'\'
			and p."ACTIVE">0';
		
		
		
		$sql='select p.id_pep, p.id_org, p.surname, p.name, p.patronymic, p.tabnum, p.login, p.flag, coalesce(p.id_orgctrl, 1) as id_orgctrl , p.id_devgroup  from people p
			where p.login=\''.$username.'\'
			and p.pswd=\''.$password.'\'
			and p."ACTIVE">0';
		
		
		
		//echo Debug::vars('36', $sql); exit;	
		try 
		{
			$query = DB::query(Database::SELECT, iconv('UTF-8','windows-1251',$sql))
			->execute(Database::instance('fb'))
			->as_array();
				
		} catch (Exception $e) { 
		
		//throw new Exception('Ошибка с базой данных при авторизации', 64);
		
		// Login failed
		return FALSE;
		}
		//echo Debug::vars('48', $query );exit;
		if(count($query) == 1)
		{
			$user=Arr::flatten($query);
			
				
				$user['ROLE']=$this->get_role(Arr::get($user, 'ID_PEP'));
	
				$this->complete_login($user);
			
			return TRUE;
			} else {
				Log::instance()->add(Log::DEBUG, '63 Неудачная попытка входа для username '.$username);
				//Пароль не найден, поэтому переходим на окно ввода пароля.
				
			}
	}
	
	/**
	 * Forces a user to be logged in, without specifying a password.
	 *
	 * @param   mixed    $username  Username
	 * @return  boolean
	 */
	
	public function force_login($id_pep=1)
	{
		$sql='select p.id_pep, p.id_org, p.surname, p.name, p.patronymic, p.tabnum, p.login, p.pswd, p.flag, p.id_orgctrl , p.id_devgroup  from people p
		where p.id_pep='.$id_pep;
		//echo Debug::vars('36', $sql); exit;	
		try 
		{
			$query = Arr::flatten(DB::query(Database::SELECT, iconv('UTF-8','windows-1251',$sql))
			->execute(Database::instance('fb'))
			->as_array());
			
				
			Auth::instance()->login(Arr::get($query, 'LOGIN'), Arr::get($query, 'PSWD'));
				
		} catch (Exception $e) { 
		
		//throw new Exception('Ошибка с базой данных при авторизации', 64);
		
		// Login failed
		return FALSE;
		}
		//echo Debug::vars('48', $query );exit;

			$user=Arr::flatten($query);
			
				
				$user['ROLE']=$this->get_role(Arr::get($user, 'ID_PEP'));
	
				$this->complete_login($user);
		
	}
	
	/**
	 * Get the stored password for a username.
	 *
	 * @param   mixed   $username  Username
	 * @return  string
	 */
	public function password($username)
	{
		return Arr::get($this->_users, $username, FALSE);
	}
	
	/**
	 * Compare password with original (plain text). Works for current (logged in) user
	 *
	 * @param   string   $password  Password
	 * @return  boolean
	 */
	public function check_password($password)
	{
		$username = $this->get_user();
		
		if ($username === FALSE)
		{
			return FALSE;
		}
		
		return ($password === $this->password($username));
	}
	
	
	
	/**
	 * Определение роли пользователя
	 *
	 * @param   string   $password  Password
	 * @return  boolean
	 */
	public function get_role($id_pep)
	{
		if(isset(Kohana::$config->load('config_newcrm')->use_acl)){
			
			if (!Kohana::$config->load('config_newcrm')->use_acl) return Kohana::$config->load('config_newcrm')->role_default;
		}
		
		$sql = 'select roles.name from roles_users 
				join roles on roles.id = roles_users.role_id
				where user_id='.$id_pep;
			
		try 
		{
				$query = DB::query(Database::SELECT, $sql)
					->execute(Database::instance('aclcfg'))
					->get('name');
			
			//echo Debug::vars('125',$sql, $query);exit;	
			return $query;
			
			
		} catch (Exception $e) { 
		
		throw new Exception('Ошибка с базой данных при получении роли', 129);
		
		// role failed
		return FALSE;
		}
		
	}
	
	
	
	
	
} // End Auth City

