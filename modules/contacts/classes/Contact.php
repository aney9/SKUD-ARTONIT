<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
25.12.2023
Класс Contact - люди и их свойства.
Класс в целом похож на Guest, но имеются существенные отличия.
*/

class Contact
{
	
	public $name;
	public $surname;
	public $patronymic;
	public $photo;
	public $id_org=1;// организация, куда входит контакт
	public $numdoc;//номер документа
	public $datedoc;//дата документы
	public $is_active;//активен или неактивен
	public $flag;//флаг
	public $sysnote;//разные записи по контакту
	public $note;//разные записи по контакту
	public $time_stamp;// время создания записи контакта
	public $tabnum;// табельный номер
	public $post;// должность
	public $cardlist;// время создания записи контакта
	public $count_identificator;// количество идентификаторв
	public $id_pep = 0;// id_pep контакта
	
	
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
		, p.photo
		, p.numdoc
		, p.datedoc
		, p."ACTIVE" as is_active
		, p.flag
		, p.sysnote
		, p.note
		, p.time_stamp
		, p.tabnum
		, p.post
		
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
		//$this->photo=Arr::get($query, 'PHOTO');
		$this->photo='';
		$this->id_org=Arr::get($query, 'ID_ORG');
		$this->numdoc=Arr::get($query, 'NUMDOC');
		$this->datedoc=Arr::get($query, 'DATEDOC');
		$this->is_active=Arr::get($query, 'IS_ACTIVE');
		$this->sysnote=Arr::get($query, 'SYSNOTE');
		$this->note=Arr::get($query, 'NOTE');
		$this->time_stamp=Arr::get($query, 'TIME_STAMP');
		$this->tabnum=Arr::get($query, 'TABNUM');
		$this->post=Arr::get($query, 'POST');
		
		
		$sql='select  c.id_cardtype, count(c.id_card) from card c
			where c.id_pep='.$id_pep.'
			group by c.id_cardtype';
		$this->count_identificator=	DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'))
				->as_array();
		//$this->cadlist=new Key($id_pep);
		
		
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
	9.01.2024 обновление данных уже существующего контакта
	
	*/
	
	public function updateContact()
	{
		$sql='UPDATE PEOPLE
			SET ID_ORG = '.$this->id_org.',
			SURNAME = \''.$this->surname.'\',
			NAME = \''.$this->name.'\',
			PATRONYMIC = \''.$this->patronymic.'\',
			NOTE = \''.$this->note.'\',
			POST = \''.$this->post.'\'
			WHERE (ID_PEP = '.$this->id_pep.') AND (ID_DB = 1)';
	//echo Debug::vars('118', $sql); exit;		
			try
		{
			
			$query = DB::query(Database::UPDATE, iconv('UTF-8', 'CP1251',$sql))
				->execute(Database::instance('fb'));
			return 0;
		
		} catch (Exception $e) {
			
			Log::instance()->add(Log::DEBUG, '178 '.$e->getMessage());
			return 3;
		}
		
	}
	
	
	/*
		добавление нового контакта
		в указанный id_org
	*/
	public function addContact()
	{
		$query = DB::query(Database::SELECT,
			'SELECT gen_id(gen_people_id, 1) FROM rdb$database')
			->execute(Database::instance('fb'));
		$result = $query->current();
		$this->id_pep=Arr::get($result, 'GEN_ID');
		
		//echo Debug::vars('109', Arr::get($result, 'GEN_ID'), $this); //exit;
		$sql=__('INSERT INTO people (id_pep, id_db, surname, name, patronymic, id_org, note, post) VALUES (:id,1, \':surname\', \':name\', \':patronymic\',:id_org,\':note\',\':post\')', array
			(
				':id'			=> $this->id_pep,
				':surname'		=> iconv('UTF-8', 'CP1251',$this->surname),
				':name'			=> iconv('UTF-8', 'CP1251',$this->name),
				':patronymic'	=> iconv('UTF-8', 'CP1251',$this->patronymic),
				':id_org'			=> $this->id_org,
				':note'			=> iconv('UTF-8', 'CP1251',$this->note),
				':post'			=> iconv('UTF-8', 'CP1251',$this->post)
				));
				
				
				
		$sql=__('INSERT INTO people (id_pep, id_db, surname, name, patronymic, id_org, note, post) VALUES (:id,1, \':surname\', \':name\', \':patronymic\',:id_org,\':note\',\':post\')', array
			(
				':id'			=> $this->id_pep,
				':surname'		=> $this->surname,
				':name'			=> $this->name,
				':patronymic'	=> $this->patronymic,
				':id_org'			=> $this->id_org,
				':note'			=> $this->note,
				':post'			=> $this->post
				));
				
				
				
				

//echo Debug::vars('127', $sql); exit;			
					try
		{
					
			$query = DB::query(Database::INSERT, iconv('UTF-8', 'CP1251',$sql))
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

					return 0;

				} catch (Exception $e) {
			
					$this->actionResult=3;
			
					Log::instance()->add(Log::DEBUG, '178 '.$e->getMessage());
					return 3;
			}

		} catch (Exception $e) {
			Log::instance()->add(Log::DEBUG, '178 '.$e->getMessage());
			return 3;
		}
		
				
	}
	
	
	
	/*
	Добавление пользователя в таблицу ss_accessuser в соответствии с правами организации.
	*/
	public function setAclDefault()
	{
		$sql='select sso.id_accessname from  ss_accessorg sso
		where sso.id_org='.$this->id_org;
		
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
					Log::instance()->add(Log::DEBUG, '178 '.$e->getMessage());
					return 3;
				}
			
			}
			
			$this->actionResult=0;
			return 0;
		
		} catch (Exception $e) {
			
			$this->actionResult=3;
			Log::instance()->add(Log::DEBUG, '178 '.$e->getMessage());
			return 3;
			
		}
		
	}
	
	/*
	10.04.2024 получить фото. Этот метод реализован отдельно, чтобы не захламлять другие методы.
	Фото передается при явном его запросе.
	
	*/
	
	public function getPhoto()
	{
		$sql='select p.photo from people p
			where p.id_pep='.$this->id_pep;
			try {
						$query = DB::query(Database::SELECT, $sql)
						->execute(Database::instance('fb'))
						->get('PHOTO');
						$this->actionResult=0;
						$this->photo=$query;
						
						
				} catch (Exception $e) {
				
					$this->actionResult=3;
					Log::instance()->add(Log::DEBUG, '178 '.$e->getMessage());
					return 3;
					$this->photo='';
				}
	
	}
	
	/*
	10.01.2024
	Добавление фотографии для указанного пипла
	*/
	
	public function savephoto($photo)
	{
		/*
		 	$sql='update people p
			set p.photo=\''.$photo.'\'
			where p.id_pep='.$this->id_pep;
		try
		{
			
			$query = DB::query(Database::UPDATE, $sql)
				->execute(Database::instance('fb'));
			return 0;
		
		} catch (Exception $e) {
			
			Log::instance()->add(Log::DEBUG, '178 '.$e->getMessage());
			return 3;
		}
		
		*/ 
	/* 	 
		 $query = DB::query(Database::INSERT, "update people 
				set photo=:photo
				where id_pep=:id_pep")
			->bind(':photo', $photo)
			->bind(':id_pep', $this->id_pep)
			->execute(Database::instance('fb'));
	 
	
	
	*/
	
	
		
		$db = new PDO( Arr::get(
      			Arr::get(
      					Kohana::$config->load('database')->fb,
      					'connection'
      					),
      		'dsn'));
        $stmt = $db->prepare("UPDATE people SET photo = ? 
				WHERE id_pep = ".$this->id_pep);
    
		$stmt->bindParam(1, $photo);
       

        $db->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
        $db->beginTransaction();
        $stmt->execute();
        $db->commit();
        $db->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
		Log::instance()->add(Log::DEBUG, '297 '.Debug::vars($stmt)); 
		
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
			
		
		} catch (Exception $e) {
			
			Log::instance()->add(Log::DEBUG, '178 '.$e->getMessage());
			
		}
		
	}
	
	/*
	сохранение параметров документа для пипла.
	особенность в том, что если не указан хоть один параметр, то сохранить документ не надо.
	*/
	public function addDoc()
	{
		$validation=Validation::factory(array('numdoc'=>$this->numdoc, 'datedoc'=>$this->datedoc));
			$validation->rule('numdoc','not_empty') 
			->rule('datedoc','not_empty')
		;			
		if ($validation->check()){
			//данные на документ есть, можно записывать.
			$sql='update people p
			set p.datedoc=\''.$this->datedoc.'\',
			p.numdoc=\''.$this->numdoc.'\'
			where p.id_pep='.$this->id_pep;
		//echo Debug::vars('147', $sql); exit; 
		$query = DB::query(Database::UPDATE, $sql)
			->execute(Database::instance('fb'));
			
		} else {
			
			// данные по документу заполнены неверно, в БД не записываются.
			
		}
			
		
		
				
	}
	
	/*
		29.12.2023
		Удаление контакта по его tabnum
	*/
	public function delOnTabNum()
	{
		
		$sql='delete from people p 
			where p.tabnum=\''.$this->tabnum.'\'';
		try {
			$query = DB::query(Database::DELETE, $sql)
				->execute(Database::instance('fb'));
			//$this->actionDesc = __('contact.delOnTabNumOK', array(':tabnum'=>$this->tabnum));
			$this->actionDesc = __('contact.delOnTabNumOk', array(':tabnum'=>$this->tabnum));
			//echo Debug::vars('219', $this->actionResult,  $this->actionDesc, __('contact.delOnTabNumOk',  array(':tabnum'=>$this->tabnum)) ); //exit;
				
		} catch (Exception $e) {
			Log::instance()->add(Log::DEBUG, $e->getMessage());
			HTTP::redirect('errorpage?err=37'.Text::limit_chars($e->getMessage(), 50));
		}	
	}
	
	
	
	/*
		29.12.2023
		Проверка наличия контакта по его tabnum
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
			
			//$this->actionDesc = __('contact.delOnTabNumOK', array(':tabnum'=>$this->tabnum));
			$this->actionDesc = __('contact.delOnTabNumOk', array(':tabnum'=>$this->tabnum));
			//echo Debug::vars('219', $this->actionResult,  $this->actionDesc, __('contact.delOnTabNumOk',  array(':tabnum'=>$this->tabnum)) ); //exit;
				
		} catch (Exception $e) {
			Log::instance()->add(Log::DEBUG, $e->getMessage());
			HTTP::redirect('errorpage?err=37'.Text::limit_chars($e->getMessage(), 50));
		}	
	}
	
	
	
	
	
	
	/*
		29.12.2023
		Проверка наличия контакта по его id_pep
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
			
			$this->actionDesc = __('contact.delOnIdPepOk', array(':tabnum'=>$this->id_pep));
			
				
		} catch (Exception $e) {
			Log::instance()->add(Log::DEBUG, $e->getMessage());
			HTTP::redirect('errorpage?err=37'.Text::limit_chars($e->getMessage(), 50));
		}	
	}
	
	
	
	/*
		29.12.2023
		Удаление контакта по его id_pep
	*/
	public function delOnIdPep()
	{
		
		$sql='delete from people p 
			where p.id_pep='.$this->id_pep;
		//	echo Debug::vars('307', $sql); exit;
		try {
			$query = DB::query(Database::DELETE, $sql)
				->execute(Database::instance('fb'));
			return 0;
			
				
		} catch (Exception $e) {
			Log::instance()->add(Log::DEBUG, $e->getMessage());
			return 3;
			
		}	
	}
	
	
	/*
		07.01.2024
		Делаю пипла НЕ активным по его id_pep
	*/
	public function setNotActiveOnIdPep()
	{
		
		$sql='update people p
					set p."ACTIVE"=0
					where p.id_pep='. $this->id_pep;
			//echo Debug::vars('307', $sql); exit;
		try {
			DB::query(Database::UPDATE,$sql)	
				->execute(Database::instance('fb'));
			return 0;
			
				
		} catch (Exception $e) {
			Log::instance()->add(Log::DEBUG, $e->getMessage());
			
			return 3;
			
		}	
	}
	
	
	/*
		07.01.2024
		Делаю пипла АКТИВНЫМ по его id_pep
	*/
	public function setIsActiveOnIdPep()
	{
		
		$sql='update people p
					set p."ACTIVE"=1
					where p.id_pep='. $this->id_pep;
			
		try {
			DB::query(Database::UPDATE,$sql)	
				->execute(Database::instance('fb'));
			
			return 0;
			
				
		} catch (Exception $e) {
			Log::instance()->add(Log::DEBUG, $e->getMessage());
				
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
				
		} catch (Exception $e) {
			Log::instance()->add(Log::DEBUG, $e->getMessage());//логирование ошибки в файл
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
				
		} catch (Exception $e) {
			Log::instance()->add(Log::DEBUG, $e->getMessage());//логирование ошибки в файл
		}	
	}
	
	

	/*
	25.12.2023 Отметка о выходе вручную
	*/
	
	public function forceexit()
	{
		
		//удаляю карту у контакта
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
	
	
	
	public function moveToContact()
	{
		//перенос контакта в Гость (т.е. он стал активным)
		$sql = 'update people p
				set p.id_org='.$this->id_org.'
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

	public function getUser($id_pep)
{
    try {
        $sql_people = 'SELECT p.surname, p.name, p.patronymic, p.id_org, p.pswd, p.login, p.flag,
                              o.name as organization_name
                       FROM people p
                       LEFT JOIN organization o ON p.id_org = o.id_org
                       WHERE p.id_pep = :id_pep AND p.id_db = 1';

					   //echo Debug::vars('673', $sql_people);exit;
        
        $query_people = DB::query(Database::SELECT, $sql_people)
            ->param(':id_pep', $id_pep)
            ->execute(Database::instance('fb'))
            ->current();

		//echo Debug::vars('680', $query_people);exit;

        $flag_value = Arr::get($query_people, 'FLAG', 0);
        $binary_flags = str_pad(decbin($flag_value), 16, '0', STR_PAD_LEFT);
        
        $flags = [
            'monitor1' => $binary_flags[15] == '1' ? 1 : 0,
            'monitor2' => $binary_flags[14] == '1' ? 1 : 0,
            'konfigurator' => $binary_flags[13] == '1' ? 1 : 0,
            'managecard' => $binary_flags[12] == '1' ? 1 : 0,
            'manageuser' => $binary_flags[11] == '1' ? 1 : 0,
            'reports' => $binary_flags[10] == '1' ? 1 : 0,
            'monitorEvents' => $binary_flags[9] == '1' ? 1 : 0,
            'other' => $binary_flags[8] == '1' ? 1 : 0,
            'integrator' => $binary_flags[7] == '1' ? 1 : 0,
            'reports1' => $binary_flags[6] == '1' ? 1 : 0,
            'reports2' => $binary_flags[5] == '1' ? 1 : 0,
            'reports3' => $binary_flags[4] == '1' ? 1 : 0,
            'reports4' => $binary_flags[3] == '1' ? 1 : 0,
            'card_manager' => $binary_flags[2] == '1' ? 1 : 0,
            'reports6' => $binary_flags[1] == '1' ? 1 : 0,
            'reports5' => $binary_flags[0] == '1' ? 1 : 0,
        ];

        $contact_data = [
            'id_pep' => $id_pep,
            'surname' => iconv('CP1251', 'UTF-8', Arr::get($query_people, 'SURNAME', '')),
            'name' => iconv('CP1251', 'UTF-8', Arr::get($query_people, 'NAME', '')),
            'patronymic' => iconv('CP1251', 'UTF-8', Arr::get($query_people, 'PATRONYMIC', '')),
            'login' => iconv('CP1251', 'UTF-8', Arr::get($query_people, 'LOGIN', '')),
            'pswd' => Arr::get($query_people, 'PSWD', ''),
            'id_org' => Arr::get($query_people, 'ID_ORG', 0),
            'org_name' => iconv('CP1251', 'UTF-8', Arr::get($query_people, 'ORGANIZATION_NAME', ''))
        ];

		

        $this->actionResult = 0;
        $contact_data = array_merge($contact_data, $flags);
        //echo Debug::vars('715', $contact_data);exit;
		return $contact_data;

    } catch (Exception $e) {
        Log::instance()->add(Log::DEBUG, 'Ошибка получения данных пользователя: ' . $e->getMessage());
        $this->actionResult = 3;
        $this->actionDesc = __('Ошибка получения данных пользователя');
        return [];
    }
}

public function updateContactAdmin($id_pep, $data)
{
    try {
        $surname = iconv('UTF-8', 'CP1251', trim($data['surname']));
        $name = iconv('UTF-8', 'CP1251', trim($data['name']));
        $patronymic = iconv('UTF-8', 'CP1251', trim($data['patronymic']));
        $login = iconv('UTF-8', 'CP1251', trim($data['login']));
        $password = trim($data['password']);
        $id_org = (int)$data['organization'];


        $sql = 'UPDATE people 
                       SET login = :login, 
                           pswd = :password, 
                           id_org = :id_org
                       WHERE id_pep = :id_pep AND id_db = 1';

        $result = DB::query(Database::UPDATE, $sql)
            ->param(':id_pep', $id_pep)
            ->param(':surname', $surname)
            ->param(':name', $name)
            ->param(':patronymic', $patronymic)
            ->param(':login', $login)
            ->param(':password', $password)
            ->param(':id_org', $id_org)
            //->param(':flag', $flag_value)
            ->execute(Database::instance('fb'));


    } catch (Exception $e) {
        //Log::instance()->add(Log::DEBUG, 'Ошибка обновления данных пользователя: ' . $e->getMessage());
        $this->actionResult = 3;
        return false;
    }
}


public function UpdateFlagAdmin($id_pep, $data)
{
    try {
        $flag_map = [
            'monitor1' => 0,
            'monitor2' => 1,
            'konfigurator' => 2,
            'managecard' => 3,
            'manageuser' => 4,
            'reports' => 5,
            'monitorEvents' => 6,
            'other' => 7,
            'integrator' => 8,
            'reports1' => 9,
            'reports2' => 10,
            'reports3' => 11,
            'reports4' => 12,
            'card_manager' => 13,
            'reports6' => 14,
            'reports5' => 15
        ];

        $flag_value = 0;
        foreach ($flag_map as $flag => $position) {
            if (!empty(Arr::get($data, $flag, 0))) {
                $flag_value |= (1 << $position);
            }
        }

        $sql_update = 'UPDATE people SET flag = :flag WHERE id_pep = :id_pep AND id_db = 1';
        $result = DB::query(Database::UPDATE, $sql_update)
            ->param(':id_pep', $id_pep)
            ->param(':flag', $flag_value)
            ->execute(Database::instance('fb'));
    } catch (Exception $e) {
        Log::instance()->add(Log::DEBUG, 'Ошибка обновления флагов: ' . $e->getMessage());
        $this->actionResult = 3;
        $this->actionDesc = __('Ошибка обновления флагов');
        return false;
    }
}
	
	
	
	
	
}
