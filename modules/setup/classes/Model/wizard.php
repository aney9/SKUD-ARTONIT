<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
* @package    ParkResident/Setup
 * @category   Base
 * @author     Artonit
 * @copyright  (c) 2025 Artonit Team
 * @license    http://artonit/ru 
 
 */
 
 

class Model_Wizard extends Model {
	
	/*11.04.2025 информация по подключенной базе данных
	*/
	
	//путь к базе данных
	public $_connectName='fb';
	public $db_path;
	public $mess;
	
	public function __construct($_connectName='fb')
	{
		
		
		
	}
	
	
	/**
		
	* @package    dashboard
	 * @category   Base
	 * @author     Artonit
	 * @copyright  (c) 2025 Artonit Team
	 * @license    http://artonit/ru 
	 * @desc проверка наличия названия категории доступа в БД СКУД. Если название имеется, то вернется false
	 
	 */
	public static function checkAccessNameIsPresent($name)
	{
		$sql='select count(*) from accessname an
			where an.name=\''.$name.'\'';
	
		if(!DB::query(Database::SELECT, iconv('UTF-8', 'CP1251', $sql))
			->execute(Database::instance('fb'))
			->get('COUNT')) return true;
			
		return false;
		
		
	}
 
		
	public function _aboutDB($sourcename)
	{
		$_fbinfo=Kohana::$config->load('database')->$sourcename;
		$_connection=Arr::get($_fbinfo, 'connection');
		$_dsn=Arr::get($_connection, 'dsn');
		//echo Debug::vars('13', Arr::get(explode(":", $_dsn), 1));

		//$reg=shell_exec('C:\Windows\system32\reg.exe query "HKEY_LOCAL_MACHINE\SOFTWARE\Wow6432Node\ODBC\ODBC.INI\SDuo" /v "Database"');
		$reg=shell_exec('C:\Windows\system32\reg.exe query "HKEY_LOCAL_MACHINE\SOFTWARE\Wow6432Node\ODBC\ODBC.INI\\'.Arr::get(explode(":", $_dsn), 1).'" /v "Database"');
		$_aaa=explode("REG_SZ", $reg);
		return array('connectName'=>$sourcename,
				'dsn'=>$_dsn,
				'pathDB'=>trim(Arr::get($_aaa, 1))
				);
		
	}
	
	/*
	20.03.2025 Проверка наличия указанных таблицы
	
	*/
	public function _checkTableIsPresent($table)
	{
		$res=array();
		$sql='select distinct RDB$RELATION_NAME
				from RDB$RELATION_FIELDS
				where RDB$RELATION_NAME=\''.$table.'\'';
		//echo Debug::vars('12', $sql); exit;
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		if($query) return true;
		return false;
		
	}
	
	
	/*
	20.03.2025 Проверка наличия указанных процедур
	
	*/
	public function _checkProcedureIsPresent($name)
	{
		$res=array();

		$sql='select distinct * from  RDB$PROCEDURES
			where RDB$PROCEDURE_name = \''.$name.'\'';
		
		//echo Debug::vars('12', $sql); exit;
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		if($query) return true;
		return false;
	}
	
	/*
	20.03.2025 Проверка наличия указанных процедур
	
	*/
	public function _checkGeneratorIsPresent($name)
	{
		$res=array();

		$sql='select distinct * from rdb$GENERATORS
    where rdb$GENERATOR_name=\'GEN_'.$name.'_ID\'';
		
		//echo Debug::vars('12', $sql); exit;
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		if($query) return true;
		return false;
	}
	
	
	
	/**3.05.2025 выполняет подготовленный sql запрос и возвращает true или false
	*/
	public function makeQuery($query)
	{
		Log::instance()->add(Log::DEBUG, '173 makeQuery выполняется запрос: '. $query);
		try{
			$this->mess = Database::instance('fb')->query(NULL, $query);
			Log::instance()->add(Log::DEBUG, '108 makeQuery выполнен успешно.');
			return true;
		} catch (Exception $e) {
			//echo Debug::vars('99', $e->getMessage());
			$this->mess = $e->getMessage();
			Log::instance()->add(Log::DEBUG, '113 makeQuery выполнен с ошибкой  '.$this->mess);
			return false;
		}
		
	}
	
	//2.05.2025 единая процедура выполнения exec с анализом ответа.
	// ответ 0 - команда выполнена успешно.
	// ответ НЕ 0 - ошибка.
	public function _makeExec($query)
	{
		Log::instance()->add(Log::DEBUG, Debug::vars('124 makeExec выполняет запрос  :', $query));	
			
		 $retval = null;
		 $output = null;
		 $result=exec(iconv('UTF-8', 'CP1251', $query), $retval, $output);
		 
		 if($output==0)
		 {
			 Log::instance()->add(Log::DEBUG, '132 makeExec выполнен успешно. Результат выполнения '. $output); 	
			 return true;
		 } else {	 
			  Log::instance()->add(Log::DEBUG, '135 makeExec выполнен с ошибкой. Результат выполнения '. $output);	
			return false;
		 }
		
	}
	
	public function _aboutTable($tableName)
	{
		
		$sql='select Rdb$Description from Rdb$Relations
			where Rdb$Relation_Name=\''.$tableName.'\'';
		//	echo Debug::vars('87', $sql);exit;
		//$this->makeQuery($sql);
		
		$query = Arr::flatten(DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array());
		return $query;
		
		
	}
	
	public function _delTable($tableName)
	{
		/* Log::instance()->add(Log::DEBUG, '117 Удадение таблицы '.$tableName);
		if($this->checkGeneratorIsPresent($name)) 
		{
			Log::instance()->add(Log::DEBUG, '173 Генератора :gen присутвует. Начинается его удаление.', array(':gen'=>$name));
			$this->makeQuery('DROP GENERATOR GEN_'. $name.'_ID');
		} else {
			Log::instance()->add(Log::DEBUG, '176 Генератора :gen Отсутсвует, удалять ничего не надо.', array(':gen'=>$name));
		}
		
		$this->delGenerator($tableName); */
		return $this->makeQuery('DROP TABLE '. $tableName);
		
	}
	
	
	
	
	public function _delTableData($tableName)
	{
		$this->delGenerator($tableName);
		$this->makeQuery('delete from '. $tableName);
		
	}
	
	//Добавление данных в указанную таблицу
	public function _addTableData($name)
	{
		//echo Debug::vars('99', $name.'.sql');exit;
		$ttt='"C:\Program Files (x86)\Firebird\Firebird_1_5_6\bin\isql.exe" localhost/3050:'.$this->db_path.' -user sysdba -pass temp -i C:\xampp\htdocs\parkresident\modules\setup\config\sql\data\\'.$name.'.sql';
		exec(iconv('UTF-8', 'CP1251', $ttt));
		
	}
	
	
	
	//31.03.2025 Добавление таблицы сводится к выполнению нескольких sql запросов, взятых из файла конфигурации.
	public function _addTable($tableName)
	{
		$retval=null;	
		$output=null;		
		$ttt='"C:\Program Files (x86)\Firebird\Firebird_1_5_6\bin\isql.exe" localhost/3050:'.$this->db_path.' -user sysdba -pass temp -i C:\xampp\htdocs\parkresident\modules\setup\config\sql\\'.$tableName.'.sql';
			
		Log::instance()->add(Log::DEBUG, Debug::vars('158 выполняю команду добавления таблицы :', iconv('UTF-8', 'CP1251', $ttt)));	
		$result=exec(iconv('UTF-8', 'CP1251', $ttt), $retval, $output);
		/* echo Debug::vars('181', $result, $output);exit;
		Log::instance()->add(Log::DEBUG, '159 результат добавления таблицы : '. Debug::vars($result));	*/
		Log::instance()->add(Log::DEBUG, '159-1 результат добавления таблицы : '. Debug::vars($output)); 	
		if($output==0) return true;
		return false;
		// echo Debug::vars('159 результат добавления таблицы :', exec(iconv('UTF-8', 'CP1251', $ttt)));
	}
	
	public function _delGenerator($name)
	{
		
			return $this->makeQuery('DROP GENERATOR GEN_'. $name.'_ID');
		
		
		
	}
	
	public function _delProcedure($name)
	{
		
		return $this->makeQuery('DROP PROCEDURE '. $name);
	}
	
	
	//31.03.2025 ДОбавление процедуры сводится к выполнению скрипта, взятого из файлов.
	public function _addProcedure($name)
	{
		$ttt='"C:\Program Files (x86)\Firebird\Firebird_1_5_6\bin\isql.exe" localhost/3050:'.$this->db_path.' -user sysdba -pass temp -i C:\xampp\htdocs\parkresident\modules\setup\config\sql\\'.$name.'.sql';
			
		Log::instance()->add(Log::DEBUG, Debug::vars('226 выполняю команду добавления процедуры :', $name));	
			
		
		return $this->makeExec($ttt);
	}
	
	
	
}
