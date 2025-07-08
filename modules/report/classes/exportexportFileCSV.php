<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
*24.02.2025
*Класс exportFileSVC - Экспорт уже существующего файла в формате csv
*вход -
*@fileNameInput - ссылка на файл для экспорта
*@fileNameOutput - название файла при экспорте
*
*все входные данные должны быть в формате utf-8!!!
*/

class exportFileCSV
{
		public $fileNameInput='C:\\xampp\\htdocs\\crm2\\temp\\reportDefault.tmp';
		public $fileNameOutput='';
		
	
	
	public function export()
	{
		
			
		$file_name=$this->fileNameOutput.'_'.date('Y-m-d_H_i_s').".csv";
		
		
		$content = Model::Factory('ReportWorkTime')->send_file($file_name);
		
		//echo Debug::vars('29', $file_name); exit;
		$this->redirect('/report');
		
		
	}
	
	
	
	
}
