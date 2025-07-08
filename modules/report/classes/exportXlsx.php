<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
*17.10.2024
*Класс exportCSV - Класс для создания файла csv.
*вход - массив класса Report
*выход - ссылка на подготовленный файл.
*/

class exportXlsx
{
	
	public function __construct(Report $report)
	{
		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br>');
		require_once APPPATH . '/vendor/PHPExcel-1.8/Classes/PHPExcel.php';
		require_once APPPATH . '/vendor//PHPExcel-1.8/Classes/PHPExcel/Writer/Excel2007.php';
		//https://snipp.ru/php/phpexcel?ysclid=lrwbz922se302951359 
	
		$objPHPExcel = new PHPExcel();//создал документ
		
		// Set document properties
		
		$objPHPExcel->getProperties()->setCreator("ООО Артсек")
									 ->setLastModifiedBy("ООО Артсек")
									 ->setTitle($report->titleReport)
									 ->setSubject($report->titleReport)
									 ->setDescription($report->titleReport)
									 ->setKeywords($report->titleReport)
									 ->setCategory($report->titleReport);

		$xls=$objPHPExcel->setActiveSheetIndex(0);	//создал новый лист
	
		
		
		
		//печать заголовок колонок
		
		$row=2;// начиная со второй строки
		$ccol=1;//автонумерация колонок
		$column_chr=65;// char кода англ буквы A для позиционирования отчета
		
		foreach($report->titleColumn as $key=>$value)
		{		
			//echo Debug::vars('70', $value); exit;
			$xls->setCellValue(chr($column_chr).$row	, $value); 
			$xls->getStyle(chr($column_chr).$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$xls->setCellValue(chr($column_chr).($row+1)	, $ccol++); 
			$xls->getStyle(chr($column_chr).($row+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			//установка автоширины колонок
		
			$xls->getColumnDimension(chr($column_chr))->setAutoSize(true);
			$column_chr++;
		}
	
		$row=4;
		foreach($report->rowData  as $key=>$value)
		{		
			$column_chr=65;//char английской буквы A
			
				foreach($value as $key2=>$value2)
				{
					$xls->setCellValue(chr($column_chr++).$row	, $value2); 
				}	
			
			$row++;
		
		}
		
		$objPHPExcel->getActiveSheet()->setTitle(iconv('CP1251', 'UTF-8', 'getActiveSheet'));


		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

		$file_name=$report->fileName.'_'.date('Y-m-d_H_i_s').'.xlsx';
		$objWriter->save($file_name);
		
		$content = Model::Factory('ReportWorkTime')->send_file($file_name);

		$this->redirect('/contacts/worktime/'.$id_pep);
		
		
	}
	
	
	
	
	
	
	
}
