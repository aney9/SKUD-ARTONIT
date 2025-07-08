<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Класс History готовит список id_event для указанного сотрудника в указанном диапазоне дат
 * тут же готовится диапазон дат, в течении которых у сотрудника имеются события
 */
class History
{
	public $dateFrom; //с какой даты сделать выборку событий
	public $dateTo; //по какую дату сделать выборку событий
	public $id_pep; //по какому пользователю делать отчет
	public $eventListNotView = array(); //события, которые не надо показывать
	public $eventListForView = array(46, 50, 65); //события, которые надо показывать
	
	public $user=1;//информация о текущем пользователе системы. Необходимо для фильтрации событий
	
    public $eventFromDate; //с какой даты имеются события для указанного сотрудника
    public $eventToDate; //по какую дату имеются события для указанного сотрудника.

    public function __construct()
    {
        $this->dateFrom = date('Y-m-d', time() - 86400);
        $this->dateTo = date('Y-m-d', time() + 86400);
        $this->id_pep = -1;
        $this->eventListNotView=array(0);
    }
	
	
	/**
	 * Подготовка журнала событий для указанного id_pep
	 * @return unknown
	 */
	public function getHistory()
	{
		
		$eventListNotView=array(0);//Список событий, которые не надо показывать для сотрудника
		
		$sql=' SELECT              
                    e.id_event,
                    e.id_eventtype,
                    e.ESS1,
                    e.ESS2,
                    p.surname,
                    p.name,
                    p.patronymic,
                    et.name  AS eventname,
                    et.color,
                    e.datetime,
                    COALESCE (e.id_card, e.ESS2) as id_card,
                    d.name AS devicename
                    FROM
                   events e
                   INNER JOIN eventtype et ON e.id_eventtype = et.id_eventtype
                   join people p on p.id_pep=e.ess1
                   left join device d on d.id_dev=e.id_dev

                WHERE
					e.ess1 = ' . $this->id_pep . '
					and e.id_eventtype  in ('.implode(",", $this->eventListForView).')
					and e.datetime between \''.$this->dateFrom.'\' and \''.$this->dateTo.'\'
				ORDER BY
					e.id_event DESC';			
		//echo Debug::vars('33', $sql); exit;
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'));
		
		return $query->as_array();
	}

    /**Полуичть диапазон дат событий для указанного сотрудника
     * @return void
     */
    public function getEventPeriod()
    {
        $sql='select min(e.datetime), max(e.datetime) from events e
        where e.ess1='.$this->id_pep;
        $query = Arr::flatten(DB::query(Database::SELECT, $sql)
            ->execute(Database::instance('fb'))
            ->as_array());
       // echo Debug::vars('64',$sql,  $query); exit;
        $this->eventFromDate=Arr::get($query, 'MIN');
        $this->eventToDate=Arr::get($query, 'MAX');


    }
    
    
    /**
     * 10.06.2024 получить id событий за указанный период
     * 
     */
    
    public function getFullHistory()
    {
       	//для ускорения выборки предварительно извлекаю список разрешенных организаций и список разрешенных точек прохода
		$user=new User;
		$org_list=array();
		$dev_list=array();
		
		$sql='select distinct id_org from organization_getchild(1, '.$user->id_orgctrl.')';
		$result = DB::query(Database::SELECT, $sql)
        ->execute(Database::instance('fb'))
		->as_array();
		foreach($result as $key=>$value)
		{
			$org_list[]=Arr::get($value, 'ID_ORG');
		}
		
		
		
		$sql='select distinct id_dev from DEVGROUP_GETCHILD(1, '.$user->id_devgroup.')';
		$result = DB::query(Database::SELECT, $sql)
        ->execute(Database::instance('fb'))
		->as_array();
		foreach($result as $key=>$value)
		{
			if(!is_Null(Arr::get($value, 'ID_DEV'))) $dev_list[]=Arr::get($value, 'ID_DEV');
		}
		
			
	
		$sql='SELECT first 10000 e.id_event from events e
        WHERE e.id_eventtype in ('.implode(",", $this->eventListForView).')
		and e.ess2 in ('.implode(",", $org_list).')
		and e.id_dev in ('.implode(",", $dev_list).')
		and e.datetime between \''.$this->dateFrom.'\' and \''.$this->dateTo.'\'
		ORDER BY e.datetime';
       


	  // echo Debug::vars('101', $sql);exit;
        $query = DB::query(Database::SELECT, $sql)
        ->execute(Database::instance('fb'));
     
        return $query->as_array();


    }
	
	
    /**
     * 20.00.2024 получить событий за указанный период оптимизирован для Акрихина
     * 
     */
    
    public function getFullHistoryA1()
    {
        $sql='select e.datetime, d.name as doorName, et.name as eventName, e.note, p.id_pep, p.post from events e
        join eventtype et on et.id_eventtype=e.id_eventtype  and et.id_db=e.id_db
        join device d on d.id_dev=e.id_dev  and d.id_db=e.id_db
        left join people p on p.id_pep=e.ess1 and p.id_db=e.id_db
          
                WHERE e.id_eventtype in ('.implode(",", $this->eventListForView).')
               
					and e.datetime between \''.$this->dateFrom.'\' and \''.$this->dateTo.'\'
				ORDER BY
					e.datetime';
       // echo Debug::vars('130', $sql);exit;
        $query = DB::query(Database::SELECT, $sql)
        ->execute(Database::instance('fb'));
     
        //return $query->as_array();
        return $query;


    }
	
	
    
    public function getEventCount()
    {
        
        $sql='SELECT  count(e.id_event) FROM events e
             WHERE e.id_eventtype not in ('.implode(",", $this->eventListNotView).')
					and e.datetime between \''.$this->dateFrom.'\' and \''.$this->dateTo.'\'';
        
    }
    
	
}
