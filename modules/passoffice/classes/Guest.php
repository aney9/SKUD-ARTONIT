<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
25.12.2023
Класс Гость - его свойства и методы
*/

class Guest
{
	public $idOrgGuest=2;//id_org организации, используемой в качестве гостевой
	private $idOrgGuestParamName='idOrgGuest';//название параметра в таблице setting БД СКУД
	
	public $idOrgGuestArchive=3;//id_org организации, используемой в качестве архива гостей
	
	public $name;
	public $surname;
	public $patronymic;
	public $id_org;// организация, куда входит гость
	public $numdoc;//номер документа
	public $datedoc;//дата документы
	public $is_active;//активен или неактивен
	public $flag;//флаг
	public $sysnote;//разные записи по гостю
	public $note;//разные записи по гостю
	public $time_stamp;// время создания записи гостя
	public $tabnum;// табельный номер
	public $cardlist;// время создания записи гостя
	public $count_identificator;// количество идентификаторв
	//public $grz;// ГРЗ
	
	public $id_pep = 0;// id_pep гостя
	
	
	public $actionResult=0;// результат выполнения команд
	public $actionDesc=0;// пояснения к результату выполнения команд
	
	
	public function __construct($id_pep = null)
	{
		if(!is_null($id_pep)){
			
		$sql='select p.id_pep
		,p.id_org
		, p.surname
		, p.name
		, p.patronymic
		, p.numdoc
		, p.datedoc
		, p."ACTIVE" as is_active
		, p.flag
		, p.sysnote
		, p.note
		, p.time_stamp
		, p.tabnum
		
		from people p

        where p.id_pep='.$id_pep;
		
		
		
		
	
		$query= Arr::flatten(DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'))
				->as_array()
				);
		$this->id_pep=$id_pep;
		$this->name=Arr::get($query, 'NAME');
		$this->surname=Arr::get($query, 'SURNAME');
		$this->patronymic=Arr::get($query, 'PATRONYMIC');
		$this->id_org=Arr::get($query, 'ID_ORG');
		$this->numdoc=Arr::get($query, 'NUMDOC');
		$this->datedoc=Arr::get($query, 'DATEDOC');
		$this->is_active=Arr::get($query, 'IS_ACTIVE');
		$this->sysnote=Arr::get($query, 'SYSNOTE');
		$this->note=Arr::get($query, 'NOTE');
		$this->time_stamp=Arr::get($query, 'TIME_STAMP');
		$this->tabnum=Arr::get($query, 'TABNUM');
		//$this->rfid=Arr::get($query, 'RFID');
		//$this->grz=Arr::get($query, 'GRZ');
		
		$sql='select  c.id_cardtype, count(c.id_card) from card c
			where c.id_pep='.$id_pep.'
			group by c.id_cardtype';
		$this->count_identificator=	DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'))
				->as_array();
		//$this->cadlist=new Key($id_pep);
		
		}
		// 22.06.2024 это вставка - временный вариант только для того, чтобы обеспечить работу бюро пропусков на Щербинке.
		//если оператор скуд имеет id_pep= настройке в конфигах, то ему меняется id_org гостевой организации. Как следствие,
		//ФИО гостя заносится в нужную организацию, доступную только этому оператору.
		
		$configcdf=Kohana::$config->load('guest');
			if(Arr::get(Auth::instance()->get_user(), 'ID_PEP') == $configcdf->get('useridek')){
				
				$this->idOrgGuest=$configcdf->get('guestekorg');
				$this->idOrgGuestArchive=$configcdf->get('guestekatchive');
				
			}
		
	}
	
	/*
	возвращает список идентификаторов указанного типа
	*/
	public function getTypeCardList($type)
	{
		$sql='select  c.id_card from card c
		where c.id_pep='.$this->id_pep.'
		and c.id_cardtype='.$type;
		return DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'));
	}
	
	
	/*
		добавление нового гостя
		ответ - tru / false /id_pep
	*/
	public function addGuest()
	{
		//получаю ID_PEP для нового гостя
	    $query = DB::query(Database::SELECT,
			'SELECT gen_id(gen_people_id, 1) FROM rdb$database')
			->execute(Database::instance('fb'));
		$result = $query->current();
		$this->id_pep=Arr::get($result, 'GEN_ID');
		
		//echo Debug::vars('109', Arr::get($result, 'GEN_ID')); exit;
		$sql=__('INSERT INTO people (id_pep, id_db, surname, name, patronymic, id_org, note) 
                VALUES (:id,1, \':surname\', \':name\', \':patronymic\',:org,  \':note\')', array
			(
				':id'			=> $this->id_pep,
				':surname'		=> iconv('UTF-8', 'CP1251',$this->surname),
				':name'			=> iconv('UTF-8', 'CP1251',$this->name),
				':patronymic'	=> iconv('UTF-8', 'CP1251',$this->patronymic),
				':org'			=> $this->idOrgGuest,
				':note'			=> iconv('UTF-8', 'CP1251',$this->note))
				);
	
					try
		{
					
			$query = DB::query(Database::INSERT, $sql)
			->execute(Database::instance('fb'));
			
			// получение присвоенного табельного номера.
				$sql='select p.tabnum from people p
					where p.id_pep='.$this->id_pep;
				try
				{
					$query = DB::query(Database::SELECT, $sql)
					->execute(Database::instance('fb'))
					->get('TABNUM');
					
					$this->tabnum=$query;
					
					
					$this->actionResult=0;
					
					
					
					$this->setAclDefault();// заполнение таблицы SS_ACCESSUSER
					return 0;

				} catch (Exception $e) {
			
					$this->actionResult=3;
					//$this->actionDesc=__('guest.addErr', array(':surname'=>$this->surname,':name'=>$this->name,':patronymic'=>$this->patronymic,':id_pep'=>$this->id_pep));
			
					Log::instance()->add(Log::DEBUG, '178 '.$e->getMessage());
					return 3;
			
		}

				
					
			
		
		} catch (Exception $e) {
			
			$this->actionResult=3;
			$this->actionDesc=__('guest.addErr', array(':surname'=>$this->surname,':name'=>$this->name,':patronymic'=>$this->patronymic,':id_pep'=>$this->id_pep));
			
			Log::instance()->add(Log::DEBUG, '178 '.$e->getMessage());
			
		}
		
				
	}
	
	
	
	/*
	Добавление пользователя в таблицу ss_accessuser в соответствии с правами организации.
	*/
	public function setAclDefault()
	{
		$sql='select sso.id_accessname from  ss_accessorg sso
		where sso.id_org='.$this->idOrgGuest;
		
		try
		{
			
			$query = DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'))
				->as_array();
				//echo Debug::vars('158', $query); exit;
			foreach ($query as $key=>$value){
				$sql2='INSERT INTO SS_ACCESSUSER (ID_DB,ID_PEP,ID_ACCESSNAME,USERNAME) VALUES (1,'.$this->id_pep.','.Arr::get($value, 'ID_ACCESSNAME').',\'ADMIN\')';
				//echo Debug::vars('158', $sql2); exit;
				try {
						$query = DB::query(Database::INSERT, $sql2)
						->execute(Database::instance('fb'));
						$this->actionResult=0;
						
				} catch (Exception $e) {
				
					$this->actionResult=3;
					//$this->actionDesc=__('guest.addTabNumErr', array(':surname'=>$this->surname,':name'=>$this->name,':patronymic'=>$this->patronymic,':tabnum'=>$this->tabnum));
					Log::instance()->add(Log::DEBUG, '178 '.$e->getMessage());
					return 3;
				}
			
			}
			
			$this->actionResult=0;
			return 0;
			//$this->actionDesc=__('guest.addTabNumOk', array(':surname'=>$this->surname,':name'=>$this->name,':patronymic'=>$this->patronymic,':tabnum'=>$this->tabnum));
		
		} catch (Exception $e) {
			
			$this->actionResult=3;
			//$this->actionDesc=__('guest.addTabNumErr', array(':surname'=>$this->surname,':name'=>$this->name,':patronymic'=>$this->patronymic,':tabnum'=>$this->tabnum));
			Log::instance()->add(Log::DEBUG, '178 '.$e->getMessage());
			return 3;
			
		}
		
	}
	
	
	public function setTabNum()
	{
		
			$sql='update people p
			set p.tabnum=\''.$this->tabnum.'\'
			where p.id_pep='.$this->id_pep;
		try
		{
			
			$query = DB::query(Database::UPDATE, $sql)
				->execute(Database::instance('fb'));
			$this->actionResult=0;
			$this->actionDesc=__('guest.addTabNumOk', array(':surname'=>$this->surname,':name'=>$this->name,':patronymic'=>$this->patronymic,':tabnum'=>$this->tabnum));
		
		} catch (Exception $e) {
			
			$this->actionResult=3;
			$this->actionDesc=__('guest.addTabNumErr', array(':surname'=>$this->surname,':name'=>$this->name,':patronymic'=>$this->patronymic,':tabnum'=>$this->tabnum));
			Log::instance()->add(Log::DEBUG, '178 '.$e->getMessage());
			
		}
		
	}
	
	/*
	сохранение параметров документа для пипла.
	особенность в том, что если не указан хоть один параметр, то сохранить документ не надо.
	*/
	public function addDoc()
	{
		
			$sql='update people p
			set p.datedoc=\''.$this->datedoc.'\',
			p.numdoc=\''.$this->numdoc.'\'
			where p.id_pep='.$this->id_pep;
		//echo Debug::vars('147', $sql); exit; 
		try {
		$query = DB::query(Database::UPDATE, iconv('UTF-8', 'CP1251', $sql))
			->execute(Database::instance('fb'));
			
			$this->actionResult=0;
			//$this->actionDesc=__('guest.adddocOK', array(':numdoc'=>$this->numdoc));
		} catch (Exception $e) {
			Log::instance()->add(Log::DEBUG, $e->getMessage());
			$this->actionResult=3;
			
		}	
	}
	
	/*
		29.12.2023
		Удаление гостя по его tabnum
	*/
	public function delOnTabNum()
	{
		
		$sql='delete from people p 
			where p.tabnum=\''.$this->tabnum.'\'';
		try {
			$query = DB::query(Database::DELETE, $sql)
				->execute(Database::instance('fb'));
			$this->actionResult=0;
			//$this->actionDesc = __('guest.delOnTabNumOK', array(':tabnum'=>$this->tabnum));
			$this->actionDesc = __('guest.delOnTabNumOk', array(':tabnum'=>$this->tabnum));
			//echo Debug::vars('219', $this->actionResult,  $this->actionDesc, __('guest.delOnTabNumOk',  array(':tabnum'=>$this->tabnum)) ); //exit;
				
		} catch (Exception $e) {
			Log::instance()->add(Log::DEBUG, $e->getMessage());
			$this->actionResult=3;
			$this->actionDesc=__('guest.delOnTabNumErr', array(':tabnum'=>$this->tabnum));
			HTTP::redirect('errorpage?err=37'.Text::limit_chars($e->getMessage(), 50));
		}	
	}
	
	
	
	/*
		29.12.2023
		Проверка наличия гостя по его tabnum
	*/
	public function checkOnTabNum()
	{
		
		
		$sql='select p.id_pep from people p 
			where p.tabnum=\''.$this->tabnum.'\'';
		try {
			$query = DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'))
				->get('ID_PEP');
				
			Log::instance()->add(Log::DEBUG, Debug::vars($query));	
			if(is_null($query)) $this->actionResult=0;// пипел с таким табельным номером существует
			if(is_null($query))	$this->actionResult=1;// пипла с таким табельным номером нет
			
			//$this->actionDesc = __('guest.delOnTabNumOK', array(':tabnum'=>$this->tabnum));
			$this->actionDesc = __('guest.delOnTabNumOk', array(':tabnum'=>$this->tabnum));
			//echo Debug::vars('219', $this->actionResult,  $this->actionDesc, __('guest.delOnTabNumOk',  array(':tabnum'=>$this->tabnum)) ); //exit;
				
		} catch (Exception $e) {
			Log::instance()->add(Log::DEBUG, $e->getMessage());
			$this->actionResult=3;
			$this->actionDesc=__('guest.delOnTabNumErr', array(':tabnum'=>$this->tabnum));
			HTTP::redirect('errorpage?err=37'.Text::limit_chars($e->getMessage(), 50));
		}	
	}
	
	/*
		29.12.2023
		Проверка наличия гостя по его id_pep
	*/
	public function checkOnIdPep()
	{
		
		
		$sql='select p.id_pep from people p 
			where p.id_pep=\''.$this->id_pep.'\'';
		try {
			$query = DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'))
				->get('ID_PEP');
				
			Log::instance()->add(Log::DEBUG, Debug::vars($query));	
			if(is_null($query)) $this->actionResult=0;// пипел с таким id_pep номером существует
			if(is_null($query))	$this->actionResult=1;// пипла с таким id_pep номером нет
			
			$this->actionDesc = __('guest.delOnIdPepOk', array(':tabnum'=>$this->id_pep));
			
				
		} catch (Exception $e) {
			Log::instance()->add(Log::DEBUG, $e->getMessage());
			$this->actionResult=3;
			$this->actionDesc=__('guest.delOnIdPepErr', array(':tabnum'=>$this->id_pep));
			HTTP::redirect('errorpage?err=37'.Text::limit_chars($e->getMessage(), 50));
		}	
	}
	
	
	
	/*
		29.12.2023
		Удаление гостя по его id_pep
	*/
	public function delOnIdPep()
	{
		
		$sql='delete from people p 
			where p.id_pep='.$this->id_pep;
			//echo Debug::vars('307', $sql); exit;
		try {
			$query = DB::query(Database::DELETE, $sql)
				->execute(Database::instance('fb'));
			$this->actionResult=0;
			//$this->actionDesc = __('guest.delOnIdPepOk', array(':id_pep'=>$this->id_pep));
			return 0;
			
				
		} catch (Exception $e) {
			Log::instance()->add(Log::DEBUG, $e->getMessage());
			$this->actionResult=3;
			//$this->actionDesc=__('guest.delOnIdPepErr', array(':id_pep'=>$this->id_pep));
			return 3;
			
		}	
	}
	
	
	/*
	Поиск пипла по указанному фильтру.
	В ответ передает массив id_pep
	
	*/
	
	public function find($filter, $mode)
	{
		$sql='';
		try {
			$query = DB::query(Database::INSERT, $sql)
				->execute(Database::instance('fb'));
			$this->actionResult=0;
			$this->actionDesc=__('', array(':'=>''));//пояснения к успешному выполнению запроса. Например, $this->actionDesc = __('guest.delOnIdPepOk', array(':tabnum'=>$this->id_pep));
				
		} catch (Exception $e) {
			Log::instance()->add(Log::DEBUG, $e->getMessage());//логирование ошибки в файл
			$this->actionResult=3;
			$this->actionDesc=__('', array(':'=>''));
		}	
		
	}
	
	/*
	
	Шаблон для реализации action
	Результатом является заполение параметров
	Типы результатов связанаы с файлом отображения на экране AlertState.php и имеют такие значения:
	$arrayType=array( '0'=>'alert_success', '1'=>'alert_info','2'=>'alert_warning', '3'=>'alert_danger');// перечень статусов
	0 - запрос выполнен успешно.
	3 - запрос выполнен с ошибкой базы данных
	остальные варианты ответов не определены и могу использоваться на усмотрение программиста.
	
	$this->actionResult надо выбирать из указанного ряда, и тогда отображение на экране будет соответсвовать общему стандарту.
	$this->actionDesc необходимо строить для каждого action самостоятель, в зависимости от выполняемой задачи.
	
	*/
	public function tmp()
	{
		
		$sql='';
		try {
			$query = DB::query(Database::INSERT, $sql)
				->execute(Database::instance('fb'));
			$this->actionResult=0;
			$this->actionDesc=__('', array(':'=>''));//пояснения к успешному выполнению запроса. Например, $this->actionDesc = __('guest.delOnIdPepOk', array(':tabnum'=>$this->id_pep));
				
		} catch (Exception $e) {
			Log::instance()->add(Log::DEBUG, $e->getMessage());//логирование ошибки в файл
			$this->actionResult=3;
			$this->actionDesc=__('', array(':'=>''));
		}	
	}
	
	
	/*
	25.12.2023 Отметка о выходе вручную
	*/
	
	public function forceexit()
	{
		
		//удаляю карту у гостя
		$sql = 'delete from card c
			where c.id_pep='.$this->id_pep;
		
		try {
				$query = DB::query(Database::DELETE, $sql)
					->execute(Database::instance('fb'));
					//$localResult_1=0;
				return 0;	
				
			} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, $e->getMessage());
			
				
				return 3;
			
		}	
		
		
	}
	
	
	
	public function moveToGuest()
	{
		//перенос гостя в Гость (т.е. он стал активным)
		$sql = 'update people p
				set p.id_org='.$this->idOrgGuest.'
				where p.id_pep='.$this->id_pep;
		try {		
		
			$query = DB::query(Database::UPDATE, $sql)
				->execute(Database::instance('fb'));
			return 0;	
				
					} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, $e->getMessage());
				
				return 3;
			
		}	
		
	}
	
	
	public function moveToArchive()
	{
		//перенос гостя в Архив
		$sql = 'update people p
				set p.id_org='.$this->idOrgGuestArchive.'
				where p.id_pep='.$this->id_pep;
		try {		
		
			$query = DB::query(Database::UPDATE, $sql)
				->execute(Database::instance('fb'));
			return 0;	
				
					} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, $e->getMessage());
				
				return 3;
			
		}	
		
	}
	
	
	/**21.08.2024 Обновление данных гостя
	*
UPDATE PEOPLE
SET ID_ORG = 549,
    SURNAME = 'ÀÂÄÅÅÂ',
    NAME = 'Ìàêñèì',
    PATRONYMIC = 'Àëåêñàíäðîâè÷',
    DATEBIRTH = NULL,
    PLACELIFE = NULL,
    PLACEREG = NULL,
    PHONEHOME = NULL,
    PHONECELLULAR = NULL,
    PHONEWORK = NULL,
    NUMDOC = NULL,
    DATEDOC = NULL,
    PLACEDOC = NULL,
    WORKSTART = '9:00:00',
    WORKEND = '17:00:00',
    "ACTIVE" = 1,
    FLAG = 0,
    LOGIN = 'USER7607',
    PSWD = '',
    ID_DEVGROUP = NULL,
    ID_ORGCTRL = NULL,
    PEPTYPE = 0,
    POST = NULL,
    PLACEBIRTH = NULL,
    ID_PLAN = NULL,
    PRESENT = 0,
    NOTE = NULL,
    ID_AREA = 0,
    TABNUM = 'vnii_5876',
    TIME_STAMP = '29-SEP-2023 12:02:21',
    AUTHMODE = 5
WHERE (ID_PEP = 7607) AND (ID_DB = 1);


	*
	*/
	public function update()
	{
		//перенос гостя в Архив
	
		$sql=__('update people (id_pep, id_db, surname, name, patronymic, id_org, note) 
                VALUES (:id,1, \':surname\', \':name\', \':patronymic\',:org,  \':note\')', array
			(
				':id'			=> $this->id_pep,
				':surname'		=> iconv('UTF-8', 'CP1251',$this->surname),
				':name'			=> iconv('UTF-8', 'CP1251',$this->name),
				':patronymic'	=> iconv('UTF-8', 'CP1251',$this->patronymic),
				':org'			=> $this->idOrgGuest,
				':note'			=> iconv('UTF-8', 'CP1251',$this->note))
				);
		$sql='UPDATE PEOPLE
	SET 
    SURNAME = \''.iconv('UTF-8', 'CP1251',$this->surname).'\',
    NAME = \''.iconv('UTF-8', 'CP1251',$this->name).'\',
    PATRONYMIC = \''.iconv('UTF-8', 'CP1251',$this->patronymic).'\',
     NUMDOC = NULL,
    DATEDOC = NULL,
    PLACEDOC = NULL,
    NOTE = \''.iconv('UTF-8', 'CP1251',$this->note).'\'
   
WHERE (ID_PEP = '.$this->id_pep.') AND (ID_DB = 1)';
//echo Debug::vars('605', $sql, $this);exit;
		try {		
		
			$query = DB::query(Database::UPDATE, $sql)
				->execute(Database::instance('fb'));
			return 0;	
				
					} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, $e->getMessage());
				
				return 3;
			
		}	
		
	}
	
	
	
}
