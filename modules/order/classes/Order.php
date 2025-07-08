<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
24.06.2025
Класс Order - работа с очередью гостевых заявок.

*/

class Order
{
	
	public function __construct($id_order = null)
	{
		if(filter_var($id_order, FILTER_VALIDATE_BOOLEAN)){
			
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
		
		from GUEST p

        where p.guest='.$id_pep;
		
		
		
		
	
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
		}
		
	}
	
	/*
		добавление нового гостя
		ответ - tru / false /id_pep
	*/
	public function add()
	{
		
	    $id = DB::query(Database::SELECT,
			'SELECT gen_id(GEN_GUESTORDER_ID, 1) FROM rdb$database')
			->execute(Database::instance('fb'))
			->get('GEN_ID');;;
				
		//echo Debug::vars('109', Arr::get($result, 'GEN_ID')); exit;

		$sql=__('INSERT INTO GUESTORDER  (ID_GUESTORDER,ID_DB,ID_PEP,ID_PEPORDER,ID_GUEST,ID_ORG,TIMEORDER,TIMEVISIT,TIMESANCTION,TIMEPLAN,TIMEVALID,REMARK) 
                VALUES (:id, :ID_DB, :ID_PEP, :ID_PEPORDER, :ID_GUEST, :ID_ORG, :TIMEORDER, :TIMEVISIT, :TIMESANCTION, :TIMEPLAN, :TIMEVALID, :REMARK)', array
			(
				':id'			=> $id,
				':ID_DB'			=> 1,
				':ID_PEP'		=> $this->id_pep,//кто делал заявку. Взять из сессии
				':ID_PEPORDER'	=> 'NULL',
				':ID_GUEST'	=> $this->id_guest,
				':ID_ORG'			=> $this->id_org,
				':TIMEORDER'			=> $this->timeorder,
				':TIMEVISIT'			=> 'NULL',
				':TIMESANCTION'			=> 'NULL',
				':TIMEPLAN'			=> $this->timeplan,
				':TIMEVALID'		=> $this->timevalid,
				':REMARK'			=> '\''.$this->remark.'\''
				));
	//echo Debug::vars('83', $sql);exit;
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
					return 0;

				} catch (Exception $e) {
			
					$this->actionResult=3;
					//$this->actionDesc=__('guest.addErr', array(':surname'=>$this->surname,':name'=>$this->name,':patronymic'=>$this->patronymic,':id_pep'=>$this->id_pep));
			
					Log::instance()->add(Log::DEBUG, '178 '.$e->getMessage());
					return 3;
			
		}

				
					
			
		
		} catch (Exception $e) {
			
			$this->actionResult=3;
			$this->actionDesc=__('guest.addErr', array());
			
			Log::instance()->add(Log::DEBUG, '178 '.$e->getMessage());
			
		}
		
				
	}
	
	
	
	
	
	
	/**21.08.2024 Обновление данных заявок
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
	public function Wupdate($id_guestorder, $timeplan, $timevalid)
	{
		 $sql = 'UPDATE GUESTORDER 
                SET TIMEPLAN = :timeplan' . ($timevalid !== null ? ', TIMEVALID = :timevalid' : '') . ' 
                WHERE ID_GUESTORDER = :id_guestorder';
        
        $query = DB::query(Database::UPDATE, $sql)
            ->parameters(array(
                ':id_guestorder' => $id_guestorder,
                ':timeplan' => $timeplan,
                ':timevalid' => $timevalid
            ))
            ->execute(Database::instance('fb'));
			return $query->as_array();
			}
	
	public function delete($id)
    {
        
            $sql = 'DELETE FROM GUESTORDER WHERE ID_GUESTORDER = :id_guestorder';
            $query = DB::query(Database::DELETE, $sql)
                ->parameters(array(
                    ':id_guestorder' => $id
                ))
                ->execute(Database::instance('fb'));

            $this->actionResult = 0;
            return 0;
	}
	
}
