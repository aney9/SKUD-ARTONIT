
<?php defined('SYSPATH') or die('No direct script access.');
/** 23.06.2024 Класс для организации бюро пропусков.
/* в отличии от имеющегося класса Guest предполагается иная организация
/* гостевая организация указывается в настройках.
/* точки выход должны быть в группе 3 (Зоны выхода), хотя в дальнейшем при наличии "правильного" триггера это не потребуется.
*/


class Controller_Passoffices extends Controller_Template
{
	public $template = 'template';
	public $archive_mode = 1;// константа режима работы Архив
	public $guest_mode = 0;// константа режима работы Гость
	public $order_mode = 2;// константа режима работы По заявкам
	public $issue = 2;// регистрация нового гостя
	public $mode;// текущий режим работы 
	public $idOrgGuest;// id_org организация, в которой происходит учет гостей
	public $idOrgGuestArchive;// id_org организация архив гостей
	
	public $id_of;// id бюро пропусков. Исходя из этого определяются все остальные параметры.
	
	//private $listsize;
	//private $session;
	
	public function before()
	{
		parent::before();
		
		//Проверка: может ли пользователь вообще заходить в этот раздел?
						$user=new User();
						$acl=new Acl(true);
						$resource='passoffice';
						
						
						if(!$acl->is_allowed($user->role,$resource, 'read')){
							
							$this->redirect('/');
							throw new Exception ('Нет прав. Доступ в раздел запрещен.', 40);
						};
						
			
				
		//$configcdf=Kohana::$config->load('guest');//загрузка данных из вспомогательной базы данных, хотя надо будет брать данные из настоящей БД СКУД
		$sql='select poc.id, poc.name, poc.id_org_guest, poc.id_org_archive, poc.is_active from po_config poc
            join po_user pou on poc.id=pou.id_po
            where pou.id_user='.Arr::get(Auth::instance()->get_user(), 'ID_PEP');
		
		$query = Arr::flatten(DB::query(Database::SELECT, $sql)
		->execute(Database::instance('pocfg'))
		    ->as_array()
		);
		
		$this->idOrgGuest=Arr::get($query, 'id_org_guest');
		$this->idOrgGuestArchive=Arr::get($query, 'id_org_archive');
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
		
		$this->redirect('passoffices/edit/0/issue');// параметр 0 - значит, новый гость
	}
	
	
	/*
	*23.06.2024
	*Организация вывода списка гостей или архива на экран. Вариант вывода определяется mode
	*режим работы (гость или архив) заданы в this и в сессии)
	*/
	public function action_index($filter = null)
	{
		
		$po = Model::factory('Passofficem');//po - passoffice
		//echo Debug::vars('161', Auth::instance()->get_user());exit;
		$po->init(Arr::get(Auth::instance()->get_user(), 'ID_PEP'));// инициализирую для текущего авторизованного пользователя.
			
	//echo Debug::vars('110', $this->mode, Session::instance()->get('mode')); //exit;
		$mode=Session::instance()->get('mode');
	//echo Debug::vars('155', $mode);
		$list = $po->getList($filter, $mode);//получил список id_pep, удовлетворяющих фильтру и режиму (гости или архив).

		$fl = $this->session->get('alert');
		$arrAlert = $this->session->get('arrAlert');
		$this->session->delete('alert');
		$this->session->delete('arrAlert');
		
		$this->template->content = View::factory('Passoffice/list')
			->bind('people', $list)
			->bind('listCount', $q)
			->bind('alert', $fl)
			->bind('arrAlert', $arrAlert)
			->bind('showphone', $showphone)
			->bind('filter', $filter)
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
	public function action_save()
	{
		echo Debug::vars('70', $_POST); //exit;
		
		
		$id			= Arr::get($_POST, 'id_pep');
		$idcard		= Arr::get($_POST, 'idcard', null);
		$todo		= Arr::get($_POST, 'todo', null);
		$rfidmode		= Arr::get($_POST, 'rfidmode', null);
		switch($todo){
			
			
			case 'savenew':// это добавление нового пользователя, т.к. $id (она же id_pep) равна 0.
				$key=new Keyk($idcard);
				$key->id_cardtype=1;//указал, что карта именно RFID
				$key->rfidmode=$rfidmode;
				$check=$key->check(1);
				
				if(is_null($check)){//проверка, что карта не выдана другому сотруднику
					$guest=new Guest;
					
					$guest->idOrgGuest = $this->idOrgGuest;// указал организацию гостя
					$guest->idOrgGuestArchive =$this->idOrgGuestArchive;//указал оргинизацию архива
				
					$guest->name=Arr::get($_POST, 'name','');
					$guest->patronymic=Arr::get($_POST, 'patronymic','');
					$guest->surname=Arr::get($_POST, 'surname','');
					$guest->numdoc=Arr::get($_POST, 'numdoc','');
					$guest->datedoc=Arr::get($_POST, 'datedoc','');
					$guest->note=Arr::get($_POST, 'note','');
					
					$guest->note=Arr::get($_POST, 'note','');

					if($guest->addGuest() == 0) { // если пользователь добавлен успешно (пока без карты), то выставляю ему набор категорий доступа по умолчанию
						
						$alert=__('guest.addOK', array(':surname'=>$guest->surname,':name'=>$guest->name,':patronymic'=>$guest->patronymic,':id_pep'=>$guest->id_pep,':tabnum'=>$guest->tabnum));
				
							// присвоение категории доступа по умолчанию для организации Гость.
							if($guest->setAclDefault()){ // если категории добавлены успешно, то записываю карту
								$alert=$alert. '<br>'. __('acl.saved');
							}
							
							$key->id_card=$idcard;
							$key->timestart=Arr::get($_POST, 'carddatestart');
							$key->timeend=Arr::get($_POST, 'carddateend');
							$key->id_pep=$guest->id_pep;
							$key->flag=1;// признак гостевой карты
							
							//присвоедние карты RFID
							if($key->addRfid()==0) { ;//сохраняю карту RFID
									$arrAlert[]=array('actionResult'=>$guest->actionResult, 'actionDesc'=>$guest->actionDesc);
									$alert=$alert.'<br>'.  __('guest.addRfidOk', array(':id_card'=>$key->id_card));
							};
							
							//если номер документа или его дата не пусты, то сохранить документы
							//echo Debug::vars('296', $guest->numdoc=='', $guest->datedoc==''); exit;

							if($guest->numdoc=='' OR $guest->datedoc==''){//если хоть одно поле пустое, то данные по документу НЕ сохранять 
								$arrAlert[]=array('actionResult'=>2, 'actionDesc'=>'guest.noDocForSave');
							} else {
							
								if($guest->addDoc() == 0) {;//добавляю данные по документу
										
										$alert.='<br>'. __('guest.adddocOK', array(':numdoc'=>$guest->numdoc, ':surname'=>$guest->surname,':name'=>$guest->name,':patronymic'=>$guest->patronymic));
								}
							}
							 
					Session::instance()->set('alert', $alert);
					} else {
						
						//не удалось добавить гостя в базу данных СКУД.
						
					}
					//Session::instance()->set('alert', __('contact.key_occuped_NO'));
					
				} else {
					//карта выдана сотруднику с id_pep=$check
					
					$anypeople=new Guest($check);
					
					$anypeople->idOrgGuest = $this->idOrgGuest;// указал организацию гостя
					$anypeople->idOrgGuestArchive =$this->idOrgGuestArchive;//указал оргинизацию архива
					
					//echo Debug::vars('315', $idcard, $anypeople, $anypeople->id_org == $anypeople->idOrgGuest, $anypeople->id_org == $anypeople->idOrgGuestArchive); exit;
					if($anypeople->id_org == $anypeople->idOrgGuest OR $anypeople->id_org == $anypeople->idOrgGuestArchive){
						$arrAlert[]=array('actionResult'=>3, 'actionDesc'=>__('guest.key_occuped', array(':idcard'=>$idcard, ':id_pep'=>$anypeople->id_pep,':name'=>iconv('CP1251', 'UTF-8',$anypeople->name),':surname'=>iconv('CP1251', 'UTF-8',$anypeople->surname),':patronymic'=>iconv('CP1251', 'UTF-8',$anypeople->patronymic))));
						
					} else {
						$arrAlert[]=array('actionResult'=>2, 'actionDesc'=>__('guest.key_occuped_contact', array(':idcard'=>$idcard, ':id_pep'=>$anypeople->id_pep,':name'=>iconv('CP1251', 'UTF-8',$anypeople->name),':surname'=>iconv('CP1251', 'UTF-8',$anypeople->surname),':patronymic'=>iconv('CP1251', 'UTF-8',$anypeople->patronymic))));
					
					}
					Session::instance()->set('arrAlert',$arrAlert);
				}
				
		$this->redirect('passoffices/edit/0/issue');
			break;
			/** ручная отметка о выходе либо обновление данных гостя
			*Если нажата кнопка submit - это отметка о выходе
			* если нажата кнопка button - это обновление данных о госте, возврат на эту страницу
			*/
			case 'forceexit':// ручная отметка о выходе
				
				//echo Debug::vars('312',$_POST, array_key_exists('forceexitUpdate', $_POST));exit;
				if(array_key_exists('forceexitUpdate', $_POST)){// надо только сохранить данные.
					$guest=new Guest(Arr::get($_POST,'id_pep', 0));
					$guest->name=Arr::get($_POST, 'name','');
					$guest->patronymic=Arr::get($_POST, 'patronymic','');
					$guest->surname=Arr::get($_POST, 'surname','');
					$guest->numdoc=Arr::get($_POST, 'numdoc','');
					$guest->datedoc=Arr::get($_POST, 'datedoc','');
					$guest->note=Arr::get($_POST, 'note','');
					if($guest->update() == 0) { // если обновление прошло успешно, то выдаю нужную надпись
						
						$alert=__('guest.updateOK', array(':surname'=>$guest->surname,':name'=>$guest->name,':patronymic'=>$guest->patronymic,':id_pep'=>$guest->id_pep,':tabnum'=>$guest->tabnum));
						$arrAlert[]=array('actionResult'=>0, 'actionDesc'=>$alert);
						Session::instance()->set('arrAlert',$arrAlert);
						
					} else {
						$alert=__('guest.updateErr', array(':surname'=>$guest->surname,':name'=>$guest->name,':patronymic'=>$guest->patronymic,':id_pep'=>$guest->id_pep,':tabnum'=>$guest->tabnum));
						$arrAlert[]=array('actionResult'=>0, 'actionDesc'=>$alert);
						Session::instance()->set('arrAlert',$arrAlert);
						
					}
					
				$this->redirect('passoffices/edit/'.Arr::get($_POST,'id_pep', 0).'/guest_mode');	
					
				}
				
				$guest=new Guest(Arr::get($_POST,'id_pep', 0));
				
				$guest->idOrgGuest = $this->idOrgGuest;// указал организацию гостя
				$guest->idOrgGuestArchive =$this->idOrgGuestArchive;//указал организацию архива
					
				//echo Debug::vars('291', $guest->id_pep); exit;
				if($guest->forceexit()==0){
					// перемещаю в архив
					$guest->moveToArchive();
					$alert=__('guest.forceexitOK', array(':name'=>iconv('CP1251', 'UTF-8',$guest->name),':surname'=>iconv('CP1251', 'UTF-8',$guest->surname),':patronymic'=>iconv('CP1251', 'UTF-8',$guest->patronymic)));
					Session::instance()->set('alert',$alert);
					
				} else {
					$alert=__('guest.forceexitErr', array(':name'=>iconv('CP1251', 'UTF-8',$guest->name),':surname'=>iconv('CP1251', 'UTF-8',$guest->surname),':patronymic'=>iconv('CP1251', 'UTF-8',$guest->patronymic)));
					Session::instance()->set('alert',$alert);
					
				}
					$this->redirect('passoffices');
			break;
			
			case 'reissue':// выдача карты уже известному гостю
				//проверка что карта не выдана какому-нибудь гостю
				$key=new Keyk($idcard);
				
				$check=$key->check(1);
				if(is_null($check)){// если NULL - значит, этой карты не в базе данных, её можно выдавать гостю
					
				$guest=new Guest(Arr::get($_POST,'id_pep', 0));
				
				$guest->idOrgGuest = $this->idOrgGuest;// указал организацию гостя
				$guest->idOrgGuestArchive =$this->idOrgGuestArchive;//указал организацию архива
				
					$key->id_card=$idcard;
							$key->timestart=Arr::get($_POST, 'carddatestart');
							$key->timeend=Arr::get($_POST, 'carddateend');
							$key->id_pep=$guest->id_pep;
							$key->flag=1;
							$key->rfidmode=$rfidmode;
							
							//присвоедние карты RFID
							if($key->addRfid()==0) { //сохраняю карту RFID
									// перемещаю гостя в Гость
								$guest->moveToGuest();	
								$alert=__('guest.addRfidOk', array(':id_card'=>$key->id_card));
								$this->session->set('mode', 'guest_mode');
								//throw new Exception($alert, 271);
							} else {
							    $alert=__('guest.addRfidErr', array(':id_card'=>$key->id_card));
								$arrAlert[]=array('actionResult'=>3, 'actionDesc'=>$alert);
								Session::instance()->set('arrAlert',$arrAlert);
							    //throw new Exception($alert, 274);
							}
				Session::instance()->set('alert', $alert);
		
				} else {
					//карта выдана сотруднику с id_pep=$check
					
					$anypeople=new Guest($check);
					
					//Session::instance()->set('alert', __('contact.key_occuped_'.$check));
					$alert=__('guest.key_occuped', array(':idcard'=>$idcard, ':id_pep'=>$anypeople->id_pep,':name'=>iconv('CP1251', 'UTF-8',$anypeople->name),':surname'=>iconv('CP1251', 'UTF-8',$anypeople->surname),':patronymic'=>iconv('CP1251', 'UTF-8',$anypeople->patronymic)));
					
					$arrAlert[]=array('actionResult'=>3, 'actionDesc'=>$alert);
					
					Session::instance()->set('arrAlert',$arrAlert);
				//throw new Exception($alert, 274);
				}
				
				
			break;
	}
			
		//$this->redirect('passoffices');
		$this->redirect('passoffices/edit/' . $id);
	}

	
	/**23.06.2024
	Регистрация нового гостя или редактирование уже зарегистрированного.
	* @input passoffices/edit/<id_pep>/<режим работы>
		'guest_mode'://просмотр гостя с картой, можно сделать отметку о выходе
		'archive_mode'://просмотр архива
		'issue'://выдача карты новому гостю
	*/
	

	public function action_edit()
	{
	
		$id_pep=$this->request->param('id');// кого редактируем
		$mode=$this->request->param('mode');//режим работы

		$force_org=$this->request->query('id_org');//получаю id_org, куда надо записать гостя. наличие этого параметра означает, что надо выбрать именно указанную организацию
		
		$org_tree = Model::Factory('Company')->getOrgList();// получить список организаций.
		
		$fl = $this->session->get('alert');
		$arrAlert = $this->session->get('arrAlert');
		
		$this->session->delete('alert');
		$this->session->delete('arrAlert');
		$topbuttonbar=View::factory('passoffice/topbuttonbar', array(
		    'id_pep'=> $id_pep,
		    '_is_active'=> 'edit',
		   
		))
		;
					
		$this->template->content = View::factory('passoffice/edit')
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
			//->bind('photo', $photo);
			;
	}

	public function _action__view()
	{
		$id=$this->request->param('id');
		$contact = Model::factory('Contact')->getContact($id);
		if (!$contact) $this->redirect('passoffices');
		$companies = Model::factory('Company')->getNames(true);
		
		$this->template->content = View::factory('passoffice/view')
			->bind('contact', $contact)
			->bind('companies', $companies);
	}
	
	
	
	public function action_history()
	{
		$id=$this->request->param('id');
		$contact = Model::factory('Contact')->getContact($id);//Получаю контакт по его id
		if (!$contact) $this->redirect('passoffices');//если контакта нет, то перенаправление на список контактов 
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
		$id_pep=$this->request->param('id');
		$guest=new Guest($id_pep);
	
	
	$guest->idOrgGuest = $this->idOrgGuest;// указал организацию гостя
		$guest->idOrgGuestArchive =$this->idOrgGuestArchive;//указал организацию архива
			
		if($guest->delOnIdPep() == 0) {
			
			//$alert = __('guest.delOnIdPepOk', array(':surname'=>iconv('CP1251', 'UTF-8',$guest->surname),':name'=>iconv('CP1251', 'UTF-8',$guest->name),':patronymic'=>iconv('CP1251', 'UTF-8',$guest->patronymic),':id_pep'=>$guest->id_pep,':tabnum'=>$guest->tabnum));
			$alert = __('guest.delOnIdPepOk', array(':surname'=>'sur_',':name'=>'name_',':patronymic'=>'patr_',':id_pep'=>$guest->id_pep,':tabnum'=>'tab_'));
			
		} else {
			
			$alert=__('guest.delOnIdPepErr', array(':id_pep'=>$guest->id_pep));
		}
		Session::instance()->set('alert', $alert);
		
		
		$this->redirect('passoffices');
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
		if (!$contact) $this->redirect('passoffices');
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
		if ($id != "0" && !$contact) $this->redirect('passoffices');//если что не так - переход на корень
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
		
		
		$alert=__('passoffices.resultUpdateAcl', array(':addAclOkCount'=>$addAclOkCount,':delAclOkCount'=>$delAclOkCount ));		
		$arrAlert[]=array('actionResult'=>0, 'actionDesc'=>$alert);		
		Session::instance()->set('arrAlert',$arrAlert);
		
		//echo Debug::vars('416', Session::instance());exit;
		
		//echo Debug::vars('254',$source, $oldACL , $aclForDel, $aclForAdd, $resultDelAcl, $resultAddAcl); exit;
		$this->redirect('passoffices/acl/'.$id_pep);
	}
	
	

	
	public function _action_reload()
	{
		$id=$this->request->param('id');
		$card = Model::factory('Card')->getCard($id);
		
		Model::factory('Card')->reload($id);
		
		Session::instance()->set('alert', __('cards.deleted'));
		$this->redirect('passoffices/cardlist');
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
		  
		
		$this->redirect('passoffices/config');
		
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
				$this->redirect('passoffices/config');
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
			            $this->redirect('passoffices/config');
			        break;
			        case 'updatePassoffice':
					
			         $po->name=Arr::get($post, 'name');
			         $po->idOrgGuest=Arr::get($post, 'idOrgGuest');
			         $po->idOrgGuestArchive=Arr::get($post, 'idOrgGuestArchive');
			         $po->is_active=Arr::get($post, 'is_active');
			         $po->update();
			         $this->redirect('passoffices/config');
			        
			        break;
			        
			    }
				
			} else {
				
			    $this->redirect('passoffices/config');
			}
			
		
		
		
		}
	

}
