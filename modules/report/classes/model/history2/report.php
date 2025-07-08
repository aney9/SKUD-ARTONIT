<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
	$ruid='history2'
	Модель "быстрого" отчета о событиях.
	
	Поля класса Report для напоминания
	public $titleReport='Шаблон отчета тестовый';//название отчета	
	public $dateCreated;	//дата создания отчета
	public $fileName='crm_report';//название файла с отчетом	
	public $fromUser='Администратор';	//от имени какого сотрудника создан отчет. Надо указать ФИО string
	public $org='ООО "Артсек"';//головная организация.
	public $depatment='Департамент';//организация, для которой сделан отчета, или где работает сотрудник, подготовивший отчет.
	public $titleColumn=array('column0','column1','column2','column3');//название колонок отчета
	public $rowData=array();//даныне отчета построчно.

	
*/

class Model_history2_report extends Model
{
	
	private $selectYear;
	private $selectMonth;

	public function getReport($post, $user){// Статистика
		
			//echo Debug::vars('12', $post, $user);exit;
			$report=new Report();
			$report->org=Kohana::$config->load('main')->get('orgname');
			$report->titleReport='Журнал событий за период с '.Arr::get($post, 'reportdatestart').' по '.Arr::get($post, 'reportdateend');
			$report->dateCreated=date('d.m.Y H:i:s');;
			$report->fileName='crm_history1_'.Arr::get($post, 'reportdatestart').'-'.Arr::get($post, 'reportdateend');
			
			// беру ФИО оператора
			$pep=new Contact($user->id_pep);
			$report->fromUser  = $pep->surname.' '.Text::limit_chars($pep->name, 1).'. '.Text::limit_chars($pep->patronymic, 1).'.';
			
			//беру название департамента оператора
			$org= new Company($user->id_orgctrl );
			$report->depatment  =  $org->name;
			
			$report->titleColumn=array('Дата/время', 'Точка прохода', 'Событие', 'Имя, Фамилия','Должность');
				
			$tempFile=new tempCSV;//в этот файл будут заноситься данные.Открыл файл.
			$tempFile->makeFile();
			$tempFile->addRow($report->titleColumn);//сохранил заголовок отчета - первая строка.
			
			if(true){
				$timeStart=time(true);
				//выбираю разрешенные организации.
				$sql='select distinct og.id_org from organization_getchild(1, '.$user->id_orgctrl. ') og';
				
				$query = DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'))
				->as_array();
				//echo Debug::vars('53', count($query)); exit;
				if(count($query)>1500) throw new  ExceptionCRM('Количество аргументов SQL запроса превышает 1500. Запрос не может быть выполнен.');
				foreach ($query as $key=>$value){
					
					$org_list[]=Arr::get($value, 'ID_ORG');
				}
				
				
				//выбираю разрешенные точки прохода.
				$sql='select distinct dg.id_dev from DEVGROUP_GETCHILD(1, '.$user->id_devgroup.') dg
				where dg.id_dev is not null';
				//echo Debug::vars('62', $sql);exit;
				$query = DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'))
				->as_array();
				//echo Debug::vars('53', count($query)); exit;
				if(count($query)>1500) throw new  ExceptionCRM('Количество аргументов SQL запроса превышает 1500. Запрос не может быть выполнен.');
				foreach ($query as $key=>$value){
					
					$dev_list[]=Arr::get($value, 'ID_DEV');
				}
				//echo Debug::vars('70', count($dev_list));
				//echo Debug::vars('57', implode("," ,$dev_list));exit;
				
				
				//события будут выбираться в цикле по 100000 (Сто тысяч) и записываться в файл.
				//т.о. можно будет избежать потребность в большом количестве выделяемой оперативной памяти.
				
				$rowCount=100000;//количество строк в sql запросе
				$sqlCount=$rowCount;//количество полученных строк в SQL запросе. Начальное значение равно максимальному, чтобы выполнился первый SQL запрос.
				$page=0;//количество итерация SQL запросов
				$totalCountRow=0;//общее количесвто строк с данными.
				
				while($sqlCount==$rowCount)
				{
					//echo Debug::vars('89', '$sqlCount='.$sqlCount,'$rowCount='.$rowCount, $sqlCount==$rowCount);
					
				
				
				$sql='select  first '.$rowCount.' skip '.$page*$rowCount.'   distinct
                    e.datetime,
					d.name as doorname,
					et.name as eventname,
					p.surname||\' \'||p.name||\' \'||p.patronymic,
					p.post
                    from device d
					join events e on e.id_dev=d.id_dev and e.datetime between \''.Arr::get($post, 'reportdatestart').'\' and \''.Arr::get($post, 'reportdateend').'\'
					join people p on p.id_pep=e.ess1
					join eventtype et on et.id_eventtype=e.id_eventtype

					where d.id_dev in ('.implode("," , $dev_list).')
					and e.ess2 in ('.implode("," ,$org_list).')
					and e.id_eventtype  in ('.implode(",", Arr::get($post, 'id_event')).')
					 ';
				
					
					
				//echo Debug::vars('134', $sql);//exit;
				
					$query = DB::query(Database::SELECT, $sql)
					->execute(Database::instance('fb'))
					->as_array();
					$sqlCount=count($query);//считаю какое количество строк получено в последнем запросе.
					$totalCountRow=$totalCountRow + $sqlCount;//считаю общую сумму строк в отчете.
					$page++;
					
					//if($page>7) exit;
					$result=array();
					
					//echo Debug::vars('117', $sqlCount, $page);//exit;
					foreach ($query as $key=>$value)
					{
						foreach($value as $key2=>$value2)
						{
							
							$result[]=iconv('CP1251', 'UTF-8', $value2);
							
						}
					//echo Debug::vars('128', $result);exit;
					if($result) $tempFile->addRow($result);//сохранил строку файла
					$result=array();//очистил строку с результатом.
					}
					//echo Debug::vars('166');exit;
					$query=array();
				}
				$report->totalCountRow=$totalCountRow;
				$report->timeExecute=(time(true) - $timeStart);
				
			} else {

				$query=array();
			}
			//$report->rowData=$query;
			$tempFile->closeFile();

			$report->view='report';//указание куда выводить отчет на экран
			$report->fileName=$tempFile->fileName;//имя файла с сохраненными данными
			
			return $report;
	}
	
	
}
	

