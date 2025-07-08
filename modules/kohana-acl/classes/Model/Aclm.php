<?php defined('SYSPATH') OR die('No direct access allowed.');

/** Модель для работы с базой данных aclcfg, где хранится конфигурация ACL
 * 
 * 
 * @author Андрей
 *
 */
class Model_Aclm extends Model
{
	/** Получить список ролей
	 * 
	 * @return NULL|array|string|boolean|unknown
	 */
	public function getRoles () // 
	{
	  
	$sql='select r.id, r.name, r.parent_id, r.description from roles r';
		
	//echo Debug::vars('28', $query); exit;
			try{
			    $query = DB::query(Database::SELECT, $sql)
			    ->execute(Database::instance('aclcfg'))
			    ->as_array();
			    return $query;
			} catch (Exception $e) { 
			    throw new Exception('Не могу получить список ролей из базы данных alccfg');
			}

	}
	
	
	/** Получить список ресурсов
	 * 
	 * @return string
	 */
	public function getResources()
	{
	    $sql='select r.id, r.parent_id, r.name, r.description from resources r';
	    
	    //echo Debug::vars('28', $query); exit;
	    try{
	        $query = DB::query(Database::SELECT, $sql)
	        ->execute(Database::instance('aclcfg'))
	        ->as_array();
	        return $query;
	    } catch (Exception $e) {
	        throw new Exception('Не могу получить список ресурсов из базы данных alccfg', 48);
	    }
	    
	}

	
	/** Получить список правил
	 * 
	 * @return string
	 */
	public function getRules()
	{
	    $sql='select r.id, r.type, r.role_id, r.resource_id, r.privilege from rules r';
	    
	    //echo Debug::vars('28', $query); exit;
	    try{
	        $query = DB::query(Database::SELECT, $sql)
	        ->execute(Database::instance('aclcfg'))
	        ->as_array();
	        return $query;
	    } catch (Exception $e) {
	        throw new Exception('Не могу получить список правил из базы данных alccfg', 69);
	    }
	    
	}

	
	/** Получить список пользователей
	 * 
	 * @return string
	 */
	public function getUsers()
	{
	    $sql='select user_id, role_id from roles_users';
	    
	    //echo Debug::vars('28', $query); exit;
	    try{
	        $query = DB::query(Database::SELECT, $sql)
	        ->execute(Database::instance('aclcfg'))
	        ->as_array();
	        return $query;
	    } catch (Exception $e) {
	        throw new Exception('Не могу получить список пользователей из базы данных alccfg', 90);
	    }
	    
	}
	
	
	/** 1.07.2024 Обновление информации по роли
	 * 
	 */

	public function updateRole($user_id, $name, $parent_id, $description)
	{
	   
	    $query = DB::update('roles')->set(array('name'=>$name, 'parent_id'=>$parent_id, 'description'=>$description))->where('id', '=', $user_id);
	    $result = $query->execute('aclcfg');
	   
	    
	    return $result;
	    
	}
	
	
	/** 1.07.2024 Добавление новой роли
	 * 
	 */

	public function addRole($name, $parent_id, $description)
	{
	   
	    //$query = DB::update('roles')->set(array('name'=>$name, 'parent_id'=>$parent_id, 'description'=>$description))->where('id', '=', $user_id);
	    
	    $query = DB::insert('roles', array('name', 'parent_id', 'description'))->values(array($name, $parent_id, $description));
	    $result = $query->execute('aclcfg');
	   
	    
	    return $result;
	    
	}
	
	
	
	/** 1.07.2024 Удаление роли
	 * 
	 */

	public function deleteRole($user_id)
	{
	   
	    $query = DB::delete('roles')->where('id', '=', $user_id);
	    $result = $query->execute('aclcfg');
	   
	    
	    return $result;
	    
	}
	
	
	
	/** 1.07.2024 updateRule правила
	 * 'id', 'type', 'role_id', 'resource_id', 'privelege'
	 */

	public function updateRule($id, $type, $role_id, $resource_id, $privelege)
	{
		//echo Debug::vars('154', $id, $type, $role_id, $resource_id, $privelege);exit;
	    $query = DB::update('rules')->set(array('type'=>$type, 'role_id'=>$role_id, 'resource_id'=>$resource_id , 'privilege'=>$privelege ))->where('id', '=', $id);
	    $result = $query->execute('aclcfg');
	   
	    
	    return $result;
	    
	}
	
	
	/** 1.07.2024 Добавление нового правила
	 *
	 */
	
	public function addRule($type, $role_id, $resource_id, $privilege)
	{
	    $query = DB::insert('rules', array('type', 'role_id', 'resource_id', 'privilege'))->values(array($type, $role_id, $resource_id, $privilege));
	    $result = $query->execute('aclcfg');
	    
	    
	    return $result;
	    
	}
	
	
	/** 1.07.2024 Удаление правил
	 *
	 */
	
	public function deleteRule($id)
	{
	    //echo Debug::vars('154', $id);exit;
	    $query = DB::delete('rules')->where('id', '=', $id);
	    $result = $query->execute('aclcfg');
	    
	    
	    return $result;
	    
	}
	
	/** 7.07.2024 updateResource Обновить ресурс
	 * 
	 */

	public function updateResource($id, $parent_id, $name, $description)
	{
		//echo Debug::vars('154', $id, $parent_id, $name, $description);exit;
	    $query = DB::update('Resources')->set(array('parent_id'=>$parent_id, 'name'=>$name, 'description'=>$description))->where('id', '=', $id);
	    $result = $query->execute('aclcfg');
	   
	    
	    return $result;
	    
	}
	
	
	/** 1.07.2024 Добавление нового ресурса
	 *
	 */
	
	public function addResource($id, $parent_id, $name, $description)
	{
	    //$query = DB::insert('Resources', array('id', 'parent_id', 'name', 'description'))->values(array($id, $parent_id, $name, $description));
	    $query = DB::insert('Resources', array('parent_id', 'name', 'description'))->values(array($parent_id, $name, $description));
	    $result = $query->execute('aclcfg');
	    
	    
	    return $result;
	    
	}
	
	
	/** 1.07.2024 Удаление ресурса
	 * Для каскадного удаления в базе данных sqlite создан триггер на удаление
	 */
	
	public function deleteResource($id)
	{
	   
	  $query = DB::delete('Resources')->where('id', '=', $id);
	    $result = $query->execute('aclcfg');
	    
	    
	    return $result;
	    
	}
	
	
	
	/** 1.07.2024 Добавление нового пользователя - правила
	 *
	 */
	
	public function addRoleUser($user_id, $role_id)
	{
	    
	    //$query = DB::update('roles')->set(array('name'=>$name, 'parent_id'=>$parent_id, 'description'=>$description))->where('id', '=', $user_id);
	    
	    $query = DB::insert('roles_users', array('user_id', 'role_id'))->values(array($user_id, $role_id));
	    $result = $query->execute('aclcfg');
	    
	    
	    return $result;
	    
	}
	
	
	/** 1.07.2024 Удаление пользователя - роль
	 *
	 */
	
	public function deleteUserRole($user_id)
	{
	    
	    $query = DB::delete('roles_users')->where('user_id', '=', $user_id);
	    $result = $query->execute('aclcfg');
	    
	    
	    return $result;
	    
	}
	
	
	
	
}
	

