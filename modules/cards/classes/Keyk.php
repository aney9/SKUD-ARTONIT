<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
25.12.2023
Класс Keyk - инфомрация об идентификаторе 
*/

class Keyk
{
	
	public $id_pep;
	public $id_card;
	public $id_card_on_screen;
	public $id_card_on_DEC;
	public $timeend;
	public $timestart;
	

	public $note;
	public $status;
	public $is_active;
	public $flag=0;
	public $id_cardtype;
	public $createdat;
	public $rfidmode;//тип RFID идентификатора. Предполагается: зашифрованный, UHF  и т.п.
	
	public $actionResult=0;// результат выполнения команд
	public $actionDesc=0;// пояснения к результату выполнения команд
	
	public $baseFormatRfid=0;
	public $screenFormatRFID=0;
	
	
	


	/*
		9.04.2024
		приведение (преобразование) форматов номера карты от формата screen к формату хранения base.
		$pattern - номер идентификатора для обработки
		$bf - base format базовый формат
		$sf - screen format - формат вывода номера на экран.
	*/
	public function _screenToBase($pattern, $bf=null, $sf=null, $id_cardtype=1)
	{
			
			if(is_null($bf)) $bf=$this->baseFormatRfid;
			if(is_null($sf)) $sf=$this->screenFormatRFID;
			//if(is_null($rf)) $rf=$this->regFormatRfid;
			
			$temp=$pattern;

			if($id_cardtype ==1){//обработка идентификаторов типа RFID
			
			
			if($bf==0){//в базе данных номера карт хранятся в формате HEX
				
				switch($sf){
					case 0:
						//ничего не делать, т.к. форматы одинаковы
					break;
					case 1:
						//привожу DEC к формату 001A к формату HEX.
					break;
					case 2:
						//привожу DEC к формату HEX. Значит, считываль работает в формате длинного десятичного числа.
						//$pattern=strtoupper(dechex($pattern));
						$pattern=Model::factory('stat')->decToHex($pattern);
							
					break;
					default:
						$pattern='---';
					break;
					
				}
			}
			
			if($bf==1){//в базе данных номера карт хранятся в формате 001A
				switch($sf){
					case 0:
						//привожу HEX к формату 001A
						//такой функции нет.
						
					break;
					case 1:
						//ничего не делаю, т.к. форматы одинаковы.
					break;
					case 2:
						//привожу длинный DEC к формату 001A. Значит, считываль работает в формате длинного десятичного числа.
						$pattern=Model::factory('stat')->decDigitTo001A($pattern);
					break;
					default:
					
					break;
					
				}
			}
		}	
	
		$this->id_card=$pattern;
		return 0;
	}
	
	/*
	9.04.2024 поиск идентификатора строгое совпадение.
	Предполагается, что id_card уже "приведен" к формату хранения данных в базе данных.
	
	*/
	
	public function search()
	{
		$sql='select c.id_card from card c
			where c.id_card like \'%'.$this->id_card.'%\'
			and c.id_cardtype='.$this->id_cardtype;
	//echo Debug::vars('119', $sql);exit;		
		try {
			$query= DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'))
				->as_array()
				;
				
		return $query;
		} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, $e->getMessage());
				$this->actionDesc=$e->getMessage();
			}	
			
	}
	
	
	/*
		9.04.2024 приведение (преобразование) форматов номера карты к формату хранения номера в базе данных.
		11.08.2024 приведение номера идентификатора от формата базы данных к формату отображения
		$pattern - номер идентификатора для обработки
		$bf - base format базовый формат
		$sf - screen format - формат вывода номера на экран.
	*/
	public function convertFormat($pattern, $bf=null, $sf=null)
	{
			
			$bf=$this->baseFormatRfid;
			$sf=$this->screenFormatRFID;
			$result=$pattern;
		//echo Debug::vars('138', $this->id_cardtype, $pattern, 'bf='.$bf, 'sf='.$sf); exit;	
			if($this->id_cardtype ==1){
			
			
			if($bf==0){//в базе данных номера карт хранятся в формате HEX 8 байт
				switch($sf){
					case 0://вывод в формате baseFormatRfid
						//ничего не делать, т.к. форматы одинаковы
						$result=$pattern;
					break;
					case 1://вывод в формате 001A
						$result='---';
					break;
					case 2:
						//привожу DEC к формату HEX. Значит, считываль работает в формате длинного десятичного числа.
						$result=Model::factory('stat')->hexToDec($pattern);
					break;
					default:
						$result='---';
					break;
					
				}
			}
			
			if($bf==1){//в базе данных номера карт хранятся в формате 001A
				switch($sf){
					case 0:
						//привожу HEX к формату 001A
						$result=$pattern;
					break;
					case 1:
						//ничего не делаю, т.к. форматы одинаковы.
						$result=$pattern;
					break;
					case 2:
						//привожу длинный DEC к формату HEX. Значит, считываль работает в формате длинного десятичного числа.
						$result=Model::factory('stat')->conv001AToDec($pattern);
					break;
					default:
					
					break;
					
				}
			}
		}	
		
		$this->id_card_on_screen=$result;
		return 0;
	}
	
	/**20.08.2024 преобразование номера идентификатора от формата хранения в базе данных в десятичный вид.
	*/
	public function makeDecFromBase($pattern)
	{
			
			$bf=$this->baseFormatRfid;
			$sf=$this->screenFormatRFID;
			$result='';
		//echo Debug::vars('138', $this->id_cardtype, $pattern, $bf, $sf); //exit;	
			if($this->id_cardtype ==1){//обработка идентификаторов RFID
			
			
			if($bf==0){//в базе данных номера карт хранятся в формате HEX 8 байт
				
						$result=Model::factory('stat')->hexToDec($pattern);
				
			}
			
			if($bf==1){//в базе данных номера карт хранятся в формате 001A
				
						$result=Model::factory('stat')->conv001AToDec($pattern);

			}
		}	
		
		//echo Debug::vars('218', $result);exit;
		return $result;
	}
	
	/**11.08.2024 Класс работы с номером идентификатора
	*@input - номер идентификатора в формате базы данных
	*/
	public function __construct($card = null)
	{
		if(!is_null(Kohana::$config->load('system')->get('baseFormatRfid', 0))) $this->baseFormatRfid=Kohana::$config->load('system')->get('baseFormatRfid', 0);
		if(!is_null(Kohana::$config->load('system')->get('screenFormatRFID'))) $this->screenFormatRFID=Kohana::$config->load('system')->get('screenFormatRFID');
		
		if(!is_null($card)){
			$sql='select * from card c
				where c.id_card=\''.$card.'\'';
			try {
				$query= Arr::flatten(DB::query(Database::SELECT, $sql)
					->execute(Database::instance('fb'))
					->as_array()
			);
			} catch (Exception $e) {
					Log::instance()->add(Log::DEBUG, $e->getMessage());
					$this->actionDesc=$e->getMessage();
			
			}
			
			//echo Debug::vars('29', $sql, $query); exit;
			$this->id_card=$card;
			$this->id_pep=Arr::get($query, 'ID_PEP');
			$this->id_card=Arr::get($query, 'ID_CARD');
			$this->timestart=Arr::get($query, 'TIMESTART');
			$this->timeend=Arr::get($query, 'TIMEEND');
			$this->status=Arr::get($query, 'STATUS');
			$this->rfidmode=Arr::get($query, 'STATUS');
			$this->is_active=Arr::get($query, 'ACTIVE');
			$this->flag=Arr::get($query, 'FLAG');
			$this->id_cardtype=Arr::get($query, 'ID_CARDTYPE');
			$this->createdat=Arr::get($query, 'CREATEDAT'); 
			$this->note=Arr::get($query, 'NOTE'); 
			$this->id_card_on_DEC=$this->makeDecFromBase($card); 

			$this->convertFormat($card, $this->baseFormatRfid, $this->screenFormatRFID);//подготовил формат для показа на экран
			//echo Debug::vars('259', $this); exit;
		}
	
		
	}
	
	
	
	
	
	/*
	возвращает список идентификаторов указанного типа
	*/
	public function getTypeCardList($type) //возвращает список идентификаторов указанного типа
	{
		$sql='select  c.id_card from card c
		where c.id_pep='.$this->id_pep.'
		and c.id_cardtype='.$type;
		try {
		return DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'));
			} catch (Exception $e) {
				$this->actionResult=3;
				Log::instance()->add(Log::DEBUG, $e->getMessage());
				$this->actionDesc=$e->getMessage();
			}	
	}
	
	/*
	проверяет наличие указанного идентификатора указанного типа в базе данных.
	ответ - id_pep владельца идентификатора (если она найдена),
	либо -1 если идентификатор не найден
	*/
	public function check($type) //проверяет наличие указанного идентификатора указанного типа.
	{
		$sql='select c.id_pep from card c
            where c.id_card=\''.$this->id_card.'\'
            and c.id_cardtype='.$type;
			//echo Debug::vars('87', $sql); exit;
			try{
		return DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'))
				->get('ID_PEP');
		} catch (Exception $e) {
			Log::instance()->add(Log::DEBUG, $e->getMessage());
			return -1;
		}	
				
	}
	
	
	
	/*
	28.12.2023 функция сохранения карты.
	*/
	public function addRfid() //функция сохранения карты.
	{
		
		$sql=__('INSERT INTO CARD (ID_CARD,ID_DB,ID_PEP, TIMESTART,TIMEEND,NOTE, STATUS,"ACTIVE",FLAG,ID_CARDTYPE)
					VALUES (
					\':id_card\'
					,:id_db
					,:id_pep
					, \':timestart\'
					,\':timeend\'
					,\':note\'
					,:status
					,:is_active
					,:flag
					,:id_cardtype
					)', array
					(
					':id_card'=>$this->id_card
					,':id_db'=>1
					,':id_pep'=>$this->id_pep
					,':timestart'=>$this->timestart
					,':timeend'=>$this->timeend
					,':note'=>Text::limit_chars($this->note, constants::RFID_NOTE)
					,':status'=>$this->rfidmode
					,':is_active'=>1
					,':flag'=>$this->flag
					,':id_cardtype'=>1
					));
		//echo Debug::vars('161', $this, $sql); exit; 
		try {
				$query = DB::query(Database::INSERT,  iconv('UTF-8', 'CP1251', $sql))
					->execute(Database::instance('fb'));
				$this->actionResult=0;
				return 0;
				//$this->actionDesc=__('guest.addRfidOk', array(':id_card'=>$this->id_card));
			
			} catch (Exception $e) {
				
				$this->actionResult=3;
				Log::instance()->add(Log::DEBUG, $e->getMessage());
				$this->actionDesc=$e->getMessage();
				
				return 3;
				
			}	
				
	}
	
	
	/*
	получить список идентификаторов для указанного пипла
	*/
	
	public function getListByPeople($id_pep, $cardType)
	{
		$query = DB::query(Database::SELECT, 
			'SELECT * FROM card WHERE id_pep = :id_pep and id_cardtype=:cardType')
			->param(':id_pep', $id_pep)
			->param(':cardType', $cardType)
			->execute(Database::instance('fb'));
		//echo Debug::vars('375', $query);exit;
		//echo Debug::vars('377',$query->as_array());exit;
		return $query->as_array();
	}
	
	/*
		Удаление указанного идентификатора
	*/
	
	public function delCard()
	{
		$sql='delete from card where id_card=\''.$this->id_card.'\'';
		//echo Debug::vars('386', $sql);//exit;
		try {
				DB::query(Database::DELETE,$sql)	
				->execute(Database::instance('fb'));
				$result= 0;	
				
			} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, $e->getMessage());
				
				$result= 3;
			}
		
		//echo Debug::vars('398', $result);exit;
		return $result;
	}
	
	
	/*
		Удаление всех идентификаторов для указанного id_pep
	*/
	
	public function delCardForPeople($id_pep)
	{
			$sql='delete from card c
				where c.id_pep='. $id_pep;
			try {
				DB::query(Database::DELETE,$sql)	
				->execute(Database::instance('fb'));
				return 0;	
				
			} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, $e->getMessage());
				
				return 3;
			}
	}
	
	/*
	11.02.2024
	Обновление данных указанной карты.
	*/
	
	public function update()
	{
			
			/*
			
UPDATE CARD
SET ID_PEP = 13792,
    ID_ACCESSNAME = NULL,
    TIMESTART = '1-OCT-2023 00:00:00',
    TIMEEND = '1-OCT-2024 00:00:00',
    NOTE = '',
    STATUS = 0,
    "ACTIVE" = 1,
    FLAG = 0,
    ID_CARDTYPE = 1,
    CREATEDAT = '29-SEP-2023 12:03:25'
WHERE (ID_CARD = '00084AC7') AND (ID_DB = 1);


*/
			$sql='UPDATE CARD
			SET TIMESTART = \''.$this->timestart.'\',
				TIMEEND = \''.$this->timeend.'\',
				NOTE = \''.$this->note.'\',
				STATUS = \''.$this->rfidmode.'\',
				"ACTIVE" = '.($this->is_active? 1 : 0 ).'
				WHERE (ID_CARD = \''.$this->id_card.'\') AND (ID_DB = 1)';
				//echo Debug::vars('242', $this,  $sql); exit;
			try {
				DB::query(Database::UPDATE,iconv('UTF-8','CP1251', $sql))	
				->execute(Database::instance('fb'));
				return 0;	
				
			} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, $e->getMessage());
				
				return 3;
			}
	}
	
}
