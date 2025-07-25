<?php defined('SYSPATH') OR die('No direct access allowed.');
/*редакция 26.04.2024
* Класс модель для работы с гостями
* в обработку добавлены условия поисков только в организациях, указанных в $idOrgGuest и $idOrgGuestArchive
*/

class Model_Passofficem extends Model_Contact
{
	
	public $idOrgGuest;//id_org организации, используемой в качестве гостевой
	public $idOrgGuestArchive;//id_org организации, используемой в качестве архива гостевой

	public $idGuest;
	public $id_active;//Признак активности
	public $id;//номер бюро пропусков

	public $name = 'Бюро пропусков Щербинка';// название Бюро пропусков
	
	
	/**
	*12.11.2023 получение (Заполнение) конфигурационных параметров режима Гость
	*27.06.2024 Модель Бюро пропусков пытаюсь сделать как отдельный класс, который имеет свои параметры и хранит их в своей базе данных. См. класс passoffice	
	*/
	
	public function __construct($id_pep = null)
	{
		//Здесь надо бы сделать проверку, что настройки po_config соответсвует базе данных СКУД: указанные id_org, id_pep и id_dev существуют. 
	    /*$configcdf=Kohana::$config->load('guest');//загрузка данных из вспомогательной базы данных, хотя надо будет брать данные из настоящей БД СКУД
		if(Arr::get(Auth::instance()->get_user(), 'ID_PEP') != $configcdf->get('useridek')){
		$sql = 'select s.value_int from setting s  where s.name= \'idOrgGuest\'';
		
		$this->idOrgGuest = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->get('VALUE_INT');
		
		$sql = 'select s.value_int from setting s  where s.name= \'idOrgGuestArchive\'';
		
		$this->idOrgGuestArchive = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->get('VALUE_INT');
		} else {
		    $this->idOrgGuest=$configcdf->get('guestekorg');
		    $this->idOrgGuestArchive=$configcdf->get('guestekatchive');
		    
		}
			
			if(!is_null($id_pep)) $this->id_pep=$id_pep;
			
	   */
	   // $this->init(Arr::get(Auth::instance()->get_user(), 'ID_PEP'));
	
	}
	
	
	
	/**	
	*27.06.2024 Модель Бюро пропусков пытаюсь сделать как модель Kohana
	*если для указанного $id_pep нет записей, то результатом будет null
	*/
	
	public function init($id_pep = null)
	{
		$configcdf=Kohana::$config->load('guest');//загрузка данных из вспомогательной базы данных, хотя надо будет брать данные из настоящей БД СКУД
		$sql='select poc.id, poc.name, poc.id_org_guest, poc.id_org_archive, poc.is_active from po_config poc
            join po_user pou on poc.id=pou.id_po
            where pou.id_user='.$id_pep;
		
		$query = Arr::flatten(DB::query(Database::SELECT, $sql)
		->execute(Database::instance('pocfg'))
		    ->as_array()
		);
		//echo Debug::vars('62', $sql, $query);// проверка результата выборки информации по бюро пропусков
		$this->name=Arr::get($query, 'name');
		$this->idOrgGuest=Arr::get($query, 'id_org_guest');
		$this->idOrgGuestArchive=Arr::get($query, 'id_org_archive');
		$this->id_active=Arr::get($query, 'is_active');
		$this->id=Arr::get($query, 'id');
		
		//echo Debug::vars('68', $this->name, $this->idOrgGuest, $this->idOrgGuestArchive, $this->id_active); exit;

	
	}
	
	
	
	
	/**
	*12.11.2023 Сохранинение конфигурационных параметров в БД СКУД в таблицу setting
	*26.06.2024 Сохранинение конфигурационных параметров в БД СКУД в таблицу пока непонятно какую, где надо будет хранить информацию про разные Бюро пропусков
	*тут где-то надо добавить номер бюро пропусков...
	*получится набор таблиц:
	*po_config (от PassOffice) - таблица с названиями различныз бюро пропусков, их id, id_org Гостя, id_org Архива. При записи организации в эту таблицу ей надо будет устанавливать flag=1
	*po_gate - группа точек прохода либо группа устройств, выход через которые будет автоматически удалять карту.
	*po_access - набор категорий доступа, которые можно выдавать на этом бюро пропусков. Возможно, что набор категорий не будет зависить от личных прав оператора Бюро пропусков.
	*po_user - связь id_pep операторов с Бюро пропусков. Один оператор - одно Бюро пропусков.
	*триггер на вставку события с типом 50: если точка прохода входит в po_gate и card имеет flag=1, то удалять карту и автоматически переводит в id_org архива.
	*но если отказаться от триггера в базе данных, то можно не добавлять новых таблиц. При этом проверку выхода надо будет делать своей службой. 
	*/
	
	public function saveconfig()
	{
	   	    
	    $sql = 'update po_config
            set name=\''.$this->name.'\',
            id_org_guest='.$this->idOrgGuest.',
            id_org_archive='.$this->idOrgGuestArchive.',
            is_active='.$this->id_active.'
            where id='.$this->id;
		
		//echo Debug::vars('105', $sql); exit;
	try{
    	   $query = DB::query(Database::UPDATE, $sql)
			 ->execute(Database::instance('pocfg'));
            return 0;
        }  catch (Exception $e)  {
            return 3;
        }
		
	
		
		
	}
	
		
	/**
	*23.06.2024
	Подготовка списка гостей.
	 
	*/
	public function getList($filter, $mode=null)
	{
		
	switch ($mode){
		case 'archive_mode': //режим Архив
						
			 $sql = 'SELECT p.id_pep, o.id_org from people p ' .
				'join organization o on p.id_org=o.id_org
				where o.id_org in('.$this->idOrgGuestArchive.')'
				.($filter ? " and p.surname containing '$filter' OR p.name containing '$filter'" : '') . 
				' ORDER BY p.time_stamp desc'; 
				
				
				
		break;
		
		case 'guest_mode'://режим Гость (работа с активными гостями)
		
		default:// режиме НЕ архив
             				

			//$sql = 'SELECT gu.id_guestorder, g.id_guest, o.id_org FROM guestorder gu ' .
				//'JOIN guest g ON gu.id_guest = g.id_guest ' .
				//'JOIN organization o ON gu.id_org = o.id_org ' .
				//'WHERE g.id_guest IN ('.$this->idGuest.') ' .
				//'AND o.id_org IN ('.$this->idOrgGuest.')';
						

            // $sql = 'SELECT p.id_pep, o.id_org from people p ' .
		  	// 	    'join organization o on p.id_org=o.id_org
    		// 		where o.id_org in('.$this->idOrgGuest.')'
    		// 		    .($filter ? " and p.surname containing '$filter' OR p.name containing '$filter'" : '') .
    		// 		    ' ORDER BY p.time_stamp desc'; 
				
				
		break;
		
		
	}
	
	   //echo Debug::vars('401', $sql); //exit;
	   //$query = DB::query(Database::SELECT, iconv('UTF-8', 'CP1251',$sql))
		      	//->execute(Database::instance('fb'));
		
		  //return $query->as_array();
	}
	
	
	public function update($id, $surname, $name, $patronymic, $datebirth, $numdoc, $datedoc, $workstart, $workend, $active, $peptype, $post, $tabnum, $org, $login, $password, $note)
	{
		
		$query = DB::query(Database::UPDATE,
			'UPDATE people SET surname = :surname, name = :name, patronymic = :patronymic, datebirth = :datebirth, numdoc = :numdoc, datedoc = :datedoc, ' .
			'workstart = :workstart, workend = :workend, "ACTIVE" = :active, peptype = :peptype, post = :post, login = :login, pswd = :password, id_org = :org, note = :note
			WHERE id_pep = :id')
			->parameters(array(
				':surname'		=> iconv('UTF-8', 'CP1251',$surname),
				':name'			=> iconv('UTF-8', 'CP1251',$name),
				':patronymic'	=> iconv('UTF-8', 'CP1251',$patronymic),
				//':datebirth'	=> $datebirth,
				':datebirth'	=> 'now',
				':numdoc'		=> $numdoc,
				':datedoc'		=> $datedoc,
				':datedoc'		=> 'now',
				':workstart'	=> $workstart,
				':workend'		=> $workend,
				':active'		=> $active,
				':peptype'		=> $peptype,
				':post'			=> iconv('UTF-8', 'CP1251',$post),
				':tabnum'		=> $tabnum,
				':org'			=> $org,
				':login'		=> iconv('UTF-8', 'CP1251',$login),
				':password'		=> iconv('UTF-8', 'CP1251',$password),
				':note'			=> iconv('UTF-8', 'CP1251',$note),
				':id'			=> $id))
			->execute(Database::instance('fb'));
	}

	

	public function delete($id)
	{
		$query = DB::query(Database::DELETE,
			'DELETE FROM people WHERE id_pep = :id')
			->param(':id', $id)
			->execute(Database::instance('fb'));
	}
	
	
	
	/** 2.07.2024 журнал событий за текущие сутки
	 * 
	 */
	
	public function getEventsList($from, $to)
	{
	   
	    
	    
	    $sql='select e.id_event from events e
            where e.datetime between \''.$from.'\' and \''.$to.'\'
            and e.id_eventtype in (50, 46, 65)
            and e.ess2 in ('.$this->idOrgGuest.', '.$this->idOrgGuestArchive.')
			order by e.id_event desc';
	    
	    $sql_test='select e.id_event from events e
            where e.datetime between \'23.06.2024\' and \'26.06.2024\'
            and e.id_eventtype in (50, 46, 65)
            and e.ess2 in ('.$this->idOrgGuest.', '.$this->idOrgGuestArchive.')
			desc';
	    
	    
	   // echo Debug::vars('692', $sql); exit;
	 
	    $query = DB::query(Database::SELECT, $sql)
	    ->execute(Database::instance('fb'))
	    ->as_array();
	    //echo Debug::vars('673', $sql, $query); exit;
	    return $query;
	    
	}
	
	
	/** 4.07.2024 Перемещение госте в Архив. Перемещаются те, у кого срок действия карты истек.
	
	*/
	public function removeFromGuestToArchiveTimeExpired($orgFrom, $orgTo)
	{
		$sql='update people p2 set p2.id_org='.$orgTo.'
			where p2.id_pep in (
			select p.id_pep from card c
			join people p on p.id_pep=c.id_pep and p.id_org in ('.$orgFrom.', '.$orgTo.')
			where c.timeend<\'now\'
			)
			and p2.id_org='.$orgFrom;
			//echo Debug::vars('698', $sql); exit;
			Log::instance()->add(Log::DEBUG, '699 перенос гостя в Архив '. $sql);
			try{
    	   $query = DB::query(Database::UPDATE, $sql)
			 ->execute(Database::instance('fb'));
            return 0;
        }  catch (Exception $e)  {
            return 3;
        }
	}
	
	/** 4.07.2024 Удаление карт у гостей в Архиве
	* id_org Архива передается как параметр
	
	*/
	public function delExpiredCardArchive($idOrgGuestArchive)
	{
		$sql='delete from card c3
            where c3.id_card in (
            select c.id_card from card c
            join people p on p.id_pep=c.id_pep and p.id_org ='.$idOrgGuestArchive.'
            where c.timeend<\'now\'
            )';
			//echo Debug::vars('720', $sql); exit;
			
			try{
    	   $query = DB::query(Database::DELETE, $sql)
			 ->execute(Database::instance('fb'));
            return 0;
        }  catch (Exception $e)  {
			echo Debug::vars('726', $e);
            return 3;
        }
	}
	
	
	/** 10.07.2024 Получить список Бюро пропусков
	
	*/
		public function getPassOfficeList()
		{
			$sql='select id from po_config order by id';
		  $query = DB::query(Database::SELECT, $sql)	
			->execute(Database::instance('pocfg'));
			
			return $query;
			
		}
}
	
	
	
