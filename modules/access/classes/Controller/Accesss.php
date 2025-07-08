<?php defined('SYSPATH') or die('No direct script access.');
/*
26.02.2025 
Контроллер предназначен для управления правами доступа у контактов.
27.02.2025 Перенесен в модули
вывод каких-либо экранных форма пока не предполагается.
Основные задачи:
1. заполение таблицы ss_accessuser по данным из ss_accessorg

*/

class Controller_Accesss extends Controller {

   
	public function action_index()
	{	
		$t1=microtime(1);
		//echo Debug::vars('14', $_POST);//exit;
		$makeForForChild=false;
		$id_org=Arr::get($_POST, 'id_org');
		$todo=Arr::get($_POST, 'todo');
		if(Arr::get($_POST, 'makeForForChild') !== null) $makeForForChild=true;
		
		//echo Debug::vars('24', $makeForForChild);exit;
		//КД - категория доступа.
		//КДК - набор категория доступа контакта.
		//КДО - набор категория доступа организации.
		//если стот addForChild, то выбрать все дочерние организации.
		//если не стоит addForChild, то выбрать только текущую организацию
		//собрать пуль организаций вместе.
		
		//выбрать все КДО для указанной организации
		$aclModel=Model::factory('access');
		$aclListOrg= $aclModel->getCompanyAcl($id_org);//список категорий доступа, уже выданных организации;//передаю новый набор категорий доступа
		
		//echo Debug::vars('26',$id_org,  $aclListOrg);//exit;
	$contacts = Model::factory('Contact');
		if($makeForForChild)
		{
			$list = $contacts->getListUser($id_org, 0, 10000, '*');//список контактов для выбранной организации и ее дочек
			
		} else {
			
			//$list = $contacts->getListUser($id_org, 0, 10000, 5);//список контактов для выбранной организации
			$list = $contacts->getListByOrg(0, 10000, $id_org);//список контактов только для выбранной организации
		}
		
			//echo Debug::vars('35', $list);exit;	
		
		//выбрать все id_pep для указанной организации.
		
		
		//$list = $contacts->getListByOrg(0, 10000, $id_org);//список контактов для выбранной организации
		
		
		

		//для каждого id_pep:
			//получить набор КД из ss_accessuser.
		//если todo == add, то для каждого id_pep добавить новую access INSERT INTO SS_ACCESSUSER (ID_DB,ID_PEP,ID_ACCESSNAME,USERNAME) VALUES (1,14401,4,'SYSDBA');
			//если запись уже есть, то будет ошибка без последствий.
			//если записи нет, то она будет добавлена, что вызовет формирование таблицы cardidx.	
		$countContactForAdd=0;
		$countContactForDel=0;
		//echo Debug::vars('65', $todo);//exit;
		switch($todo)
		{
			case 'add': //добавляю категории доступа
		
				foreach ($list as $key=>$row)
				{
					
					foreach($aclListOrg as $key2=>$row2)
					{
						//echo Debug::vars('44', $this->request->referrer()); exit;
						$id_pep=Arr::get($row, 'ID_PEP');// id_pep обрабатываемого контакта
						$aclListСontact=$aclModel->getContactAcl($id_pep);//получил лист КД контакта.
						//echo Debug::vars('55',$id_pep, $aclListСontact, $aclListOrg);//exit;
						//echo Debug::vars('56 у контакта надо удалить организации ', array_diff($aclListСontact, $aclListOrg));//exit;
						
						//echo Debug::vars('57 Контакту надо добавить организации ', array_diff($aclListOrg, $aclListСontact));exit;
						$accessForAdd=array_diff($aclListOrg, $aclListСontact);
						//echo Debug::vars('77', $accessForAdd);exit;
						if(count($accessForAdd)>0)
						{/* 
							echo Debug::vars('62 Пиплу надо добавить КД');
							echo Debug::vars('63', count($accessForAdd));
							echo Debug::vars('64', $accessForAdd); */
							foreach($accessForAdd as $key=>$value)
							{
								$aclModel->addAccessForContact($value, $id_pep);
								$countContactForAdd++;
								
							}
							//echo Debug::vars('70 вставку закончил');exit;
						} else 
						{
							//echo Debug::vars('66 Пиплу добавлять ничего не надо');exit;
							
							
						};
						//echo Debug::vars('44', Arr::get($row2, 'ID_ACCESSNAME'));exit;
						//получаю список КД, выданных контакту
						///$accesslist=$contact->contact_acl($id_pep);
						///echo Debug::vars('53', $accesslist, $aclListOrg);exit;
						Log::instance()->add(Log::DEBUG,'46 для id_pep='.Arr::get($row, 'ID_PEP').' добавляется категория доступа id_accessname='. Arr::get($row2, 'ID_ACCESSNAME'));
						
						//echo Debug::vars('56', $result);exit;
						//Log::instance()->add(Log::DEBUG, '47 Ответ: '.Debug::vars(Model::factory('Contact')->add_contact_acl(Arr::get($row, 'ID_PEP'), Arr::get($row2, 'ID_ACCESSNAME'))));
					}
					
					
					
				}
			break;
			
			case 'del': //запрет (удаление) категорий доступа
			//echo Debug::vars('118 del', $aclListOrg);exit;
				foreach ($list as $key=>$row)//для каждого контакта
				{
					
						//echo Debug::vars('44', $this->request->referrer()); exit;
						$id_pep=Arr::get($row, 'ID_PEP');// id_pep обрабатываемого контакта
						$aclListСontact=$aclModel->getContactAcl($id_pep);//получил лист КД контакта.
						
						$accessForDel=array_diff($aclListСontact, $aclListOrg);//получил список КД для удалдения
						//echo Debug::vars('77', $accessForDel);exit;
						if(count($accessForDel)>0)
						{/* 
							echo Debug::vars('62 Пиплу надо добавить КД');
							echo Debug::vars('63', count($accessForAdd));
							echo Debug::vars('64', $accessForAdd); */
							foreach($accessForDel as $key=>$value)
							{
								$aclModel->delAccessForContact($value, $id_pep);
								$countContactForDel++;
							Log::instance()->add(Log::DEBUG,'46 для id_pep='.Arr::get($row, 'ID_PEP').' удляется категория доступа id_accessname='. $value);
							
							}
							//echo Debug::vars('70 вставку закончил');exit;
						} 
						
						
					
				}
			
			break;
			
				default:
				
				
				break;
			
			
		}
			//echo Debug::vars('42');exit;
				
				
				//если todo == del, то для каждого id_pep выполнить следующее:
				//выбрать из таблицы ss_accessuser все id_access этого пользователя.
				//найти КД, которые есть у контакта, но нет у организации. удалить эти элементы из таблицы ss_accessuser
			
		
		
		//$this->template->content = $content;
		//echo View::factory('profiler/stats');
		$alert=__('Обработано :countContact контатов.<br>Добавлены категории доступа для countContactForAdd контактов.<br>Удалены категории доступа у countContactForDel контактов.', array(':countContact' => count($list),'countContactForAdd'=>$countContactForAdd,'countContactForDel'=>$countContactForDel));
			$arrAlert[]=array('actionResult'=>1, 'actionDesc'=>$alert);
			Session::instance()->set('arrAlert',$arrAlert);
		$this->redirect($this->request->referrer());
		return;
	}

	
}
