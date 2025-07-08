<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
*04.02.2025
*Класс tempCSV - Класс для создания файла csv и временного хранения данных.
*вход - имя файла
*выход - ссылка на подготовленный файл.
*все входные данные должны быть в формате utf-8!!!
*/

class tempCSV
{
	
	public $path = 'temp';//имя создаваемого файла
	public $fileName = 'reportDefault.tmp';//имя создаваемого файла
	public $fp;//указатель на файл (file pointer)
	public $separator=",";//разделитель данных в файле
	
	public function __construct()
	{
		$path='';
		if(Kohana::$config->load('report')->get('filePath') !== null) $this->path = Kohana::$config->load('report')->get('filePath');
		if(Kohana::$config->load('report')->get('separator') !== null) $this->separator = Kohana::$config->load('report')->get('separator');
		
		//проверяю наличие папки для временных файлов
			$path = $this->path;
			if (!file_exists($path)) {
				mkdir($path, 0777, true);
				//echo Debug::vars('22', "Директория создана успешно!"); exit;
			} else {
				//echo "Директория уже существует.";
				//echo Debug::vars('25', "Директория уже существует."); exit;
			}
		//если папки нет, то создаю папку		
		//$this->fp=$this->makeFile($this->fileName);	
		//echo Debug::vars('30', Session::instance()->id());exit;
		
		$this->fileName=$this->path.DIRECTORY_SEPARATOR.Session::instance()->id();	//имя файла формируется как номер сессии.	
		
	}
	
	/**4.02.2025 открываю файл для чтения
	*@input $filename - имя открываемого файла
	*@output - указать ресурса.
	*/
	public function getFile($fileName = null)
	{
		if(is_null($fileName))
		{
			$this->fp=fopen($this->fileName, "r");
			return true;			
		} else {
			$this->fp=fopen($fileName, 'r');
			return true;
		}
		
		return false;
	}
	
	/**4.02.2025 открываю файл для записи
	*@input $filename - имя создавамого файла
	*@output - указать ресурса.
	*/
	public function makeFile($fileName = null)
	{
		if(is_null($fileName))
		{
			$this->fp=fopen($this->fileName, 'w');
			return true;			
		} else {
			$this->fp=fopen($fileName, 'w');
			return true;
		}
		return false;
	}
	
	/**4.02.2025 закрываю файл для записи
	*@input $fp - указатель ресурса
	*@output - пока не используется.
	*/
	public function closeFile()
	{
				
		return fclose($this->fp); //Закрытие файла
	}
	
	
	/**4.02.2025 добавляю строку в файл
	*@input $fp - указатель ресурса
	*@output - пока не знаю.
	*/
	public function addRow(array $row)
	{
				
		return fputcsv ($this->fp, $row, $this->separator);//добавил строку в файл/ возвращает количество записанных символом или false
	}
	
	
	/**4.02.2025 получить строку из файла
	*@output - строка из файла.
	*/
	public function getRow()
	{
				
		return fgetcsv ($this->fp, $this->separator);//получить одну строку из файла с данными
	}
	
}
