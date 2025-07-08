<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Companies extends Controller_Template
{

	/**
	5.12.2023
	при переходе на search данные из POST записываются в 
	Ожидаемый параметр q - это номер организации, по которой надо вывести информацию.
	этот параметр сохраняется в сессию (для последующего вывода на формах
	и передается в index.
	*/
	public function action_search()
	{
		$pattern = Arr::get($_POST, 'q', null);
		if ($pattern) {
			$this->session->set('search_company', $pattern);
		} else {
			$pattern = $this->session->get('search_company', '');
		}
		$this->action_index($pattern);
	}
	
	/** 7.03.2025 ревью кода с целью убрать лишнее
	*
	*/
	public function action_index($filter = null)
	{
		//смотрю указание на родительскую организацию для вывода списка организаций.
		$parent_org=$this->request->query('parent');
		//echo Debug::vars('43', $parent_org);//exit;
		
		$user=new User;//информация о текущем авторизованном пользователе
				
		if(is_null($parent_org)) $parent_org=$user->id_orgctrl;// если parent_org не указан, то беру id_orgctrl подчиняемой организации
		
		
		$companies = Model::factory('Company');
		//проверка, что полученный через query действительно разрешен для текущего пользователи. Это защита от попыток получить информацию о группах путем формирования
		//GET-запроса
		
		$accellListOrg = $companies->getOrgListForOnce($user->id_orgctrl );
		if(! Arr::get($accellListOrg, $parent_org)) $this->redirect('companies');
		//echo Debug::vars('56', $accellListOrg);exit;
		// получение списка организаций, удовлетворяющего фильтру		
		
		$list = $companies->getListAdmin(
				//$user->id_pep,
				$parent_org,
				$filter
				);
		
		
		//проверка наличия алертов, их извлечение их сессии.
		$fl = $this->session->get('alert');
		$this->session->delete('alert');
		
	
		// готовлю список организаций в виде иерархического дерева
		//$org_tree = Model::Factory('Company')->getOrgList();// я получил список организаций.
		$org_tree = $companies->getOrgListForOnce($user->id_orgctrl);// я получил список организаций разрешенных текущему пользователю.
		//echo Debug::vars('84', $org_tree);exit;
		
		$org_tree=Model::Factory('treeorg')->make_tree($org_tree, 1);//формирую иерархический список
		//$org_tree=Model::Factory('treeorg')->make_tree($check2, 2);//формирую иерархический список
		
		
		$this->template->content = View::factory('companies/list')
			->bind('companies', $list)
			->bind('alert', $fl)
			->bind('filter', $filter)
			->bind('org_tree', $org_tree)
			;
		
			//echo View::factory('profiler/stats');
	}
	
	
	/*
	18.12.2023
	Вывод списк сотрудников указанной организации
	@input id - id_org организации
	*/
	public function action_people()
	{
		$id=$this->request->param('id');
		$company = Model::factory('company')->getCompany($id);
		if (!$company) $this->redirect('companies');// если организации нет, то переход на список компаний.
		$isAdmin = Auth::instance()->logged_in('admin');
		
		$contacts = Model::factory('Contact');
		$contacts->peopleIsActive=1;;
	
		$q = $contacts->getCountByOrg($id);
		$list = $contacts->getListByOrg(Arr::get($_GET, 'page', 1), $this->listsize, $id);
		$fl = null;
		
		$arrAlert = $this->session->get('arrAlert'); //извлечь алерт из сессии
		$this->session->delete('arrAlert');//очистить алерт в сессии



		$showphone = $this->session->get('showphone', 0);
		$filter = null;
		$hidesearch = true;
		
		$this->template->content = View::factory('contacts/list')
			->bind('people', $list)
			->bind('alert', $fl)
			->bind('arrAlert', $arrAlert)
			->bind('company', $company)
			->bind('showphone', $showphone)
			->bind('hidesearch', $hidesearch)
			->bind('filter', $filter)
			;
	}
	
	/*
	10.08.2023
	Получить и вывести на экран Список категорий доступа, присвоенных организации
	*/
	public function action_acl()
	{
		$id=$this->request->param('id');
		$company = Model::factory('company')->getCompany($id);//информация об организации
		$company_acl = Model::factory('company')->company_acl($id);//список категорий доступа, уже выданных организации
		if (!$company) $this->redirect('companies');
		$isAdmin = Auth::instance()->logged_in('admin');
		$aclsForCurrentUser = Model::factory('company')->getListAccessNameForCurrentUser(Arr::get(Auth::instance()->get_user(), 'ID_PEP'));//получить список всех категорий доступа
		
		
		$fl = null;
		$showphone = $this->session->get('showphone', 0);
		$filter = null;
		$hidesearch = true;
		
		$arrAlert = $this->session->get('arrAlert'); //извлечь алерт из сессии
		$this->session->delete('arrAlert');//очистить алерт в сессии
		$this->template->content = View::factory('companies/acl')
			->bind('alert', $fl)
			->bind('company', $company)
			->bind('company_acl', $company_acl)
			->bind('aclsForCurrentUser', $aclsForCurrentUser)
			->bind('arrAlert', $arrAlert)
			;
	}
	
		/*
	Обновление списка категорий доступа, выданных организации
	входные параметры:
	id - id_org организации, у которого меняют набор категорий доступа
	"aclList" => array(2) ( - новый набор категорий доступа
        213 => string(1) "1"
        1 => string(1) "1"
	
	*/
	public function action_saveACL()
	{
		
		$id=$this->request->post('id');
		$aclList=$this->request->post('aclList');
		
		if(!$aclList)
		{
			//если массив нового набора категорий доступа пуст, то очищаю таблицу SS_ACCESSORG для этой организации
			$resultDelAcl=Model::factory('company')->clear_company_acl($id);//удаляю все из таблицы ss_accessuser
			
		} else {
			//если массив нового набора категорий доступа НЕ пуст, то начинаю обработку этого массива
			
			foreach($aclList as $key=>$value)
			{
				$source[]=$key;//это массив вновь созданного набора категорий доступа в виде, удобном для последующего сравнения
			}
		
			//смотрим какие категории доступа уже есть у организации
			$contact_acl = Model::factory('company')->company_acl($id);//список категорий доступа, уже имеющихся у организации
			if(!$contact_acl)
			{
				//если категорий доступа ранее не было выдано, то надо их просто добавить
				//echo Debug::vars('284', $aclList); exit;
				foreach($aclList as $key=>$value)
					{
						//echo Debug::vars('288',$value, $resultAddAcl, $id, $value); exit;
						$resultAddAcl=Model::factory('company')->add_company_acl($id, $key );
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
							$resultDelAcl=Model::factory('company')->del_company_acl($id, $value );
						}
					}
					//поиск категорий доступа, которые необходимо добавить. Это элементы, которые есть в новом наборе, но которых нет в старом наборе
					$aclForAdd=array_diff($source, $oldACL);
					$resultAddAcl=-1;
					
					foreach($aclForAdd as $key=>$value)
					{
						//echo Debug::vars('288',$value, $resultAddAcl, $id, $value); exit;
						$resultAddAcl=Model::factory('company')->add_company_acl($id, $value );
					}
			}
		}		
		//echo Debug::vars('254',$source, $oldACL , $aclForDel, $aclForAdd, $resultDelAcl, $resultAddAcl); exit;
		$this->redirect('companies/acl/'.$id);
	}
	
	/*
	14.04.2024 большая доработка 14.04.2024
	Результат выполнения берется из модели.
	ошибки:
	0 - все успешно,
	2 - ошибка валидации данных, должно быть описание.
	3 - ошибка при работе с базой данных
	*/
	
	public function action_save()//добавление организации
	{
		//echo Debug::vars('235', $_POST); exit;
		Log::instance()->add(Log::DEBUG, '239 '. Debug::vars($_POST)); //exit;
		$id		= Arr::get($_POST, 'id');
		$name	= Arr::get($_POST, 'name');
		$code	= Arr::get($_POST, 'code');
		$access	= Arr::get($_POST, 'access');
		$parent = Arr::get($_POST, 'parent');
		$group	= Arr::get($_POST, 'group');

		//$company = Model::factory('company');
		$arrAlert=array();
		

		if ($id == 0) {// если id==0 - это значит, что организации нет, надо регистрировать.
			$company = new Company();
			$company->name=$name;
			$company->id_parent= ($parent == '')? 1 : $parent;
			$result=$company->addOrg();
			switch($result) {
				case 0:
					$parentOrg=new Company($company->id_parent);
					$alert=__('companies.addOk', array('name'=>$company->name, 'parentName'=>iconv('CP1251','UTF-8',  $parentOrg->name)));
					
				break;
				case 2://ошибка валидации данных
					
					Log::instance()->add(Log::DEBUG,$company->errors);
					$alert=__('companies.addValidationErr', array('name'=>$company->errors));
					
				break;
				case 3://ошибка вставки в базу данных
				
					Log::instance()->add(Log::DEBUG,$company->errors);
					$alert=__('companies.addDbErr', array('name'=>$company->name));
				
				break;
				default:
					$alert=__('unknownErr');
				break;
				
			}
			
		
		} else { // если id!=0 - это значит, что выполняется обновлене данных организации
			$company = new Company($id);
			$company->id_org=$id;
			$company->name=$name;
			//$company->divcode=$code;
			$company->id_parent= ($parent == '')? 1 : $parent;
			$result=$company->updateOrg();
			switch($result) {
				case 0:
					$alert=__('companies.updateOk', array('name'=>$company->name));
					
				break;
				case 2://ошибка валидации данных
					
					Log::instance()->add(Log::DEBUG,$company->errors);
					$alert=__('companies.updateValidationErr', array('name'=>$company->errors));
					
				break;
				case 3://ошибка вставки в базу данных
					Log::instance()->add(Log::DEBUG,$company->errors);
					$alert=__('companies.updateDbErr', array('name'=>$company->errors));
				
				break;
				default:
					$alert=__('unknownErr');
				break;
				
				}
		}
			$arrAlert[]=array('actionResult'=>$result, 'actionDesc'=>$alert);
			Session::instance()->set('arrAlert',$arrAlert);
		$this->redirect('companies/edit/' . $id);
	}
	
	
	/*
	8.01.2024 удаление организации.
	
	*/
	public function action_delete()
	{
		$id_org=$this->request->param('id');
		$id_parent=$this->request->param('parent');
		
		$alert='';
		$delCompany = new Company($id_org);

		//получить список дочерних организаций.
		$childrenList=$delCompany->getChildIdOrg();

		if($childrenList){
			//каждой организации меняю родителя на новый

			foreach ($childrenList as $key=>$value){

				$updCompany=new Company(Arr::get($value, 'ID_ORG'));// udpCompany - обновляемая организация

				$updCompany->id_parent = $delCompany->id_parent;// указываю новое значение родительской организации как родителя удаляемой
				$alert.=' '.$updCompany->id_org;
				
				if($updCompany->setIdParentOrg() == 0) { //выполняют обновление 
					$alert.=__('remove id_org=:id_org<br>', array(':id_org'=>$updCompany->id_org));
				} else {
					
					$alert.=__('NOT remove id_org=:id_org<br>', array(':id_org'=>$updCompany->id_org));
				};
				
			}
			
		}
		//для всех контактов удаляемой организации меняю организацию на указанную (в данном случае - на родителя удаляемой организации)
		//!!! не обязательно! пиплы получают Активность =0 и переводятся выше триггерами базы данных.
		//if($delCompany->setNewOrgForPeople($delCompany->id_parent) == 0) $alert.=__('remove_pep_to_new_parent',  array(':id_org'=>$delCompany->id_parent));;
		$delCompany->delOrgId();
		$this->session->set('alert', $alert);
		//echo Debug::vars('293', $alert); exit;
		$this->redirect('companies/?parent='.$id_parent);
	}
	
	
	/** 1.12.2024 вывод информации для редактирования организации
	*
	*/
	public function action_edit()
	{
		//Log::instance()->add(Log::DEBUG, '324 '. Debug::vars($_POST), $this->request->param('id')); exit;
		//echo Debug::vars('358', Debug::vars($_GET), Debug::vars($_POST));//exit;
		//echo Debug::vars('359', $this->request->param('id'));exit;
		$id=$this->request->param('id');
		$var=Validation::factory(array('test'=>$id));
		
		$var->rule('test','not_empty') 
					->rule('test','digit')
						;
		if($var->check())
		{
			$company = Model::factory('company');
			$data = $company->getCompany($id);
			
			if ($id != "0" && !$data) $this->redirect('companies');
			$list = $company->getNames(null);
			
			$acls = $company->getListAccessName();//получить список всех категорий доступа
			//$org_tree = Model::Factory('Company')->getOrgList();// получить список организаций.
			$org_tree = Model::Factory('Company')->getOrgListForOnce(Arr::get(Auth::instance()->get_user(), 'ID_ORGCTRL'));
			
			$fl = $this->session->get('alert');
			$this->session->delete('alert');
			
			$arrAlert = $this->session->get('arrAlert'); //извлечь алерт из сессии
			$this->session->delete('arrAlert');//очистить алерт в сессии

			
			$this->template->content = View::factory('companies/edit')
				->bind('company', $data)
				->bind('parents', $list)
				->bind('org_tree', $org_tree)
				//->bind('groups', $grps)
				->bind('alert', $fl)
				->bind('arrAlert', $arrAlert)
				
				->bind('acl', $acls);
		} else {
			$this->redirect('companies');
		}
			
	}
	
	public function action_view()
	{
		$id=$this->request->param('id');
		$company = Model::factory('company');

		$data = $company->getCompany($id);
		if (!$data) $this->redirect('companies');
		$list = $company->getNames(null);
		$acls = Accessname::getList();
		
		$this->template->content = View::factory('companies/view')
			->bind('company', $data)
			->bind('parents', $list)
			->bind('acl', $acls);
	}
	

	
	public function addpeople()
	{
			$id=$this->request->param('id');
			$this->redirect('contacts/edit/0');
		
	}
	
}
