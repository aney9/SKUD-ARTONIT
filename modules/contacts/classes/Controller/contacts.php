<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Contacts extends Controller_Template
{

	public $orgFastOrder=null;
	
	public $template = 'template';
	
	public function before()
	{
		parent::before();
	//	echo Debug::vars('13',$this->request->controller(),  $this->request->action());
		
	}

	
	
	/**04.09.2024 сохранение настроек для быстрой регистрации
	*/
	public function action_saveFastOrder()
	{
		//echo Debug::vars('31', $_POST); exit;
		$confFastOrder=array();
		$confFastOrder= Kohana::$config->load('system')->get('fastorder');
		$confFastOrder[Arr::get($_POST, 'id_pep')] = Arr::get($_POST, 'id_org');
		Kohana::$config->_write_config('system', 'fastorder', $confFastOrder);
		$this->redirect('/');
		
	}
	
	/*
	21.01.2024 подготовка отчета рабочего времени
	
	
	*/
	public function action_worktime()
	{
		//echo Debug::vars('29', $this->request->param('id')); exit;
		$id_pep= $this->request->param('id'); 
		
		$topbuttonbar=View::factory('contacts/topbuttonbar', array(
			'id_pep'=> $id_pep,
			'_is_active'=> 'worktime',
			))
		;
		
		
		$this->template->content = View::factory('contacts/reportsetting')
				->bind('id_pep', $id_pep)
				->bind('mode', $mode)
				->bind('alert', $fl)
				->bind('topbuttonbar', $topbuttonbar);
		
	}
	
	
	/*
	10.01.2024 загрузка фотографии для контакта
	*/
	
	public function action_upload()
	{
		// check request method
		
		if ($this->request->method() === Request::POST)
		{
			$id_pep=Arr::get($_POST, 'id_pep');
			// create validation object
			$validation = Validation::factory($_FILES)
				->label('image', 'Picture')
				->rules('image', array(
					array('Upload::not_empty'),
					array('Upload::image'),
				));

			// check input data
			if ($validation->check())
			{
		
				// process upload
				Upload::save($validation['image'], 'vnii_photo', 'C:\xampp\tmp'); 
				//запись содержимого файла в базу данных
				$file_name='C:\xampp\tmp\vnii_photo';
			
			
			//$filename = strtolower(Text::random('alnum', 20)).'.jpg';
 /* Преобразование изображения к нужному размеру
 https://kohana.top/3.3/guide/image/examples/dynamic
 */
 
	/* 		 $directory = '';
            $img = Image::factory($file_name);
			
		//	$new_height = (int) $img->height / 2;
 
         //   $img->crop($new_height, $new_height)
         //       ->save($directory.$file_name.'-xx');
				
			 $width=400;
			 $height = null;
			$img->resize($width, $height)
                ->render('jpg');
				$img->save($directory.$file_name.'-xx.jpg');
			
			echo Debug::vars('81');exit;  */
				
			$photo=file_get_contents($file_name);
				$contact=new Contact ($id_pep);
				if($contact->savephoto($photo) == 0) {
					
					$alert=__('contact.insertphotoOk');
				} else {
					
					$alert=__('contact.insertphotoErr1');
				}
				
		
				
			} else {
				
				$alert=__('contact.insertphotoErr2');
			}
			Session::instance()->set('alert', $alert);
			$this->redirect('contacts/edit/'.$id_pep);		
		}

	
		
	}
	
/*
При поиске параметр фильтра сохраняется в сессию, в ключ search_contact
Затем вызывается index с указанным параметров для поиска.
Поиск - это один указанный id_pep
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
	
	/**
	$filter - текст для поиска фамилии
	*/
	public function action_index($filter = null)
	{
		$t1=microtime(true);
		//если указано, что выводить с учетом фильтра, либо ВСЕХ (ALL), то начинаю выборку с учетом фильтра.
		if((!is_null($filter)) OR (strtoupper($this->request->param('id')) == 'ALL')){
		$contacts = Model::factory('Contact');
		//определяю режим показа: 
		// is_active = 0, что означает работу с удаленными сотрудниками.
		
		$q=0;// количество доступных контактов пока 0.
		$list=array();// список контактов пока пуст.
		
		$id_orgctrl=$this->user->id_orgctrl;//организация для управления текущего пользователя
		// если $this->session->get('is_host') == 1, то работаем в режиме быстрой регистрации. Для этого $id_orgctrl берется из настроек fastorder для текущего юзера
		if($this->session->get('is_host') == 1) {
		//предстоит работа с быстрой регистрацией. Для этого:
			//проверяю наличие настроек для текущего юзера
			$id_orgctrl=Arr::get(Kohana::$config->load('system')->get('fastorder'), $this->user->id_pep);
			
			//если настройки нет, то перевожу оператора на настройку
			if(is_null($id_orgctrl)) $this->redirect('contacts/disp/hostSetup');
			
			//если же настройка имеется, то надо убедиться, что эта организация разрешена текущему пользователю. Его права доступа могли измениться с 
			//момента предыдущей настройки.
			
			if(!in_array(array('ID_ORG'=>$id_orgctrl), $this->user->getChildOrg())) $this->redirect('contacts/disp/hostSetup');
			
			//если все в порядке - работаем дальше
		}
		//что отображать: Session::instance()->get('viewDeletePeopleOnly') == 1 - значит, отображать уволенных.
		if(Session::instance()->get('viewDeletePeopleOnly') == 1) {
			$contacts ->peopleIsActive=0;
			$list = $contacts->getListUser($id_orgctrl, Arr::get($_GET, 'page', 1), $this->listsize, iconv('UTF-8', 'CP1251', $filter));
			
		} else {
			//иначе - отображать Активных
			$contacts ->peopleIsActive=1;
		}
		

			
		$list = $contacts->getListUser($id_orgctrl, Arr::get($_GET, 'page', 1), $this->listsize, iconv('UTF-8', 'CP1251', $filter));
	
		//echo Debug::vars('190', count($list), (microtime(true)-$t1));exit;

		//контроль количества выводимых строк. если стро много - то будет заметное "торможение".
		$alert='';
		if(count($list)>Kohana::$config->load('config_newcrm')->get('table_view_max_contact')){
			$alert=__('contCount', array(':contCount'=>Kohana::$config->load('config_newcrm')->table_view_max_contact, ':totalCount'=>count($list)));
			$list=array_slice($list, 0, Kohana::$config->load('config_newcrm')->get('table_view_max_contact'));
			
		}
		//$list = array_slice($contacts->newInit(), 0, 5);
		$list = $contacts->newInit($filter);//май 2025 г, получаю весь список пиплов сразу. Время выполнения примерно 0,2 секунды.
		//echo Debug::vars('203', count($list), (microtime(true)-$t1));exit;
		$fl=$alert;
			
		$arrAlert = $this->session->get('arrAlert'); //извлечь алерт из сессии
		$this->session->delete('arrAlert');//очистить алерт в сессии
		}

		//include Kohana::find_file('views\alerttest','testarralert');
		$view = View::factory('contacts/list2')// это "новый" вариант, где все должно браться из единого массива, и этот вариант работает быстрее в 10 раз, но еще не доделан
		//$view = View::factory('contacts/list')// это "старый" вариант, где много new
			
			->bind('people', $list)
			->bind('alert', $fl)
			->bind('arrAlert[]', $arrAlert[])
			->bind('filter', $filter)
			->bind('arrAlert', $arrAlert)
			->bind('t1', $t1)
			;
	$this->template->content =	$view;	
	

		
	//echo View::factory('profiler/stats');
	}
	
	/*
	обработка POST-запросов
	09.01.2024 сохранение информации по контакту.
	
	*/
	public function action_save()
	{
		//echo Debug::vars('70', $_POST); exit;
	    if(Arr::get($_POST, 'id_org') == 1) {
			//throw new Exception('Вы не можете добавлять сотрудников в организацию "Все"', 182);
			$alert='Добавление сотрудников в организацию Все запрещено. Укажите другую организацию при регистрации.';
			$arrAlert[]=array('actionResult'=>3, 'actionDesc'=>$alert);
			Session::instance()->set('arrAlert', $arrAlert);
			$this->redirect('contacts/edit/0');
		}
		$todo			= Arr::get($_POST, 'todo');
		$active			= Arr::get($_POST, 'active');
		$id			= Arr::get($_POST, 'id_pep');
		$idcard		= Arr::get($_POST, 'idcard', null);
		$alert='';

	
					$contact=new contact($id);
					$contact->name=Arr::get($_POST, 'name','');
					$contact->patronymic=Arr::get($_POST, 'patronymic','');
					$contact->surname=Arr::get($_POST, 'surname','');
					$contact->numdoc=Arr::get($_POST, 'numdoc','');
					$contact->datedoc=Arr::get($_POST, 'datedoc','');
					$contact->note=Arr::get($_POST, 'note','');
					$contact->id_org=Arr::get($_POST, 'id_org','');
					$contact->post=Arr::get($_POST, 'post','');
		
					
					
		if($id == 0){ // если 0, то это - новый контакт					
					if($contact->addContact() == 0) { // если пользователь добавлен успешно, то выставляю ему набор категорий доступа по умолчанию
					
						$alert=__('contact.addOK', array(':surname'=>$contact->surname,':name'=>$contact->name,':patronymic'=>$contact->patronymic,':id_pep'=>$contact->id_pep,':tabnum'=>$contact->tabnum));
						
						// присвоение категории доступа по умолчанию для организации.
							if($contact->setAclDefault()){ // если категории добавлены успешно
								$alert=$alert. '<br>'. __('acl.saved');
							}
							//если номер документа или его дата не пусты, то сохранить документы
							//echo Debug::vars('296', $guest->numdoc=='', $guest->datedoc==''); exit;
							if($contact->numdoc!='' AND $contact->datedoc!=''){
								if($contact->addDoc() == 0) {;//добавляю данные по документу
									$alert.='<br>'. __('contact.adddocOK', array(':numdoc'=>$contact->numdoc, ':surname'=>$contact->surname,':name'=>$contact->name,':patronymic'=>$contact->patronymic));
									}
							}
							 
						Session::instance()->set('alert', $alert);
						} else {
						//не удалось добавить гостя в базу данных СКУД.
						$alert=__('contact.addErr', array(':surname'=>$contact->surname,':name'=>$contact->name,':patronymic'=>$contact->patronymic,':id_pep'=>$contact->id_pep,':tabnum'=>$contact->tabnum));
						
						}
						$arrAlert[]=array('actionResult'=>0, 'actionDesc'=>$alert);
						Session::instance()->set('arrAlert', $arrAlert);
					
				} else {
					// обновление данных уже существующего сотрудника
					if($contact->updateContact() == 0) { // если пользователь обновлен успешно, то выставляю ему набор категорий доступа по умолчанию
					
						$alert=__('Контакт :surname :name :patronymic обновлен успешно.', array(':surname'=>$contact->surname,':name'=>$contact->name,':patronymic'=>$contact->patronymic,':id_pep'=>$contact->id_pep,':tabnum'=>$contact->tabnum));
							
							//если номер документа или его дата не пусты, то сохранить документы
							//echo Debug::vars('296', $guest->numdoc=='', $guest->datedoc==''); exit;
							if($contact->numdoc!='' AND $contact->datedoc!=''){
								if($contact->addDoc() == 0) {;//добавляю данные по документу
									$alert.='<br>'. __('contact.updatedocOK', array(':numdoc'=>$contact->numdoc, ':surname'=>$contact->surname,':name'=>$contact->name,':patronymic'=>$contact->patronymic));
									}
							}
							 
						Session::instance()->set('alert', $alert);
						} else {
						//echo Debug::vars('70 err'); exit;
						//не удалось обновлить данные по контакту
						$alert=__('contact.updateErr', array(':surname'=>$contact->surname,':name'=>$contact->name,':patronymic'=>$contact->patronymic,':id_pep'=>$contact->id_pep,':tabnum'=>$contact->tabnum));
						
						
						}
					$arrAlert[]=array('actionResult'=>0, 'actionDesc'=>$alert);
					Session::instance()->set('arrAlert', $arrAlert);
				
		};
		
		
		//$this->redirect('guests');
		//Session::instance()->set('alert', $alert);
		$this->redirect('contacts/edit/' . $contact->id_pep);
	}
	
	
	public function action_hardDeleteContact()
	{
		echo Debug::vars('276', $_POST); exit;
	}
	
	
	public function action__save()
	{
		//echo Debug::vars('70', $_POST); exit;
		$id			= Arr::get($_POST, 'id_pep');
		$surname	= Arr::get($_POST, 'surname');
		$name		= Arr::get($_POST, 'name','');
		$patronymic	= Arr::get($_POST, 'patronymic','');
		$datebirth	= Arr::get($_POST, 'datebirth', 'NULL');
		$numdoc		= Arr::get($_POST, 'numdoc', '');
		$datedoc	= Arr::get($_POST, 'datedoc', '');
		$workstart	= Arr::get($_POST, 'workstart', '09:00:00');
		$workend	= Arr::get($_POST, 'workend', '18:00:00');
		$active		= Arr::get($_POST, 'active', 1);
		$peptype	= Arr::get($_POST, 'peptype', 0);
		$post		= Arr::get($_POST, 'post', null);
		$tabnum		= Arr::get($_POST, 'tabnum');
		$login		= Arr::get($_POST, 'login', '');
		$password	= Arr::get($_POST, 'password', '');
		$org		= Arr::get($_POST, 'id_org');
		$inherit	= Arr::get($_POST, 'inherit', 0);
		$note		= Arr::get($_POST, 'note', null);
		$id_org_old		= Arr::get($_POST, 'id_org_old', null);

		$contact = Model::factory('Contact');

		if ($id == 0) { // это добавление нового пользователя, т.к. $id (она же id_pep) равна 0.
			//echo Debug::vars('91', $surname, $name, $patronymic, $datebirth, $numdoc, $datedoc, $workstart, $workend, $active, $peptype, $post, $tabnum, $org, $login, $password); exit;
			$id = $contact->save($surname, $name, $patronymic, $datebirth, $numdoc, $datedoc, $workstart, $workend, $active, $peptype, $post, $tabnum, $org, $login, $password, $note);
			
			if($inherit == 1) $contact->setInheritAcl($id);
			Session::instance()->set('alert', __('contact.saved'));
		} else {
			$contact->update($id, $surname, $name, $patronymic, $datebirth, $numdoc, $datedoc, $workstart, $workend, $active, $peptype, $post, $tabnum, $org, $login, $password, $note);
			echo Debug::vars('101 ', $id, $id_org_old, $org); //exit;
			if(($id_org_old != $org) and ($inherit ==1)) // если есть изменения в организации, и включено наследование, то надо поменять и набор категорий доступа
			{
				
				$aclList= Model::factory('company')->company_acl($org);//список категорий доступа, уже выданных организации;//передаю новый набор категорий доступа
				
				foreach($aclList as $key=>$value)
				{
					
					$res[Arr::get($value, 'ID_ACCESSNAME')]=1;
				}
							
				//echo Debug::vars('114 ', $aclList, $res); exit;
				$this->checkACL($id, $res);
				//echo Debug::vars('106', $aclList, $res); exit;
		
			} else {
				//echo Debug::vars('120 набор категорий доступа не менялся.'); exit;
				Session::instance()->set('alert', __('120 набор категорий доступа не менялся'));
			}
			Session::instance()->set('alert', __('contact.updated'));
		}
		//$this->redirect('contacts');
		$this->redirect('contacts/edit/' . $id);
	}

	
	/** 04.09.2024 диспетчер: что показывать при вызове с экранной формы Контакт
	* основая задача - установка метки is_host в сессии $this->session->set('is_host', '0');
	* is_host = 0, что означает работу по id_orgctrl
	* is_host = 1, что означает работу по idOrhGuest.
	* activeOnlyList - показывать актвные контакты,
	* viewDeletePeopleOnly - показывать удалденные контакты,
	* на основании этой метки будет выводится информация либо по все разрешенным организациям, либо только под одной, указанной как host
	*/
	public function action_disp()
	{
		$mode=$this->request->param('id');
		switch($mode){
			case 'activeOnlyList':// показать активные контакты
			//echo Debug::vars('358');exit;
					
				$this->session->set('viewDeletePeopleOnly', '0');
				$this->session->set('is_host', '0');
				$this->redirect('contacts');
			
			break;
			case 'addContact': //добавление нового контакта с выбором организации 
			
				$this->session->set('is_host', '0');
				$this->redirect('contacts/edit/0');
							
			break;
			case 'deletedList':// показать удаленные контакты.
				$this->session->set('is_host', '0');
				$this->redirect('contacts/deletedList');
			break;
			
			case 'HostOnlyList': // показать только host контакты (быстрая регистрация)
				$this->session->set('viewDeletePeopleOnly', '0');
				$this->session->set('is_host', '1');
				$this->checkFastRegOrg();
				$this->redirect('contacts');
			
			break;
			case 'hostAddContact'://добавление нового контакта строго в организацию host
				$this->session->set('is_host', '1');
				$this->checkFastRegOrg();
				$this->redirect('contacts/edit/0');
			
			break;
			case 'hostDeletedList': // показать удаленные контакты орагизации host.
				$this->session->set('is_host', '1');
				$this->checkFastRegOrg();
				$this->redirect('contacts/deletedList');
			
			break;
			case 'hostSetup': // настройки для быстрой регистрации.
				
				$arrAlert = $this->session->get('arrAlert'); //извлечь алерт из сессии
				$this->session->delete('arrAlert');//очистить алерт в сессии

				$this->template->content = View::factory('contacts/hostSetup')
					->bind('arrAlert', $arrAlert);
			
			break;
				
		}
		
	}
	
	
	/*
	04.12.2023 Установка метки is_host = 0 при выводе списка контактов.
	is_host = 0, что означает работу по id_orgctrl
	is_host = 1, что означает работу по idOrhGuest.
	*/
	public function action_HostOnlyList()
	{
		$this->session->set('viewDeletePeopleOnly', '0');
		$this->session->set('is_host', '1');
		$this->redirect('contacts');
	}
	
	
	/*
	04.12.2023 Установка метки is_host = 0 при выводе списка контактов.
	is_host = 0, что означает работу по id_orgctrl
	is_host = 1, что означает работу по idOrhGuest.
	*/
	public function action_OnlyList()
	{
		$is_host=
		$this->session->set('viewDeletePeopleOnly', '0');
		$this->session->set('is_host', '1');
		$this->redirect('contacts');
	}
	
	
	
	public function action_edit()
	{
		$id=$this->request->param('id');//это id_pep
		
		$force_org=$this->request->query('id_org');//наличие этого параметра означает, что надо выбрать именно указанную организацию для правильной работы дерева организаций.
		
		//выбор головной организации
		$id_orgctrl=$this->user->id_orgctrl;
		//выбираю организацию для быстрой регистрации из параметров настройки 'system' для текущего авторизованного пипла
		if($this->session->get('is_host') == 1) $id_orgctrl=Arr::get(Kohana::$config->load('system')->get('fastorder'), $this->user->id_pep);
		
		$contact= new Contact($id);// сразу формирую контакт (т.к. он один, то объем данных сравнительно маленький). Фото в контакте нет!
		
		
		
		if($id == 0) {    //если id=0, то создаем нового пипла
			$contact->is_active=1;
			$contact->id_org=$id_orgctrl;// при создании нового пипла ему присваивается id_org, которым управляет текущий пользователь
		} else {
			
			//защита от умышленно указанных неправильных данных.
		$post=Validation::factory(array('id_pep'=>trim($this->request->param('id'))));//определяю тип идентификатора, который надо выводить
		 $post->rule('id_pep', 'not_empty')
				->rule('id_pep', 'digit')
				->rule('id_pep', 'Model_Contact::unique_idpep')
				;
				
		 if(!$post->check())
		{
			//echo Debug::vars('486');exit;
			$alert=__('contact.validKeyErr', array(':id_pep'=>Arr::get($post, 'id_pep'), ':desc'=>implode(",", $post->errors('validation'))));
			$arrAlert[]=array('actionResult'=>constants::ALERT_WARNING, 'actionDesc'=>$alert);
			Session::instance()->set('arrAlert', $arrAlert);
			$this->redirect('contacts');
						
			
		}  
		
		    $org= new Company($contact->id_org);
		    if($org->flag & 1) $this->redirect('passoffices/edit/'.$id.'/guest_mode'); //если это гость, то уходим на показ свойства гостя
			$contact->getPhoto(); 
		}
		
		$contact_acl = Model::factory('Contact')->contact_acl($id);//список категорий доступа, выданных контакту
		$check_acl = Model::factory('Contact')->check_acl($id);//получить список категорий доступа родительской организации для сверки с текущим списком категорий для контакта: совпадает или нет? 0 -совпадает, 1 - не совпадает.
		if ($id != "0" && !$contact) $this->redirect('contacts');
		if ($id ==1) $this->redirect('contacts');
	
		$isAdmin = Auth::instance()->logged_in('admin');
		$companies = Model::factory('Company')->getNames($isAdmin ? null : Auth::instance()->get_user());

		$org_tree = Model::Factory('Company')->getOrgListForOnce($id_orgctrl);
		//echo Debug::vars('349', $org_tree); exit;
		$fl = $this->session->get('alert');
		$this->session->delete('alert');
		
		// извлекают описание ошибки из сессии
		$arrAlert = $this->session->get('arrAlert'); //извлечь алерт из сессии
		$this->session->delete('arrAlert');//очистить алерт в сессии


		//определяю режим работы окна редактирования.
		$mode='unknow';
		if($contact->id_pep==0) $mode='new';//режим добавления нового контакта
		if($contact->id_pep >0 and $contact->is_active==1) $mode='edit';//режим редактирования существующего контакта
		if($contact->id_pep >0 and $contact->is_active==0) $mode='fired';//режим редактирования "удаленного" контакта
		
		$topbuttonbar=View::factory('contacts/topbuttonbar', array(
			'id_pep'=> $contact->id_pep,
			'_is_active'=> 'edit',
			))
		;
		
		$this->template->content = View::factory('contacts/edit')
			->bind('contact', $contact) //контакт передается уже как экземпляр класса!!!
			->bind('alert', $fl)
			->bind('arrAlert', $arrAlert)
			->bind('contact_acl', $contact_acl)
			->bind('org_tree', $org_tree)
			->bind('force_org', $force_org)
			->bind('check_acl', $check_acl)
			->bind('companies', $companies)
			->bind('mode', $mode)
			->bind('topbuttonbar', $topbuttonbar)// передал панель кнопок управления
			//->bind('photo', $photo);
			;
			//echo View::factory('profiler/stats');
	}

	public function action_view()
	{
		$id=$this->request->param('id');
		$contact = Model::factory('Contact')->getContact($id);
		
		if (!$contact) $this->redirect('contacts');
		$companies = Model::factory('Company')->getNames($id);
		
		$this->template->content = View::factory('contacts/view')
			->bind('contact', $contact)
			->bind('companies', $companies);
	}
	

	
	public function action_history()
	{
		$id=$this->request->param('id');
		$contact = Model::factory('Contact')->getContact($id);//Получаю контакт по его id
		if (!$contact) $this->redirect('contacts');//если контакта нет, то перенаправление на список контактов 
		//$data = History::getHistory($id);// беру историю для указанного контакта историю (контроллер History.php, метод getHistory($user))
		$hist=new History();
		$hist->id_pep=$id;
		$hist->dateFrom='2000-01-01';
		$hist->eventListForView=array(46, 50, 65, 70, 71, 47, 48, 31, 32, 33, 34, 47, 48, 17, 18);

		//echo Debug::vars('573', $hist); exit;
		
		$data = $hist->getHistory();
		//echo Debug::vars('381', $id, $data); exit;
		
		$topbuttonbar=View::factory('contacts/topbuttonbar', array(
			'id_pep'=> $id,
			'_is_active'=> 'history',
			))
		;
		
		
		$this->template->content = View::factory('contacts/history')//вызываю вью contacts/history.php
			->bind('contact', $contact)
			->bind('data', $data)
			->bind('id', $id)
			->bind('topbuttonbar', $topbuttonbar)
			;
	}
	
	/*
		Увольнение сотрудника 
	
	*/
	
	
	public function action_fired()
	{
		
		$id_pep=$this->request->param('id');
		//$key=new Keyk();
		$contact=new Contact($id_pep);
		
		
		
		
		$alert='';
		/* удаляю карту сотрудника*/
		switch (ConfigType::howDeletePeople()) {
			case 0://делать неактивным
			if($contact->setNotActiveOnIdPep() == 0){
					$cards = Model::factory('Card')->getListByPeople($contact->id_pep);
				//echo Debug::vars('617', $cards); exit;
		
				if(count($cards)>0)
				{
					foreach ($cards as $key=>$value)
					{
						$key=new Keyk(Arr::get($value, 'ID_CARD'));
						$key->delCard();
						
					}
				
				}
				
				$alert.='<br>'.__('contact.setNotActiveOK', array(':name'=>iconv('CP1251', 'UTF-8',$contact->name),':surname'=>iconv('CP1251', 'UTF-8',$contact->surname),':patronymic'=>iconv('CP1251', 'UTF-8',$contact->patronymic)));
				$arrAlert[]=array('actionResult'=>0, 'actionDesc'=>$alert);	
			} else {
				$alert.='<br>'.__('contact.setNotActiveErr', array(':name'=>iconv('CP1251', 'UTF-8',$contact->name),':surname'=>iconv('CP1251', 'UTF-8',$contact->surname),':patronymic'=>iconv('CP1251', 'UTF-8',$contact->patronymic)));
				$arrAlert[]=array('actionResult'=>3, 'actionDesc'=>$alert);
		}
		
		
		break;
		
		case 1:// удалять сотрудника
		
			if($contact->delOnIdPep() == 0){
			$alert=__('contact.deleteOK', array(':name'=>iconv('CP1251', 'UTF-8',$contact->name),':surname'=>iconv('CP1251', 'UTF-8',$contact->surname),':patronymic'=>iconv('CP1251', 'UTF-8',$contact->patronymic)));
			$arrAlert[]=array('actionResult'=>0, 'actionDesc'=>$alert);	
		} else {
			$alert=__('contact.deleteErr', array(':name'=>iconv('CP1251', 'UTF-8',$contact->name),':surname'=>iconv('CP1251', 'UTF-8',$contact->surname),':patronymic'=>iconv('CP1251', 'UTF-8',$contact->patronymic)));
			
			$arrAlert[]=array('actionResult'=>3, 'actionDesc'=>$alert);	
			
		}
		
		break;
		
		
	
		}
		Session::instance()->set('arrAlert',$arrAlert);
		$this->redirect('companies/people/'.$contact->id_org);
	}
	
	/*
		12.01.2024
		Удаление сотрудника 
	
	*/
	
	public function action_delete()
	{
		//echo Debug::vars('430', $_POST); exit;
		$this->session->set('is_host', '0');	
		$contact=new Contact(Arr::get($_POST, 'id_pep'));
		
		$alert='';
		/* делаю сотрудника неактивным*/
		if($contact->delOnIdPep() == 0){
			$alert.='<br>'.__('contact.deleteOK', array(':name'=>iconv('CP1251', 'UTF-8',$contact->name),':surname'=>iconv('CP1251', 'UTF-8',$contact->surname),':patronymic'=>iconv('CP1251', 'UTF-8',$contact->patronymic)));
				
		} else {
			$alert.='<br>'.__('contact.deleteErr', array(':name'=>iconv('CP1251', 'UTF-8',$contact->name),':surname'=>iconv('CP1251', 'UTF-8',$contact->surname),':patronymic'=>iconv('CP1251', 'UTF-8',$contact->patronymic)));
	
		}
		
		Session::instance()->set('alert',$alert);
		$this->redirect('contacts/deletedList');
	}
	
	/*
	7.01.2024
	Сделать неактивного пипла активным
	
	*/
	public function action_restore()
	{
		$id_pep=$this->request->param('id');
		$contact=new Contact($id_pep);
		
		/* делаю сотрудника АКТИВНЫМ*/
		if($contact->setIsActiveOnIdPep()==0){
			
			//$contact->setAclDefault();// Категории доступа должны храниться в ss_accessuser
			$alert=__('contact.setIsActiveOK', array(':name'=>iconv('CP1251', 'UTF-8',$contact->name),':surname'=>iconv('CP1251', 'UTF-8',$contact->surname),':patronymic'=>iconv('CP1251', 'UTF-8',$contact->patronymic)));
				
		} else {
			$alert=__('contact.setIsActiveErr', array(':name'=>iconv('CP1251', 'UTF-8',$contact->name),':surname'=>iconv('CP1251', 'UTF-8',$contact->surname),':patronymic'=>iconv('CP1251', 'UTF-8',$contact->patronymic)));
	
		}
		
		
		
		Session::instance()->set('alert',$alert);
		$this->redirect('contacts');
	}
	
	
	
	/*
	Переход на окно добавления RFID
	
	*/
	public function action_addrfid()
	{
		$id=$this->request->param('id');
		$contact = new Contact($id);
		$mode='unknow';
		if($contact->id_pep==0) $mode='new';//режим добавления нового контакта
		if($contact->id_pep >0 and $contact->is_active==1) $mode='edit';//режим редактирования существующего контакта
		if($contact->id_pep >0 and $contact->is_active==0) $mode='fired';//режим редактирования "удаленного" контакта
		
		$anames = AccessName::getList();
		$card = array();
		
		$this->template->content = View::factory('contacts/card')
			->bind('contact', $contact)
			->bind('mode', $mode)
			->bind('anames', $anames);
	}
	
	public function action_addgrz()
	{
		$id=$this->request->param('id');
		$contact = Model::factory('Contact')->getContact($id);
		$anames = AccessName::getList();
		$card = array();
		
		$this->template->content = View::factory('contacts/grz')
			->bind('contact', $contact)
			->bind('anames', $anames);
	}
	
		
	/*
	19.12.2023 Установка метки is_active = 0, что означает работу с удаленными сотрудниками.
	is_active = 0, что означает работу с удаленными сотрудниками.
	is_active = 1, что означает работу с неудаленными сотрудниками.
	*/
	public function action_deletedList()
	{
		$this->session->set('viewDeletePeopleOnly', '1');
		$this->redirect('contacts');
	}
	
	
	/*
	19.12.2023 Установка метки is_active = 0, что означает работу с удаленными сотрудниками.
	is_active = 0, что означает работу с удаленными сотрудниками.
	is_active = 1, что означает работу с неудаленными сотрудниками.
	*/
	public function action_activeOnlyList_()
	{
		$this->session->set('viewDeletePeopleOnly', '0');
		$this->session->set('is_host', '0');
		$this->redirect('contacts');
	}
	
	
	/*
	Вывод информации по карте
	*/
	public function action_card()
	{
		$id=$this->request->param('id');
		$card = Model::factory('Card')->getCard($id);
		
		if ($id != "0" && !$card) $this->redirect('/');
		$contact = new Contact($card['ID_PEP']);
		$contact_acl = Model::factory('Contact')->contact_acl($card['ID_PEP']);
		
		$loads = Model::factory('Card')->getLoads($card['ID_CARD']);
		$anames = AccessName::getList();

		$fl = $this->session->get('alert');
		$this->session->delete('alert');
		
		$mode='edit';
		
		//переключатель view
		$viewList=array(
		1=>'card',
		4=>'grz');
		// имя файла для редактирования вызывается из настроек viewList. Это либо card для идентификаторов RFID, либо grz для номерного знака
		$this->template->content = View::factory('contacts/'.Arr::get($viewList, Arr::get($card, 'ID_CARDTYPE')))
			->bind('contact', $contact)//данные о контакте (ФИО)
			->bind('contact_acl', $contact_acl)//категории доступа, выданные контакту
			->bind('card', $card)// данные о карте
			->bind('loads', $loads)//данные о заргузке карты в контроллеры
			->bind('multiple', $multiple)//не используется
			->bind('anames', $anames)//Перечень всех категорий доступа. не используется
			->bind('alert', $fl)//сообщение alert
			->bind('mode', $mode)//режим работы формы
			->bind('id', $id);//номер карты
	}
	
	/*
		вывод списка идентификторов 
		
	
	*/
	
	public function action_cardlist()
	{
		$id=$this->request->param('id');
		
		$contact = new Contact($id);
		if (!$contact) $this->redirect('contacts');
		$cards = Model::factory('Card')->getListByPeople($id);

		$fl = $this->session->get('alert');
		$this->session->delete('alert');
		
		$arrAlert = $this->session->get('arrAlert');
		$this->session->delete('arrAlert');
		
		$mode='unknow';
		if($contact->id_pep==0) $mode='new';//режим добавления нового контакта
		if($contact->id_pep >0 and $contact->is_active==1) $mode='edit';//режим редактирования существующего контакта
		if($contact->id_pep >0 and $contact->is_active==0) $mode='fired';//режим редактирования "удаленного" контакта
		$topbuttonbar=View::factory('contacts/topbuttonbar', array(
			'id_pep'=> $contact->id_pep,
			'_is_active'=> 'cardlist',
			))
		;
		
		
		$this->template->content = View::factory('contacts/cardlist')
			->bind('contact', $contact)
			->bind('cards', $cards)
			->bind('alert', $fl)
			->bind('arrAlert', $arrAlert)
			->bind('mode', $mode)
			->bind('topbuttonbar', $topbuttonbar)
			->bind('id', $id);
	}
	
	/*
	9.08.2023
	Вывод списка категорий доступа, выданных пиплу
	
	*/
	public function action_acl()
	{
		$id=$this->request->param('id');
		//$contact = Model::factory('Contact')->getContact($id);//информация о контакте
		$contact = new Contact($id);//информация о контакте
		
		$contact_acl = Model::factory('Contact')->contact_acl($id);//список категорий доступа, выданных контакту
		if ($id != "0" && !$contact) $this->redirect('contacts');
		$isAdmin = Auth::instance()->logged_in('admin');
		$companies = Model::factory('Company')->getNames($isAdmin ? null : Auth::instance()->get_user());
		
		$mode='unknow';
		if($contact->id_pep==0) $mode='new';//режим добавления нового контакта
		if($contact->id_pep >0 and $contact->is_active==1) $mode='edit';//режим редактирования существующего контакта
		if($contact->id_pep >0 and $contact->is_active==0) $mode='fired';//режим редактирования "удаленного" контакта
		
		
		$fl = $this->session->get('alert');
		$this->session->delete('alert');
		
		$topbuttonbar=View::factory('contacts/topbuttonbar', array(
			'id_pep'=> $contact->id_pep,
			'_is_active'=> 'acl',
			))
		;
		
		$this->template->content = View::factory('contacts/acl')
			->bind('contact', $contact)
			->bind('alert', $fl)
			->bind('mode', $mode)
			->bind('contact_acl', $contact_acl)
			->bind('companies', $companies)
			->bind('topbuttonbar', $topbuttonbar)
			;
	}
	
	
	/*10.11.2024 проверка настройки организации для быстрой регистрации.
	* если $this->session->get('is_host'), то $this->orgFastOrder должен быть установлен.
	* если $this->orgFastOrder не установлен, то надо сделать переход на конфигурирование orgFastOrder
	*/
	private function checkFastRegOrg()
	{
		//echo Debug::vars('881', $this->session->get('is_host'), $this->orgFastOrder);exit;
		//если предстоит работать с быстрой регистрацией, то надо убедиться, что выполнена его настройка
		if(($this->session->get('is_host')==1) and ($this->orgFastOrder==null)){
		
		$this->orgFastOrder=Arr::get(Kohana::$config->load('system')->get('fastorder'), $this->user->id_pep, null);
		
			//если же настройка имеется, то надо убедиться, что эта организация разрешена текущему пользователю. Его права доступа могли измениться с 
			//момента предыдущей настройки.
			
			if(!in_array(array('ID_ORG'=>$this->orgFastOrder), $this->user->getChildOrg())) {
				
				
				$arrAlert[]=array('actionResult'=> constants::ALERT_INFO, 'actionDesc'=>'Требуется настройка быстрой регистрации.');	
				
				Session::instance()->set('arrAlert',$arrAlert);
				$this->redirect('contacts/disp/hostSetup');
			} else {
				
				//echo Debug::vars('896');exit;
				
			}
			
			
		}
		
	}
	
	/*
	17.08.2023
	Проверка категорий доступа для контакта
	$id - контакт, для которого устанавливается набор категорий доступа
	$aclList - набор устанавливаемых категорий доступа
	*/
	public function checkACL($id, $aclList)
	{
				if(!$aclList)
		{
			//если массив нового набора категорий доступа пуст, то очищаю таблицу ss_accessuser для этого пипла
			$resultDelAcl=Model::factory('Contact')->clear_contact_acl($id);//удаляю все из таблицы ss_accessuser
			
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
	$contact_acl_befor - набор категорий доступа, который был у контакта до изменения
	$user_acl_anabled - массив категорий доступа, которые может менять текущий оператор.
	$aclList - массив категорий доступа, которые установил оператор для контакта.
	*/
	public function action_saveACL()
	{
		echo Debug::vars('274', $_POST); //exit;
		$id=$this->request->post('id');
		$contact_acl_befor=json_decode($this->request->post('contact_acl_befor'));// массив категорий доступа до изменения
		$user_acl_anabled=json_decode($this->request->post('user_acl_anabled'));// массив категорий доступа, которые может менять текущий оператор.
		
		
		//acl_resident - эти категории доступа должны остаться, т.к. их установил другой оператор, а текущий оператор не может ими распоряжаться:
		$acl_resident=array_diff($contact_acl_befor, $user_acl_anabled);
		
		// полученный массив категорий доступа, которые указал текущий оператор в рамках своих полномочий
		$aclList_local=$this->request->post('aclList');
		
		//собираю массив категорий доступа как набор $acl_resident и $aclList_local
		$list=array();
		if($acl_resident) $list=array_merge($list, $acl_resident);
		if($aclList_local) $list=array_merge($list, $aclList_local);
		
		
	//	echo Debug::vars('953', $contact_acl_befor, $user_acl_anabled, $list, array_flip($list)); exit;
			
		
		
		
		
		
		//$aclList=$this->request->post('aclList');
		//echo Debug::vars('274', $_POST, $id, $aclList); exit;
		$this->checkACL($id, array_flip($list));
		//echo Debug::vars('254',$source, $oldACL , $aclForDel, $aclForAdd, $resultDelAcl, $resultAddAcl); exit;
		$this->redirect('contacts/acl/'.$id);
	}
	
	
	
	public function action_deletecard()
	{
		$id=$this->request->param('id');
		$card = new Keyk($id);
		$card->delCard();
		Session::instance()->set('alert', __('cards.deleted', array(':id_card'=>$id)));
		$this->redirect('contacts/cardlist/' . $card->id_pep);
	}
	
	
	
	/*
	редакция 10.04.2024
	
	добавление карты:
	 "hidden" => string(9) "form_sent"
    "id" => string(5) "14401"
    "id_cardtype" => string(1) "1"
    "idcard" => string(6) "123432"
    "carddatestart" => string(10) "10.04.2024"
    "carddateend" => string(10) "10.04.2025"
    "note" => string(0) ""
    "cardisactive" => string(2) "on"
	
	
	добавление ГРЗ
	 "hidden" => string(9) "form_sent"
    "id" => string(5) "14401"
    "id_cardtype" => string(1) "4"
    "idcard" => string(6) "345526"
    "note" => string(10) "мотор"
    "carddatestart" => string(10) "10.04.2024"
    "carddateend" => string(10) "10.04.2025"
    "cardisactive" => string(2) "on"
	*/
	
	public function action_savecard()
	{
		//echo Debug::vars('348', $_POST ); exit;
		$post=Validation::factory($_POST);
		$arrAlert=array();
		$post->rule('id', 'not_empty')// id сотрудника, которому присваивается номер карты
					->rule('id', 'digit')
					->rule('id_cardtype', 'not_empty')
					->rule('id_cardtype', 'digit')// тип идентификатора. Для RFID это 1.
					->rule('idcard', 'not_empty')//проверяю только наличие номера карты (или RFID). Сам номеро надо будет проверять отдельно
					->rule('idcard', 'max_length', array(':value', constants::RFID_MAX_LENGTH()))
					->rule('idcard', 'min_length', array(':value', constants::RFID_MIN_LENGTH()))
					->rule('carddatestart', 'not_empty')
					->rule('carddatestart', 'date')
					->rule('carddateend', 'not_empty')
					->rule('carddateend', 'date')
					->rule('cardisactive', 'not_empty')
					->rule('cardisactive', 'alpha_numeric')
					
					;
		if($post->check()){
			//определяю тип идентификатора для правильной валидации данных
		    //echo Debug::vars('820 validRfid OK ',Arr::get($post, 'id_cardtype')); exit;
			switch(Arr::get($post, 'id_cardtype')){// обработка карт RFID
				case 1://обработка RFID
					$validRfid=Validation::factory(array('idcard'=>Arr::get($post, 'idcard')));
					$validRfid->rule('idcard', 'not_empty')
								->rule('idcard', 'min_length', array(':value', constants::RFID_MIN_LENGTH))
								->rule('idcard', 'max_length', array(':value', constants::RFID_MAX_LENGTH))
								;
					if(Kohana::$config->load('system')->get('regFormatRfid') == 0)	$validRfid->rule('idcard', 'regex', array(':value', '/^[A-F0-9]+$/'));//проверка, что входной формат HEX
					if(Kohana::$config->load('system')->get('regFormatRfid') == 2)	$validRfid->rule('idcard', 'digit');//проверка, что входной формат - десятичное число
					//echo Debug::vars('843', $validRfid);exit;
					$idcard=Arr::get($post, 'idcard');
					if($validRfid->check()){
						
						//все условия выполнениы, можно сохранять RFID
						
						//выбор вариантов преобразования номера от регкомплекта к формату базы данных
						switch(Kohana::$config->load('system')->get('baseFormatRfid', 0) ) {// формат базы данных
							case 0:	//если формат базы данных 0 (HEX8)
								switch(Kohana::$config->load('system')->get('regFormatRfid')){//варианты преобразования в зависимости от формата регистрационного считывателя
									case 0://формат как у базы данных.
										// ничего делать не надо: форматы регистрационного считывателя и базы данных совпадают.
									break;
									case 2://регкомплект выдает DEC10
										$idcard=Model::Factory('Stat')->decDigitToHEX8($idcard);//привожу формат DEC к HEX8
									break;
									default:
										throw new ExceptionCRM('Неправильные настройки регистрационного считывателя! Должна быть 0 или 2, а настроен '.Kohana::$config->load('system')->get('regFormatRfid'));	
									break;
								}
							break;
							case 1: //формат базы 001A
								switch(Kohana::$config->load('system')->get('regFormatRfid')){ //варианты преобразования в зависимости от формата регистрационного считывателя
								
									case 0://регкомплект выдает HEX8
										// ничего делать не надо: форматы регистрационного считывателя и базы данных совпадают.
									break;
									case 2://регкомплект выдает DEC10. Преобразование надо делать к формату 001A
										switch(Kohana::$config->load('system')->get('regFormatRfid')){
											case 0://формат как у базы данных.
										// ничего делать не надо: форматы регистрационного считывателя и базы данных совпадают.
											break;
											case 2://регкомплект выдает DEC10. его надо привести к формату базы 001A
												$idcard=Model::Factory('Stat')->decDigitTo001A($idcard);//привожу формат HEX8 к 001A
												
											break;
											default:
												throw new ExceptionCRM('Неправильные настройки регистрационного считывателя! Должна быть 0 или 2, а настроен '.Kohana::$config->load('system')->get('regFormatRfid'));	
											break;
										}
										
									break;
									default:
										throw new ExceptionCRM('Неправильные настройки регистрационного считывателя! Должна быть 0 или 2, а настроен '.Kohana::$config->load('system')->get('regFormatRfid'));	
									break;
								}
							break;
						}

						//echo Debug::vars('910', $idcard);exit;
						$key=new Keyk();
						$key->id_card=$idcard;
						$check=$key->check(1);
						
							if(is_null($check)){
								$key->id_card=$idcard;
								$key->timestart=Arr::get($post, 'carddatestart');
								$key->timeend=Arr::get($post, 'carddateend');
								$key->id_pep=Arr::get($post, 'id');
								$key->id_cardtype=Arr::get($post, 'id_cardtype');
								$key->cardisactive=Arr::get($post, 'cardisactive');
								$key->note=Arr::get($post, 'note');
								$key->flag=0;
								$key->rfidmode=Arr::get($post, 'rfidmode',0);
								
								//присвоедние карты RFID
								if($key->addRfid()==0) { ;//сохраняю карту RFID
									$alert=__('contact.addRfidOk', array(':id_card'=>$key->id_card));
								} else {
									$alert=__('contact.saveRfidErr', array(':id_card'=>$key->id_card));
								};
								
								$arrAlert[]=array('actionResult'=>0, 'actionDesc'=>$alert);
							} else {
								//карта выдана сотруднику с id_pep=$check
								
								$anypeople=new Contact($check);
								
								$alert=__('contact.key_occuped', array(':idcard'=>$key->id_card_on_screen, ':id_pep'=>$anypeople->id_pep,':name'=>iconv('CP1251', 'UTF-8',$anypeople->name),':surname'=>iconv('CP1251', 'UTF-8',$anypeople->surname),':patronymic'=>iconv('CP1251', 'UTF-8',$anypeople->patronymic)));
								
								$arrAlert[]=array('actionResult'=>3, 'actionDesc'=>$alert);
								
								Session::instance()->set('arrAlert',$arrAlert);
							
						}
					} else {
						//echo Debug::vars('886 validRfid ERR ', $validRfid->errors()); exit;
						$alert=__('contact.validKeyErr :desc', array(':desc'=>implode(",", $validRfid->errors('upload'))));
						
		
						$arrAlert[]=array('actionResult'=>2, 'actionDesc'=>$alert);
						Session::instance()->set('alert', $arrAlert);
					}
					
				
				break;
				
				case 4://обработка GRZ
				
				    break;
				
				default:
				
				break;
				
			}
			//echo Debug::vars('794 valid OK ',$post); exit;
		} else {
			$alert=__('contact.validKeyErr :desc', array(':desc'=>implode(",", $post->errors('upload'))));
			$arrAlert[]=array('actionResult'=>2, 'actionDesc'=>$alert);
			//echo Debug::vars('797 valid Err ',$arrAlert); exit;
		}
						
		
		//echo Debug::vars('924'); exit;			
		Session::instance()->set('arrAlert',$arrAlert);	
		
		$this->redirect('contacts/cardlist/' . Arr::get($post, 'id'));
	}
	
	
	

	
}
