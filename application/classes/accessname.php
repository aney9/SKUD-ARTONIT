<?php defined('SYSPATH') OR die('No direct access allowed.');

class AccessName
{
	public static function getList()
	{
		$query = DB::query(Database::SELECT,
			'SELECT * FROM accessname order by NAME')
			->execute(Database::instance('fb'))
			->as_array();
			
			
			foreach($query as $key=>$value)
			{
				$result[Arr::get($value, 'ID_ACCESSNAME')]['ID_ACCESSNAME']=Arr::get($value, 'ID_ACCESSNAME');
				$result[Arr::get($value, 'ID_ACCESSNAME')]['NAME']=Arr::get($value, 'NAME');
								
			}
		return $result;
	}
	
	public static function getListId()//получить только ID категорий доступа
	{
		$result=array();
		$query = DB::query(Database::SELECT,
			'SELECT accessname.ID_ACCESSNAME FROM accessname order by NAME')
			->execute(Database::instance('fb'));
			
			foreach($query as $key=>$value)
			{
				$result[]=Arr::get($value, 'ID_ACCESSNAME');
				
			}
		return $result;
	}
	
	
}
