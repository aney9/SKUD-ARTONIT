<?php defined('SYSPATH') OR die('No direct script access.');

class MenuModuleUser {
	
	/**
	 *06.07.2025
	 * класс готовит массив пунктов меню, разрешенных авторизованному пользователю.
	 * @config_file - список всех доступных меню.Из этого списка выбираются только те пункты меню, которые указаны в свойтве flag авторизованного пользователя.
	 */
	 
	 /*
	15 14 13 12 11 10 9 8 . 7 6 5 4 3 2 1 0
	 1  1  0  1  1  1 1 1 . 1 1 1 1 1 1 1 1
	 |  |  |  |  |  | | | . | | | | | | | |-управление дверями
	 |  |  |  |  |  | | | . | | | | | | |-выбор групп в Мониторе
	 |  |  |  |  |  | | | . | | | | | |-конфигуратор
	 |  |  |  |  |  | | | . | | | | |-менеджер карт
	 |  |  |  |  |  | | | . | | | |-менеджер пользователей
	 |  |  |  |  |  | | | . | | |-Отчеты
	 |  |  |  |  |  | | | . | |-Монитор
	 |  |  |  |  |  | | | . |-не используется
	                      .................................................
	 |  |  |  |  |  | | |-интегратор
	 |  |  |  |  |  | |-Отчет Список карт
	 |  |  |  |  |  |-Отчет журнал событий
	 |  |  |  |  |-Отчет Учет рабочего времени
	 |  |  |  |-Отчет Журнал уволенных сотрудников
	 |  |  |-Гостевое рабочее место
	 |  |-отчет Журнал сотрудников
	 |-отчет Журнал рабочего времени 2
	 
	 
	0	1	Управление дверями
	1	2	Выбор группы устройств в Мониторе
	2	4	Конфигуратор
	3	8	Менеджер карт
	4	16	Менеджер пользователей
	5	32	Отчеты
	6	64	Монитор
	7	128	Не используется
	8	256	Интегратор
	9	512	Отчет Список карточек
	10	1024	Отчет Журнал событий
	11	2048	Отчет Учет рабочего времени
	12	4096	Отчет Журнал уволенных сотрудников
	13	8192	Гостевое рабочее место
	14	16384	Отчет Журнал сотрудников
	15	32768	Отчет Журнал рабочего времени 2
	*/
	//набор констант - битовые маски модулей см. класс Menu

	
	public static function factory($config_file = 'configMenu')
	{
		$configMenu= Kohana::$config->load('menu\config')->get($config_file);// получаю массив меню из указанного в конфигурации файла
		
		$user=new User();
		
		$userFlag=$user->flag;// в переменной flag хранятся права доступа в битовых масках.
		$res=array();
		//делаю проверку массива пунктов меню.
		//результатом является массив меню, который будет выведен на экран.
		foreach($configMenu as $key=>$value)
		{
			if(empty($value)) //если у пункта меню нет подчиненного массива, то его вывод разрешен
			{	
				$res[]=$key;
			} else {
				foreach($value as $mask)//если есть подчиненные меню, то выполняю побитовую проверку с переменной flag авторизованного пользователя.
				{
					if(($userFlag & (1 << $mask)) !== 0){
						$res[]=$key;
						break;//если нашлось хоть одно совпадения - этого достаточно, завершает пополение меню
					} else {
					}
				}
			}
		}
		return $res;
	}
	

}