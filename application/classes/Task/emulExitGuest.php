    <?php defined('SYSPATH') or die('No direct script access.');
     
    /**
     * Test class
     *
     * @author Бухаров
	 Эмуляция выход гостя
	 Будет вставлено событие о выходе указанного гостя.
	 Если параметры не передавать, то они будут взяты из _options
	 
	 C:\xampp\php\php.exe c:\xampp\htdocs\crm2\modules\minion\minion --task=emulExitGuest --id_dev=484 --card=AAABBBCCC3
     */
	 
     
    class Task_emulExitGuest extends Minion_Task {
		
		
	 	    protected $_options = array(
       
        'id_pep'   => 33737,
        'id_dev'   => 14,
        'card'   => 484,
		
       		);
		
        
        protected function _execute(array $params)
        {
			
			
			$sql=__(' select c.id_pep from card c
                where c.id_card=\':card\'', 
				array(
				':id_pep'=>Arr::get($params, 'id_pep'),
				':id_dev'=>Arr::get($params, 'id_dev'),
				':card'=>Arr::get($params, 'card'),
				
				));
				
				$idd = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->get('ID_PEP');
			
			$sql=__('INSERT INTO EVENTS (ID_DB,ID_EVENTTYPE,ID_DEV,ID_PLAN,DATETIME,ID_CARD,NOTE,ID_VIDEO,ID_PEP,ESS1,ESS2)
				VALUES (1,50,:id_dev,NULL,\'now\',\':card\',NULL,NULL,1,:id_pep,2)', 
				array(
				':id_pep'=>$idd,
				':id_dev'=>Arr::get($params, 'id_dev'),
				':card'=>Arr::get($params, 'card'),
				
				));
			
			
		Log::instance()->add(Log::DEBUG, 'Line 23 '. $sql);	
		$query = DB::query(Database::INSERT, $sql)
			->execute(Database::instance('fb'));
			
		}
    }