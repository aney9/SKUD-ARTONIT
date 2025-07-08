<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
*20.09.2024
*Класс для формирования строки события под требоания Акрихина.

*/
class AkrihinMonitor
{
	public $id_event;//id события, берется из базы  данных
    public $timestamp;//метка времени
    public $eventCode;//код события
    public $name;//название события события
    public $eventPlace;//Название точки прохода
    public $note;//запасные комментарии к событию. Тут отображать ФИО из note таблицы events
    public $id_pep;//id_pep сотрудника, по которому выводятся данные. id_pep может отсутствовать - это значит, что его уволили
   
    public $addData;//должность
  
    


   
    
	public function __construct($id_event)
	{
	   
	    
	    $this->id_event=$id_event;
        $sql='select e.datetime, e.id_eventtype, d.name as doorName, et.name as eventName, e.note, p.id_pep, p.post from events e
        join eventtype et on et.id_eventtype=e.id_eventtype  and et.id_db=e.id_db
        join device d on d.id_dev=e.id_dev  and d.id_db=e.id_db
        left join people p on p.id_pep=e.ess1 and p.id_db=e.id_db
  		where e.id_event='.$this->id_event;
		
				
		//echo Debug::vars('24',$sql);exit;

        $query =Arr::flatten(DB::query(Database::SELECT,
            $sql)
            ->execute(Database::instance('fb'))
            ->as_array()
        );

        $this->id_pep=Arr::get($query,'ID_PEP');
        $this->eventCode=Arr::get($query,'ID_EVENTTYPE');
        $this->name=Arr::get($query,'EVENTNAME');
        $this->timestamp=Arr::get($query,'DATETIME');
        $this->eventPlace=Arr::get($query,'DOORNAME');
        $this->note=iconv('windows-1251','UTF-8', Arr::get($query,'NOTE'));
        $this->addData=iconv('windows-1251','UTF-8', Arr::get($query,'POST'));
		
		if(is_null(Arr::get($query,'ID_PEP'))) $this->addData='Уволен';

	}
	
}
