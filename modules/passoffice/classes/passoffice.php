<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
10.07.2024
Класс Бюро пропусков - его свойства и методы
*/

class Passoffice
{
	public $idOrgGuest;//id_org организации, используемой в качестве гостевой
	public $idOrgGuestArchive;//id_org организации, используемой в качестве архива гостей
	
	public $name;//название бюро пропусков

	public $is_active;//активен или неактивен
	public $id;//id номер бюро пропусков по базе данных
	
	public $table_po='po_config';//таблица, где хранятся данные о бюро пропусков
	public $base_po='pocfg';//название базы данных в конфигурации


		
	public function __construct($id_pep = null)
	{
		$this->idOrgGuest=null;
		$this->idOrgGuestArchive=null;
		$this->name=null;
		$this->is_active=null;
		$this->id=null;
		
	}
	
	public function init($id = null)
	{
		if(!is_null($id)){
			$sql='select  poc.id,poc.name, poc.id_org_guest, poc.id_org_archive, poc.is_active, poc.is_active from po_config poc
				where poc.id='.$id;
				
			 $query = DB::query(Database::SELECT, $sql)	
			->execute(Database::instance('pocfg'));
			
			foreach($query as $key=>$value){
				$this->idOrgGuest=Arr::get($value,'id_org_guest');
				$this->idOrgGuestArchive=Arr::get($value,'id_org_archive');
				$this->name=Arr::get($value,'name');
				$this->is_active=Arr::get($value,'is_active');
				$this->id=Arr::get($value,'id');
			}
			//echo Debug::vars('36', $query);exit;			
		} else {
			$this->idOrgGuest=null;
			$this->idOrgGuestArchive=null;
			$this->name=null;
			$this->is_active=null;
			$this->id=null;
		}
	}
	
	
	/** 11.07.2024 добавление бюро пропусков
	 * 
	 * @return number
	 */
	public function add()
	{
	    $query = DB::insert($this->table_po, array(
	        'name', 'id_org_guest', 
	        'id_org_archive', 'is_active'))
	    ->values(array(
	        $this->name, 
	        $this->idOrgGuest, 
	        $this->idOrgGuestArchive, 
	        $this->is_active));
	    
	    $result = $query->execute($this->base_po);
	    Log::instance()->add(Log::NOTICE, '75 Добавление бюро пропусков '. Debug::vars($this, $result));
	    return $result;
	}
	
	
	
	/** 11.07.2024 добавление бюро пропусков
	 * 
	 * @return number
	 */
	public function delete()
	{
	    $query = DB::delete($this->table_po)->where('id', '=', $this->id);
	    $result = $query->execute($this->base_po);
	    Log::instance()->add(Log::NOTICE, '89 Удаление бюро пропусков '. Debug::vars($this, $result));
	    return $result;
	}
	
	
	/** 11.07.2024 добавление бюро пропусков
	 * 
	 * @return number
	 */
	public function update()
	{
	    $query = DB::update($this->table_po)->set(array(
	        'name'=>$this->name, 
	        'id_org_guest'=>$this->idOrgGuest, 
	        'id_org_archive'=>$this->idOrgGuestArchive, 
	        'is_active'=>$this->is_active))
	    ->where('id', '=', $this->id);
	   $result = $query->execute($this->base_po);
	   Log::instance()->add(Log::NOTICE, '107 Обновление бюро пропусков '. Debug::vars($this, $result));
	    return $result;
	}
	
	
	
	
}
