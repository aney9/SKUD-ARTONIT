<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Класс History готовит список id_event для указанного сотрудника в указанном диапазоне дат
 * тут же готовится диапазон дат, в течении которых у сотрудника имеются события
 */
class History
{
	public $dateFrom; //с какой даты сделать выборку событий
	public $dateTo; //по какую дату сделать выборку событий
	public $id_pep; //по какому пользователю
	public $eventListNotView = array(); //события, которые не надо показывать
	public $eventListForView = array(); //события, которые надо показывать

    public $eventFromDate; //с какой даты имеются события для указанного сотрудника
    public $eventToDate; //по какую дату имеются события для указанного сотрудника.

    public function __construct()
    {
        $this->dateFrom = date("Y-m-d");
        $this->dateTo = date("Y-m-d");
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
		$eventListForView=array(50, 65);//Список событий, которые надо показывать для сотрудника
		$sql=' SELECT              
                    e.id_event,
                    e.id_eventtype,
                    e.ESS1,
                    e.ESS2,
                    p.surname,
                    p.name,
                    p.patronymic,
                    et.name  AS eventname,
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

    /**Получить диапазон дат событий для указанного сотрудника
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
        $sql=' SELECT first 10000
                    e.id_event
                   from events e
            
                WHERE e.id_eventtype in ('.implode(",", $this->eventListForView).')
               
					and e.datetime between \''.$this->dateFrom.'\' and \''.$this->dateTo.'\'
				ORDER BY
					e.id_event DESC';
        
        $query = DB::query(Database::SELECT, $sql)
        ->execute(Database::instance('fb'));
       // echo Debug::vars('112', $sql); exit;
        return $query->as_array();


    }
    
    public function getEventCount()
    {
        
        $sql='SELECT  count(e.id_event) FROM events e
             WHERE e.id_eventtype not in ('.implode(",", $this->eventListNotView).')
					and e.datetime between \''.$this->dateFrom.'\' and \''.$this->dateTo.'\'';
        
    }
    
	
}
