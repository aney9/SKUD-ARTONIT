    <?php defined('SYSPATH') or die('No direct script access.');
     
    /**
     * Test class
     *
     * @author Бухаров
	 Проверка срока действия гостевых карт.
	 По истечению срока действия карта перемещается в Архив.
	 c:\xampp\php\php.exe c:\xampp\htdocs\crm2\modules\minion\minion --task=checkGuestCardExpired --id_pep=19144
	 
     */
     
    class Task_checkGuestCardExpired extends Minion_Task {
		
		   protected $_options = array(
        // param name => default value
        'id_pep'   => 'World',

		);
		
        
        protected function _execute(array $params)
        {
		$po = Model::factory('Passofficem');//po - passoffice
		$po->init(19144);// инициализирую для текущего авторизованного пользователя.
				$po->removeFromGuestToArchiveTimeExpired($po->idOrgGuest, $po->idOrgGuestArchive);
				$po->delExpiredCardArchive($po->idOrgGuestArchive);
			
		}
    }