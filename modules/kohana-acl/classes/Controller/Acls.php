<?php defined('SYSPATH') or die('No direct script access.');
/*
30.06.2024
Контроллер для организации ролей, ресурсов и прав

*/

class Controller_Acls extends Controller_Template {

   public $template = 'template';
	
	public function before()
	{

		parent::before();

		$acl=new Acl(true);
		$resource='acl';
		if(!$acl->is_allowed($this->user->role,$resource, 'read')){
			
			
			$arrAlert[]=array('actionResult'=>3, 'actionDesc'=>__('No_ACL'));
			Session::instance()->set('arrAlert',$arrAlert);
			$this->redirect('/');
			
		}
	
	}
	
	
	
	/** Формирует страницу для редактирования ролей, ресурсов и связи между ними.
	 * 
	 * 
	 */
	public function action_index()
	{	
		//echo Debug::vars('35', $_POST);exit;
		$content = View::factory('acl/list')
			;
		
		$this->template->content = $content;
		//echo View::factory('profiler/stats');
		
	}

    
	public function action_editRole()
	{
	    echo Debug::vars('46', $_POST); exit;
	    
	    
	}

	
	public function action_addItem()
	{
	    //echo Debug::vars('54', $_POST); exit;
	    $todo=Arr::get($_POST, 'todo');
	    $acl=Model::factory('Aclm');
	    switch($todo){
	        case('addUserRole'):
	            $acl->addRoleUser(Arr::get($_POST, 'user_id'), Arr::get($_POST, 'role_id'));
	            break;
	            
	            
	        case('addRule'):
	                $acl->addRule(Arr::get($_POST, 'type'), Arr::get($_POST, 'role_id'), Arr::get($_POST, 'resource_id'), Arr::get($_POST, 'privelege'));
	            break;
	            
	            
	        
	        
	    }
	    $this->redirect('acls');
	    
	}

	
	public function action_editItem()
	{
	   //echo Debug::vars('78', $_POST); exit;
	    $todo=Arr::get($_POST, 'todo');
	    $acl=Model::factory('Aclm');
	    switch($todo){
	         case('addRole')://добавить новую роль
	            $acl->addRole(Arr::get($_POST, 'name'), Arr::get($_POST, 'parent_id'), Arr::get($_POST, 'description'));
	        break;
			
			case('updateRole'):// редактирование роли
	            
	            $acl->updateRole(Arr::get($_POST, 'id'), Arr::get($_POST, 'name'), Arr::get($_POST, 'parent_id'), Arr::get($_POST, 'description'));
	        break;
	            
	            
	        case('deleteRole')://удаление роли
	            $acl->deleteRole(Arr::get($_POST, 'id'));
	        break;
	            
	            
	        case('addResource')://добавить новую роль

	            $acl->addResource(Arr::get($_POST, 'id'),Arr::get($_POST, 'parent_id'),Arr::get($_POST, 'name'),  Arr::get($_POST, 'description'));
	        break;
			
			case('updateResource'):// редактирование роли
	           
	            $acl->updateResource(Arr::get($_POST, 'id'), Arr::get($_POST, 'parent_id'), Arr::get($_POST, 'name'), Arr::get($_POST, 'description'));
	        break;
	            
	            
	        case('deleteResource')://удаление роли
	            $acl->deleteResource(Arr::get($_POST, 'id'));
	        break;
	            
	            
	       
	            
	            
	        case('updateRule')://обновить правило
	            $acl->updateRule(Arr::get($_POST, 'id'), Arr::get($_POST, 'type'), Arr::get($_POST, 'role_id'), Arr::get($_POST, 'resource_id'), Arr::get($_POST, 'privelege'));
	        break;
	            
	            
	        case('deleteRule')://удалить правило
			
	            $acl->deleteRule(Arr::get($_POST, 'id'));
	        break;
	            
	            
	        case('deleteUserRole')://удалить юзер - роль
	            $acl->deleteUserRole(Arr::get($_POST, 'user_id'));
	        break;
	            
	            
	        
	        
	    }
	    $this->redirect('acls');
	    
	}

	
}
