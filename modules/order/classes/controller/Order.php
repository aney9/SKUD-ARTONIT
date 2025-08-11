
<?php defined('SYSPATH') or die('No direct script access.');
/** 23.06.2024 Класс для организации бюро пропусков.
/* в отличии от имеющегося класса Guest предполагается иная организация
/* гостевая организация указывается в настройках.
/* точки выход должны быть в группе 3 (Зоны выхода), хотя в дальнейшем при наличии "правильного" триггера это не потребуется.
*/


class Controller_Order extends Controller_Template
{
	public $template = 'template';
	public $archive_mode = 1;// константа режима работы Архив
	public $guest_mode = 0;// константа режима работы Гость
	public $order_mode = 2;// константа режима работы По заявкам
	public $issue = 2;// регистрация нового гостя
	public $mode;// текущий режим работы 
	public $idOrgGuest=2;// id_org организация, в которой происходит учет гостей
	public $idOrgGuestArchive=3;// id_org организация архив гостей
	
	public $id_of;// id бюро пропусков. Исходя из этого определяются все остальные параметры.
	
	//private $listsize;
	//private $session;
	
	public function before()
	{
		parent::before();
		
		//Проверка: может ли пользователь вообще заходить в этот раздел?
						$user=new User();
						//echo Debug::vars($user);exit;
						$acl=new Acl(true);
						$resource='passoffice';
						
						
						if(!$acl->is_allowed($user->role,$resource, 'read')){
							
							$this->redirect('/');
							throw new Exception ('Нет прав. Доступ в раздел запрещен.', 40);
						};
						
			
				
		//$configcdf=Kohana::$config->load('guest');//загрузка данных из вспомогательной базы данных, хотя надо будет брать данные из настоящей БД СКУД
		// $sql='select poc.id, poc.name, poc.id_org_guest, poc.id_org_archive, poc.is_active from po_config poc
        //     join po_user pou on poc.id=pou.id_po
        //     where pou.id_user='.Arr::get(Auth::instance()->get_user(), 'ID_PEP');
		
		// $query = Arr::flatten(DB::query(Database::SELECT, $sql)
		// ->execute(Database::instance('pocfg'))
		//     ->as_array()
		// );
		
		// $this->idOrgGuest=Arr::get($query, 'id_org_guest');
		// $this->idOrgGuestArchive=Arr::get($query, 'id_org_archive');
	}
	
	
	
	/** 2.07.2024 Событий по гостевым картам, для бюро пропусков.
	 * 
	 */
	public function action_events()
	{
	    
	    //echo Debug::vars('49 Бюро пропусков события'); exit;
	    
	    $po = Model::factory('Passofficem');//po - passoffice
	    //$contacts->idOrgGuest = $this->idOrgGuest;//указал организацию для гостей
	    $po->init(Arr::get(Auth::instance()->get_user(), 'ID_PEP'));// инициализирую для текущего авторизованного пользователя.
	    
	    $date=time();
	    
	    $dateFrom=date ('d.m.Y', $date);
	    $dateTo= date ('d.m.Y', strtotime($dateFrom . ' +1 day'));
	    $data=$po->getEventsList($dateFrom, $dateTo );
	   
	    
	    $content=View::factory('passoffice/event', array(
	        'data'=>$data,
	        'dateFrom'=>$dateFrom,
	        'dateTo'=>$dateTo,
	    ));
	    
	    $this->template->content=$content;
	    
	    
	}
	
	/*
	Поиск по ФИО
	*/
	public function action_search()
	{
		$pattern = Arr::get($_POST, 'q', null);
		if ($pattern) {
			$this->session->set('search_contact', $pattern);
		} else {
			$pattern = $this->session->get('search_contact', '');
		}
		$this->action_index($pattern);
	}
	


	//ВОТ ЭТО  Я ИСКАЛ!!!!!!!!!!!!!!!!!!!!!!!!
	/*
	 * 23.06.2024
	*Включаю режим работы Гость.
	*Присваиваю признаку 'mode' значение 'guest_mode'
	*вывод списка гостей на территории
	*/
	public function action_guest()
	{
		//$this->action_checkGuest();// проверка на истекщий срок действия и перенос в Архив
		$this->session->set('mode', 'guest_mode');// устанавилваю режим Гости (т.е. показ активных гостей)
		$this->action_index();//переход на index. Режим работы задан и как $this, и в сессии.
	}
	
	/*23.06.2024
	Включаю режим работы Архив гостей
	Присваиваю признаку 'mode' значение 'archive_mode'
	возможен просмотр архива гостей.
	*/
	public function action_archive()
	{
		$this->session->set('mode', 'archive_mode');
		$this->action_index();
	}
	
	/** 23.06.2024
		Включаю режим работы issue - Выдача карты
		Новый гость, новая карта
		issue - выдавать
	*/
	public function action_issue()
	{
		$this->session->set('mode', 'issue');
		
		$this->redirect('order/edit/0/issue');// параметр 0 - значит, новый гость
	}
	
	
	/*
	*23.06.2024
	*Организация вывода списка гостей или архива на экран. Вариант вывода определяется mode
	*режим работы (гость или архив) заданы в this и в сессии)
	*/
public function action_index($filter = null)
{
    $po = Model::factory('Order');
    $mode = Session::instance()->get('mode');
    $user = new User();
    $id_pep = $user->id_pep;
    $user_role = $user->id_role;

    $buro = new Buro();
    $buro_filter = '';

    // Получаем все бюро пользователя
    $user_buros = $buro->getIdBuroForUser($id_pep);
	//echo Debug::vars('163', $user_buros);exit;
    $access_names = [];
    $current_accesses = [];

    if ($user_role == 2 && !empty($user_buros)) {
        // Собираем всех пользователей из всех бюро пользователя
        $all_users = [];
        foreach ($user_buros as $user_buro) {
            $buro_users = $buro->getUsersByBuroId($user_buro['id_buro']);
            $all_users = array_merge($all_users, $buro_users);
        }
        
        // Добавляем текущего пользователя и убираем дубликаты
        $all_users[] = $id_pep;
        $all_users = array_unique($all_users);
        
        // Получаем доступы для всех бюро пользователя
        foreach ($user_buros as $user_buro) {
            $accesses = $buro->getBuroAccesses($user_buro['id_buro']);
            $current_accesses = array_merge($current_accesses, Arr::pluck($accesses, 'id_accessname'));
        }
        $current_accesses = array_unique($current_accesses);
        
        $access_names = $buro->getAccessName();

        if (!empty($all_users)) {
            $all_users = array_map('intval', $all_users); 
            $buro_filter = '(p."ID_PEP" IN (' . implode(',', $all_users) . ') OR g."ID_PEP" IN (' . implode(',', $all_users) . '))';
        } else {
            $buro_filter = '1=0'; 
        }
    }

    $list = $po->getListNowOrder($id_pep, $mode, $user_role, $buro_filter);
	//echo Debug::vars('197', $list);exit;

    $fl = $this->session->get('alert');
    $arrAlert = $this->session->get('arrAlert');
    $this->session->delete('alert');
    $this->session->delete('arrAlert');
    $mode = 'neworder';

	$user = new User();
	//echo Debug::vars('204', $user);exit;

    $this->template->content = View::factory('order/list')
        ->bind('mode', $mode)
        ->bind('people', $list)
        ->bind('alert', $fl)
        ->bind('arrAlert', $arrAlert)
        ->bind('filter', $filter)
		->bind('user', $user)
        ->bind('pagination', $pagination);
}
	/*
	обработка POST-запросов
	11.11.2023 сохранение информации по гостю.
	24.06.2024 сохранение информации по гостю.
	20.08.2024 добавлен признак, что сохраняем RFID
	Особенность: в одной функции идет последовательное выполнение сохранение данных ФИО, получение id_pep и сохранение номера карты для этого id_pep
	Данные по гостю обновлять нельзя!
	
	Пример полученного POST
	 "hidden" => string(9) "form_sent"
    "id_pep" => string(1) "0"
    "surname" => string(16) "тестов1310"
    "name" => string(0) ""
    "patronymic" => string(0) ""
    "numdoc" => string(0) ""
    "datedoc" => string(10) "20.08.2024"
    "id_cardtype" => string(1) "1"
    "idcard" => string(8) "34344343"
    "rfidmode" => string(1) "0"
    "carddatestart" => string(10) "20.08.2024"
    "carddateend" => string(10) "21.08.2024"
    "note" => string(0) ""
    "todo" => string(7) "savenew"
    "savenew" => string(18) "Сохранить"
	
	*/
	
	/**24.06.2025 сохранить заявку гостевую
	*
	*
	*
	*/
	public function action_save()
	{
		//echo Debug::vars('70', $_POST); exit;
		
		
		$id			= Arr::get($_POST, 'id_pep');
		$idcard		= Arr::get($_POST, 'idcard', null);
		$todo		= Arr::get($_POST, 'todo', null);
		$rfidmode		= Arr::get($_POST, 'rfidmode', null);
		switch($todo){
			
			
			case 'savenew':// это добавление новой заявки
						
				
					$guest=new Guest2;
					
					//$guest->idOrgGuest = $this->idOrgGuest;// указал организацию гостя
					//$guest->idOrgGuestArchive =$this->idOrgGuestArchive;//указал оргинизацию архива
				
					$guest->name=Arr::get($_POST, 'name','');
					$guest->patronymic=Arr::get($_POST, 'patronymic','');
					$guest->surname=Arr::get($_POST, 'surname','');
					
					$docnum1 = Arr::get($_POST, 'docnum1', '');
					$docnum2 = Arr::get($_POST, 'docnum2', '');
					$doc_type = Arr::get($_POST, 'doc_type', '');
					$guest->numdoc = $docnum1 . '#' . $docnum2 . '@' . $doc_type;
					//$guest->numdoc=Arr::get($_POST, 'numdoc','');
					$guest->docdate=Arr::get($_POST, 'datedoc','');
					//$guest->note=Arr::get($_POST, 'note','');
					
					$guest->note=Arr::get($_POST, 'note','');
					//echo Debug::vars('246', $guest);exit;


					//echo Debug::vars('252', $_POST);exit;
					//echo Debug::vars('249',$guest->addGuest());exit;


					//27.06.2025 - вытягивание ID сотрудника и ID организации из сессии
					// $session = Session::instance();
					// $auth_user = $session->get('auth_user_crm');
					// $id_pep = $auth_user['ID_PEP'];
					//echo Debug::vars($id_pep);exit;
					// $id_org = $auth_user['ID_ORG'];
					$user = new User();
					//echo Debug::vars($user);exit;
					$id_pep = $user->id_pep;
					$id_org = $user->id_org;
					$cardDateStart = isset($_POST['carddatestart']) ? trim($_POST['carddatestart']) : date('d.m.Y');
					$cardDateEnd = isset($_POST['carddateend']) ? trim($_POST['carddateend']) : date('d.m.Y', strtotime('+1 day'));
					$timeplan = DateTime::createFromFormat('d.m.Y', $cardDateStart);
					$timevalid = DateTime::createFromFormat('d.m.Y', $cardDateEnd);
					if($guest->addGuest() >= 0) { // если гость добавлен успешно (пока без карты), то то формирую запись о заказе гостя
						//echo Debug::vars($guest);exit;

						
						
						$alert=__('guest.addOK', array(':surname'=>$guest->surname,':name'=>$guest->name,':patronymic'=>$guest->patronymic,':id_pep'=>$guest->id));
							
							// присвоение категории доступа по умолчанию для организации Гость.
							if($id_buro = Arr::get($_POST, 'selected_buro')) {
							$buro = new Buro();
							if(!$buro->addGuestToBuro($guest->id_pep, $id_buro)) {
								Kohana::$log->add(Log::WARNING, "Added guest but failed to add to bu_guest");
							}
						}
							
							$order=new Order();//формирую ордер (заказ пропуска)
							
							//echo Debug::vars($order);exit;
							$order->id_pep=$id_pep;//кто заказал пропуск
							$order->id_guest=$guest->id_pep;//ссылка на гостя, на которого оформляется заказ
							//echo Debug::vars('275', $order);exit;
							//$order->id_guest=$guest->id;//ссылка на гостя, на которого оформляется заказ
							$order->id_org=$id_org;//в какую организацию должен пойти гость
							$order->timeorder='\'now\'';//метка времени во сколько сделана заявка
							$order->timeplan="'" . $timeplan->format('Y-m-d H:i:s') . "'";//во сколько ждем гостя
							$order->timevalid="'" . $timevalid->format('Y-m-d H:i:s') . "'";;//до какого времени ждем гостя
							$order->remark=Arr::get($_POST, 'note','');//комментарии (цель визита, например).
							//echo Debug::vars($order);exit;
							if($order->add())
							{
								
								//вставка заявки прошла успешно.
								$arrAlert[]=array('actionResult'=>$guest->actionResult, 'actionDesc'=>$guest->actionDesc);
									$alert=$alert.'<br>'.  __('guest.addRfidOk', array(':id_card'=>$key->id_card));
									
								
							} else {
								
								//вставка заявки прошла с ошибкой.
								//echo Debug::vars('262 не удалось вставить гостя', $guest->addGuest());exit;
									$arrAlert[]=array('actionResult'=>2, 'actionDesc'=>'guest.noDocForSave');
							}
							
							
							
							 
					Session::instance()->set('alert', $alert);
					} else {
						
						//не удалось добавить гостя в базу данных СКУД.
						
					}
					//Session::instance()->set('alert', __('contact.key_occuped_NO'));
					
				$this->redirect('order/edit/0/newguest');
			break;
			/** ручная отметка о выходе либо обновление данных гостя
			*Если нажата кнопка submit - это отметка о выходе
			* если нажата кнопка button - это обновление данных о госте, возврат на эту страницу
			*/

			case 'savenewwithcard':
				$guest = new Guest2();
				$id = $guest->getLastIdPep();
				$id_pe = isset($id[0]['LAST_ID_PEP']) && $id[0]['LAST_ID_PEP'] !== null 
					? (int)$id[0]['LAST_ID_PEP'] 
					: 0;
				$id_pep = $id_pe +1;
				$guest->name = Arr::get($_POST, 'name', '');
				$guest->patronymic = Arr::get($_POST, 'patronymic', '');
				$guest->surname = Arr::get($_POST, 'surname', '');
				
				$docnum1 = Arr::get($_POST, 'docnum1', '');
				$docnum2 = Arr::get($_POST, 'docnum2', '');
				$doc_type = Arr::get($_POST, 'doc_type', '');
				$guest->numdoc = $docnum1 . '#' . $docnum2 . '@' . $doc_type;
				$guest->docdate = Arr::get($_POST, 'datedoc', '');
				$guest->note = Arr::get($_POST, 'note', '');
				$user = new User();
					//echo Debug::vars($user);exit;
					$id_pep_user = $user->id_pep;
					//echo Debug::vars('366', $id_pep_user);exit;
					$id_org = $user->id_org;
					$cardDateStart = isset($_POST['carddatestart']) ? trim($_POST['carddatestart']) : date('d.m.Y');
					$cardDateEnd = isset($_POST['carddateend']) ? trim($_POST['carddateend']) : date('d.m.Y', strtotime('+1 day'));
					$timeplan = DateTime::createFromFormat('d.m.Y', $cardDateStart);
					$timevalid = DateTime::createFromFormat('d.m.Y', $cardDateEnd);
					if($guest->addGuest() >= 0) { // если гость добавлен успешно (пока без карты), то то формирую запись о заказе гостя
						//echo Debug::vars($guest);exit;
						
						$alert=__('guest.addOK', array(':surname'=>$guest->surname,':name'=>$guest->name,':patronymic'=>$guest->patronymic,':id_pep'=>$guest->id));
							
							// присвоение категории доступа по умолчанию для организации Гость.
							
							
							$order=new Order();//формирую ордер (заказ пропуска)
							
							//echo Debug::vars($order);exit;
							$order->id_pep=$id_pep_user;//кто заказал пропуск
							$order->id_guest=$id_pep;//ссылка на гостя, на которого оформляется заказ
							//echo Debug::vars('275', $order);exit;
							//$order->id_guest=$guest->id;//ссылка на гостя, на которого оформляется заказ
							$order->id_org=$id_org;//в какую организацию должен пойти гость
							$order->timeorder='\'now\'';//метка времени во сколько сделана заявка
							$order->timeplan="'" . $timeplan->format('Y-m-d H:i:s') . "'";//во сколько ждем гостя
							$order->timevalid="'" . $timevalid->format('Y-m-d H:i:s') . "'";;//до какого времени ждем гостя
							$order->remark=Arr::get($_POST, 'note','');//комментарии (цель визита, например).
							//echo Debug::vars($order);exit;
							if($order->add())
							{
								
								//вставка заявки прошла успешно.
								$arrAlert[]=array('actionResult'=>$guest->actionResult, 'actionDesc'=>$guest->actionDesc);
									$alert=$alert.'<br>'.  __('guest.addRfidOk', array(':id_card'=>$key->id_card));
									
								
							} else {
								
								//вставка заявки прошла с ошибкой.
								//echo Debug::vars('262 не удалось вставить гостя', $guest->addGuest());exit;
									$arrAlert[]=array('actionResult'=>2, 'actionDesc'=>'guest.noDocForSave');
							}
							if (!empty($idcard)){
					//echo Debug::vars('382', $idcard);exit;
					$key=new Keyk($idcard);
					$check=$key->check(1);

					if(is_null($check)){// если NULL - значит, этой карты не в базе данных, её можно выдавать гостю
							$key->id_card=$idcard;
							$key->timestart=Arr::get($_POST, 'carddatestart');
							$key->timeend=Arr::get($_POST, 'carddateend');
							$key->id_pep=$guest->id_pep;
							$key->flag=1;
							$key->rfidmode=$rfidmode;
							$id_pep1 = $id_pep;
							$id_accessname = Arr::get($_POST, 'ACCESS_NAME');
							if ($id_accessname) {
        						$guest->setAclDefault($id_pep1, $id_accessname);
    						}
							//echo Debug::vars('435', $id_accessname);exit;
							//echo Debug::vars('433',$id_pep);exit;
							
							//присвоедние карты RFID
							if($key->addRfid()==0) { //сохраняю карту RFID
									// перемещаю гостя в Гость
								//$guest->moveToGuest();	
								$alert=__('guest.addRfidOk', array(':id_card'=>$key->id_card));
								$this->session->set('mode', 'guest_mode');
								//throw new Exception($alert, 271);
							} else {
							    //$alert=__('guest.addRfidErr', array(':id_card'=>$key->id_card));
								//$arrAlert[]=array('actionResult'=>3, 'actionDesc'=>$alert);
								//Session::instance()->set('arrAlert',$arrAlert);
							    //throw new Exception($alert, 274);
							}
							//echo Debug::vars('398', $key);exit;
				//Session::instance()->set('alert', $alert);
		
				} else {
					//карта выдана сотруднику с id_pep=$check
					
					$anypeople=new Guest2($check);
					
					//Session::instance()->set('alert', __('contact.key_occuped_'.$check));
					$alert=__('guest.key_occuped', array(':idcard'=>$idcard, ':id_pep'=>$anypeople->id_pep,':name'=>iconv('CP1251', 'UTF-8',$anypeople->name),':surname'=>iconv('CP1251', 'UTF-8',$anypeople->surname),':patronymic'=>iconv('CP1251', 'UTF-8',$anypeople->patronymic)));
					
					$arrAlert[]=array('actionResult'=>3, 'actionDesc'=>$alert);
					
					Session::instance()->set('arrAlert',$arrAlert);
				//throw new Exception($alert, 274);
				}

			}
		}
		$this->redirect('order/edit/0/neworder');
		break;



			//06.08.2025
			//Данный кейс нужен для создания повторной заявки из архива
			case 'newguestorder':
				$guest=new Guest2(Arr::get($_POST,'id_pep', 0));
				$id_guest = $guest->id_pep;
				//echo Debug::vars('487', $id_guest);exit;
				//$org = '3';
				//$org = $guest->id_org=Arr::get ($_POST, 'id_org','');
				//$name = $guest->name=Arr::get($_POST, 'name','');
				$get = $guest->getOrderByIdpep($id_guest);
				$id_pep = $get[0]['ID_PEP'];
				$id_org = $get[0]['ID_ORG'];
				//echo Debug::vars('Привет');exit;
				//echo Debug::vars('475', $guest->updateOrder($id_pep, $id_guest, $id_org));exit;
				if($guest->updateOrder($id_pep, $id_guest, $id_org))
							{
								
								
									//echo Debug::vars('Привет');exit;
								
							} else {
								
								//вставка заявки прошла с ошибкой.
								//echo Debug::vars('262 не удалось вставить гостя', $guest->addGuest());exit;
									$arrAlert[]=array('actionResult'=>2, 'actionDesc'=>'guest.noDocForSave');
							}
				$this->redirect('order/guest');
				break;


			case 'forceexit':// ручная отметка о выходе
				
				//echo Debug::vars('312',$_POST);//exit;
				
				
				$guest=new Guest2(Arr::get($_POST,'id_pep', 0));
				//echo Debug::vars('326', $guest);exit;
				$guest->idOrgGuest = $this->idOrgGuest;// указал организацию гостя
				$guest->idOrgGuestArchive =$this->idOrgGuestArchive;//указал организацию архива
					
				
								
				//удаляю карту у гостя - не реализовано 7.07.2025
				/*
				я предлагаю сделать гостю метод "Удалить все его карты". Быстро и сердито.
				Вызывать этот метод здесь всегда.
				
				*/
				if($guest->forceexit()==0){
					// перемещаю в архив
					$guest->moveToArchive();
					//делаю запись о ручном удалении карты
					/*
					не реализовано 7.07.2025
					*/
					$alert = __('guest.forceexitOK', array(
						':name' => @iconv('CP1251', 'UTF-8//IGNORE', $guest->name) ?: $guest->name,
						':surname' => @iconv('CP1251', 'UTF-8//IGNORE', $guest->surname) ?: $guest->surname,
						':patronymic' => @iconv('CP1251', 'UTF-8//IGNORE', $guest->patronymic) ?: $guest->patronymic
					));
					Session::instance()->set('alert', $alert);

					} else {
						$alert = __('guest.forceexitErr', array(
							':name' => @iconv('CP1251', 'UTF-8//IGNORE', $guest->name) ?: $guest->name,
							':surname' => @iconv('CP1251', 'UTF-8//IGNORE', $guest->surname) ?: $guest->surname,
							':patronymic' => @iconv('CP1251', 'UTF-8//IGNORE', $guest->patronymic) ?: $guest->patronymic
						));
						Session::instance()->set('alert', $alert);
					}

					$this->redirect('order');
				break;

			case 'consent':
    			$this->redirect('order/PersonalData/'.$id);
    			break;


			
			case 'reissue':// выдача карты уже известному гостю + обновление данных о госте
				//проверка что карта не выдана какому-нибудь гостю
				//echo Debug::vars('366', $idcard, $_POST);exit;

				
				$guest=new Guest2(Arr::get($_POST,'id_pep', 0));
				//echo Debug::vars('527', $guest);exit;
				$guest->name=Arr::get($_POST, 'name','');
				$guest->patronymic=Arr::get($_POST, 'patronymic','');
				$guest->surname=Arr::get($_POST, 'surname','');
				$docnum1 = Arr::get($_POST, 'docnum1', '');
				$docnum2 = Arr::get($_POST, 'docnum2',  '');
				$doc_type = Arr::get($_POST, 'doc_type', '');
				$guest->numdoc = $docnum1 . '#' . $docnum2 . '@' . $doc_type;
				$guest->docdate=Arr::get($_POST, 'datedoc','');
				$guest->note=Arr::get($_POST, 'note','');
				$guest -> update($guest->id);
				//echo Debug::vars('373', $guest -> update($guest->id));exit;
				
				
				$order=new Order(Arr::get($_POST, 'id_order', 0));
				//echo Debug::vars('383', $_POST);exit;
				$order->timestart=Arr::get($_POST, 'carddatestart');
				$order->timeend=Arr::get($_POST, 'carddateend');
				//echo Debug::vars('385', $order);exit;
				//$order -> update($order -> id_order, $order -> timestart, $order -> timeend);
				


				//echo Debug::vars('383', $idcard	);exit;
				//echo Debug::vars('381', empty($idcard), $idcard);exit;
				if (!empty($idcard)){
					//echo Debug::vars('382', $idcard);exit;
					$key=new Keyk($idcard);
					$check=$key->check(1);

					if(is_null($check)){// если NULL - значит, этой карты не в базе данных, её можно выдавать гостю
							$key->id_card=$idcard;
							$key->timestart=Arr::get($_POST, 'carddatestart');
							$key->timeend=Arr::get($_POST, 'carddateend');
							$key->id_pep=$guest->id_pep;
							$key->flag=1;
							$key->rfidmode=$rfidmode;
							$id_pep = $guest->id_pep;
							$id_accessname = Arr::get($_POST, 'ACCESS_NAME');
							if ($id_accessname) {
        						$guest->setAclDefault($id_pep, $id_accessname);
    						}
							//echo Debug::vars('435', $id_accessname);exit;
							//echo Debug::vars('433',$id_pep);exit;
							
							//присвоедние карты RFID
							if($key->addRfid()==0) { //сохраняю карту RFID
									// перемещаю гостя в Гость
								//$guest->moveToGuest();	
								$alert=__('guest.addRfidOk', array(':id_card'=>$key->id_card));
								$this->session->set('mode', 'guest_mode');
								//throw new Exception($alert, 271);
							} else {
							    //$alert=__('guest.addRfidErr', array(':id_card'=>$key->id_card));
								//$arrAlert[]=array('actionResult'=>3, 'actionDesc'=>$alert);
								//Session::instance()->set('arrAlert',$arrAlert);
							    //throw new Exception($alert, 274);
							}
							//echo Debug::vars('398', $key);exit;
				//Session::instance()->set('alert', $alert);
		
				} else {
					//карта выдана сотруднику с id_pep=$check
					
					$anypeople=new Guest2($check);
					
					//Session::instance()->set('alert', __('contact.key_occuped_'.$check));
					$alert=__('guest.key_occuped', array(':idcard'=>$idcard, ':id_pep'=>$anypeople->id_pep,':name'=>iconv('CP1251', 'UTF-8',$anypeople->name),':surname'=>iconv('CP1251', 'UTF-8',$anypeople->surname),':patronymic'=>iconv('CP1251', 'UTF-8',$anypeople->patronymic)));
					
					$arrAlert[]=array('actionResult'=>3, 'actionDesc'=>$alert);
					
					Session::instance()->set('arrAlert',$arrAlert);
				//throw new Exception($alert, 274);
				}
			}else{
				//$alert .= '<br>' . __('guest.card_already_registered', array(':id_card' => $idcard));
			}
			// Session::instance()->set('alert', $alert);
    		// $arrAlert[] = array('actionResult' => 0, 'actionDesc' => $alert);
    		// Session::instance()->set('arrAlert', $arrAlert);
    		// $this->redirect('order/edit/' . $id);
			//$this->session->set('mode', 'buro');
    		$this->redirect('order/edit/' . $id .'/buro');
			break;
	}
			
		//$this->redirect('order');
		// $this->redirect('order/edit/' . $id);
		$this->redirect('order/guest');
	}

	
	/**23.06.2024
	Регистрация нового гостя или редактирование уже зарегистрированного.
	* @input order/edit/<id_pep>/<режим работы>
		'guest_mode'://просмотр гостя с картой, можно сделать отметку о выходе
		'archive_mode'://просмотр архива
		'issue'://выдача карты новому гостю
	*/
	

	public function action_edit()
    {
        $id_pep = $this->request->param('id');
        $mode = $this->request->param('mode');
        $force_org = $this->request->query('id_org');

        $org_tree = Model::Factory('Company')->getOrgList();
        $fl = $this->session->get('alert');
        $arrAlert = $this->session->get('arrAlert');
        
        $this->session->delete('alert');
        $this->session->delete('arrAlert');

        $doc = new Documents();
        $topbuttonbar = View::factory('order/topbuttonbar', array(
            'id_pep' => $id_pep,
            '_is_active' => 'edit',
        ));

        $key = new Keyk();
        $cardlist = $key->getListByPeople($id_pep, 1);
        $user = new User();

        if ($user->id_role == 1 || $user->id_role == 2) {
            if ($mode == 'guest_mode') {
                $mode = 'buro';
            }
        }
        
        $buro = new Buro();
        $buro_accesses = array();
        $selected_access = '';
        if ($user->id_role == 2 && !is_null($user->id_buro)) {
            $access_ids = Arr::pluck($buro->getBuroAccesses($user->id_buro), 'id_accessname');
            foreach ($access_ids as $access_id) {
                $access_data = $buro->getAccessById($access_id);
                if (!empty($access_data)) {
                    $buro_accesses[] = $access_data;
                }
            }
            $selected_access = $buro->getAccessUserByIdPep($id_pep);
        }
		$user = new User();
		$id_pepp = $user->id_pep;
		$buro_list = $buro->getIdBuroForUser($id_pepp);
		//echo Debug::vars('691', $buro_list);exit;

        $this->template->content = View::factory('order/edit')
            ->bind('id_pep', $id_pep)
            ->bind('contact', $contact)
            ->bind('alert', $fl)
            ->bind('arrAlert', $arrAlert)
            ->bind('contact_acl', $contact_acl)
            ->bind('org_tree', $org_tree)
            ->bind('force_org', $force_org)
            ->bind('check_acl', $check_acl)
            ->bind('companies', $companies)
            ->bind('cardlist', $cardlist)
            ->bind('mode', $mode)
            ->bind('topbuttonbar', $topbuttonbar)
            ->bind('surname', $surname)
            ->bind('timeend', $timeend)
            ->bind('timestart', $timestart)
            ->bind('guest', $guest)
            ->bind('buro_accesses', $buro_accesses)
            ->bind('selected_access', $selected_access)
			->bind('buro_list', $buro_list)
            ->bind('user', $user);
    }
	public function _action__view()
	{
		$id=$this->request->param('id');
		$contact = Model::factory('Contact')->getContact($id);
		if (!$contact) $this->redirect('order');
		$companies = Model::factory('Company')->getNames(true);
		
		$this->template->content = View::factory('order/view')
			->bind('contact', $contact)
			->bind('companies', $companies);
	}
	
	
	
	public function action_history()
	{
		$id=$this->request->param('id');
		$contact = Model::factory('Contact')->getContact($id);//Получаю контакт по его id
		if (!$contact) $this->redirect('order');//если контакта нет, то перенаправление на список контактов 
	//	беру историю для указанного контакта историю. Для гостя надо указать бОльший диапазон дат за последний год
		$hist=new History();
		$hist->id_pep=$id;
		$hist->dateFrom = date('Y-m-d', time() - 365*60*60*24);
        $hist->dateTo = date('Y-m-d', time() + 60*60*24);
        $hist->eventListForView = array(46, 50, 65, 32, 17, 18, 40);
		
		$data = $hist->getHistory();
		//echo Debug::vars('381', $id, $data); exit;
		
		
		$topbuttonbar=View::factory('passoffice/topbuttonbar', array(
		    'id_pep'=> $id,
		    '_is_active'=> 'history',
		))
		;
		
		$this->template->content = View::factory('passoffice/history')//вызываю вью contacts/history.php
			->bind('contact', $contact)
			->bind('data', $data)
			->bind('id', $id)
			->bind('topbuttonbar', $topbuttonbar)
		;
	}
	
	public function action_delete()
	{
		//echo Debug::vars('372', $_GET, $_POST, $this->request->param('id')); exit;
		$id=$this->request->param('id');
		//echo Debug::vars('786', $id);exit;
		$guest=new Order($id);
		//echo Debug::vars('788', $guest);exit;
	
	
		$guest->idOrgGuest = $this->idOrgGuest;// указал организацию гостя
		$guest->idOrgGuestArchive =$this->idOrgGuestArchive;//указал организацию архива
		
		if($guest->delete($id) == 0) {
			
			//$alert = __('guest.delOnIdPepOk', array(':surname'=>iconv('CP1251', 'UTF-8',$guest->surname),':name'=>iconv('CP1251', 'UTF-8',$guest->name),':patronymic'=>iconv('CP1251', 'UTF-8',$guest->patronymic),':id_pep'=>$guest->id_pep,':tabnum'=>$guest->tabnum));
			//$alert = __('guest.delOnIdPepOk', array(':surname'=>'sur_',':name'=>'name_',':patronymic'=>'patr_',':id_pep'=>$guest->id_pep,':tabnum'=>'tab_'));
			
		} else {
			
			$alert=__('guest.delOnIdPepErr', array(':id_pep'=>$guest->id_pep));
		}
		//Session::instance()->set('alert', $alert);
		
		
		$this->redirect('order');
	}
	
	
	public function _action_addgrz()
	{
		$id=$this->request->param('id');
		$contact = Model::factory('Contact')->getContact($id);
		$anames = AccessName::getList();
		$card = array();
		
		$this->template->content = View::factory('passoffice/grz')
			->bind('contact', $contact)
			->bind('anames', $anames);
	}
	
	
	
	/*
	1.01.2023 Получить список идентификаторов
	*/
	public function action_cardlist()
	{
		$id=$this->request->param('id');
		$contact = Model::factory('Contact')->getContact($id);
		if (!$contact) $this->redirect('order');
		$cards = Model::factory('Card')->getListByPeople($id);

		$fl = $this->session->get('alert');
		$this->session->delete('alert');
		
		$topbuttonbar=View::factory('passoffice/topbuttonbar', array(
		    'id_pep'=> $id,
		    '_is_active'=> 'cardlist',//выделить кнопку cardlist
		))
		;
		
		$this->template->content = View::factory('passoffice/cardlist')
			->bind('contact', $contact)
			->bind('cards', $cards)
			->bind('alert', $fl)
			->bind('id', $id)
			->bind('topbuttonbar', $topbuttonbar)
		;
	}
	
	/*
	23.06.2023
	Вывод списка категорий доступа, выданных пиплу
	
	*/
	public function action_acl()
	{
	    
	    $fl = $this->session->get('alert');
	    $arrAlert = $this->session->get('arrAlert');
	    $this->session->delete('alert');
	    $this->session->delete('arrAlert');
	    
	    //echo Debug::vars('416', $arrAlert);
	    
		$id=$this->request->param('id');//id_pep контакта, для которого берется список категорий доступа
		//$contact = Model::factory('Contact')->getContact($id);//информация о контакте
		
		$contact = new Contact($id);//информация о контакте
		
			
		
		$contact_acl = Model::factory('Contact')->contact_acl($id);//список категорий доступа, выданных контакту
		if ($id != "0" && !$contact) $this->redirect('order');//если что не так - переход на корень
		$isAdmin = Auth::instance()->logged_in('admin');
		
		//$companies = Model::factory('Company')->getNames($isAdmin ? null : Auth::instance()->get_user());
		

		$fl = $this->session->get('alert');
		$this->session->delete('alert');
		
		$mode='edit';
		$topbuttonbar=View::factory('passoffice/topbuttonbar', array(
			'id_pep'=> $contact->id_pep,
			'_is_active'=> 'acl',
			))
		;
		
		$this->template->content = View::factory('passoffice/acl')
			->bind('contact', $contact)
			->bind('alert', $fl)
			->bind('arrAlert', $arrAlert)
			->bind('mode', $mode)
			->bind('contact_acl', $contact_acl)
			->bind('topbuttonbar', $topbuttonbar)
			//->bind('companies', $companies)
			;
			
		
	}
	
	
	/**
	*17.08.2023
	*24.06.2024 
	*при работе со списком категорий доступа необходимо учитывать уроверь прав оператора. Оператор может добавлять/удалять только те категории
	*доступа, которые ему разрешены, и не затрагивать те, которые ему запрещены.
	*Особенностью HTML является то, что в запросе мы получаем только установленные checkbox, и для определения порядка действия необходимо знать *предыдущее состояние.
	*Проверка категорий доступа для контакта
	*$id - id_pep контакта, для которого проверяют категории доступа
	*$aclList - набор категорий доступа, которые должны быть у контакта
	*$aclListForCurrentUser - набор категорий доступа, в рамках которого можно менять категории доступа для контакта.
	*/
	public function _checkACL($id, $aclList, $aclListForCurrentUser)
	{
	
	    if(!$aclList)
		{
			//если массив нового набора категорий доступа пуст, то очищаю таблицу ss_accessuser для этого пипла
			//$resultDelAcl=Model::factory('Contact')->clear_contact_acl($id);//удаляю все из таблицы ss_accessuser
			
		} else {
			//если массив нового набора категорий доступа НЕ пуст, то начинаю обработку этого массива
			
			foreach($aclList as $key=>$value)
			{
				$source[]=$key;//это массив вновь созданного набора категорий доступа в виде, удобном для последующего сравнения
			}
		
			//смотрим какие категории доступа уже есть у пипла
			$contact_acl = Model::factory('Contact')->contact_acl($id);//список категорий доступа, уже имеющихся у пипла
			if(!$contact_acl)
			{
				//если категорий доступа ранее не было выдано, то надо их просто добавить
				//echo Debug::vars('284', $aclList); exit;
				foreach($aclList as $key=>$value)
					{
						//echo Debug::vars('288',$value, $resultAddAcl, $id, $value); exit;
						$resultAddAcl=Model::factory('Contact')->add_contact_acl($id, $key );
					}
				
			} else {
				//а если категории доступа ранее выданы, то надо и убавить, и добавить
				foreach ($contact_acl as $key=>$value)
				{
					$oldACL[]=Arr::get($value, 'ID_ACCESSNAME');// это массив уже имеющихся категорий доступа в виде, удобном для последующего сравнения
				}
				
					//поиск категорий доступа, которые необходимо удалить. Это элементы, которые есть в "старом" наборе, но которых нет в в новом наборе
					
					$aclForDel=array_diff($oldACL, $source);
					$resultDelAcl=-1;
					if(!$aclForDel)
					{
						//зарегистрированных категорий доступа нет, удалять ничего не надо 
					} else {
						//зарегистрированные категории доступа имеются, удаляем их 
						foreach($aclForDel as $key=>$value)
						{
							$resultDelAcl=Model::factory('Contact')->del_contact_acl($id, $value );
						}
					}
					//поиск категорий доступа, которые необходимо добавить. Это элементы, которые есть в новом наборе, но которых нет в старом наборе
					$aclForAdd=array_diff($source, $oldACL);
					$resultAddAcl=-1;
					
					foreach($aclForAdd as $key=>$value)
					{
						//echo Debug::vars('288',$value, $resultAddAcl, $id, $value); exit;
						$resultAddAcl=Model::factory('Contact')->add_contact_acl($id, $value );
					}
			}
		}
		
	}
	
	/*
	Обновление списка категорий доступа, выданных пиплу
	входные параметры:
	id - id_pep пользователя, у которого меняют набор категорий доступа
	"aclList" => array(2) ( - новый набор категорий доступа
        213 => string(1) "1"
        1 => string(1) "1"
    
    *24.06.2024 
	*при работе со списком категорий доступа необходимо учитывать уроверь прав оператора. Оператор может добавлять/удалять только те категории
	*доступа, которые ему разрешены, и не затрагивать те, которые ему запрещены.
	*Особенностью HTML является то, что в запросе мы получаем только установленные checkbox, и для определения порядка действия необходимо знать *предыдущее состояние.
	
	
	*/
	public function action_saveACL()
	{
		//echo Debug::vars('274', $_POST); exit;
		$id_pep=$this->request->post('id');// получил id_pep, для которого надо отправлять новый набор категорий доступа
		$aclList=$this->request->post('aclList');//новый набор категорий доступа
		//echo Debug::vars('274', $_POST, $id, $aclList); exit;
		
		$aclsForCurrentUser = Model::factory('company')->getListAccessNameForCurrentUser(Arr::get(Auth::instance()->get_user(), 'ID_PEP'));// набор категорий доступа, разрешенных текущему оператору. он может вносить изменения только в рамках этого набора.
		
		
		echo Debug::vars('548 может управлять', $aclsForCurrentUser, $id_pep,  'Получили для установки', $aclList); //exit;
		
		$aclForCheckAndDel=array();
		$aclForAdd=array();
		
		if($aclList) $aclForCheckAndDel=array_diff($aclsForCurrentUser, $aclList);// эти категории надо проверить, и, если они есть, удалить
		if($aclList) $aclForAdd=array_diff($aclList , $aclsForCurrentUser);// эти категории надо добавить
		
		//echo Debug::vars('550 этих быть не должно', $aclForCheckAndDel); //exit;
		//echo Debug::vars('551 надо добавить', $aclForAdd); //exit;
		
		$addAclOkCount=0;//Счетчик успешно добавленных категорий доступа
		$addAclOErrount=0;//Счетчик НЕуспешно добавленных категорий доступа
		$delAclOkCount=0;//Счетчик успешно удаленных категорий доступа
		$delAclOErrount=0;//Счетчик НЕуспешно удадленных категорий доступа
		
		foreach($aclsForCurrentUser as $key=>$value){ //для каждой разрешенной оператору категории делаю проверку: надо ли ее выставлять?
		    if(in_array($value, $aclList)){
		        //echo Debug::vars('562 категория доступа '.$value.' должна быть установлена');
		        
		        if($this->checkAccessnameForContact($id_pep, $value)){
		            echo Debug::vars('564 Проверка: категория доступа '.$value.' уже установлена');
		        } else {
		            echo Debug::vars('566 Проверка: категория доступа '.$value.' еще не установлена. НАдо добавлять');
		            $resultAddAcl=Model::factory('Contact')->add_contact_acl($id_pep, $value );
		            if($resultAddAcl){
		                //echo Debug::vars('569 Категория доступа '.$value.' добавлена успешно.');
		                //throw new Exception('Категория доступа добавлена успешно.');
		                $addAclOkCount++;
		            } else {
		                //echo Debug::vars('569 Категория доступа '.$value.' НЕ добавлена. Ошибка!');
		                throw new Exception('Категория доступа не добавлена. Ошибка!', 580);
		            }
		            
		        }
		    } else {
		        
		        //echo Debug::vars('562 категория доступа '.$value.' НЕ должна быть установлена');
		        
		        if($this->checkAccessnameForContact($id_pep, $value)){
		            echo Debug::vars('574 Проверка: категория доступа '.$value.' уже установлена. Надо ее удалять.');
		            $resultDelAcl=Model::factory('Contact')->del_contact_acl($id_pep, $value );
		            if($resultDelAcl){
		                //echo Debug::vars('569 Категория доступа '.$value.' удален успешно.');
		                //throw new Exception('Категория доступа удалена успешно.', 595);
		                $delAclOkCount++;
		            } else {
		                //echo Debug::vars('569 Категория доступа '.$value.' НЕ удалена. Ошибка!');
		                throw new Exception('Категория доступа удалена не удалена. Ошибка!.', 596);
		            }
		         }
		    
		    
		  }
		}
		
		
		$alert=__('order.resultUpdateAcl', array(':addAclOkCount'=>$addAclOkCount,':delAclOkCount'=>$delAclOkCount ));		
		$arrAlert[]=array('actionResult'=>0, 'actionDesc'=>$alert);		
		Session::instance()->set('arrAlert',$arrAlert);
		
		//echo Debug::vars('416', Session::instance());exit;
		
		//echo Debug::vars('254',$source, $oldACL , $aclForDel, $aclForAdd, $resultDelAcl, $resultAddAcl); exit;
		$this->redirect('order/acl/'.$id_pep);
	}
	
	

	
	public function _action_reload()
	{
		$id=$this->request->param('id');
		$card = Model::factory('Card')->getCard($id);
		
		Model::factory('Card')->reload($id);
		
		Session::instance()->set('alert', __('cards.deleted'));
		$this->redirect('order/cardlist');
	}
	
	
	

	public function action_config()
	{
		
		//$org_tree = Model::Factory('Company')->getOrgList();// получить список организаций.
		$org_tree = Model::Factory('Company')->getOrgListForOnce(Arr::get(Auth::instance()->get_user(), 'ID_ORGCTRL'));
		
		$guestConfig=Model::factory('passofficem');
		$guestConfig->init(Arr::get(Auth::instance()->get_user(), 'ID_PEP'));// инициализирую для текущего авторизованного пользователя.
		//$guestConfig->init();
		//echo Debug::vars('555', $guestConfig, $guestConfig->idOrgGuest ); exit;
		$this->template->content = View::factory('passoffice/config')
		->bind('alert', $fl)
		->bind('guestConfig', $guestConfig)
		->bind('org_tree', $org_tree)
			;
		
	}
	
	
	/**27.06.2024 сохранение конфигурации Бюро пропусков
	 * 
	 */
	
	public function action_saveconfig()
	{
		echo Debug::vars('527', $_POST); //exit;
		$post=Validation::factory($_POST)
		  ->rule('idOrgGuest', 'not_empty')
		  ->rule('idOrgGuest', 'digit')
		  
		  ->rule('idOrgGuestArchive', 'not_empty')
		  ->rule('idOrgGuestArchive', 'digit')
		  
		  ->rule('po_id', 'not_empty')
		  ->rule('po_id', 'digit')
		  
		  ->rule('po_name', 'not_empty')
		  //->rule('po_name', 'alpha_numeric')
		  
		  ->rule('po_name', 'regex', array(':value', '/^[A-Za-zА-яЁё0-9\s_]+$/iDu' ))
		  //A-Za-zА-яЁё
		  
		  
		  ;
		  if($post->check()){
		      
		      echo Debug::vars('727 Валидация конфиг бюро пропусков прошла успешно.'); //exit;
		      //обновляют данные по бюро пропусков
		      
		      
		      $po=Model::factory('passofficem');
		      $po->init(Arr::get(Auth::instance()->get_user(), 'ID_PEP'));// инициализирую для текущего авторизованного пользователя.
		      
		      $po->idOrgGuest=Arr::get($post, 'idOrgGuest');
		      $po->idOrgGuestArchive=Arr::get($post, 'idOrgGuestArchive');
		      $po->name=Arr::get($post, 'po_name');
		      $po->is_active=1;
		        //echo Debug::vars('743', $po); exit;
		      
		      if($po->saveconfig() == 0){
		          //echo Debug::vars('740 Конфигурация сохранена успешно.'); exit;
		          //обновляют данные по бюро пропусков
		          
		          // данные сохранены успешно
		      } else {
		          //echo Debug::vars('746 Конфигурация сохранена с ОШИБКОЙ.'); exit;
		          //ошибка при сохранении данных
		      }
		    
		  } else {
		      echo Debug::vars('730 Валидация конфиг бюро пропусков прошла с ошибкой', $post->errors()); exit;
		      //отказ в обновлении, сообщение об ошибке
		  }
		  
		
		$this->redirect('order/config');
		
	}
	

		
		
		/**24.06.2024 Проверка: имеет ли контакт указанную категорию доступа
		 *
		 * @param $id_accessname - категория доступа для проверки
		 * $return true - указанная категория доступа присвоена контаку, false - указанная категория доступа не присвоена контакту.
		 */
		
		public function checkAccessnameForContact($id_pep, $id_accessname)
		{
		    
		    
		    $sql='select count(ssa.id_accessname) from ss_accessuser ssa
				where ssa.id_pep='.$id_pep.'
                and ssa.id_accessname='.$id_accessname;
		    
		    try {
		        $query = DB::query(Database::SELECT, $sql)
		        ->execute(Database::instance('fb'))
		        ->get('COUNT');
		        if($query>0) {
		            return true;
		        } else {
		                return false;
		            }
		        
		    } catch (Exception $e) {
		        
		    }
		}


		/** 7.04.2024
		*public $idOrgGuest;// id_org организация, в которой происходит учет гостей
	public $idOrgGuestArchive;// id_org организация архив гостей
	*/

		public function action_checkGuest()
		{
				//echo Debug::vars('846'); //exit;
				//перемещаю из Гость в Архив тех, у кого истек срок действия
				$po = Model::factory('Passofficem');//po - passoffice
		//$contacts->idOrgGuest = $this->idOrgGuest;//указал организацию для гостей
		$po->init(Arr::get(Auth::instance()->get_user(), 'ID_PEP'));// инициализирую для текущего авторизованного пользователя.
				//echo Debug::vars('851', $po); exit;
				$po->removeFromGuestToArchiveTimeExpired($po->idOrgGuest, $po->idOrgGuestArchive);
				$po->delExpiredCardArchive($po->idOrgGuestArchive);
		}
	
	/** 11.04.2024
		*public $idOrgGuest;// id_org организация, в которой происходит учет гостей
	public $idOrgGuestArchive;// id_org организация архив гостей
	*/

		public function action_addpassoffice()
		{
			//echo Debug::vars('886', $_POST); exit;
			$post=Validation::factory($_POST);
			$post->rule('po_name', 'not_empty')
				->rule('po_name', 'regex', array(':value', '/^[A-Za-zА-яЁё0-9\s_]+$/iDu' ))
				->rule('idOrgGuest', 'not_empty')
				->rule('idOrgGuest', 'digit')
				->rule('idOrgGuestArchive', 'not_empty')
				->rule('idOrgGuestArchive', 'digit')
				->rule('todo', 'not_empty')
				->rule('todo', 'regex', array(':value', '/^[A-Za-zА-яЁё0-9\s_]+$/iDu' ))
			;
			if($post->check()){
				//echo Debug::vars('898');exit;
				
				$po = new Passoffice;//po - passoffice
				$po->init();
				$po->idOrgGuest=Arr::get($post, 'idOrgGuest');
				$po->idOrgGuestArchive=Arr::get($post, 'idOrgGuestArchive');
				$po->name=Arr::get($post,'po_name');
				$po->is_active=1;
				$po->add();
				$this->redirect('order/config');
			} else {
				
				throw new exceptioncrm();
			}
			
		
		
		
		}
	
	/** 12.07.2024
	 * редактирование свойств бюро пропусков
	*/

		public function action_editItem()
		{
			//echo Debug::vars('924', $_POST); exit;
			$post=Validation::factory($_POST);
			$post->rule('todo', 'not_empty')
				->rule('todo', 'regex', array(':value', '/^[A-Za-zА-яЁё0-9\s_]+$/iDu' ))
				->rule('id', 'not_empty')
				->rule('id', 'digit')
			;
			if($post->check()){
				//echo Debug::vars('898');exit;
				$po=new Passoffice();
				$po->init(Arr::get($post, 'id'));
			    switch(Arr::get($post, 'todo')){
			        case 'deletePassoffice':
			            $po->delete();
			            $this->redirect('order/config');
			        break;
			        case 'updatePassoffice':
					
			         $po->name=Arr::get($post, 'name');
			         $po->idOrgGuest=Arr::get($post, 'idOrgGuest');
			         $po->idOrgGuestArchive=Arr::get($post, 'idOrgGuestArchive');
			         $po->is_active=Arr::get($post, 'is_active');
			         $po->update();
			         $this->redirect('order/config');
			        
			        break;
			        
			    }
				
			} else {
				
			    $this->redirect('order/config');
			}
			
		
		
		
		}

public function action_settings() {
    $buro = new Buro();
    $buros = $buro->getBuro();
    
    $guest = new Guest2();
    $people = $guest->getPeopleWithLogin();
    
    $peopleWithBuro = [];
    foreach ($people as $person) {
        $userBuros = $buro->getIdBuroForUser($person['ID_PEP']);
        $buroRoles = []; // Здесь будет ассоциативный массив [id_buro => role_name]
        
        foreach ($userBuros as $userBuro) {
            $role = $buro->getRoleById($userBuro['id_role']);
            $buroRoles[$userBuro['id_buro']] = [
                'role_name' => isset($role[0]['name']) ? $role[0]['name'] : '-'
            ];
        }
        
        $person['buros'] = $buroRoles;
        $peopleWithBuro[] = $person;
    }

	//echo Debug::vars('1095', $peopleWithBuro);exit;
    
    $this->template->content = View::factory('order/settings')
        ->set('buros', $buros)
        ->set('people', $peopleWithBuro);
}

public function action_buro_details()
{
    $id_buro = $this->request->param('id');
    
    $buro = new Buro();
    $users = $buro->getUsersByIdBuro($id_buro);  
    $buroInfo = $buro->getBuroById($id_buro); 
    $peoplee = new Guest2();  

    $people = [];
    $roles = [];
    
    foreach ($users as $user) {
        $people1 = $peoplee->getUserById($user['id_pep']);
        if ($people1) {
            $people[$user['id_pep']] = $people1;
        }
        
        $role = $buro->getRoleById($user['id_role']);
        if ($role) {
            $roles[$user['id_role']] = $role;
        }
    }
    
    $access_names = $buro->getAccessName();
    $current_accesses = Arr::pluck($buro->getBuroAccesses($id_buro), 'id_accessname');
    
    $data = [
        'buro' => isset($buroInfo[0]) ? $buroInfo[0] : null,
        'users' => $users,
        'people' => $people,
        'roles' => $roles,
        'access_names' => $access_names,
        'current_accesses' => $current_accesses 
    ];

    $this->template->content = View::factory('order/buro_details', $data);
}

public function action_delete_buro()
{
    $id_buro = $this->request->param('id');
    
    if (!$id_buro) {
        Session::instance()->set('message', 'Не указан ID бюро для удаления');
        Session::instance()->set('message_type', 'error');
        $this->redirect('order/settings');
    }
    
    $buro = new Buro();
    $result = $buro->deleteBuro($id_buro);
    
    if ($result) {
        Session::instance()->set('message', 'Бюро успешно удалено');
        Session::instance()->set('message_type', 'success');
    } else {
        Session::instance()->set('message', 'Ошибка при удалении бюро');
        Session::instance()->set('message_type', 'error');
    }
    
    $this->redirect('order/settings');
}

   
public function action_UpdateBuro() {
        $id_buro = $this->request->param('id');
        $id_pep = $this->request->query('user_id');
        
        $from_table = !empty($id_pep); 
        $current_role_id = $this->request->query('role_id');
        
        $user = new Guest2();
        $current_user = array();
        if ($from_table) {
            $current_user = $user->getPersonDetails($id_pep);
            // Получаем role_id из базы, если не передан через GET
            if (empty($current_role_id)) {
                $buro = new Buro();
                $records = $buro->getUsersByIdBuro($id_buro);
                foreach ($records as $record) {
                    if ($record['id_pep'] == $id_pep) {
                        $current_role_id = $record['id_role'];
                        break;
                    }
                }
            }
        }
        
        $data = new Buro();
        $roles = $data->getRoles();
        $users = $user->getPeopleWithLogin();
        
        $error = null;
        if ($this->request->method() === 'POST') {
            $post = $this->request->post();
            
            // Проверяем наличие user_id и role_id в POST
            if (isset($post['user_id']) && isset($post['role_id'])) {
                $user_id = $post['user_id'];
                $role_id = $post['role_id'];
                $buro_id = $id_buro;
                
                $data->id_pep = $user_id;
                $data->id_role = $role_id;
                $data->id_buro = $buro_id;
                
                if ($from_table) {
                    $existing_records = $data->getUsersByIdBuro($buro_id);
                    foreach ($existing_records as $record) {
                        if ($record['id_pep'] == $user_id) {
                            $data->id = $record['id'];
                            break;
                        }
                    }
                    $result = $data->update($buro_id);
                } else {
                    $result = $data->add();
                }
                
                if ($result) {
                    $this->redirect('order/buro_details/' . $buro_id);
                } else {
                    $error = $from_table ? 'Не удалось обновить данные' : 'Не удалось добавить данные';
                }
            } else {
                $error = 'Не выбраны сотрудник или роль';
            }
        }

        $this->template->content = View::factory('order/UpdateBuro')
            ->set('roles', $roles)
            ->set('current_user', $current_user)
            ->set('id_buro', $id_buro)
            ->set('id_pep', $id_pep)
            ->set('users', $users)
            ->set('current_role_id', $current_role_id)
            ->set('from_table', $from_table)
            ->set('error', $error);
    }
	public function action_addBuro()
{
    $this->template->title = 'Добавление нового бюро';
    $this->template->content = View::factory('order/addBuro');
    
    if ($this->request->method() === Request::POST) {
        try {
            $post = $this->request->post();
            
            if (empty($post['name'])) {
                throw new Exception('Необходимо указать название бюро');
            }
        
            $buro = new Buro();
            
            $result = $buro->addBuro($post['name'], $post['information']);
            
            if ($result) {
                Session::instance()->set('message', 'Бюро успешно добавлено');
                Session::instance()->set('message_type', 'success');
                $this->redirect('order/UpdateBuro');
            }
        } catch (Exception $e) {
            Session::instance()->set('message', $e->getMessage());
            Session::instance()->set('message_type', 'error');
            $this->redirect('order/addBuro');
        }
    }
}

public function action_update_buro()
{
    $id_buro = $this->request->param('id');
    $post = $this->request->post();
    
    if (!$id_buro || empty($post)) {
        Session::instance()->set('message', 'Не указаны данные для обновления');
        Session::instance()->set('message_type', 'error');
        $this->redirect('order/UpdateBuro');
    }
    
    $buro = new Buro();
    $result = $buro->updateBuro($id_buro, $post['name'], $post['information']);
    
    if ($result) {
        Session::instance()->set('message', 'Информация о бюро успешно обновлена');
        Session::instance()->set('message_type', 'success');
    } else {
        Session::instance()->set('message', 'Ошибка при обновлении бюро');
        Session::instance()->set('message_type', 'error');
    }
    
    $this->redirect('order/buro_details/'.$id_buro);
}

public function action_PersonalData()
    {
		//echo Debug::vars('1323');exit;
        $id_pep = $this->request->param('id');
        
        // Создаем экземпляр модели Guest2
        $guest = new Guest2();
        
        // Получаем данные о человеке
        $person = $guest->getPersonDetails($id_pep);
        
        if (empty($person)) {
            $this->response->body(json_encode(['status' => 'error', 'message' => 'Пользователь не найден']));
            return;
        }

        // Формируем ФИО, проверяем и нормализуем данные
        $surname = !empty($person['SURNAME']) ? trim($person['SURNAME']) : 'Unknown';
        $name = !empty($person['NAME']) ? trim($person['NAME']) : 'Unknown';
        $patronymic = !empty($person['PATRONYMIC']) ? trim($person['PATRONYMIC']) : 'Unknown';

        // Преобразуем кодировку, если нужно
        if (!mb_check_encoding($surname, 'UTF-8') || !mb_check_encoding($name, 'UTF-8') || !mb_check_encoding($patronymic, 'UTF-8')) {
            $surname = iconv('CP1251', 'UTF-8//IGNORE', $surname);
            $name = iconv('CP1251', 'UTF-8//IGNORE', $name);
            $patronymic = iconv('CP1251', 'UTF-8//IGNORE', $patronymic);
        }

        $full_name = trim("$surname $name $patronymic");
        $full_name = preg_replace('/\s+/', ' ', $full_name);

        // Передаем данные в представление
        $view = View::factory('order/PersonalData')
            ->set('id_pep', $id_pep)
			->set('surname', $surname)
			->set('name', $name)
			->set('patronymic', $patronymic)
            ->set('full_name', $full_name);

        $this->template->content = $view;
    }

    public function action_save_signature()
    {
		//echo Debug::vars('1361');exit;
        $this->auto_render = false;
        $this->response->headers('Content-Type', 'application/json');

        $id_pep = $this->request->param('id');
        if ($this->request->method() !== Request::POST) {
            $this->response->body(json_encode(['status' => 'error', 'message' => 'Недопустимый метод запроса']));
            return;
        }

        $signature_data = $this->request->post('signature_data');
        if (empty($signature_data)) {
            $this->response->body(json_encode(['status' => 'error', 'message' => 'Подпись не предоставлена']));
            return;
        }

        $signature_data = str_replace('data:image/jpeg;base64,', '', $signature_data);
        $signature_data = str_replace(' ', '+', $signature_data);
        $image = base64_decode($signature_data);

        $upload_dir = DOCROOT . 'downloads';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $guest = new Guest2();
        $person = $guest->getPersonDetails($id_pep);
        if (empty($person)) {
            $full_name = 'Unknown_Unknown_Unknown';
        } else {
            $surname = !empty($person['SURNAME']) ? trim($person['SURNAME']) : 'Unknown';
            $name = !empty($person['NAME']) ? trim($person['NAME']) : 'Unknown';
            $patronymic = !empty($person['PATRONYMIC']) ? trim($person['PATRONYMIC']) : 'Unknown';

            if (!mb_check_encoding($surname, 'UTF-8') || !mb_check_encoding($name, 'UTF-8') || !mb_check_encoding($patronymic, 'UTF-8')) {
                $surname = iconv('CP1251', 'UTF-8//IGNORE', $surname);
                $name = iconv('CP1251', 'UTF-8//IGNORE', $name);
                $patronymic = iconv('CP1251', 'UTF-8//IGNORE', $patronymic);
            }

            $full_name = trim("$surname_$name_$patronymic");
        }
        $full_name = preg_replace('/[^A-Za-z0-9_]/', '_', $full_name);

        $filename = $id_pep . '.jpg';
		//echo Debug::vars('1410', $filename);exit;
        $filepath = $upload_dir . DIRECTORY_SEPARATOR . $filename;

        if (file_exists($filepath)) {
            $counter = 1;
            $base_filename = pathinfo($filepath, PATHINFO_FILENAME);
            $extension = pathinfo($filepath, PATHINFO_EXTENSION);
            while (file_exists($filepath)) {
                $filename = $base_filename . '_' . $counter . '.' . $extension;
                $filepath = $upload_dir . DIRECTORY_SEPARATOR . $filename;
                $counter++;
            }
        }

        // Сохранение файла
        file_put_contents($filepath, $image);

        $this->response->body(json_encode(['status' => 'success', 'message' => 'Подпись сохранена', 'file' => $filename]));
    }

	public function action_addAccessBuro()
{
    $id_buro = $this->request->param('id');
    $post = $this->request->post();
    
    if (!isset($post['access_names']) || empty($post['access_names'])) {
        Session::instance()->set('flash_error', 'Пожалуйста, выберите хотя бы одну зону доступа');
        HTTP::redirect('order/buro_details/' . $id_buro);
    }
    
    $buro = new Buro();
    $access_ids = $post['access_names'];
    
    $buro->addAccessBuro($id_buro, $access_ids);
    
    Session::instance()->set('flash_success', 'Зоны доступа успешно обновлены');
    HTTP::redirect('order/buro_details/' . $id_buro);
}







}
	
