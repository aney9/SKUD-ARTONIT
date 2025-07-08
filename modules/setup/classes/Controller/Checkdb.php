<?php defined('SYSPATH') or die('No direct script access.');

/**
* @package    ParkResident/Setup
 * @category   Base
 * @author     Artonit
 * @copyright  (c) 2025 Artonit Team
 * @license    http://artonit/ru 
 
 */
/*
20.03.2025 
Checkdb - контроллера для проверки базы данных.
цель проверки - проверить наличие необходимых таблиц в базе данных и возможность установить эти таблицы.

*/


class Controller_Checkdb extends Controller_Template { // класс описывает въезды и вызды (ворота) для парковочных площадок
	
	
	public $template = 'template';
	public $tableList=array(
			'HL_EVENTCODE',
			'HL_EVENTS',
			'HL_GARAGENAME',
			'HL_ORGACCESS',
			'HL_GARAGE',
			'HL_RESIDENT',
			'HL_INSIDE',
			'HL_MESSAGES',
			//'HL_COUNTERS',
			'HL_PARAM',
			'HL_PARKING',
			'HL_PLACE',
			//'HL_PLACEGROUP',
			'HL_SETTING'
		);
		
	public	$procedureList=array(
				'HL_UPDATE_GARAGE_NAME',
				
				'VALIDATEPASS_HL_PARKING',
				'VALIDATEPASS_HL_PARKING_2',
				'VALIDATEPASS_HL_PARKING_3',
				'REGISTERPASS_HL_2',
				
			);
			
	public	$dataList=array(
				'HL_EVENTCODE',
				'HL_MESSAGES',
				'HL_RESIDENT',
				'HL_GARAGE',
				
			);
			
	public	$triggerList=array(
				'EVENTS_PARKING_HL',
			);
			
	
	
	public function before()
	{
			
			parent::before();
			$session = Session::instance();
	
	}
	
	
	public function action_index()
	{
		$_SESSION['menu_active']='rmo';
		$id_garage = $this->request->param('id');
		//echo Debug::vars('37');exit;
		$tableList=$this->tableList;
		$procedureList=$this->procedureList;
		$dataList=$this->dataList;
		$triggerList=$this->triggerList;
		
		
		$db=Model::factory('Parkdb');
		foreach($tableList as $key=>$value)
		{
			//echo Debug::vars('54', $value, $db->checkTableIsPresent($value));//exit;
			$tableListCheck[$value]=$db->checkTableIsPresent($value);
			
		}
		//echo Debug::vars('58', $tableListCheck);exit;
		//$db=Model::factory('Parkdb');
		foreach($procedureList as $key=>$value)
		{
			//echo Debug::vars('60', $value, $db->checkProcedureIsPresent($value));//exit;
			$procedureListCheck[$value]=$db->checkProcedureIsPresent($value);
			
		}
		
		foreach($triggerList as $key=>$value)
		{
			//echo Debug::vars('60', $value, $db->checkProcedureIsPresent($value));//exit;
			$triggerListCheck[$value]=$db->checktriggerIsPresent($value);
			
		}
		//echo Debug::vars('106', $triggerListCheck);exit;
		$content = View::factory('setup/tableList', array(
			'tableList'=>$tableList,
			'tableListCheck'=>$tableListCheck,
			
			'procedureList'=>$procedureList,
			'procedureListCheck'=>$procedureListCheck,
			
			
			'triggerList'=>$triggerList,
			'triggerListCheck'=>$triggerListCheck,
			
			'dataList'=>$dataList,
				
		));
        $this->template->content = $content;
		
	}
	
	
	//25.03.2025 метод для обработки запросов в части добавления и удаления таблиц.
	//для добавления таблицы должен быть создан массив, в котором последовательно перечислены нужные команды.
	
	public function action_worker()
	{
		//echo Debug::vars('81', $_POST);
		//echo Debug::vars('82', Arr::get($_POST, 'addTable'));
		//echo Debug::vars('83', Arr::get($_POST, 'delTable'));//exit;
		//обработка добавления таблицы.
		$parkDB=Model::factory('Parkdb');
		
		
		
		//31.03.2025 удалить все таблицы
		if(Arr::get($_POST, 'delAllTable'))
		{
			Log::instance()->add(Log::DEBUG, '114 Получена команда удалить все таблицы');
			
			//сначала удаляю процедуры
			foreach(array_reverse($this->procedureList) as $key=>$value)
			{
				Log::instance()->add(Log::DEBUG, '118 Удаляется процедура  '.$value);
				if( $parkDB->delProcedure(iconv('UTF-8', 'CP1251', $value))) 
				{
					Log::instance()->add(Log::DEBUG, '121 Процедура  '.$value.' удалена успешно. '.Debug::vars($parkDB->mess));
				} else {
					
					Log::instance()->add(Log::DEBUG, '124 Ошибка при удалении процедуры  '.$value.' . '.Debug::vars($parkDB->mess));
				}
				
				
			}
			
				
			//затем удаляю таблицы
			foreach(array_reverse($this->tableList) as $key=>$value)
			{
				try{
					//Database::instance('fb')->query(NULL, 'DROP TABLE '. $value);
					$parkDB->delTable(iconv('UTF-8', 'CP1251', $value));
					
				} catch (Exception $e) {
				echo Debug::vars('139', $e->getMessage());
				}	
			}
			
			//затем удаляю генераторы
			foreach($this->tableList as $key=>$value)
			{
				try{
					//Database::instance('fb')->query(NULL, 'DROP TABLE '. $value);
					$parkDB->delGenerator(iconv('UTF-8', 'CP1251', $value));
					
				} catch (Exception $e) {
				echo Debug::vars('151', $e->getMessage());
				}	
			}
			
			
			
			
			$this->redirect('/checkdb');
		}
		
		
		//31.03.2025 добавить все таблицы и процедуры
		if(Arr::get($_POST, 'addAllTable'))
		{
						
			foreach($this->tableList as $key=>$value)
			{
				//$parkDB->delTable($value);//удаляю таблицу (вдруг она есть в базе данных)
				$parkDB->addTable($value);
			}
			
			
			foreach($this->procedureList as $key=>$value)
			{
				$parkDB->addProcedure($value);
			}	
			$this->redirect('/checkdb');
		}
		
		if(Arr::get($_POST, 'addTable'))
		{
			 $table=Arr::get($_POST, 'addTable'); //получил название таблицы
					 
		 //проверяю, есть ли нужная таблица
		 //если таблица сущесвует, то завершаю работу.
		 Log::instance()->add(Log::DEBUG, '166 Добавление  таблицы '.$table);
		 if($parkDB->checkTableIsPresent($table))
		 {
			Log::instance()->add(Log::DEBUG, '169 Таблица '.$table.' уже существует. Завершаю работу.');
			$this->redirect('/checkdb');
		 } 
		 
		 Log::instance()->add(Log::DEBUG, '173 Таблица '.$table.' не существует. Продолжаю работу.');
		
		 //если таблица не сущесвует, то запускаю скрипт, который добавит и таблицу и, если необходимо, генератор
		 
		
		if($parkDB->addTable($table))
		{
			Log::instance()->add(Log::DEBUG, '190 Таблица '.$table.' Добавлена успешно.');
		} else {
			Log::instance()->add(Log::DEBUG, '192 При добавлении таблицы '.$table.' возникла ошибка: '. $parkDB->mess);
		}
		
		$this->redirect('/checkdb');
		}

		//Удаление таблицы.
		//Таблица может не удалена, если она связана с другими таблицами.
		if(Arr::get($_POST, 'delTable'))
		{
			$table=Arr::get($_POST, 'delTable'); //получил название таблицы
						
		Log::instance()->add(Log::DEBUG, '225 Удаление  таблицы '.$table);
		
		 if(!$parkDB->checkTableIsPresent($table))// проверка на наличие удаляемой таблицы
		 {
			//если таблицы нет, то завершаю работу.
			Log::instance()->add(Log::DEBUG, '228 Таблица '.$table.' уже не существует. Завершаю работу по удалению таблицы '.$table);
			$this->redirect('/checkdb');
		 } 
		 
		 Log::instance()->add(Log::DEBUG, '232 Таблица '.$table.' существует. Продолжаю работу по удалению таблицы '.$table);
		
		//если таблица есть, то удаляю таблицу		
		if($parkDB->delTable($table))
		{
			Log::instance()->add(Log::DEBUG, '237 Таблица '.$table.' удалена успешно.');
			//проверяю на наличие генератора. Если он есть, то его тоже надо удалить
			
			if($parkDB->checkGeneratorIsPresent($table))
			{
				//есть генератор. Надо удалять.
				Log::instance()->add(Log::DEBUG, '227 Генератор для таблицы '.$table.' имеется и должен быть удален.');
				if($parkDB->delGenerator($table))
				{
					//если удален успешно, то 
					Log::instance()->add(Log::DEBUG, '230 Генератор для таблицы '.$table.' удален успешно.');
				} else {
					Log::instance()->add(Log::DEBUG, '232 Ошибка при удалении генератора для таблицы '.$table);
				}
				
			} else {
				
				//нет генератора, удалять не надо.
				Log::instance()->add(Log::DEBUG, '227 Генератор для таблицы '.$table.' нет. Удалять генератор не требуется.');
			}
		} else {
			Log::instance()->add(Log::DEBUG, '239 При удалении таблицы '.$table.' возникла ошибка: '. $parkDB->mess);
		}
		
		$this->redirect('/checkdb');
		}
		
		
		//31.03.2025 добавление процедур
		if(Arr::get($_POST, 'addProcedure'))
		{
			
			$procedure=Arr::get($_POST, 'addProcedure'); //получил название процедуры
			if($parkDB->checkProcedureIsPresent($procedure))
			{
				//процедура уже есть
				Log::instance()->add(Log::DEBUG, '257 Процедура  '.$procedure.' уже существует. Завершаю работу по добавлению процедуры.');
				$this->redirect('/checkdb');
			} else {
				Log::instance()->add(Log::DEBUG, '260 Процедура  '.$procedure.' не существует. Продолжаю работу по добавлению процедуры.');
				
				
				if($parkDB->addProcedure($procedure))
				{
					Log::instance()->add(Log::DEBUG, '265 Процедура  '.$procedure.' добавлена успешно.');
				} else {
					Log::instance()->add(Log::DEBUG, '267 При добавлении процедуры '.$procedure.' возникла ошибка: '. $parkDB->mess);
				}
			}
			$this->redirect('/checkdb');
			
		}
		
		if(Arr::get($_POST, 'delProcedure'))
		{
			$parkDB->delProcedure(Arr::get($_POST, 'delProcedure'));
			$this->redirect('/checkdb');
		}
		
		
		//31.03.2025 добавление триггера
		if(Arr::get($_POST, 'addTrigger'))
		{
			
			$name=Arr::get($_POST, 'addTrigger'); //получил название процедуры
			if($parkDB->checkTriggerIsPresent($name))
			{
				//триггер уже есть
				Log::instance()->add(Log::DEBUG, '257 Триггер   '.$name.' уже существует. Завершаю работу по добавлению триггера.');
				$this->redirect('/checkdb');
			} else {
				Log::instance()->add(Log::DEBUG, '260 Триггер  '.$name.' не существует. Продолжаю работу по добавлению триггера.');
				
				
				if($parkDB->addProcedure($name))
				{
					Log::instance()->add(Log::DEBUG, '265 Триггер  '.$name.' добавлена успешно.');
				} else {
					Log::instance()->add(Log::DEBUG, '267 При добавлении триггера '.$name.' возникла ошибка: '. $parkDB->mess);
				}
			}
			$this->redirect('/checkdb');
			
		}
		
		if(Arr::get($_POST, 'delTrigger'))
		{
			//echo Debug::vars('344', $_POST);exit;
			$parkDB->delTrigger(Arr::get($_POST, 'delTrigger'));
			$this->redirect('/checkdb');
		}
		
		
		//07.04.2025 работа с данными в таблице
		if(Arr::get($_POST, 'addData'))
		{
			
			$procSql=Arr::get($_POST, 'addProcedure'); //получил название процедуры
			$parkDB->addData($procSql);
			$this->redirect('/checkdb');
			
		}
		
		
		
		if(Arr::get($_POST, 'delTableData'))
		{
		//echo Debug::vars('235', $_POST);exit;
			$parkDB->delTableData(Arr::get($_POST, 'delTableData'));
			$this->redirect('/checkdb');
		}
		
		/*9.04.2025 добавить данные в указанную таблицу
		* будеть произведен поиск файла с именем таблицы в папке data
		*/
		if(Arr::get($_POST, 'addTableData'))
		{
			//echo Debug::vars('245', $_POST);exit;
			$parkDB->addTableData(Arr::get($_POST, 'addTableData'));
			$this->redirect('/checkdb');
		}
		
	}
	
	
} 
