<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
24.06.2025
Класс Гость - его свойства и методы
В отличии от Guest это Guest2 добавляет данные в таблицу guest
*/

class Guest2
{
	public $idOrgGuest=2;//id_org организации, используемой в качестве гостевой
	// private $idOrgGuestParamName='idOrgGuest';//название параметра в таблице setting БД СКУД
	
	// public $idOrgGuestArchive=3;//id_org организации, используемой в качестве архива гостей
	public $id_guest;
	public $surname;
	public $name;
	public $patronymic;
	
	// public $id_org;// организация, куда входит гость
	public $docnum;//номер документа
	public $placedoc;
	public $docdate;//дата документы
	public $is_active;//активен или неактивен
	public $flag;//флаг
	public $sysnote;//разные записи по гостю
	public $note;//разные записи по гостю
	public $time_stamp;// время создания записи гостя
	public $tabnum;// табельный номер
	public $cardlist;// время создания записи гостя
	public $count_identificator;// количество идентификаторв
	public $grz;// ГРЗ

	//public $timeplan;//плановое время прихода

	public $login;
	public $pswd;
	public $id = 0;// id_pep гостя
	
	public $timeplan;
	public $timevalid;
	public $actionResult=0;// результат выполнения команд
	public $actionDesc=0;// пояснения к результату выполнения команд
	
	
	public function __construct($id_pep = null)
{
    if (!is_null($id_pep)) {
        $sql = 'select p.id_pep
            ,p.id_org
            ,p.surname
            ,p.name
            ,p.patronymic
            ,p.numdoc
            ,p.datedoc
            ,p."ACTIVE" as is_active
            ,p.flag
            ,p.sysnote
            ,p.note
            ,p.time_stamp
            ,p.tabnum
            ,p.pswd
            ,p.login
            from PEOPLE p
            where p.id_pep='.$id_pep;

        $query = Arr::flatten(DB::query(Database::SELECT, $sql)
            ->execute(Database::instance('fb'))
            ->as_array()
        );
        $this->id_pep = $id_pep;
        $this->name = iconv('CP1251', 'UTF-8', Arr::get($query, 'NAME', ''));
        $this->surname = iconv('CP1251', 'UTF-8', Arr::get($query, 'SURNAME', ''));
        $this->patronymic = iconv('CP1251', 'UTF-8', Arr::get($query, 'PATRONYMIC', ''));
        $this->id_org = Arr::get($query, 'ID_ORG');
        $this->numdoc = iconv('CP1251', 'UTF-8', Arr::get($query, 'NUMDOC', ''));
        $this->docdate = Arr::get($query, 'DATEDOC'); // Дата уже в формате DD.MM.YYYY
        $this->is_active = Arr::get($query, 'IS_ACTIVE');
        $this->sysnote = iconv('CP1251', 'UTF-8', Arr::get($query, 'SYSNOTE', ''));
        $this->note = iconv('CP1251', 'UTF-8', Arr::get($query, 'NOTE', ''));
        $this->time_stamp = Arr::get($query, 'TIME_STAMP');
        $this->tabnum = Arr::get($query, 'TABNUM');

        $sql = 'select c.id_cardtype, count(c.id_card) from card c
            where c.id_pep='.$id_pep.'
            group by c.id_cardtype';
        $this->count_identificator = DB::query(Database::SELECT, $sql)
            ->execute(Database::instance('fb'))
            ->as_array();
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
	//30.06.2025 внесены правки в добавление гостя (пипла)
	public function addGuest()
	{
		//получаю ID_PEP для нового гостя
	//     $id_guest = DB::query(Database::SELECT,
	// 		'SELECT gen_id(GEN_PEOPLE_ID, 1) FROM rdb$database')
	// 		->execute(Database::instance('fb'))
	// 		->get('GEN_ID');
	// 	//echo Debug::vars('111', $id_guest);exit;	
		
	// 	$sql=__('INSERT INTO people (id_pep, id_db, id_org, surname, name, patronymic, ) 
    //             VALUES (:id,1, \':surname\', \':name\', \':patronymic\')', array
	// 		(
	// 			':id'			=> $id_guest,
	// 			':surname'		=> iconv('UTF-8', 'CP1251',$this->surname),
	// 			':name'			=> iconv('UTF-8', 'CP1251',$this->name),
	// 			':patronymic'	=> iconv('UTF-8', 'CP1251',$this->patronymic),
				
	// 			));
	// //echo Debug::vars('111', $sql);exit;
	// 	try
	// 	{
					
	// 		$query = DB::query(Database::INSERT, $sql)
	// 		->execute(Database::instance('fb'));
	// 		$this->id= $id_guest;
	// 		return $id_guest;
			
		
	// 	} catch (Exception $e) {
			
	// 		$this->actionResult=3;
	// 		$this->actionDesc=__('guest.addErr', array(':surname'=>$this->surname,':name'=>$this->name,':patronymic'=>$this->patronymic,':id_pep'=>$this->id_pep));
			
	// 		Log::instance()->add(Log::DEBUG, '178 '.$e->getMessage());
	// 		return -1;
	// 	}
		

		$query = DB::query(Database::SELECT,
			'SELECT gen_id(gen_people_id, 1) FROM rdb$database')
			->execute(Database::instance('fb'));
		$result = $query->current();
		$this->id_pep=Arr::get($result, 'GEN_ID');
		
		//echo Debug::vars('109', Arr::get($result, 'GEN_ID')); exit;
		$sql=__('INSERT INTO people (id_pep, id_db, surname, name, patronymic, id_org, numdoc, datedoc, note) 
                VALUES (:id,1, \':surname\', \':name\', \':patronymic\',:org, \':numdoc\', \':datedoc\',  \':note\')', array
			(
				':id'			=> $this->id_pep,
				':surname'		=> iconv('UTF-8', 'CP1251',$this->surname),
				':name'			=> iconv('UTF-8', 'CP1251',$this->name),
				':patronymic'	=> iconv('UTF-8', 'CP1251',$this->patronymic),
				':org'			=> $this->idOrgGuest,
				':numdoc'       => $this->numdoc,
				':datedoc'      => $this->docdate,
				':note'			=> iconv('UTF-8', 'CP1251',$this->note))
				);

				//echo Debug::vars('197', $sql);exit;
				//$id = $this->id_pep;
	
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
					
					
					
					//$this->setAclDefault();// заполнение таблицы SS_ACCESSUSER
					return $this->id_pep;

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
	public function setAclDefault($id_pep, $id_accessname)
{
    $deleteSql = 'DELETE FROM SS_ACCESSUSER WHERE ID_PEP = ' . (int)$id_pep;
    DB::query(Database::DELETE, $deleteSql)->execute(Database::instance('fb'));
    
    $insertSql = 'INSERT INTO SS_ACCESSUSER (ID_DB, ID_PEP, ID_ACCESSNAME, USERNAME) VALUES (1, ' . (int)$id_pep . ', ' . (int)$id_accessname . ', \'ADMIN\')';
    DB::query(Database::INSERT, $insertSql)->execute(Database::instance('fb'));
    
    $this->actionResult = 0;
    return 0;
}
	
	
	public function WsetTabNum()
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
	public function WaddDoc()
	{
		
			$sql='update people p
			set p.datedoc=\''.$this->docdate.'\',
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
	public function WdelOnTabNum()
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
	public function WcheckOnTabNum()
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
	public function WcheckOnIdPep()
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
	public function WdelOnIdPep()
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
	
	public function Wfind($filter, $mode)
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
	public function Wtmp()
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
	07.07.2025 Отметка о выходе вручную
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



protected function formatDateForFirebird($date)
{
    if (!$date) {
        return null;
    }
    try {
        $dateObj = DateTime::createFromFormat('d.m.Y', $date);
        if ($dateObj && $dateObj->format('d.m.Y') === $date) {
            return $dateObj->format('Y-m-d');
        }
    } catch (Exception $e) {
        Log::instance()->add(Log::DEBUG, 'Invalid date format: ' . $date);
    }
    return null;
}

public static function getPeopleById($id_pep_array)
{
    $result = [];
    if (empty($id_pep_array)) {
        return $result;
    }

    $id_pep_array = array_map('intval', $id_pep_array);
    $id_pep_list = implode(',', $id_pep_array);

    $sql = 'SELECT p.id_pep, p.surname, p.name, p.patronymic
            FROM PEOPLE p
            WHERE p.id_pep IN ('.$id_pep_list.')';

    try {
        $query = DB::query(Database::SELECT, $sql)
            ->execute(Database::instance('fb'))
            ->as_array();

        foreach ($query as $row) {
            $result[] = [
                'id_pep' => $row['ID_PEP'],
                'surname' => iconv('CP1251', 'UTF-8', Arr::get($row, 'SURNAME', '')),
                'name' => iconv('CP1251', 'UTF-8', Arr::get($row, 'NAME', '')),
                'patronymic' => iconv('CP1251', 'UTF-8', Arr::get($row, 'PATRONYMIC', ''))
            ];
        }
    } catch (Exception $e) {
        Log::instance()->add(Log::DEBUG, 'Error fetching people by id_pep: ' . $e->getMessage());
    }

    return $result;
}

public function update($id_pep)
{
    $formattedDate = $this->formatDateForFirebird($this->docdate);
    $sql = 'UPDATE PEOPLE
        SET 
        SURNAME = \''.iconv('UTF-8', 'CP1251', addslashes($this->surname)).'\',
        NAME = \''.iconv('UTF-8', 'CP1251', addslashes($this->name)).'\',
        PATRONYMIC = \''.iconv('UTF-8', 'CP1251', addslashes($this->patronymic)).'\',
        NUMDOC = \''.iconv('UTF-8', 'CP1251', addslashes($this->numdoc)).'\',
        DATEDOC = '.($formattedDate ? '\''.addslashes($formattedDate).'\'' : 'NULL').',
        PLACEDOC = NULL,
        NOTE = \''.iconv('UTF-8', 'CP1251', addslashes($this->note)).'\'
        WHERE (ID_PEP = '.(int)$this->id_pep.') AND (ID_DB = 1)';
    try {        
        $query = DB::query(Database::UPDATE, $sql)
            ->execute(Database::instance('fb'));
        return 0;    
    } catch (Exception $e) {
        Log::instance()->add(Log::DEBUG, $e->getMessage());
        return 3;
    }    
}

	public function getOrganizations() {
        $organizations = [];
        try {
            $result = DB::select('ID_ORG', 'NAME')
                ->from('ORGANIZATION')
                ->execute(Database::instance('fb'))
                ->as_array();
            foreach ($result as $row) {
                $name = trim(iconv('CP1251', 'UTF-8//IGNORE', $row['NAME']));
                if (!empty($name)) {
                    $organizations[] = [
                        'id' => $row['ID_ORG'],
                        'name' => $name,
                    ];
                }
            }

        } catch (Exception $e) {
            Log::instance()->add(Log::ERROR, 'Ошибка при загрузке организаций: ' . $e->getMessage());
        }
        return $organizations;
    }

    public function addPeople() {
            $query = DB::query(Database::SELECT, 'SELECT gen_id(gen_people_id, 1) FROM rdb$database')
                ->execute(Database::instance('fb'));
            $this->id = $query->current()['GEN_ID'];
            //$this->name = $query->current()['NAME'];

            $sql = 'INSERT INTO people (id_pep, id_db, surname, name, patronymic, id_org, login, pswd) 
                    VALUES (:id_pep, :id_db, :surname, :name, :patronymic, :id_org, :login, :pswd)';

            DB::query(Database::INSERT, $sql)
                ->parameters(array(
                    ':id_pep' => $this->id,
                    ':id_db' => 1,
                    ':surname' => iconv('UTF-8', 'CP1251', $this->surname),
                    ':name' => iconv('UTF-8', 'CP1251', $this->name),
                    ':patronymic' => iconv('UTF-8', 'CP1251', $this->patronymic),
                    ':id_org' => $this->idOrgGuest,
                    ':login' => $this->login,
                    ':pswd' => $this->pswd, 
                ))
                ->execute(Database::instance('fb'));
            $tabnum_query = DB::query(Database::SELECT, 'SELECT p.tabnum FROM people p WHERE p.id_pep = :id')
                ->param(':id', $this->id)
                ->execute(Database::instance('fb'));
            $this->tabnum = $tabnum_query->get('TABNUM');

            $this->setAclDefault();

            $this->actionResult = 0;
			//echo Debug::vars('744', $this->id);exit;
            return $this->id;
    }

public function getPeopleWithLogin() {
    $sql = "SELECT 
                p.ID_PEP AS ID_PEP, 
                p.SURNAME AS SURNAME, 
                p.NAME AS NAME, 
                p.PATRONYMIC AS PATRONYMIC,
                p.ID_ORG AS ID_ORG,
                o.NAME AS ORG_NAME
            FROM people p
            LEFT JOIN organization o ON o.ID_ORG = p.ID_ORG
            WHERE p.LOGIN IS NOT NULL 
              AND p.LOGIN != ''
              AND p.PSWD IS NOT NULL
              AND p.PSWD != ''";
    
    $query = DB::query(Database::SELECT, $sql)
        ->execute(Database::instance('fb'));
    
    $result = [];
    foreach ($query->as_array() as $row) {
        $result[] = [
            'ID_PEP' => $row['ID_PEP'],
            'SURNAME' => !empty($row['SURNAME']) ? iconv('CP1251', 'UTF-8', $row['SURNAME']) : '',
            'NAME' => !empty($row['NAME']) ? iconv('CP1251', 'UTF-8', $row['NAME']) : '',
            'PATRONYMIC' => !empty($row['PATRONYMIC']) ? iconv('CP1251', 'UTF-8', $row['PATRONYMIC']) : '',
            'ID_ORG' => $row['ID_ORG'],
            'ORG_NAME' => !empty($row['ORG_NAME']) ? iconv('CP1251', 'UTF-8', $row['ORG_NAME']) : ''
        ];
    }
    
    return $result;
}

public function getUserById($id){
	$sql = 'SELECT p.id_pep, p.surname, p.name, p.patronymic FROM people p
	WHERE p.id_pep='.$id;


	$query = DB::query(Database::SELECT, $sql)
        ->execute(Database::instance('fb'));
    
    $result = [];
    foreach ($query->as_array() as $row) {
        $result[] = [
            'ID_PEP' => $row['ID_PEP'],
            'SURNAME' => !empty($row['SURNAME']) ? iconv('CP1251', 'UTF-8', $row['SURNAME']) : '',
            'NAME' => !empty($row['NAME']) ? iconv('CP1251', 'UTF-8', $row['NAME']) : '',
            'PATRONYMIC' => !empty($row['PATRONYMIC']) ? iconv('CP1251', 'UTF-8', $row['PATRONYMIC']) : ''
        ];
    }
    
    return $result;
}


public function getPersonDetails($id_pep)
{
    $sql = "SELECT 
                p.ID_PEP, 
                p.SURNAME, 
                p.NAME, 
                p.PATRONYMIC,
                p.ID_ORG,
                o.NAME AS ORG_NAME,
                p.LOGIN,
                p.TABNUM,
                p.NUMDOC,
                p.DATEDOC,
                p.NOTE
            FROM people p
            LEFT JOIN organization o ON o.ID_ORG = p.ID_ORG
            WHERE p.ID_PEP = :id_pep";
    
    try {
        $query = DB::query(Database::SELECT, $sql)
            ->param(':id_pep', $id_pep)
            ->execute(Database::instance('fb'));
        
        if ($query->count() === 0) {
            return [];
        }
        
        $row = $query->current();
        
        return [
            'ID_PEP' => $row['ID_PEP'],
            'SURNAME' => !empty($row['SURNAME']) ? iconv('CP1251', 'UTF-8', $row['SURNAME']) : '',
            'NAME' => !empty($row['NAME']) ? iconv('CP1251', 'UTF-8', $row['NAME']) : '',
            'PATRONYMIC' => !empty($row['PATRONYMIC']) ? iconv('CP1251', 'UTF-8', $row['PATRONYMIC']) : '',
            'ID_ORG' => $row['ID_ORG'],
            'ORG_NAME' => !empty($row['ORG_NAME']) ? iconv('CP1251', 'UTF-8', $row['ORG_NAME']) : '',
            'LOGIN' => !empty($row['LOGIN']) ? $row['LOGIN'] : '',
            'TABNUM' => !empty($row['TABNUM']) ? $row['TABNUM'] : '',
            'NUMDOC' => !empty($row['NUMDOC']) ? iconv('CP1251', 'UTF-8', $row['NUMDOC']) : '',
            'DATEDOC' => !empty($row['DATEDOC']) ? $row['DATEDOC'] : '',
            'NOTE' => !empty($row['NOTE']) ? iconv('CP1251', 'UTF-8', $row['NOTE']) : ''
        ];
        
    } catch (Exception $e) {
        Log::instance()->add(Log::ERROR, 'Ошибка при получении данных пользователя: ' . $e->getMessage());
        return [];
    }
}
	
}