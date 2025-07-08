<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
9.07.2024
класс для глобальных констант.
вызов констант в других файлах т.о.: 
	constants::MAX_VALUE,
	constants::DANGER
	constants::RFID.MAX_LENGTH
	файл должен лежать тут: C:\xampp\htdocs\crm2\application\classes\constants.php
*/
class constants
{
	
	const idRfid=1;
	const idGrz=4;
	
	
	const MAX_VALUE = 10;
	const MAX_VALUE_001A = 10;
	const DANGER = 'danger';
	
	//допустимая длина идентификаторов
	const RFID_MAX_LENGTH = 10;
	const RFID_MIN_LENGTH = 6;
	
	const RFID_DEC_MAX_LENGTH = 10;
	const RFID_DEC_MIN_LENGTH = 6;
	
	//длина пояснения к идентификатору
	const RFID_NOTE = 50;
	
	//коды сообщений
	const ALERT_SUCCESS = 0;
	const ALERT_INFO = 1;
	const ALERT_WARNING = 2;
	const ALERT_ERROR = 3;
	
	//ограничение длины для ФИО

		const NAME_LENGHT = 50;
		const SURNAME_LENGHT = 50;
		const PATRONYMIC_LENGHT = 50;
		const POST_LENGHT = 100;
		
	// ограничение длины номера документа

	const NUMDOC_LENGHT = 23;
	
	// ограничение длины поля note для people

	const PEOPLENOTE_LENGHT = 250;
	
	// ограничение длины названия организации
	const ORGNAME_LENGHT = 50;
	
	
	// максимальное количество иконок на экране при отображении количества идентификаторов
	const maxCountImageForId = 3;

    
	//условия валидации ФИО.  pattern="2-[0-9]{3}-[0-9]{3}"
	const FIO_VALID = '^[a-zA-Zа-яА-ЯЁё0-9\s№*]+$';	
	const IP_VALID = '\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}';	
	const HEX8_VALID = '^[0-9ABCDEF]{'.constants::RFID_MIN_LENGTH.','.constants::RFID_MAX_LENGTH.'}$';	
	const DEC10_VALID = '^[0-9]+$';	
	const HEX001A_VALID = '[0-9ABCDEF]{6}001A$';	
		
	
	
	//определение максимальной длины идентификатора RFID. Длина определяется исходя из настроек базового формата хранения данных в СКУДе.
	//вызов осуществляется как constants::RFID_MAX_LENGTH()
	
	public static  function RFID_MAX_LENGTH()
	{
		$result=-1;
		if(Kohana::$config->load('system')->get('baseFormatRfid', 0) == 0)	$result= 10;
		if(Kohana::$config->load('system')->get('baseFormatRfid', 0) == 1)	$result=  10;
		return $result;
		
	}
	
	
	public static  function RFID_MIN_LENGTH()
	{
		$result=4;
		
		return $result;
		
	}
	
	
	
	
	
}
