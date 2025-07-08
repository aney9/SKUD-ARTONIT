<?php defined('SYSPATH') OR die('No direct access allowed.');

class Acls
{
    public static function getListAllowedIdOrg($id_org =null)
	{
	    if(is_null($id_org)){
	        $sql='SELECT  id_org FROM ORGANIZATION_GETCHILD(1, '.Arr::get(Auth::instance()->get_user(), 'ID_ORGCTRL').')';
	    } else {
	        
	        $sql='SELECT  id_org FROM ORGANIZATION_GETCHILD(1, '.$id_org.')';
	    }
	    
	    $query = DB::query(Database::SELECT, $sql)
	    ->execute(Database::instance('fb'))
	    ->as_array();
	    foreach($query as $key=>$value)
	    {
	        
	       $res[]=Arr::get($value, 'ID_ORG'); 
	        
	    }
	    return $res;
		
	}

	public static function getList()
	{
		$query = DB::query(Database::SELECT,
			'SELECT * FROM roles WHERE id > 2')
			->execute();
		return $query->as_array();
	}

	public static function canAddCompany($user)
	{
		$query = DB::query(Database::SELECT, 
			'SELECT * FROM usersgroups WHERE id_user = ' . $user . ' AND "O_ADD" = 1')
			->execute(Database::instance('fb'));
		
		return $query->count() > 0;
	}
	
	public static function canAddContact($user)
	{
		$query = DB::query(Database::SELECT, 
			'SELECT * FROM usersgroups WHERE id_user = ' . $user . ' AND "P_ADD" = 1')
			->execute(Database::instance('fb'));
		
		return $query->count() > 0;
	}
	
	public static function getGroupId($user)
	{
		$query = DB::query(Database::SELECT,
			'SELECT FIRST 1 id_group FROM usersgroups WHERE id_user = ' . $user . ' AND "O_ADD" = 1')
			->execute(Database::instance('fb'))
			->current();

		return $query['ID_GROUP'];
	}
	
	
	
	
	
}
