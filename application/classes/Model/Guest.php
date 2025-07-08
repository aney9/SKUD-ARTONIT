<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
Класс моедль для работы с гостями
*/

class Model_Guest extends Model
{
	
	public $idOrgGuest;//id_org организации, используемой в качестве гостевой
	private $idOrgGuestParamName='idOrgGuest';//название параметра в таблице setting БД СКУД
	
	public $idOrgGuestArchive;//id_org организации, используемой в качестве архива гостевой
	private $idOrgGuestArchiveParamName='idOrgGuestArchive';//название параметра в таблице setting БД СКУД
	private $id_pep = 0;// id_pep гостя
	
	
	/*
	12.11.2023 получение (Заполнение) конфигурационных параметров режима Гость
	*/
	
	public function __construct($id_pep = null)
	{
		$configcdf=Kohana::$config->load('guest');
		if(Arr::get(Auth::instance()->get_user(), 'ID_PEP') != $configcdf->get('useridek')){
		$sql = 'select s.value_int from setting s  where s.name= \''.$this->idOrgGuestParamName.'\'';
		
		$this->idOrgGuest = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->get('VALUE_INT');
		
		$sql = 'select s.value_int from setting s  where s.name= \''.$this->idOrgGuestArchiveParamName.'\'';
		
		$this->idOrgGuestArchive = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->get('VALUE_INT');
		} else {
		    $this->idOrgGuest=$configcdf->get('guestekorg');
		    $this->idOrgGuestArchive=$configcdf->get('guestekatchive');
		    
		}
			
			if(!is_null($id_pep)) $this->id_pep=$id_pep;
	
	}
	
	
	
	
	/*
	12.11.2023 Сохранинение конфигурационных параметров в БД СКУД в таблицу setting
	*/
	
	public function saveconfig()
	{
		$sql = 'update setting s
				set s.value_int='.$this->idOrgGuest.'
				where s.name=\''.$this->idOrgGuestParamName.'\'';
		
		$query = DB::query(Database::UPDATE, $sql)
			->execute(Database::instance('fb'));
		
		$sql = 'update setting s
				set s.value_int='.$this->idOrgGuestArchive.'
				where s.name=\''.$this->idOrgGuestArchiveParamName.'\'';
		
		$query = DB::query(Database::UPDATE, $sql)
			->execute(Database::instance('fb'));
		
		
		
	}
	
	

	
	
	/*
	
	Количествво пиплов в указанной организации
	*/
	
	
	public function getCountByOrg($org)
	{
		$sql = 'SELECT COUNT (*) FROM people WHERE id_org = ' . $org;
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->current();
			
		return $query['COUNT'];
	}
	
	/*
	17.08.2023 
	Сверка категориий доступа контакта и родительской организации.
	0 - совпадает,
	1 - больше, чем в организации.
	2 - меньше, чем в организации
	*/
	public function check_acl($id_pep)
	{
		if($id_pep>0)
		{
			$peopleacl=array();
			$orgacl=array();
			$sql='select ssa.id_accessname from ss_accessuser ssa
				where ssa.id_pep='.$id_pep;
			$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			foreach($query as $key=>$value)
			{
				$peopleacl[]=Arr::get($value, 'ID_ACCESSNAME');
			}
		
			$sql='select sso.id_accessname from ss_accessorg sso
			join people p on p.id_org=sso.id_org
			where p.id_pep='.$id_pep;
			
			$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			foreach($query as $key=>$value)
			{
				$orgacl[]=Arr::get($value, 'ID_ACCESSNAME');
			}
			
			$diff1=	count(array_diff($peopleacl, $orgacl));//на сколько категорий контакта больше, чем у организации
			$diff2=	count(array_diff($orgacl, $peopleacl));//на сколько категорий больше в организации, чем у контакта
			$res=-1;
			if(($diff1==0) and ($diff2 == 0)) $res=0;//все совпадает.
			if(($diff1>0) and ($diff2 == 0)) $res=1;//категорий контакта больше, чем у организации
			if(($diff1==0) and ($diff2 > 0)) $res=2;//категорий больше в организации, чем у контакта
			if(($diff1>0) and ($diff2 > 0)) $res=3;//количество категорий контакта совпадает с количество категорий организации, но они разные
			
			//echo Debug::vars('49',$id_pep, $peopleacl, $orgacl, count(array_diff($peopleacl, $orgacl)),  count(array_diff($orgacl, $peopleacl))); exit;
		} else {
			$res=-1;
		}
		return $res;
	}
	/*
	8.08.2023 
	Список категорий доступа, выданных пиплу
	*/
	public function contact_acl($id_pep)
	{
		$sql = 'select ssa.id_accessuser, ssa.id_pep, ssa.id_accessname, ssa.username, an.name from ss_accessuser ssa
				join accessname an on ssa.id_accessname=an.id_accessname
                where ssa.id_pep=' . $id_pep;
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			
		return $query;
	}
	
	/*
	8.08.2023 
	Добавление категории доступа для пипла в таблицу ss_accessuser
	*/
	public function add_contact_acl($id_pep, $id_accessname)
	{
		//echo Debug::vars('40',$id_pep, $id_accessname, Arr::get(Auth::instance()->get_user(), 'LOGIN')); exit;
		$sql = 'INSERT INTO SS_ACCESSUSER (ID_DB, ID_PEP, ID_ACCESSNAME, USERNAME)
					VALUES ( 1, '.$id_pep.', '.$id_accessname.', \''.Arr::get(Auth::instance()->get_user(), 'LOGIN').'\')';
		
		$query = DB::query(Database::INSERT, $sql)
			->execute(Database::instance('fb'));
		
		return $query;
	}
	
	/*
	9.08.2023 
	Удаление указанной категории доступа для пипла из таблицу ss_accessuser
	*/
	public function del_contact_acl($id_pep, $id_accessname)
	{
		$sql = 'delete from ss_accessuser  ssa
				where ssa.id_pep='.$id_pep.'
				and ssa.id_accessname='.$id_accessname;
		
		//echo Debug::vars('42',$id_pep, $id_accessname, $sql, Arr::get(Auth::instance()->get_user(), 'LOGIN')); exit;
		
		$query = DB::query(Database::DELETE, $sql)
			->execute(Database::instance('fb'));
				
		return $query;
	}
	
	/*
	9.08.2023 
	Удаление всех категорий доступа для пипла из таблицу ss_accessuser
	*/
	public function clear_contact_acl($id_pep)
	{
		$sql = 'delete from ss_accessuser  ssa
				where ssa.id_pep='.$id_pep;
				
		
		$query = DB::query(Database::DELETE, $sql)
			->execute(Database::instance('fb'));
				
		return $query;
	}
	
	
	/*
	11.08.2023 
	Устанвока категорий доступа для пипла по умолчанию (от родительской организации)
	*/
	public function setInheritAcl($id_pep) //Устанвока категорий доступа для пипла по умолчанию (от родительской организации)
	{
		$sql = 'select first 1 ssa.id_accessname from ss_accessorg ssa
				join people p on ssa.id_org=p.id_org
				where p.id_pep='.$id_pep.'
				order by ssa.id_accessname';

		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		
		if($query)
		{
			foreach($query as $key=>$value)
			{
				//echo Debug::vars('100', $id_pep, Arr::get($value, 'ID_ACCESSNAME'));exit;	
				$this->add_contact_acl($id_pep,  Arr::get($value, 'ID_ACCESSNAME'));
			}
		}
				
		return $query;
	}
	
	
	/*
	12.11.2023 
	Устанвока организации как Гость
	*/
	public function setGuestOrg($id_pep)
	{
		$sql = 'update people p
				set p.id_org= '.$this->idOrgGuest.'
				where p.id_pep='.$id_pep;

		$query = DB::query(Database::UPDATE, $sql)
			->execute(Database::instance('fb'));
		try
			{
			$query = DB::query(Database::UPDATE, $sql)
			->execute(Database::instance('fb'));
			} catch (Exception $e) {
				HTTP::redirect('errorpage?err=l386_'.Text::limit_chars($e->getMessage(), 50));
			}
	
	}
	
	
	/*
	25.12.2023 
	Устанвока организации как Архив
	*/
	public function setArchiveOrg($id_pep)
	{
		$sql = 'update people p
				set p.id_org='.$this->idOrgGuestArchive.'
				where p.id_pep='.$id_pep;
				
	
		$query = DB::query(Database::UPDATE, $sql)
			->execute(Database::instance('fb'));
		try
			{
			$query = DB::query(Database::UPDATE, $sql)
			->execute(Database::instance('fb'));
			} catch (Exception $e) {
				HTTP::redirect('errorpage?err=l386_'.Text::limit_chars($e->getMessage(), 50));
			}
	
	}
	
	
	/*
	подготовка счетчика записей. Количество записей зависит от режима работы: гость, архив или заявки.
	режим заявок пока не реализован.
	*/
	
	public function getCountAdmin($filter, $mode='guest_mode')
	{
		//проверяю режим работы: архив или оперативный
	switch ($mode){
		case 'archive_mode': //режим Архив
			$sql = 'SELECT COUNT (*) FROM people p
				join organization o on p.id_org=o.id_org
				where o.id_org='.$this->idOrgGuestArchive.'
	' . ($filter ? " and upper(p.surname) containing upper('$filter') OR upper(p.name) containing upper('$filter')" : '');
		break;
		
		case 'guest_mode':
		
		
		
		default:
       $sql = 'SELECT COUNT (*) FROM people p
				join organization o on p.id_org=o.id_org
				where o.id_org='.$this->idOrgGuest.'
				' . ($filter ? " and p.surname containing '$filter' OR p.name containing '$filter'" : '');
		
		
		
	}
		//$sql = 'SELECT COUNT (*) FROM people ' . ($filter ? " WHERE surname containing '$filter' OR name containing '$filter'" : ' WHERE id_org=2');
		//echo Debug::vars('197', $sql, $mode); exit;
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->current();
		
		return $query['COUNT'];
	}
	

	
	/*
	подготовка массива записей. Количество записей зависит от режима работы: гость, архив или заявки.
	режим заявок пока не реализован.
	*/
	public function getListF($user, $page = 1, $perpage = 10, $filter, $mode='guest_mode')
	{
		$g = array();
		$s = "SELECT DISTINCT id_group FROM users_groups WHERE id_user = $user";
		$q = DB::query(Database::SELECT, $s)
			->execute(Database::instance('default'));
		foreach ($q->as_array() as $key => $value) {
			$g[] = $value['id_group'];
		}

		$sql =	'SELECT FIRST ' . $perpage . ' SKIP ' . ($page - 1) * $perpage . ' p.*, o.name AS oname ' . 
    			'FROM organization o INNER JOIN people p  ON (p.id_org = o.id_org) ' .
				'WHERE o.id_group IN (' . join(', ', $g) . ') ' . ($filter ? " AND (p.surname containing '$filter' OR p.name containing '$filter')" : '') .
				'ORDER BY id_pep';
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'));
		
		return $query->as_array();
	}
	
	public function getListByOrg($page = 1, $perpage = 10, $org)
	{
		
		if (Auth::instance()->logged_in('admin'))
		$sql =  'SELECT FIRST ' . $perpage . ' SKIP ' . ($page - 1) * $perpage . ' p.*, o.name AS oname, 1 AS canedit ' .
				'FROM people p INNER JOIN organization o ON p.id_org = o.id_org ' .
				'WHERE p.id_org = ' . $org .  
				' ORDER BY id_pep'; 
		else
		$sql = '
				SELECT FIRST ' . $perpage . ' SKIP ' . ($page - 1) * $perpage . '
    				o.id_org,
    				o.name AS oname,
    				ug."P_EDIT" AS canedit,
    				ug."P_DELETE" AS candelete,
    				p.id_pep,
    				p.tabnum,
    				p.surname,
    				p.name
    			FROM
    				usersgroups ug
    				INNER JOIN organizationgroup og ON (ug.id_group = og.id_group)
    				INNER JOIN organization o ON (og.id_org = o.id_org)
    				INNER JOIN people p ON p.id_org = o.id_org
    			WHERE
    				ug.id_user = ' . Auth::instance()->get_user() . '
    				AND p.id_org = ' . $org . '
    				AND (ug."O_VIEW" + ug."P_EDIT" + ug."P_ADD" + ug."P_DELETE" > 0) ' . '
				ORDER BY
					p.id_pep';
//echo "<hr>$sql<hr>";
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'));
		
		return $query->as_array();
	}
	
	/*
	Подготовка списка гостей.
	 
	*/
	public function getListAdmin($page = 1, $perpage = 10, $filter, $mode=null)
	{
		
		/* SELECT FIRST 10 SKIP 0 p.id_pep, o.id_org from people p join organization o on p.id_org=o.id_org
                where o.id_org=2 ORDER BY p.time_stamp desc
				 */
				
		switch ($mode){
		case 'archive_mode': //режим Архив
			 $sql = 'SELECT FIRST ' . $perpage . ' SKIP ' . ($page - 1) * $perpage . ' p.id_pep, o.id_org from people p ' .
				'join organization o on p.id_org=o.id_org
				where o.id_org='.$this->idOrgGuestArchive.''
				.($filter ? " and p.surname containing '$filter' OR p.name containing '$filter'" : '') . 
				' ORDER BY p.time_stamp desc'; 
		break;
		
		case 'guest_mode':
		
		default:// режиме НЕ архив
       $sql = 'SELECT FIRST ' . $perpage . ' SKIP ' . ($page - 1) * $perpage . ' p.id_pep, o.id_org from people p ' .
				'join organization o on p.id_org=o.id_org
				where o.id_org='.$this->idOrgGuest.''
				.($filter ? " and p.surname containing '$filter' OR p.name containing '$filter'" : '') . 
				' ORDER BY p.time_stamp desc'; 
		
		
		
	}
	//echo Debug::vars('401', $sql); exit;
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'));
		
		return $query->as_array();
	}
	
	public function getPicture($id)
	{
		$sql = 'SELECT photo FROM people WHERE id_pep =' .$id;
		
		$res = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->get('PHOTO');
		//echo Debug::vars('271', $sql, $res); exit;
		return $res;
	} 
	

	
	public function getContact($id)
	{
		if (!is_numeric($id)) return false;
		
		$sql='SELECT people.*, 1 AS canedit FROM people WHERE id_pep ='. $id;
		if (Auth::instance()->logged_in('admin'))
			$query = DB::query(Database::SELECT, $sql)				
				->execute(Database::instance('fb'));
		else
			$query = DB::query(Database::SELECT,
				'SELECT p.*, ug."P_EDIT" AS canedit ' .
    			'FROM usersgroups ug ' .
    			'INNER JOIN organizationgroup og ON (ug.id_group = og.id_group) ' .
    			'INNER JOIN organization o ON (og.id_org = o.id_org) ' .
    			'INNER JOIN people p ON p.id_org = o.id_org ' .
    			'WHERE ug.id_user = ' . Auth::instance()->get_user() . ' ' .
    			'AND (ug."O_VIEW" + ug."O_EDIT" + ug."O_ADD" + ug."O_DELETE" > 0) ' .
				'AND p.id_pep = :id')
				->param(':id', $id)
				->execute(Database::instance('fb'));
		
		if ($query->count() == 0) return false;
		return $query->current();
	}
	
	public function save($surname, $name, $patronymic, $datebirth, $numdoc, $datedoc, $workstart, $workend, $active, $peptype, $post, $tabnum, $org, $login, $password, $note)
	{
		$query = DB::query(Database::SELECT,
			'SELECT gen_id(gen_people_id, 1) FROM rdb$database')
			->execute(Database::instance('fb'));
		$result = $query->current();
		//echo Debug::vars('210-0', Arr::get($result, 'GEN_ID'), $surname, $name, $patronymic, $datebirth, $numdoc, 'ttt='. $datedoc, $workstart, $workend, $active, $peptype, $post, $tabnum, $org, $login, $password, $note);  //exit;
		if($numdoc =='' or $datedoc == '') {
			$sql=__('INSERT INTO people (id_pep, id_db, surname, name, patronymic, id_org, note) VALUES (:id,1, \':surname\', \':name\', \':patronymic\',:org,  \':note\')', array
			(
				':id'			=> Arr::get($result, 'GEN_ID'),
				':surname'		=> iconv('UTF-8', 'CP1251',$surname),
				':name'			=> iconv('UTF-8', 'CP1251',$name),
				':patronymic'	=> iconv('UTF-8', 'CP1251',$patronymic),
				//':datebirth'	=> $datebirth,
				':datebirth'	=> 'now',
				':numdoc'		=> $numdoc,
				':datedoc'		=> $datedoc,
				//':datedoc'		=> 'now',
				':workstart'	=> $workstart,
				':workend'		=> $workend,
				':active'		=> $active,
				':peptype'		=> $peptype,
				':post'			=> iconv('UTF-8', 'CP1251',$post),
				':tabnum'		=> 'tabnum_'.Arr::get($result, 'GEN_ID'),
				':org'			=> $org,
				':login'		=> iconv('UTF-8', 'CP1251','USER'.Arr::get($result, 'GEN_ID')),
				':password'		=> iconv('UTF-8', 'CP1251',$password),
				':note'			=> iconv('UTF-8', 'CP1251',$note))
				);
			
		} else {
		$sql=__('INSERT INTO people (id_pep, id_db, surname, name, patronymic, numdoc, datedoc,id_org, note) VALUES (:id,1, \':surname\', \':name\', \':patronymic\',\':numdoc\', \':datedoc\',:org,  \':note\')', array
			(
				':id'			=> Arr::get($result, 'GEN_ID'),
				':surname'		=> iconv('UTF-8', 'CP1251',$surname),
				':name'			=> iconv('UTF-8', 'CP1251',$name),
				':patronymic'	=> iconv('UTF-8', 'CP1251',$patronymic),
				//':datebirth'	=> $datebirth,
				':datebirth'	=> 'now',
				':numdoc'		=> $numdoc,
				':datedoc'		=> $datedoc,
				//':datedoc'		=> 'now',
				':workstart'	=> $workstart,
				':workend'		=> $workend,
				':active'		=> $active,
				':peptype'		=> $peptype,
				':post'			=> iconv('UTF-8', 'CP1251',$post),
				':tabnum'		=> 'tabnum_'.Arr::get($result, 'GEN_ID'),
				':org'			=> $org,
				':login'		=> iconv('UTF-8', 'CP1251','USER'.Arr::get($result, 'GEN_ID')),
				':password'		=> iconv('UTF-8', 'CP1251',$password),
				':note'			=> iconv('UTF-8', 'CP1251',$note))
				);
		};
				//echo Debug::vars('496', $sql); exit; 
				$query = DB::query(Database::INSERT, $sql)
				->execute(Database::instance('fb'));
		
	/* 	$query = DB::query(Database::INSERT,
			'INSERT INTO people (id_pep, id_db, surname, name, patronymic, datebirth, numdoc, datedoc, workstart, workend, "ACTIVE", peptype, post, login, pswd, id_org, tabnum, note) ' .
			'VALUES (:id,1, :surname, :name, :patronymic, :datebirth, :numdoc, :datedoc, :workstart, :workend, :active, :peptype, :post, :login, :password, :org, :tabnum, :note)')
			->parameters(array(
				':id'			=> Arr::get($result, 'GEN_ID'),
				':surname'		=> iconv('UTF-8', 'CP1251',$surname),
				':name'			=> iconv('UTF-8', 'CP1251',$name),
				':patronymic'	=> iconv('UTF-8', 'CP1251',$patronymic),
				//':datebirth'	=> $datebirth,
				':datebirth'	=> 'now',
				':numdoc'		=> $numdoc,
				':datedoc'		=> $datedoc,
				//':datedoc'		=> 'now',
				':workstart'	=> $workstart,
				':workend'		=> $workend,
				':active'		=> $active,
				':peptype'		=> $peptype,
				':post'			=> iconv('UTF-8', 'CP1251',$post),
				':tabnum'		=> 'tabnum_'.Arr::get($result, 'GEN_ID'),
				':org'			=> $org,
				':login'		=> iconv('UTF-8', 'CP1251','USER'.Arr::get($result, 'GEN_ID')),
				':password'		=> iconv('UTF-8', 'CP1251',$password),
				':note'			=> iconv('UTF-8', 'CP1251',$note))
				)
			->execute(Database::instance('fb')); */
		return $result['GEN_ID'];
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

	public function reissuecard($id, $surname, $name, $patronymic, $datebirth, $numdoc, $datedoc, $workstart, $workend, $active, $peptype, $post, $tabnum, $org, $login, $password, $note)
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
}
