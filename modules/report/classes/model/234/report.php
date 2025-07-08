<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
	отчет 234.
	Модель для работы с конфигурацией как с единым целым
*/

class Model_234_report extends Model
{
	
	private $selectYear;
	private $selectMonth;

	public function getReport($post, $user){// Статистика
		
			//echo Debug::vars('12', $post, $user);exit;
			$report=new Report();
			$report->org=Kohana::$config->load('main')->get('orgname');
			$report->titleReport='Количество зарегистрированных сотрудников за '.Arr::get($post, 'monceList');
			$report->fileName='crm_report_'.Arr::get($post, 'howManyMonce').'_month';
			
			// беру ФИО оператора
			$pep=new Contact($user->id_pep);
			
			$report->fromUser  = $pep->surname.' '.Text::limit_chars($pep->name, 1).'. '.Text::limit_chars($pep->patronymic, 1).'.';
			
			
			//беру название департамента оператора
			$org= new Company($user->id_orgctrl );
			$report->depatment  =  $org->name;
			
			//расчитываю диапазон дат на основании строки Год-номер месяца 2024-10
			//валидация данных.

			$var1=Arr::get($post, 'monceList');
			
			if($this->chekSelectDate($var1)){
			
				$month=Arr::get($post, 'howManyMonce');
				
				$ttime=time()-60*60*24*30*$month;
				$timeFrom=date('Y-m-d', mktime(0, 0, 0, date('m', $ttime), 1, date('Y', $ttime)));

						
				$sql='SELECT EXTRACT(year from p.time_stamp) as yearFrom, EXTRACT(month from p.time_stamp) as montFrom, o.name, count(*) FROM people p
				join organization_getchild(1, '.Arr::get(Auth::instance()->get_user(), 'ID_ORGCTRL').') og on og.id_org=p.id_org
				join organization o on og.id_org=o.id_org
				where p.time_stamp>\''.date('Y-m-01', strtotime($var1)).'\'
				and p.time_stamp<\''.date('Y-m-01', strtotime("+1 month", strtotime($var1))).'\'
					GROUP BY 1, 2, 3
					order by 1, 2, 3';
				
				
				
				//echo Debug::vars('21', $sql);exit;
				$query = DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'))
				->as_array();
				//заменяю номер месяца на его название
				$monthes = array('NullMonth', 'Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь', 'Декабрь');
				foreach ($query as $key=>$value)
				{
					$query[$key]['MONTFROM']=Arr::get($monthes, $value['MONTFROM']).' ('.$value['MONTFROM'].')';
					$query[$key]['NAME']=iconv('CP1251', 'UTF-8', Arr::get($value,'NAME'));
					
				}
			} else {

				$query=array();
			}
			$report->titleColumn=array('Год', 'Месяц (номер)', 'Организация (отдел)','Количество зарегистрированных сотрудников' );
			$report->rowData=$query;
			
			return $report;
	}
	
	private function chekSelectDate($selectDate){
		if(is_string($selectDate)){
			if(strlen($selectDate)<8){
				$var=explode('-',$selectDate);
				
				return true;
			}
			return false;	
		}
		return false;
	}
}
	

