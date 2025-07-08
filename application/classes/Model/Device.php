<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Device extends Model
{
	
	/*
	Обновление данных устройства
	
	*/
	
	public function update($id, $name, $ip, $port, $devtype, $is_active, $id_server)
	{
		$sql='UPDATE DEVICE
		SET ID_SERVER = '.$id_server.',
		ID_DEVTYPE ='.$devtype.',
		NETADDR = \''.$ip.'\',
		NAME = \''.$name.'\',
		"ACTIVE" = '.$is_active.'
		WHERE (ID_DEV = '.$id.') AND (ID_DB = 1)';

			
		$query = DB::query(Database::UPDATE, iconv('UTF-8', 'CP1251',$sql))
			->execute(Database::instance('fb'));
			return;
		
	}
	
	
	
	public function getListNotActiveDevices()//16.03.2020 г. Получить список  устройств с ACTIVE=0
	{
		$sql='select distinct cd.id_dev, d.name, d2.name as CONTROLLER_NAME, s.name as SERVER_NAME, count (cd.id_cardindev) from cardindev cd
				join device d on d.id_dev=cd.id_dev
				join device d2 on d2.id_ctrl=d.id_ctrl and d2.id_reader is null
				join server s on s.id_server=d2.id_server
				where d."ACTIVE"=0
				group by cd.id_dev, d.name, d2.name, s.name';
		$query = DB::query(Database::SELECT, DB::expr($sql))
			->execute(Database::instance('fb'));
		//array $res();
		foreach ($query as $key => $value)
		{
			$res[$key]['ID_DEV']=$value['ID_DEV'];
			$res[$key]['NAME']=iconv('windows-1251','UTF-8',$value['NAME']);
			$res[$key]['CONTROLLER_NAME']=iconv('windows-1251','UTF-8',$value['CONTROLLER_NAME']);
			$res[$key]['SERVER_NAME']=iconv('windows-1251','UTF-8',$value['SERVER_NAME']);
			$res[$key]['COUNT']=$value['COUNT'];
		}	
		return $res;
	}
	
	public function readkey_once($id_dev)// вычитка карт из указанного контроллера и запись данных в файл.
	{
		$file_name="readkey_".date('Y-m-d_H_i_s')."_id_door=".$id_dev.".csv";
		$fp = fopen($file_name, "w"); // Открываем файл в режиме записи	
		if(is_numeric($id_dev))
		{			
			$sql='select d.id_reader from device d
			where d.id_dev='. $id_dev;
			$is_door=DB::query(Database::SELECT, DB::expr($sql))
				->execute(Database::instance('fb'))
				->get('ID_READER');
			//$value=$id_dev;
				
			
				if(!is_null($is_door))// если это точка прохода, то продолжить, иначе отказ.
				{			
					//echo Debug::vars('40', $value); exit;
					$res='';
					$res_out=__('readkey_title').'<br>'.__('command_time_start').date('d.m.Y H:i:s');
					$value=$id_dev;
					$a=$this->get_device_info($value);
					$device_name=$a['device_name'];// device name
					$device_id=$value;// device name
					$door_name=$a['door_name'];// device name
					$ip_server= $a['ip_server'];// ip server
					$port=$a['port'];// port
					$id_reader=$a['id_reader'];
					$sql='select max(cd.devidx) from cardidx cd where cd.id_dev='.$value;
				$max_cell = DB::query(Database::SELECT, DB::expr($sql))
					->execute(Database::instance('fb'))
					->get('MAX');
				
				$sql='select c.id_card, c.devidx, c.id_dev, c.load_time, c.load_result from cardidx c where c.id_dev='.$value.' order by c.devidx';
				$query = DB::query(Database::SELECT, DB::expr($sql))
					->execute(Database::instance('fb'));

				foreach ($query as $key => $value)
				{
					
					$cell[$value['DEVIDX']]=$value['ID_CARD'];// номер карты
					$load_time[$value['DEVIDX']]=$value['LOAD_TIME'];// время загрузки
				}
				// готовлю соединение с ТС
						$smes = 'r55 login name="3", password="3"';
										
						//создаем сокет для подключения ТСП
						if (false == ($socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP))) {
							HTTP::redirect("Couldn't create socket, error code is: " . socket_last_error() .
									",error message is: " . socket_strerror(socket_last_error()));
						}
						
						// создаем соединение с сервером
						$connection = @socket_connect($socket, $ip_server, $port);
						//if ($connection === false)      die("Cannot connect to server {$ip_server}:{$port}");
						if ($connection === false)      HTTP::redirect('errorpage?err=Cannot connect to TS server '.$ip_server .':'. $port.' in readkey_arr function.');
						$reply = socket_read($socket,4096);
						
						//авторизация
						socket_write($socket, $smes."\r\n", strlen($smes."\r\n"));
						//получаем ответ
						$reply = socket_read($socket,4096);
						// соединение подготовлено
									
						
				// соединение с ТС установлено.		
						
						
				//$file_name="readkey_".date('Y-m-d_H_i_s')."_id_door=".$device_id.".csv";
				//$fp = fopen($file_name, "w"); // Открываем файл в режиме записи	
				$mytext ="cell;door;key_from_database;load_time;key_from_device;TZ;status; result_compare;read_count\r\n"; // строка данных
							$test = fwrite($fp, $mytext); // Запись в файл
				set_time_limit(1800);
				
				$i=0;
						$time_start_local=microtime(true);
						while ($i<$max_cell+20) //делаю выборку на 20 ячеек больше
						{
							
							$i_count=0;
							$res_command='ERR';
							while ($res_command == 'ERR' and $i_count<10)// до 10 попыток чтения данных из ячейки
							{
										
								// Для Адемантов надо указывать номер ячейки, а для Артонитов - номер карты.
								if($port==5666) $command='readkey door='.$id_reader.', cell='.$i;
								if($port==1967) $command='readkey door='.$id_reader.', key=""'.Arr::get($cell, $i, 'no').'""';
								$smes_command = 'r55 exec device="'.$device_name.'", command="'.$command.'"';
								
								//send command
								socket_write($socket, iconv('UTF-8','windows-1251',$smes_command."\r\n"), strlen(iconv('UTF-8','windows-1251',$smes_command."\r\n")));
								//получаем ответ
								$reply = socket_read($socket,4096);
								$res1=trim($reply);
								$temp=Model::Factory('Stat')->parser_1($res1);// ответ на команду
								
								// проверка результата ответ
								$res_command=substr($temp, 0, stripos($temp, " "));// выборка результат выполнения команды: OK или ERR
								$i_count++;
							}
							
							$key=str_replace(" ",'',Model::Factory('Stat')->parser_2($res1));
							parse_str(str_replace(",","&",$key), $bbb);
							$key2=substr($key, 0, stripos($key, ","));
							$result_compare='ERROR';
							if(strcasecmp(Arr::get($cell, $i, 'no'), $key2) == 0) $result_compare='OK1';
							if(Arr::get($cell, $i, '0000000000') == '0000000000' and strcasecmp($key2,'0000000000') == 0 and strcasecmp($bbb['TZ'], '0x0001' ) != 0) $result_compare='OK2';
							
							$mytext =$i.';'.$id_reader.';'. Arr::get($cell, $i, '----------').';'.Arr::get($load_time, $i, '---').';'.str_replace(",",";",trim($key)).';'.$result_compare.';'.$i_count."\r\n"; // строка данных
							$test = fwrite($fp, $mytext); // Запись в файл
						$i++;
						
						}
					
					socket_close($socket);// Закрытие сокета
					$res=$res_out.'<br>'.__('readkey_result', array(':device_name' => $device_name, ':keycount' => $max_cell,':file_name'=>iconv('windows-1251','UTF-8',$file_name),  ':during'=> round(microtime(true) - $time_start_local, 2)));
				} else 
				{
					
					$res=__('readkey_once_is_not_door');
					$test = fwrite($fp, $res); // Запись в файл
				}
				
		} else 
		{
			$res=__('readkey_once_is_not_number');
			$test = fwrite($fp, $res); // Запись в файл
			
		}
		
		
		fclose($fp); //Закрытие файла
		Kohana::$log->add(Log::INFO, 'Result read from controller and write to file '.$res);
		return $res;
	}
	
	public function readkey_arr($dev)// вычитка карт из контроллеров и их запись в файл. С 27.05.2020 суть функции изменилась. Теперь функция читает данные из контроллера, сравнивает их с базой данных, выделяет разночтения и записывает их в файл.
	{
		
		$time_start=microtime(true);
		$res='';
		$res_out=__('readkey_title').'<br>'.__('command_time_start').date('d.m.Y H:i:s');
		$bbb=array();
		foreach ($dev as $key)
		{		
			$res=$this->check_and_delete_card_from_device($key);// 18.05.2021 исправлено для правильной записи результата в лог
			$res_out=$res_out.'<br>'.__('command_time_end').date('d.m.Y H:i:s').'<br>'.$res;
		}
		$this->last_command($res_out);
		return $res_out;
	}
			
	public function check_and_delete_card_from_device($dev)// функция вычитывает карты из контроллера, делает проверку в базе данных. Если проход запрещен, то карта ставиться в очередь. Результат: файл со списком удаленных карт. Функция возвращает текстовую строку с результатом поиска..
	{
			//echo Debug::vars('181',$dev); exit;
			$st_data_for_dev=Model::Factory('Stat')->load_table($dev);
			$err_count_max=0;//после указанного количества ошибок проверка прекратиться.
			//echo Debug::vars('184',$dev, $st_data_for_dev['384'], $err_count_max ); exit;
			//echo Debug::vars('184', Arr::get($st_data_for_dev[$dev] , 'DEVICE_COUNT', 0), Arr::get($st_data_for_dev[$dev] , 'BASE_COUNT_AT_TIME', 0), $err_count_max ); exit;
			$err_count_max=Arr::get($st_data_for_dev[$dev] , 'BASE_COUNT_AT_TIME', 0) - Arr::get($st_data_for_dev[$dev] , 'DEVICE_COUNT', 0);// выявленное расхождение. Если число меньше нуля - надо удалять лишние карты. Если число больше нуля - надо искать карты, которые не записаны.
			Log::instance()->add(Log::DEBUG, 'Line 187. err_count_max='. $err_count_max);
			$memstart=memory_get_usage();
			$a=$this->get_device_info($dev);
			$device_name=$a['device_name'];// device name
			$device_id=$dev;// device id
			$door_name=$a['door_name'];// device name
			$ip_server= $a['ip_server'];// ip server
			$port=$a['port'];// port
			$id_reader=$a['id_reader'];
			$count_err=0;
			
		//If($err_count_max !=){	
		//===готовлю соединение с ТС
												
				//создаем сокет для подключения ТСП
				if (false == ($socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP))) {
					Log::instance()->add(Log::DEBUG, "Line 203.Couldn't create socket, error code is: " . socket_last_error().", error message is: " . socket_strerror(socket_last_error()));
					HTTP::redirect("'errorpage?err=Couldn't create socket, error code is: " . socket_last_error() .
							",error message is: " . socket_strerror(socket_last_error()));
				}
				
				// создаем соединение с сервером
				$connection = @socket_connect($socket, $ip_server, $port);
				//if ($connection === false)      die("Cannot connect to server {$ip_server}:{$port}");
				if ($connection === false) 
				{
					Log::instance()->add(Log::DEBUG, 'Line 213.Cannot connect to TS server '.$ip_server .':'. $port.' in readkey_arr function.');
					HTTP::redirect('errorpage?err=Cannot connect to TS server '.$ip_server .':'. $port.' in readkey_arr function.');
				}
				$reply = socket_read($socket,4096);
				
		//====авторизация. ПРи подключении к ТС2 авторизация не требутся, так что эту часть можно обойти.
				$login_mes = 'r51 login name="3", password="35"';
				socket_write($socket, $login_mes."\r\n", strlen($login_mes."\r\n"));
				//получаем ответ
				$reply = socket_read($socket,4096);
				

		//== ждем ответ на команду авторизации, т.к. может быть и отказ
				
				// соединение подготовлено
					
				
		// соединение с ТС установлено.		
					
		set_time_limit(1800);
		
		
		
		//echo Debug::vars('230', 'Select direct: write or delete',$dev, $err_count_max); exit;
		$res_out='<br>'.__('Цифры совпадают.');
		$time_start_local=microtime(true);		

		if($err_count_max == 0){ // если все совпадает, то просто фиксируем результат
		$file_name="find_and_write_key_".date('Y-m-d_H_i_s')."_id_door=".$device_id.".log";
		$fp_findkey = fopen($file_name, "w"); // Открываем файл в режиме записи	
		fwrite($fp_findkey,date('Y-m-d H:i:s')."_id_door=".$device_id." Расхождений в количестве карт в контроллере и в базе данных СКУД не выявлено.\r\n");	
		fclose($fp_findkey);
		$res_out='<br>'.__('checkkey_result_equal', array(':device_name' => $device_name,
					':during'=> round(microtime(true) - $time_start_local, 2),
					));
		
		}

		
		if($err_count_max<0){ // процесс вычитки номеров карт из контроллера и сравнение их с базой данных. В результате процесса выискиваются "лишние" карты в контроллере.
		$file_name="find_and_delete_key_".date('Y-m-d_H_i_s')."_id_door=".$device_id.".log";
		$fp_findkey = fopen($file_name, "w"); // Открываем файл в режиме записи	
		fwrite($fp_findkey,date('Y-m-d H:i:s')."_id_door=".$device_id." Найти и удалить в контроллере лишние карты.\r\n");	
			$i=0;
			$reply='';
			$max_cell=30000;
			//echo Debug::vars('242', 'Check1', $i, $max_cell, $err_count_max); exit;
					while (($i<$max_cell) and ($err_count_max<0)) //проверка по всему диапазону карт в контроллере
					{
						$i_count=0;
						$res_command='ERR';
							// Для Адемантов надо указывать номер ячейки, а для Артонитов - номер карты. 8.07.2020 дальнейшая работа ведется только с Артонитами.
							$command='readkey door='.$id_reader.', cell='.$i;
							//if($port==5666) $command='readkey door='.$id_reader.', cell='.$i;
							//if($port==1967) $command='readkey door='.$id_reader.', key=""'.Arr::get($cell, $i, 'no').'""';
							$descriptor='r'.$i.'_'.$i_count;//дескриптор команды
							$smes_command = $descriptor.' exec device="'.$device_name.'", command="'.$command.'"';
						
						//send command
							if(socket_write($socket, iconv('UTF-8','windows-1251',$smes_command."\r\n"), strlen(iconv('UTF-8','windows-1251',$smes_command."\r\n"))))
							{
								//получаем ответ. При приеме ответа надо убедиться, что получен именно ответ на команду, а не событие. Признак правильного ответа - совпадение первой части с дескриптором команды
								while ($res_command == 'ERR' and $i_count<10)// организую вычитку ответа, до 10 попыток чтения данных из ячейки
								{
									//$reply = socket_read($socket,4096);// получили ответ
									//if($reply)
									if($reply = socket_read($socket,4096))
									{
										if(stripos($reply, $descriptor) !== false) // Если команда начинается с дескриптора, то это ответ на команду.
										{
											$res_command='OK';
											$i_count=10;
										} else {
											$res_command = 'ERR';
										}
									} else {
										Log::instance()->add(Log::DEBUG, 'Can\'t socket read '.socket_last_error());
										fwrite($fp_findkey,'Can\'t socket read '.socket_last_error());//записал сообщения о невозможности чтения из сокета
									}
									$i_count++;
								}
									
							} else { // не удалось записать данные в сокет
								
								Log::instance()->add(Log::DEBUG, 'Can\'t socket write '.socket_last_error());
								fwrite($fp_findkey,'Can\'t socket write '.socket_last_error());//записал сообщения о невозможности записи в сокет
							}
							
						
						// далее идет "разбор" ответа и выделение номера карты
						//echo Debug::vars('276', $smes_command, $reply); exit;
						$reply=str_replace("\r\n", NULL, $reply);
						$reply=str_replace("\"\"", NULL, $reply);
						//надо выделить ситуацию, когда в ответ приходит сообщение об ошибке выполнения команды.
						if(strpos($reply, "ERR "))
						{
							fwrite($fp_findkey,'cell ='.$i.', answer='.$reply."\r\n");
						} else {
							$reply=substr($reply,stripos($reply, "cell="));
							parse_str(str_replace(",","&",$reply), $bbb);
							//echo Debug::vars('299', Arr::get($bbb, 'Key'), $bbb); // exit;
							//Log::instance()->add(Log::DEBUG, 'Line 300 '. Debug::vars($bbb, ((Arr::get($bbb, 'Key') != '0000000000') or (Arr::get($bbb, 'Access') == 'Yes'))));
							$mess='';
							if((Arr::get($bbb, 'Key') != '0000000000') AND (Arr::get($bbb, 'Access') == 'Yes')) // выполнять проверку для карт ненулевый и с разрешенным проходом
							{
									
									if($this->check_key_for_id_dev(Arr::get($bbb, 'Key'),$device_id ))
								{
									// карта должна тут ходить. Ничего не делаем
									
								} else { // выявлена карта, который нет в базе данных. Эту карту надо удалить.
								Log::instance()->add(Log::DEBUG, 'Line 342 '. Debug::vars($bbb));
									$mess='Key='.Arr::get($bbb, 'Key').', cell='.$i.' , id_dev='.$device_id.' ('.$reply.")  карту надо удалить из контроллера.\r\n";	
									fwrite($fp_findkey,'Key='.Arr::get($bbb, 'Key').', cell='.$i.' , id_dev='.$device_id.' ('.$reply.")  карту надо удалить из контроллера.\r\n");	
									$this-> delKeyFromIdDev(Arr::get($bbb, 'Key'), $device_id, $i);// постановка карты в очередь на удаление
									
									$count_err++;
									$err_count_max++;
									
								}
												
												
							}
						}
										
						//fwrite($fp_findkey,$mess);//записал строку в файл с результатом
						$bbb=array();// очистил массив
										
						
					$i++;// завершение цикла чтения карты и ячейки
					}
				//Log::instance()->add(Log::DEBUG, 'End memory_get_usage= '. memory_get_usage(). "\r\n\r\n");
				fwrite($fp_findkey,'Найдено "лишних" карт '.$count_err."\r\n");
				fwrite($fp_findkey,'cell max ='.$i."\r\n");
				fwrite($fp_findkey,'Увеличение памяти '. number_format(memory_get_usage()-$memstart));
				fclose($fp_findkey);
				socket_close($socket);// Закрытие сокета
				$res_out='<br>'.__('readkey_result', array(':device_name' => $device_name,
					':keycount' => $max_cell,
					':file_name'=>iconv('windows-1251','UTF-8',$file_name),
					':during'=> round(microtime(true) - $time_start_local, 2),
					':count_err'=>$count_err,
					));
					
				// тут завершается сверка контроллера с базой данных
		};
		
		
		if($err_count_max>0){ // вычитка номеров карт из из базы данных для указанного контроллера и поиск этих карт в контроллере и последующая запись этих карт в контроллеры
		$file_name="find_and_write_key_".date('Y-m-d_H_i_s')."_id_door=".$device_id.".log";
		$fp_findkey = fopen($file_name, "w"); // Открываем файл в режиме записи	
		fwrite($fp_findkey,date('Y-m-d H:i:s')."_id_door=".$device_id." Найти и записать в контроллере недостающие карты.\r\n");	
			$sql='select * from cardidx cd where cd.id_dev='.$dev;
				try
			{
				$query = DB::query(Database::SELECT, DB::expr($sql))
			->execute(Database::instance('fb'))
			->as_array();
			} catch (Exception $e) {
				HTTP::redirect('errorpage?err=l386_'.Text::limit_chars($e->getMessage(), 50));
			}
			$count_lost_key=0;// счетчик незаписанных карт
			$count_check_item=0;// количество фактов чтения
			
			$command='getkeycount door='.$id_reader;
			$descriptor='r4';//дескриптор команды
			$smes_command = $descriptor.' exec device="'.$device_name.'", command="'.$command.'"';
			socket_write($socket, iconv('UTF-8','windows-1251',$smes_command."\r\n"), strlen(iconv('UTF-8','windows-1251',$smes_command."\r\n")));
			$keyCount = socket_read($socket,4096);
			//echo Debug::vars('362',$smes_command, $keyCount); exit;
			foreach ($query as $key=>$value)// чтение карты в контроллере. Если карты нет - то надо поставить её на запись в контроллер.
			{
				$count_check_item++;
				$i_count=0;
				$res_command='ERR';
				// Для Адемантов надо указывать номер ячейки, а для Артонитов - номер карты. 8.07.2020 дальнейшая работа ведется только с Артонитами.
				$command='readkey door='.$id_reader.', key=""'.Arr::get($value, 'ID_CARD').'""';
							//if($port==5666) $command='readkey door='.$id_reader.', cell='.$i;
							//if($port==1967) $command='readkey door='.$id_reader.', key=""'.Arr::get($cell, $i, 'no').'""';
				$descriptor='r'.Arr::get($value, 'ID_CARD').'_'.$i_count;//дескриптор команды
				$smes_command = $descriptor.' exec device="'.$device_name.'", command="'.$command.'"';
						
						//send command
							if(socket_write($socket, iconv('UTF-8','windows-1251',$smes_command."\r\n"), strlen(iconv('UTF-8','windows-1251',$smes_command."\r\n"))))
							{
								//получаем ответ. При приеме ответа надо убедиться, что получен именно ответ на команду, а не событие. Признак правильного ответа - совпадение первой части с дескриптором команды
								while ($res_command == 'ERR' and $i_count<10)// организую вычитку ответа, до 10 попыток чтения данных из ячейки
								{
									//$reply = socket_read($socket,4096);// получили ответ
									//if($reply)
									if($reply = socket_read($socket,4096))
									{
										if(stripos($reply, $descriptor) !== false) // Если команда начинается с дескриптора, то это ответ на команду.
										{
											$res_command='OK';
											$i_count=10;
										} else {
											$res_command = 'ERR';
										}
									} else {
										Log::instance()->add(Log::DEBUG, 'Can\'t socket read '.socket_last_error());
										fwrite($fp_findkey,'Can\'t socket read '.socket_last_error());//записал сообщения о невозможности чтения из сокета
									}
									$i_count++;
								}
									
							} else { // не удалось записать данные в сокет
								
								Log::instance()->add(Log::DEBUG, 'Can\'t socket write '.socket_last_error());
								fwrite($fp_findkey,'Can\'t socket write '.socket_last_error());//записал сообщения о невозможности записи в сокет
							}
							
						
						// далее идет "разбор" ответа и выделение номера карты
						//echo Debug::vars('395', $smes_command, trim($reply)); exit;
						$reply=trim($reply);
						//надо выделить ситуацию, когда в ответ приходит сообщение об ошибке выполнения команды.
						if(strpos($reply, "ERR "))
						{
							fwrite($fp_findkey,'Answer error='.$reply."\r\n");
						} else {
							if(stripos($reply, "Access=Yes"))
							{
								//карта есть в контроллере
								//fwrite($fp_findkey,'Key='.Arr::get($value, 'ID_CARD').', id_dev='.$device_id.' ('.$reply.")  in controller!\r\n");	
								
							} else {
								
								//карты нет в контроллере
								$this-> writeKeyToDevice(Arr::get($value, 'ID_CARD'), $device_id);// постановка карты в очередь на запись
								fwrite($fp_findkey,'Key='.Arr::get($value, 'ID_CARD').', id_dev='.$device_id.' ('.$reply.")  карты нет в контроллере!\r\n");	
								$count_lost_key++;
							}
						}
						
			}

			fwrite($fp_findkey,'Проверка закончена. В контроллере должно быть '.$keyCount.' карт. В базе данных '.count($query).' карт, сравнений сделано '.$count_check_item.', на запись отправлено '.$count_lost_key.' карт'."\r\n");
			fclose($fp_findkey);
			
						$res_out='<br>'.__('checkkey_result', array(':device_name' => $device_name,
					':keycount' => count($query),
					':file_name'=>iconv('windows-1251','UTF-8',$file_name),
					':during'=> round(microtime(true) - $time_start_local, 2),
					':count_err'=>$count_lost_key,
					));
			
		}
			
		
			return	$res_out;		
	}
		
	
	public function writeKeyToDevice($id_card, $device_id)
	{
		$sql='update cardidx cd
			set cd.time_stamp=\'now\'
			where cd.id_dev='.$device_id.'
			and cd.id_card=\''.$id_card.'\'';
		$query = DB::query(Database::UPDATE, DB::expr($sql))
			->execute(Database::instance('fb'));
			return;
		
	}
	
	
	
	public function delKeyFromIdDev($id_card, $id_dev, $devidx)
	{
		$sql='INSERT INTO CARDINDEV (
				ID_DB
				,ID_CARD
				,ID_DEV
				,DEVIDX
				,OPERATION
				,ATTEMPTS
				,ID_PEP
				,TIME_STAMP
				,ID_CARDTYPE
				,FROMUSER) 
				VALUES (
				1
				,\''.$id_card.'\'
				,'.$id_dev.'
				,'.$devidx.'
				,2
				,0
				,0
				,\'now\'
				,1
				,\'SYSDBA\')';
	//echo Debug::vars('363', $sql); exit;
	try
			{
			$query = DB::query(Database::INSERT, $sql)
			->execute(Database::instance('fb'));
			} catch (Exception $e) {
				//HTTP::redirect('errorpage?err=368_'.$e->getMessage()); // тут выводилось сообщение вида 368_SQLSTATE[IM001]: Driver does not support this function: driver does not support lastInsertId()
			}
	return;
	}
	
	
	public function check_and_write_card_to_device($dev)// функция вычитывает карты из контроллера по списку из базы данных. Если карты в контроллере нет, то карта ставится в очередь на запись в контроллер. Результат: файл со списком карт для записи.
	{
			$st_data_fro_dev=Model::Factory('Stat')->load_table($dev);
			$err_count_max=0;//после указанного количества ошибок проверка прекратиться.
			$err_count_max=Arr::get($st_data_fro_dev[$dev] , 'DEVICE_COUNT', 0)- Arr::get($st_data_fro_dev[$dev] , 'BASE_COUNT_AT_TIME', 0);
			//echo Debug::vars('181', Arr::get($st_data_fro_dev[$dev] , 'DEVICE_COUNT', 0), Arr::get($st_data_fro_dev[$dev] , 'BASE_COUNT_AT_TIME', 0), $err_count_max ); exit;
			$memstart=memory_get_usage();
			$a=$this->get_device_info($dev);
			$device_name=$a['device_name'];// device name
			$device_id=$dev;// device id
			$door_name=$a['door_name'];// device name
			$ip_server= $a['ip_server'];// ip server
			$port=$a['port'];// port
			$id_reader=$a['id_reader'];
			$count_err=0;
			
			
		// готовлю соединение с ТС
				$smes = 'r55 login name="3", password="3"';
								
				//создаем сокет для подключения ТСП
				if (false == ($socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP))) {
					HTTP::redirect("Couldn't create socket, error code is: " . socket_last_error() .
							",error message is: " . socket_strerror(socket_last_error()));
				}
				
				// создаем соединение с сервером
				$connection = @socket_connect($socket, $ip_server, $port);
				//if ($connection === false)      die("Cannot connect to server {$ip_server}:{$port}");
				if ($connection === false)      HTTP::redirect('errorpage?err=Cannot connect to TS server '.$ip_server .':'. $port.' in readkey_arr function.');
				$reply = socket_read($socket,4096);
				
				//авторизация
				socket_write($socket, $smes."\r\n", strlen($smes."\r\n"));
				//получаем ответ
				$reply = socket_read($socket,4096);
				// соединение подготовлено
							
				
		// соединение с ТС установлено.		
					
		set_time_limit(1800);
		
		$file_name="findkey_".date('Y-m-d_H_i_s')."_id_door=".$device_id.".log";
		$fp_findkey = fopen($file_name, "w"); // Открываем файл в режиме записи	
		//fwrite($fp_findkey,'Разница в количестве карт  ='.$delta."\r\n");	
		
		
		$i=0;
		$time_start_local=microtime(true);
		
		Log::instance()->add(Log::DEBUG, '221 memory_get_usage= '. memory_get_usage(). "\r\n\r\n");
		$reply='';
				//while ($i<($max_cell+200)) //делаю выборку на 200 ячеек больше
				$max_cell=1000;
				//while (($i<$max_cell) and ($delta>0)) //
				while (($i<$max_cell) and ($err_count_max>0)) //проверка по всему диапазону карт в контроллере
				{
					
					$i_count=0;
					$res_command='ERR';
					
													
						// Для Адемантов надо указывать номер ячейки, а для Артонитов - номер карты.
						$command='readkey door='.$id_reader.', cell='.$i;
						//if($port==5666) $command='readkey door='.$id_reader.', cell='.$i;
						//if($port==1967) $command='readkey door='.$id_reader.', key=""'.Arr::get($cell, $i, 'no').'""';
						$descriptor='r'.$i.'_'.$i_count;//дескриптор команды
						$smes_command = $descriptor.' exec device="'.$device_name.'", command="'.$command.'"';
					
					//send command
						if(socket_write($socket, iconv('UTF-8','windows-1251',$smes_command."\r\n"), strlen(iconv('UTF-8','windows-1251',$smes_command."\r\n"))))
						{
							//получаем ответ. При приеме ответа надо убедиться, что получен именно ответ на команду, а не событие. Признак правильного ответа - совпадение первой части с дескриптором команды
							while ($res_command == 'ERR' and $i_count<10)// организую вычитку ответа, до 10 попыток чтения данных из ячейки
							{
								//$reply = socket_read($socket,4096);// получили ответ
								//if($reply)
								if($reply = socket_read($socket,4096))
								{
									if(stripos($reply, $descriptor) !== false) // Если команда начинается с дескриптора, то это ответ на команду.
									{
										$res_command='OK';
										$i_count=10;
									} else {
										$res_command = 'ERR';
									}
								} else {
									Log::instance()->add(Log::DEBUG, 'Can\'t socket read '.socket_last_error());
									fwrite($fp_findkey,'Can\'t socket read '.socket_last_error());//записал сообщения о невозможности чтения из сокета
								}
								$i_count++;
							}
								
						} else { // не удалось записать данные в сокет
							
							Log::instance()->add(Log::DEBUG, 'Can\'t socket write '.socket_last_error());
							fwrite($fp_findkey,'Can\'t socket write '.socket_last_error());//записал сообщения о невозможности записи в сокет
						}
						
					
					// далее идет "разбор" ответа и выделение номера карты
					//echo Debug::vars('276', $smes_command, $reply); exit;
					$reply=str_replace("\r\n", NULL, $reply);
					$reply=str_replace("\"\"", NULL, $reply);
					//надо выделить ситуацию, когда в ответ приходит сообщение об ошибке выполнения команды.
					if(strpos($reply, "ERR "))
					{
						fwrite($fp_findkey,'cell ='.$i.', answer='.$reply."\r\n");
					} else {
						$reply=substr($reply,stripos($reply, "cell="));
						parse_str(str_replace(",","&",$reply), $bbb);
						//echo Debug::vars('299', Arr::get($bbb, 'Key'), $bbb); // exit;
						$mess='';
						if(Arr::get($bbb, 'Key') != '0000000000')
						{
								
								if($this->check_key_for_id_dev(Arr::get($bbb, 'Key'),$device_id ))
							{
								//$mess='Key='.Arr::get($bbb, 'Key').', cell='.$i.' , id_dev='.$device_id.' ('.$reply.") is_OK \r\n";
								//fwrite($fp_findkey,'Key='.Arr::get($bbb, 'Key').', cell='.$i.' , id_dev='.$device_id.' ('.$reply.") is_OK \r\n");
								
							} else { // выявлена карта, который нет в базе данных. Эту карту надо удалить.
								$mess='Key='.Arr::get($bbb, 'Key').', cell='.$i.' , id_dev='.$device_id.' ('.$reply.")  is_error \r\n";	
								fwrite($fp_findkey,'Key='.Arr::get($bbb, 'Key').', cell='.$i.' , id_dev='.$device_id.' ('.$reply.")  is_error \r\n");	
								$this-> delKeyFromIdDev(Arr::get($bbb, 'Key'), $device_id, $i);// постановка карты в очередь на удаление
								
								$count_err++;
								$err_count_max--;
								
							}
											
											
						}
					}
									
					//fwrite($fp_findkey,$mess);//записал строку в файл с результатом
					$bbb=array();// очистил массив
									
					
				$i++;// завершение цикла чтения карты и ячейки
				}
			//Log::instance()->add(Log::DEBUG, 'End memory_get_usage= '. memory_get_usage(). "\r\n\r\n");
			fwrite($fp_findkey,'Найдено "лишних" карт '.$count_err."\r\n");
			fwrite($fp_findkey,'cell max ='.$i."\r\n");
			fwrite($fp_findkey,'Увеличение памяти '. number_format(memory_get_usage()-$memstart));
			socket_close($socket);// Закрытие сокета
			$res_out='<br>'.__('readkey_result', array(':device_name' => $device_name,
				':keycount' => $max_cell,
				':file_name'=>iconv('windows-1251','UTF-8',$file_name),
				':during'=> round(microtime(true) - $time_start_local, 2),
				':count_err'=>$count_err,
				));
			
			fclose($fp_findkey);
			return	$res_out;		
	}
	
	public function check_key_for_id_dev($id_card, $id_dev)// процедура проверка возможности прохода карты id_card в точке прохода id_dev
	{
			$sql=' select count(*) from ss_accessuser ssa
					 join access ac on ac.id_accessname=ssa.id_accessname
					 join card c on c.id_pep=ssa.id_pep
					 where c.id_card=\''.$id_card.'\'
					and ac.id_dev='.$id_dev.'
					and ((c.timeend>\'now\') or (c.timeend is null))
                     and c."ACTIVE">0';
			try
			{
				$query = DB::query(Database::SELECT, DB::expr($sql))
			->execute(Database::instance('fb'))
			->get('COUNT');
			} catch (Exception $e) {
				HTTP::redirect('errorpage?err=l386_'.Text::limit_chars($e->getMessage(), 50));
			}
			return $query;
	}
	
		
	public function settz_arr ($dev)
	{	//echo Debug::vars('6', $dev); exit;
		$time_start=microtime(true);
		$res='';
		$res_out=__('settz_log_title').'<br>'.__('command_time_start').date('d.m.Y H:i:s');
		
		//получаю список ТZ
		$sql='select t.id_timezone, t.name, t.timestart, t.timeend, t.flag from timezone t';
		
		$query = DB::query(Database::SELECT, DB::expr($sql))
			->execute(Database::instance('fb'));
		
		foreach ($dev as $id_dev)
		{
			//echo Debug::vars('18', $dev , $value, $value['ID_DEV']);
			$a=$this->get_device_info($id_dev);
			//echo Debug::vars('21', $a); exit;
			$device_name=$a['device_name'];// device name
			$door_name=$a['door_name'];// device name
			$ip_server= $a['ip_server'];// ip server
			$port=$a['port'];// port
			foreach ($query as $key=>$value)
			{
				//читаю TZ  до установки
				$time_start_local=microtime(true);
				$command='gettz zone='.$value['ID_TIMEZONE'];
				$res1=trim($this->sendCommand($ip_server, $port, $device_name, $command));
				
				//команда на запись TZ
				$days=$value['FLAG'] & 0xFF;
				$night_shift=($value['FLAG'] >> 8) & 1;
				$EveryTime=($value['FLAG'] >> 9) & 1;
				$ZoneType='Auto';
				if ($EveryTime == 1) $ZoneType = 'EveryTime';
				$command='settz zone='.$value['ID_TIMEZONE'].', TimeStart=#'.$value['TIMESTART'].'#, TimeEnd=#'.$value['TIMEEND'].'#, Days='.$days;//.', ZoneType='.$ZoneType;
				//echo Debug::vars('33', $command); //exit;
				$res2=$this->sendCommand($ip_server, $port, $device_name, $command);
				
				//читаю TZ  до установки
				$command='gettz zone='.$value['ID_TIMEZONE'];
				$res3=trim($this->sendCommand($ip_server, $port, $device_name, $command));
				
				$res_out=$res_out.'<br>'.__('settz_result', array(':device_name' => $device_name, ':tz_number' => $value['ID_TIMEZONE'],  ':tz_befor'=> Model::Factory('Stat')->parser_2($res1), ':tz_after'=>Model::Factory('Stat')->parser_2($res3), ':settz_result' => Model::Factory('Stat')->parser_1($res2) ,':during'=> round(microtime(true) - $time_start_local, 2)));
				}
			
		}
		
			$res_out=$res_out.'<br>'.__('command_time_end').date('d.m.Y H:i:s');
			
		$this->last_command($res_out);
			
		return $res_out;
	}
	
	
	public function clear_device_arr($dev)// формирование списка для загрузки в устройство
	{
		$time_start=microtime(true);
		$res='';
		$res_out=__('clear_device_log_title').'<br>'.__('command_time_start').date('d.m.Y H:i:s');
		
		foreach ($dev as $id_dev=>$value)
		{
			$a=$this->get_device_info($id_dev);
			$command1='ClearKeys Door='.$a['id_reader'];
			$command3='GetkeyCount Door='.$a['id_reader'];
			$device_name=$a['device_name'];// device name
			$door_name=$a['door_name'];// device name
			$ip_server= $a['ip_server'];// ip server
			$port=$a['port'];// port
			//echo Debug::vars('20', $command1, $command3);
			//читаю количество ключей в канале 0 до очистки.
			$time_start_local=microtime(true);
			$res1=trim($this->sendCommand($ip_server, $port, $device_name, $command3));
			
			//команда на удаление ключей в канале 0
			$res2=$this->sendCommand($ip_server, $port, $device_name, $command1);
			
			//читаю количество ключей в канале 0 после удаления.
			$res3=trim($this->sendCommand($ip_server, $port, $device_name, $command3));
			
			$res_out=$res_out.'<br>'.__('clear_key_result', array(
			':device_name' => $device_name,
			':door_name'=> $door_name, 
			':result'=>Model::Factory('Stat')->parser_1($res2), 
			':keycount_befor'=> Model::Factory('Stat')->parser_2($res1), 
			':keycount_after'=>Model::Factory('Stat')->parser_2($res3), 
			':during'=> round(microtime(true) - $time_start_local, 2)
			));
			
			
		}
		
			$res_out=$res_out.'<br>'.__('command_time_end').date('d.m.Y H:i:s');
			$this->last_command($res_out);
			
		return $res_out;
	}
		
		
	public function unlock_door_arr($dev, $command)// Команда Разблокировать устройство для указанных дверей.
	{
		//echo Debug::vars('782', $dev, $command); exit;
		$time_start=microtime(true);
		$res='';
		$res_out=__('unlock_door_log_title').'<br>'.__('command_time_start').date('d.m.Y H:i:s');
		
		foreach ($dev as $id_dev=>$value)
		{
			$a=$this->get_device_info($id_dev);
			$command1= $command. ' Door='.$a['id_reader'];
			$device_name=$a['device_name'];// device name
			$door_name=$a['door_name'];// device name
			$ip_server= $a['ip_server'];// ip server
			$port=$a['port'];// port
			$time_start_local=microtime(true);
			
			//выполнение команды
			$res2=$this->sendCommand($ip_server, $port, $device_name, $command1);
			
			$res_out=$res_out.'<br>'.__('comand_door_result', array(
			':device_name' => $device_name,
			':door_name'=> $door_name, 
			':command'=> $command, 
			':result'=>Model::Factory('Stat')->parser_1($res2), 
			':during'=> round(microtime(true) - $time_start_local, 2)
			));
		}
	
			$res_out=$res_out.'<br>'.__('command_time_end').date('d.m.Y H:i:s');
			$this->last_command($res_out);
			
		return $res_out;
	}
	
	public function load_card_arr($dev)// формирование списка для загрузки в устройство
	{
		$time_start=microtime(true);
		$res='';
		$res_out=__('load_card_log_title').'<br>'.__('command_time_start').date('d.m.Y H:i:s');
		foreach ($dev as $key=>$value)
		{
			$a=$this->get_device_info($key);
			$device_name=$a['device_name'];// device name
			$door_name=$a['door_name'];// device name
			$ip_server= $a['ip_server'];// ip server
			$port=$a['port'];// port
			$time_start_local=microtime(true);
			//$sql='execute procedure cardidx_refresh ('.$key.')';
			$sql='select COUNT(*) from cardidx cd
				where cd.id_dev='.$key;
			$countKeyForLoad=DB::query(Database::SELECT, DB::expr($sql))
			->execute(Database::instance('fb'))
			->get('COUNT');	
			
			$sql='update cardidx cd
				set cd.time_stamp=\'now\',
				cd.devidx=null
				where cd.id_dev in ('.$key.')';
			//echo Debug::vars('263', $sql); exit;
			$query = DB::query(Database::UPDATE, DB::expr($sql))
			->execute(Database::instance('fb'));
			//echo Debug::vars('65',$sql, $query, $countKeyForLoad ); exit;
			$res_out=$res_out.'<br>'.__('load_card_result', array(':device_name' => $device_name, ':door_name' => $door_name, ':during'=> round(microtime(true) - $time_start_local, 2), 'key'=>$key, 'countKeyForLoad'=>$countKeyForLoad));
		}
		$this->last_command($res_out);
		return $res_out;
	}
	
	
	public function checkStatus ($id_server=FALSE)// опрос контроллеров и занесение данных в базу. Опрашиваются контроллеры указанного ТС. 26.11.2018 эта функция устарела по своей сути... 
	{
		
		$a=$this->getdeviceList($id_server);// проверка 4.11.2018. Выборка только для указанного ТС. Передаются id контроллеров (а не точек прохода).
		//$ser=Model::factory('Stat')->GetOrder($id_server);// получил номер текущей операции опроса
		//echo Debug::vars('285', $id_server, $a); exit;
		if($a){
			foreach ($a as $key)
			{
				$this->getStatForOneController($key);
				
			}
			} else {


			}			
		//$res=Model::factory('Stat')->CloseOrder($ser);
		Model::factory('Stat')->ClearStat();
		//return $res;
		return;
	}
	
	public function getStatForOneController($key)// запись статистических данных в таблицу ST_DATA для указанного контроллера. Входные данные - $key - id контроллера, $ser - номер ордера сбора данных 
	{
		//echo Debug::vars('303', $key); exit;
		$a=$this->get_device_info($key);
		$device_name=$a['device_name'];// device name
				$ip_server= $a['ip_server'];// ip server
				$port=$a['port'];// port
				$id_dev=$key;
				$id_order=444;//$ser;
				$id_agent=1;
				$version=1;
				$reportstatus=2;
				$count_door_0=3;
				$count_door_1=4;
				$DBKeyCount0=5;
				$DBKeyCount1=6;
				$deviceconfig=10;// конфигурация устройства
				
				$command='reportstatus';//id=2
				$res[$key]['reportstatus']=$this->sendCommand($ip_server, $port, $device_name, $command);
				$this->stat_insert($id_order, $id_dev, $id_agent, $reportstatus, $res[$key]['reportstatus']);
				//Если reportstatus !== OK, то остальные данные заполняются сообщением unenanled
				$etalon='r77 OK';
				$pos=stripos($res[$key]['reportstatus'], $etalon);
				if($pos !== false) { 
								
					$command='getversion';// id=1
					$res[$key]['version']=$this->sendCommand($ip_server, $port, $device_name, $command);
					$this->stat_insert($id_order, $id_dev, $id_agent, $version, $res[$key]['version']);
					

					$command='getconfig';// id=1
					$res[$key]['config']=$this->sendCommand($ip_server, $port, $device_name, $command);
					$this->stat_insert($id_order, $id_dev, $id_agent, $deviceconfig, $res[$key]['config']);
					
					$command='getkeycount door=0';//3
					$res[$key]['count_door_0']=$this->sendCommand($ip_server, $port, $device_name, $command);
					$this->stat_insert($id_order, $id_dev, $id_agent,$count_door_0, $res[$key]['count_door_0']);
					
					$command='getkeycount door=1';//4
					$res[$key]['count_door_1']=$this->sendCommand($ip_server, $port, $device_name, $command);
					$this->stat_insert($id_order, $id_dev, $id_agent, $count_door_1, $res[$key]['count_door_1']);
				} else {
					$res_err='n/a';
					$this->stat_insert($id_order, $id_dev, $id_agent, $version, $res_err);
					$this->stat_insert($id_order, $id_dev, $id_agent,$count_door_0, $res_err);
					$this->stat_insert($id_order, $id_dev, $id_agent, $count_door_1, $res_err);
				}
		
	}
	
	public function insertStatusIdDev_arr($id_dev)// запись состояния контроллеров в БД для указанного масива точек прохода. Входные данные $id_dev - номер точки прохода
	{
		//echo Debug::vars('601', $id_dev); exit;
		$sql='select distinct d2.id_dev from device d
			join device d2 on d2.id_ctrl=d.id_ctrl and d2.id_reader is null and d2."ACTIVE">0
			where d.id_dev in ('.implode(",", $id_dev).')
			and d."ACTIVE">0';
		$query = DB::query(Database::SELECT, DB::expr($sql))
			->execute(Database::instance('fb'))
			->as_array();
	
		//echo Debug::vars('309', $id_dev, $query); exit;
		foreach ($query as $key=>$value)
		{
			$this->getStatForOneController(Arr::get($value, 'ID_DEV'));
		
		}
		return 'insertStatusIdDev_arr for id_dev '. implode(",",$id_dev);
	}

	
	public function getDBKeyCount($id)
	{
		$sql='select  count(distinct c.id_card) from ss_accessuser ssu
			join card c on ssu.id_pep=c.id_pep
			join access ac on ssu.id_accessname=ac.id_accessname
			where
			c."ACTIVE">0
			and (c.timeend>\'NOW\' or c.timeend is null)
			and c.id_cardtype in (1,2)
			and ac.id_dev='.$id;
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->get('COUNT');
		return $query;
	}
	
	public function getCardIDX($id_dev)//выборка количества карт для указанной точки прохода, полученные из таблицы cardidx
	{
		$sql='select count(*) from cardidx cd where cd.id_dev='.$id_dev;
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->get('COUNT');
			
		return $query;
	}
	
	
	
	public function stat_insert ($order, $id_dev, $id_agent, $id_param, $param)
	{
	
	//удаляю уже имеющиеся аналогичные данные
	$sql='delete from st_data where id_dev='.$id_dev.' and id_param='.$id_param;
	$query = DB::query(Database::DELETE, $sql)
		->execute(Database::instance('fb'));
	
	$sql='insert into st_data (id_order, id_dev, id_agent, id_param, facts) values ('.$order.', '.$id_dev.', '.$id_agent.', '.$id_param.', \''.$param.'\')';
	try
			{
			$query = DB::query(Database::INSERT, $sql)
			->execute(Database::instance('fb'));
			} catch (Exception $e) {
			}
	}
	
	
	//public function getdeviceList($id_server=FALSE)// 4.11.2018 список id_dev для указанного сервера. Выборка только контроллеров, а не точек прохода
	public function getdeviceList($page = 1, $perpage = 10, $filter=null)// 4.11.2018 список id_dev для указанного сервера. Выборка только контроллеров, а не точек прохода
	{
		$sql='select d.id_dev from device d where d.id_reader is null and d."ACTIVE">0 and d.id_devtype in (1,2)';
		
		$sql='select FIRST ' . $perpage . ' SKIP ' . ($page - 1) * $perpage . ' d.id_dev, d.id_server, d.id_devtype, d.netaddr, d.name, d."VERSION" from device d
            where d.id_reader is null and d."ACTIVE">0'.
			($filter? ' and d.name like \'%'.$filter.'%\'' : '').
			' ORDER BY  d.name';
		
		
		$query = DB::query(Database::SELECT, iconv('UTF-8','windows-1251',$sql))
			->execute(Database::instance('fb'))
			->as_array();
		
		return $query;
	}
	
	
	/**
	*Получение списка контроллеров и точек прохода
	* 17.05.2024
	*входной массив имеет формат (`id`, `title`, `parent`)
	*/
	public function getdeviceListForTree()	{
		
		$res=array();//результирующий массив
		$res2=array();//результирующий массив
		
		$sql='select d.id_dev, d.name, d.id_ctrl, d.id_reader  from device d
			where d.id_reader is null';
		
	
		try
		{
			$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();

			
		foreach ($query as $key=>$value)
		{
			//echo Debug::vars('58', $value); exit;
			$res[Arr::get($value, 'ID_DEV')]['id']=Arr::get($value, 'ID_DEV');
			$res[Arr::get($value, 'ID_DEV')]['title']=iconv('windows-1251','UTF-8', Arr::get($value, 'NAME'));
			$res[Arr::get($value, 'ID_DEV')]['parent']=0;
		}
	//echo Debug::vars('1081', $res, $query); exit;
			//добавляю точки прохода со ссылкой на родительский контроллер
	 	foreach ($query as $key=>$value)
		{
			
			$device=new Device(Arr::get($value,'ID_DEV'));
			$device->getChild();
			//echo Debug::vars('1088', $key, $value, Arr::get($value,'ID_DEV'), $device->getChild(), $device); exit;
			foreach($device->child as $key2=>$value2)
			{
				//echo Debug::vars('1091',$key2,$value2 );//exit;
				$door=new Door($value2);
				//echo Debug::vars('1094',$door);exit;
				$res[$value2]['id']=$door->id;
				$res[$value2]['title']=iconv('windows-1251','UTF-8', $door->name);
				$res[$value2]['parent']=Arr::get($value,'ID_DEV');
				
			}
			
			
			
		}	 
		
			//echo Debug::vars('1095', $res, '$res2', $res2); exit;
			return $res;
		} catch (Exception $e) {
			Log::instance()->add(Log::ERROR, $e);
			echo Debug::vars('1105 Fatal err tree', $e); exit;
		}
	}
	
	

	
	
	public function getDoorList($id_server=FALSE)// 28.03.2020 список id_dev точек прохода для указанного сервера.
	{
		$sql='select d.id_dev from device d where d.id_reader is null and d."ACTIVE">0 and d.id_devtype in (1,2)';
		
		$sql='select d2.id_dev from device d
            join device d2 on d.id_ctrl=d2.id_ctrl and d2.id_reader is not null
            where
            d.id_reader is null and d."ACTIVE">0
            and d.id_server='.$id_server;
			
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		$res='';
		foreach ($query as $key => $value)
		{
			$res[$value['ID_DEV']]=$value['ID_DEV'];
		}
		return $res;
	}
	
	
	
	public function synctime ($dev)
	{
		$time_start=microtime(true);
		$res='';
		$res_out=__('synctime_log_title').'<br>'.__('command_time_start').date('d.m.Y H:i:s');
		foreach ($dev as $id_dev=>$value)
		{
			
			$a=$this->get_device_info($id_dev);
			$command='synctime';
			$command1='getdevicetime';
			$device_name=$a['device_name'];// device name
			$door_name=$a['door_name'];// device name
			$ip_server= $a['ip_server'];// ip server
			$port=$a['port'];// port
			
			Log::instance()->add(Log::DEBUG, 'Device Read device time command: '.$ip_server.', '.$port.', '.$device_name.', '.$command1);
			//читаю время до синхронизации
			$time_start_local=microtime(true);
			$res1=trim($this->sendCommand($ip_server, $port, $device_name, $command1));
			Log::instance()->add(Log::DEBUG, 'Read device time result: :res ', array('res'=>trim($res1)));
			
			//команда на синхронизацию времени
			$res2=$this->sendCommand($ip_server, $port, $device_name, $command);
			
			//читаю время после синхронизации
			$res3=trim($this->sendCommand($ip_server, $port, $device_name, $command1));
			
			$res_out=$res_out.'<br>'.__('synctime_result', array('device_name' => $device_name, 'door_name'=> $door_name, 'time_befor'=> Model::Factory('Stat')->parser_2($res1), 'time_after'=>Model::Factory('Stat')->parser_2($res3), 'during'=> round(microtime(true) - $time_start_local, 2)));
		}
		
			$res_out=$res_out.'<br>'.__('command_time_end').date('d.m.Y H:i:s');
			//$_SESSION['res'][]=$res_out;
			//$_SESSION['res']=array();
			$this->last_command($res_out);
			
		return $res_out;
	}

	public function last_command($res)
	{
		$_SESSION['res'][4]=Arr::get(Arr::get($_SESSION, 'res', ''),3, '');
		$_SESSION['res'][3]=Arr::get(Arr::get($_SESSION, 'res', ''),2, '');
		$_SESSION['res'][2]=Arr::get(Arr::get($_SESSION, 'res', ''),1, '');
		$_SESSION['res'][1]=Arr::get(Arr::get($_SESSION, 'res', ''),0, '');
		$_SESSION['res'][0]=$res;
	
	}
	
	/*
	17.08.2023
	*/
	public function get_device_info ($id_dev)
	{	
		$sql='select d.id_dev, d.id_server, d.id_devtype, d.id_ctrl, d.id_reader, d.netaddr as IP, d.name, d."VERSION", d.flag, d."ACTIVE" as is_active, d.config, d.param, d.id_guide from device d
            where d.id_dev='.$id_dev;
		//echo Debug::vars('281', $sql); exit;
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			$res=array();
			
		return Arr::flatten($query);			
	
	}

		public function sendCommand ($server, $port, $device_name, $command)
	{
		//данные для авторизации
		$smes = 'r77 login name="3", password="3"';
		$smes1 = 'r77 enumdevices';
		if(isset($device_name)) 
		{
			$smes_command = 'r77 exec device="'.$device_name.'", command="'.$command.'"';
		} else {
			$smes_command = 'r77 '.$command;
		}
		
			
		
		//создаем сокет для подключения ТСП
		if (false == ($socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP))) {
			HTTP::redirect("Couldn't create socket, error code is: " . socket_last_error() .
			",error message is: " . socket_strerror(socket_last_error()));
			}
		
		// создаем соединение с сервером
		$connection = @socket_connect($socket, $server, $port);
		//if ($connection === false)      die("Cannot connect to server {$server}:{$port}");
		if ($connection === false)      HTTP::redirect('errorpage?err=Cannot connect to TS server '.$server .':'. $port.' in sendCommand function.');
		$reply = socket_read($socket,4096);
		
		//авторизация
		socket_write($socket, $smes."\r\n", strlen($smes."\r\n"));
		//получаем ответ
		$reply = socket_read($socket,4096);

		//$reply = socket_read($socket,4096);
		$res_command='ERR';	
		$i_count=0;

		while ($res_command == 'ERR' and $i_count<10)// до 10 попыток чтения данных из ячейки или получения сообщения об ошибке в ответ на команду
					{
						 //echo Debug::vars('616', $i_count); //exit;
						//send command
						if($trt= socket_write($socket, iconv('UTF-8','windows-1251',$smes_command."\r\n"), strlen(iconv('UTF-8','windows-1251',$smes_command."\r\n"))))
						{
							//получаем ответ. При этом надо убедитьсяб что это именно ответ на команду, а не пришедшее в то же время событие.
							$temp=socket_read($socket,4096);//прочитал ответ и записал в лог-файл для отладки
							//echo Debug::vars('621', $temp, $i_count); exit;
							if($temp)
							{
								//Kohana::$log->add(Log::INFO, 'Get answer in sendCommand function: '.$temp.', count '.$i_count);
								
								//ищу положительный ответ на команду
								
								$etalon='r77 OK';
								$pos=stripos($temp, $etalon);
								//echo Debug::vars('628', $pos, $i_count); exit;
								//Если ответ на запрос так и не полученб то в ответ выдавать Err; если ответ получен - то передать ответ.
									if($pos !== false) 
									{
										$res_command='OK';
										$answer=$temp;
										//echo Debug::vars('634'); exit;
									} else {
											$answer='Err';
											//echo Debug::vars('637', $i_count); //exit;
									}
							} else {
								$answer='Can\'t socket read '.socket_last_error();
							}
						} else {
							$answer='Can\'t socket write '.socket_last_error();
						}
						
						$i_count = $i_count + 1;
						
					}
		
		socket_close($socket);
		return $answer;
	}
	
		
	
	public function getServerList ()// *** удалить 4.06.2016
	{
		$sql='select * from server';
		$query = DB::query(Database::SELECT, $sql)
		->execute(Database::instance('fb'))
		->as_array();
		return $query;
	}

		
		
		public function getIdFromName($device_name)
		{
			
			$sql='select id_dev from device d where d.name=\''.iconv('windows-1251','UTF-8', trim($device_name)).'\'';
			//$sql='select id_dev from device d where d.name=\''.$device_name.'\'';
			$query = DB::query(Database::SELECT, iconv('UTF-8','windows-1251',$sql))
			->execute(Database::instance('fb'))
			->get('ID_DEV');
			return $query;
		}
			
		
		
		
		
		

}


