    <?php defined('SYSPATH') or die('No direct script access.');
     
    /**
     * Test class
     *
     * @author Бухаров
	 Проверка срока действия гостевых карт.
	 По истечению срока действия карта перемещается в Архив.
	 
	 
	 C:\xampp\php\php.exe c:\xampp\htdocs\crm2\modules\minion\minion --task=checkGuestCardExpired
	 
	 
     */
     
    class Task_checkGuestCardExpired extends Minion_Task {
		
		   protected $_options = array(
        // param name => default value
        'id_pep'   => 'World',

		);
		
        
        protected function _execute(array $params)
        {
			$t1=time(true);
			Log::instance()->add(Log::DEBUG, '178 checkGuestCardExpired Проверка гостевых карт начата');
		$po = Model::factory('Passofficem');//po - passoffice
		$po->init(19144);// инициализирую для текущего авторизованного пользователя.
				$po->removeFromGuestToArchiveTimeExpired($po->idOrgGuest, $po->idOrgGuestArchive);// переношу гостей в архив
				$po->delExpiredCardArchive($po->idOrgGuestArchive);//удляю карту у гостей. Какое-то время гость с картой находится в архиве
				
				
			Log::instance()->add(Log::DEBUG, '178 checkGuestCardExpired Проверка гостевых карт завершена. Время исполнения: '.(time(true) - $t1));
			
		}
    }