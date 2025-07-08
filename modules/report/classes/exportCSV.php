<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
*17.10.2024
*Класс exportCSV - Класс для подготовки файла csv.
*вход - массив класса Report
*выход - ссылка на подготовленный файл.
*все входные данные должны быть в формате utf-8!!!
*/

class exportCSV
{
	public $filename;//ссылка на файл, подготовленный к экспорту
	public $makeOk;//результат подготовки файла. true - все в порядке, false - ошибка
	public $mess;//результат подготовки файла. true - все в порядке, false - ошибка
	
	public function __construct(Report $report = null)
	{
		
		/**2.03.2025 проверка наличия класса
		*
		*
		*/
		if( $report instanceof Report) 
		{
			$file_name=$report->fileName.'_'.date('Y-m-d_H_i_s').".csv";
			
			$fp = fopen($file_name, 'w');
				
			//собираю заголовок	Название отчета и головную организацию
			fputcsv ($fp, Array(iconv('UTF-8','CP1251', $report->titleReport), iconv('UTF-8','CP1251',$report->org)),';');
			//дата создания отчета
			fputcsv ($fp, Array(iconv('UTF-8','CP1251',__('dateCreated')),iconv('UTF-8','CP1251',$report->dateCreated)),';');
			
			//кто готовил и департамент
			
			fputcsv ($fp, Array(iconv('UTF-8','CP1251',__('fromUser')),$report->fromUser,iconv('UTF-8','CP1251',__('depatment')),$report->depatment),';');
			
			//заголовок таблицы
			$title=$report->titleColumn;
			//преобразую каждый элемент массива в Win1251
			foreach($title as $key=>$value)
				{
					$title[$key]=iconv('UTF-8','CP1251', $value);
				}
			
			fputcsv ($fp, $title,';');

			foreach ($report->rowData as $key=>$value)
			{
				//преобразую каждый элемент массива в Win1251
				foreach($value as $key2=>$value2)
				{
					//echo Debug::vars('38',$value,  $key2, $value2);exit;
					$value[$key2]=iconv('UTF-8','CP1251', $value2);
					
					
				}
				fputcsv ($fp, $value,';');
			}
		
			fclose($fp); //Закрытие файла
			//$content = Model::Factory('ReportWorkTime')->send_file($file_name);
			
			//echo Debug::vars('29', $file_name); exit;
			//$this->redirect('/report');
			$this->filename=$file_name;
			$this->makeOk=true;
			//echo Debug::vars('58', $this);exit;
		} else {
			$this->makeOk=false;
			$this->mess='Code 11. Нет объекта класса Report для экспорта.';
		}
		
	}
	
	
	
	
}
