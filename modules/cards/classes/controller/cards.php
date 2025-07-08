<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cards extends Controller_Template
{

	public $id_type;
	public $template = 'template';
	
	public function before()
	{
		parent::before();
	}

	
	/**
	*9.04.2024 диспетчер режима отображения данных: какие карты отображать? (RFID, ГРЗ, или еще что-то там....)
	*Тип отображаемых данных хранится в параметрах сессии под именем identifier
	*/
	
	public function action_select()
	{
		//echo Debug::vars('29', $this->request->param('id')); exit;
		 $post=Validation::factory(array('key_type'=>trim($this->request->param('id'))));//определяю тип идентификатора, который надо выводить
		 $post->rule('key_type', 'not_empty')
				->rule('key_type', 'alpha_numeric');
				$cards=array();
		if($post->check())
		{
			switch(Arr::get($post, 'key_type')){
				
				case 'rfid':
					$this->session->set('identifier', '1');
				break;
				
				case 'grz':
					$this->session->set('identifier', '4');
				break;
				case 'uhf':
					$this->session->set('identifier', '5');
				break;
				
				
				default:
				
				break;
				
				
			}
		$this->action_index();	
		
		}
	
	}


	/** 2024 получить список карт с истекшим сроком дейсвтия
	*
	*/
	public function action_expired()
	{
		$cards = Model::factory('Card')->getExpired($this->user->id_orgctrl);
					//echo Debug::vars('51', $cards);//exit;
		$this->action_index($cards);
		
	}
	
	
	
	/**
	 * подготовка входных данных для поиска по идентификторам
	 * особенность: при поиске необоходимо проверять права оператора: можно ли ему показывать информацию по владельцу карты?
	 * если можно - то показывать все
	 * если нельзя - то надо показать результат поиска, но не давать прав на редактирование.
	 */
	
	public function action_search()
	{
		/*
		порядок проверки входящих значений определяется типов идентификатора
		тип идентификатора указан в сессии $this->session->get('identifier')
		*20.08.2024 поиск надо проводить по всем возможным вариантам
		*для этого из входногой строки надо сделать все допустимые варианты хранения номера идентификатора
		*в базе данных.
		*строка поиска RFID должна содержать строго цифры и буквы ABCDEF (от HEX).
		*т.к. формат входной неизвестен, то надо сделать преобразования DEC->формат хранения и HEX8->формат хранения
		*контрольная проверка: 007E3600 (0008271360) Коровицын.
		*программа должна находить Коровицына при введение в строку поиска следующих параметров:
		*007E3600
		*7E3600
		*0008271360
		*8271360
		*все эти варианты должны дать результат: найдена карта Коровина
		*регистрационный считыватель работает строго в формате DEC. Это должно быть строго указано в документации. Полученый номер преобразуется к формату
		*хранения в базе данных строго с учетом длины.
		*Проверка:
		*при регистрации 
		
		*/
		echo Debug::vars('22', $_POST, $this->session->get('identifier')); exit;
		$pattern = trim(Arr::get($_POST, 'q', null));// убрал лишние знаки вокруг строки поиска
		$this->session->set('search_card', $pattern);//параметры поиска мы сохраняем, чтобы повторно вывести в строке поиска.
		$temp=$pattern;
		$post=Validation::factory($_POST);
		$_key='';
		switch($this->session->get('identifier')){ //определяю тип идентификатора для поиска. параметр identifier передается в сессии
			case 1:// поиск надо вести в RFID
				if(Kohana::$config->load('system')->get('screenFormatRFID') == 2){ // если формат экрана 2 (DEC10), то проверяю что это число
					$post->rule('q', 'not_empty')
						->rule('q', 'digit')
						->rule('q', 'range', array(':value', 100, pow(2,32)))
						;
					if($post->check()){//если проверка на целое десятичное DEC выполнена, то преобразовываю его к формату базы данных
						if (Kohana::$config->load('system')->get('baseFormatRfid', 0) == 0){ //преобразование DEC к HEX8
							$_key=Model::Factory('Stat')->decDigitToHEX8(Arr::get($post, 'q'));//привожу формат DEC к HEX8
						}

						if (Kohana::$config->load('system')->get('baseFormatRfid', 0) == 1){ //преобразование DEC к 001A
							$_key=$idcard=Model::Factory('Stat')->decDigitTo001A(Arr::get($post, 'q'));//привожу формат HEX8 к 001A
						}
					
					} else {
						//переход на основную страницу с указанием, что проверка не пройдена
						$alert=__(implode(",", $post->errors('validateCard')));
						$alert=__('Указанный номер идентификатора не может быть найден.');
						$arrAlert[]=array('actionResult'=>2, 'actionDesc'=>$alert);
						Session::instance()->set('arrAlert',$arrAlert);
						//echo Debug::vars('115', $arrAlert);//exit;
						Session::instance()->set('arrAlert',$arrAlert);
						$this->redirect('cards');
					}
				}
				
				if(Kohana::$config->load('system')->get('screenFormatRFID') == 0){ // если формат экрана как формат базы, то это должен быть строго HEX в любом случае
				//проверка, что код для поиска содержит только цифры и значения HEX
				
					$post->rule('q', 'not_empty')
						->rule('q', 'regex', array(':value', '/^[A-F0-9]+$/'))
						->rule('idcard', 'min_length', array(':value', Kohana::$config->load('rfid')->get('min_length')))
						->rule('idcard', 'max_length', array(':value', Kohana::$config->load('rfid')->get('max_length')))
						;
						
					if($post->check()){
						$_key=Arr::get($post, 'q');
					
					} else {
						$alert=__(implode(",", $post->errors('validateCard')));
						$arrAlert[]=array('actionResult'=>2, 'actionDesc'=>$alert);
						Session::instance()->set('arrAlert',$arrAlert);
						//echo Debug::vars('137');exit;
						$this->redirect('cards');
					}						
				}
					//echo Debug::vars('138',$pattern,  $_key);exit;
					$temp=new Keyk();
					$temp->id_card=$_key;
					$temp->id_cardtype=1;
					$var2=$temp->search();

					//echo Debug::vars('144', $_key, $temp, $var2);exit;
					$this->action_index($var2);
	
			
			break;
			case 4://поиск ГРЗ	
				$post->rule('q', 'not_empty')
					->rule('q', 'regex', array(':value', '/^[A-F0-9]+$/'));
					if($post->check()){
						$temp=new Keyk();
						$temp->id_card=Arr::get($post, 'q');
						$temp->id_cardtype=4;
						$var2=$temp->search();
						echo Debug::vars('99', $_POST, $this->session->get('identifier'), $pattern, $var2); exit;
					//$this->session->set('search_card', $pattern);	//в поисковой строке будет ГРЗ без изменения
					$this->action_index($var2);	
						
				} else {
					
					$this->action_index();	
				}
			
			
			break;
			
			default:
				$this->action_index();	
			break;
			
		} 
	
		
	}
	
	/**21.08.2024 расширенный поиск карты
	*номер карты преобразуется в несколько форматов, и выполняется поиск.
	*/
	public function action_search_any()
	{
		/*
		порядок проверки входящих значений определяется типов идентификатора
		тип идентификатора указан в сессии $this->session->get('identifier')
		*20.08.2024 поиск надо проводить по всем возможным вариантам
		*для этого из входногой строки надо сделать все допустимые варианты хранения номера идентификатора
		*в базе данных.
		*строка поиска RFID должна содержать строго цифры и буквы ABCDEF (от HEX).
		*т.к. формат входной неизвестен, то надо сделать преобразования DEC->формат хранения и HEX8->формат хранения
		*контрольная проверка: 007E3600 (0008271360) Коровицын.
		*программа должна находить Коровицына при введение в строку поиска следующих параметров:
		*007E3600
		*7E3600
		*0008271360
		*8271360
		*все эти варианты должны дать результат: найдена карта Коровина
		*регистрационный считыватель работает строго в формате DEC. Это должно быть строго указано в документации. Полученый номер преобразуется к формату
		*хранения в базе данных строго с учетом длины.
		*Проверка:
		*при регистрации 
		
		*/
		//echo Debug::vars('224', $_POST, $this->session->get('identifier')); exit;
		$pattern = trim(Arr::get($_POST, 'q', null));// убрал лишние знаки вокруг строки поиска
		$this->session->set('search_card', $pattern);//параметры поиска мы сохраняем, чтобы повторно вывести в строке поиска.
		$temp=$pattern;
		$post=Validation::factory($_POST);
		$_key='';
		$_keys=array();
		switch($this->session->get('identifier')){ //определяю тип идентификатора для поиска. параметр identifier передается в сессии
			case 1:// поиск надо вести в RFID
			$post->rule('q', 'not_empty')// строка поиска не пуста
					->rule('q', 'max_length', array(':value', constants::RFID_MAX_LENGTH()+4))// длина строки поиска может длиннее чем формат в базе данных
					->rule('q', 'min_length', array(':value', constants::RFID_MIN_LENGTH()))
					->rule('q', 'regex', array(':value', '/^[A-F0-9]+$/'))
				;
				
					if($post->check()){//если проверка выполнена, то преобразовываю его к формату базы данных
					
					//выполняю "нормировку" номера - устанавливаю его длину 8 или 10 байт; 
					//Получаю переменную $_keyNormal длиной constants::RFID_MAX_LENGTH() (т.е. 8 или 10 байт
					//это будет первый элемент массива поиска: исходный номер.
					$_keyNormal= STR_PAD(ltrim(Arr::get($post, 'q'), 0), constants::RFID_MAX_LENGTH(), '0', STR_PAD_LEFT);
					$_keys[]=$_keyNormal;
					
						//если формат базы hex, то:
						//строка поиска может быть или DEC, или HEX.
						//если входной формат HEX, то преобразования делать не надо, т.к. форматы совпадают. 
						//если же входной формат DEC, то требуется преобразование DEC->HEX
						
						if (Kohana::$config->load('system')->get('baseFormatRfid', 0) == 0){ //преобразование номера к формату базы к HEX8
						//echo Debug::vars('252',Arr::get($post, 'q'),  $_keyNormal, $_keys, ctype_digit($_keyNormal)); exit;
							if(ctype_digit($_keyNormal)) $_keys[]=Model::Factory('Stat')->decDigitToHEX8($_keyNormal);//привожу формат DEC к HEX8
						}

						
						//если формат базы 001A, то:
						//строка поиска может быть или DEC, или HEX.
						//если входной формат DEC, то требуется преобразование DEC->001A
						//если входной формат HEX, то требуется преобразование HEX->001A
						if (Kohana::$config->load('system')->get('baseFormatRfid', 0) == 1){ //преобразование DEC к 001A
							//$_keys[]=$idcard=Model::Factory('Stat')->decDigitTo001A($_keyNormal);//привожу формат HEX8 к 001A
							//$_keys[]=$idcard=Model::Factory('Stat')->hexTo001A($_keyNormal);//привожу формат HEX8 к 001A
							$_keys[]=$idcard=Model::Factory('Stat')->decDigitTo001A($_keyNormal);//привожу формат HEX8 к 001A
							
						}
					
					} else {
						//переход на основную страницу с указанием, что проверка не пройдена
						$alert=__(implode(",", $post->errors('validateCard')));
						$alert=__('Указанный номер идентификатора не может быть найден.');
						$arrAlert[]=array('actionResult'=>2, 'actionDesc'=>$alert);
						Session::instance()->set('arrAlert',$arrAlert);
						//echo Debug::vars('115', $arrAlert);//exit;
						$this->redirect('cards');
					}
				
			
					//echo Debug::vars('284',$pattern,  $_keys);//exit;
					//в массиве $_keys хранится номера, которые надо найти в таблице card.
					$var2=array();
					foreach($_keys as $key => $value){
						
						//echo Debug::vars('283', $key, $value);
						$temp=new Keyk();
						$temp->id_card=$value;
						$temp->id_cardtype=1;
						//echo Debug::vars('287', $temp->search());//exit;
						if(count($temp->search())>0) $var2[]=Arr::get($temp->search(), 0);
					}
					

					//echo Debug::vars('144', $_key, $temp, $var2);exit;
					$this->action_index($var2);
	
			
			break;
			case 4://поиск ГРЗ	
				$post->rule('q', 'not_empty')
					->rule('q', 'regex', array(':value', '/^[A-F0-9]+$/'));
					if($post->check()){
						$temp=new Keyk();
						$temp->id_card=Arr::get($post, 'q');
						$temp->id_cardtype=4;
						$var2=$temp->search();
						echo Debug::vars('99', $_POST, $this->session->get('identifier'), $pattern, $var2); exit;
					//$this->session->set('search_card', $pattern);	//в поисковой строке будет ГРЗ без изменения
					$this->action_index($var2);	
						
				} else {
					
					$this->action_index();	
				}
			
			
			break;
			
			default:
				$this->action_index();	
			break;
			
		} 
	}
	
	/**
	$filter - массив с номерами идентификаторов, которые надо вывести на экран
	
	*/
	public function action_index($filter = null)
	{
		//echo Debug::vars('326', $this->user);//exit;
		//echo Debug::vars('46', $filter); exit;
		$this->id_type = $this->session->get('identifier', 1);//получил тип идентификатора для отображения

		$cards = Model::factory('Card');
		if(is_null($filter)){// если фильтра (списка) нет, то выбираю все, что разрешено авторизованному пользователю
		
		$q = $cards->getCountUser($this->user->id_orgctrl, iconv('UTF-8', 'CP1251', $filter), $this->id_type);//подсчет количества карт, доступных текущему пользователю. Это необходимо для правильного разбиения на страницы
		
		$list = $cards->getListUser($this->user->id_orgctrl, Arr::get($_GET, 'page', 1), $this->listsize, iconv('UTF-8', 'CP1251', $filter), $this->id_type);
		
		//$q=0;
		//$list=array();
		} else {

			$q=count($filter);
			$list=$filter;//todo надо сделать фильтрацию, отобрать только те номера, которые доступны текущему пользователю
		}
		
		

		$catdTypelist = $cards->getcatdTypelist();//формирую переменную, что затем передать ее во view
		
		//echo Debug::vars('55', $list ); //exit;	
		$fl = $this->session->get('alert');
		$this->session->delete('alert');
		
		$arrAlert = $this->session->get('arrAlert');
		$this->session->delete('arrAlert');
		
		$filter=$this->session->get('search_card');
		//для правильного отображения номера RFID в разделе поиска беру данные из сессии
				
		$this->template->content = View::factory('cards/list')
			->bind('cards', $list)
			->bind('cardsList', $list)
			->bind('catdTypelist', $catdTypelist)
			->bind('alert', $fl)
			->bind('arrAlert', $arrAlert)
			->bind('filter', $filter)
			;
			//echo View::factory('profiler/stats');
	}

	/**2.12.2024 Удаление идентификатора
	*@input id - номер RFID или ГРЗ
	*/
	
	public function action_delete()
	{
		//echo Debug::vars('72', $this->request->param('id')); exit;
		 $post=Validation::factory(array('key'=>trim($this->request->param('id'))));//провожу валидацию номера карты, который надо удалить
		 $post->rule('key', 'not_empty')
				->rule('key', 'alpha_numeric')
				->rule('key', 'min_length', array(':value', constants::RFID_MIN_LENGTH))
				->rule('key', 'max_length', array(':value', constants::RFID_MAX_LENGTH))
				;
			//->rule('key', 'regex', array(':value', '/^[A-F0-9]+$/'))
		if($post->check()){
			$key=new Keyk(Arr::get($post, 'key'));
			if($key->delCard()==0){
				$alert=__('cards.deletedOk', array(':id_card'=>$this->request->param('id')));
				$arrAlert[]=array('actionResult'=>constants::ALERT_SUCCESS, 'actionDesc'=>$alert);
			} else {
				$alert=__('cards.deletedErr', array(':id_card'=>$this->request->param('id')));
				$arrAlert[]=array('actionResult'=>constants::ALERT_ERROR, 'actionDesc'=>$alert);
			}
		} else {
			
			$alert=__('cards.deletedErr', array(':id_card'=>Arr::get($post, 'key'), ':mess'=>implode(",", $post->errors('validateCard'))));
			$arrAlert[]=array('actionResult'=>constants::ALERT_WARNING, 'actionDesc'=>$alert);
			Session::instance()->set('arrAlert',$arrAlert);
		}
		Session::instance()->set('arrAlert',$arrAlert);
		//echo Debug::vars('401', $arrAlert);exit;
		//echo Debug::vars('402', $post);exit;
		$this->redirect('cards');
	}
	
	
	/**	11.02.2024 Просмотр свойств идентификатора
	*@input id - номер RFID или ГРЗ 
	*/
	public function action_edit()
	{
		
		//$id=trim($this->request->param('id'));
		
		 $post=Validation::factory(array('key_type'=>trim($this->request->param('id'))));//определяю тип идентификатора, который надо выводить
		 $post->rule('key_type', 'not_empty')
				->rule('key_type', 'alpha_numeric')
				->rule('key', 'min_length', array(':value', constants::RFID_MIN_LENGTH))
				->rule('key', 'max_length', array(':value', constants::RFID_MAX_LENGTH))
				;
		if($post->check())
		{
		$mode='edit';
		$key=new Keyk(Arr::get($post, 'key_type'));
		//echo Debug::vars('402', $key->check(constants::idRfid));
		//echo Debug::vars('403', $key->check(constants::idGrz));exit;
		if(!($key->check(constants::idRfid) OR $key->check(constants::idGrz))) $this->redirect('cards');

		$loads = Model::factory('Card')->getLoads(Arr::get($post, 'key_type'));
				
		
		$fl = $this->session->get('alert');
		$this->session->delete('alert');
		
		$arrAlert = $this->session->get('arrAlert');
		$this->session->delete('arrAlert');
		Log::instance()->add(Log::NOTICE, '408'. Debug::vars($key));
		$this->template->content = View::factory('cards/card')
			->bind('key', $key)//передаю key как класс
			->bind('catdTypelist', $catdTypelist)
			->bind('alert', $fl)
			->bind('arrAlert', $arrAlert)
			->bind('mode', $mode)
			->bind('filter', $filter)
			//->bind('pagination', $pagination)
			;
		} else {
			
			$this->redirect('cards');
			
		}
	}
	
	
	/*
	11.02.2024 
	Просмотр загрузки идентификатора в контроллеры
	*/
	public function action_load()
	{
		$id=$this->request->param('id');
		$post=Validation::factory(array('key'=>trim($this->request->param('id'))));//провожу валидацию номера карты, информацию по которой необходимо вывести
		 $post->rule('key', 'not_empty')
				->rule('key', 'alpha_numeric')
				->rule('key', 'min_length', array(':value', constants::RFID_MIN_LENGTH))
				->rule('key', 'max_length', array(':value', constants::RFID_MAX_LENGTH))
				;
			//->rule('key', 'regex', array(':value', '/^[A-F0-9]+$/'))
		if($post->check()){
		
			$mode='edit';
			$key=new Keyk($id);
			
			$loads = Model::factory('Card')->getLoads($id);
			
			$fl = $this->session->get('alert');
			$this->session->delete('alert');
			
			$this->template->content = View::factory('cards/load')
				->bind('key', $key)
				->bind('catdTypelist', $catdTypelist)
				->bind('alert', $fl)
				->bind('mode', $mode)
				->bind('filter', $filter)
				->bind('loads', $loads)//данные о заргузке карты в контроллеры
				->bind('pagination', $pagination);
		} else {
			$alert=__('card.errDataForUpdate', array(':mess'=>implode(",", $post->errors('upload'))));
			$arrAlert[]=array('actionResult'=>2, 'actionDesc'=>$alert);
			Session::instance()->set('arrAlert',$arrAlert);
			$this->redirect('cards');
		}
		
	}
	
	
/*
	11.02.2024 
		Просмотр истории идентификатора
	*/
	public function action_history()
	{
		 $post->rule('key', 'not_empty')
				->rule('key', 'alpha_numeric')
				->rule('key', 'min_length', array(':value', constants::RFID_MIN_LENGTH))
				->rule('key', 'max_length', array(':value', constants::RFID_MAX_LENGTH))
				;
			//->rule('key', 'regex', array(':value', '/^[A-F0-9]+$/'))
		if($post->check()){
			$id=$this->request->param('id');
			
			$mode='edit';
			$key=new Keyk($id);
			
			$loads = Model::factory('Card')->getLoads($id);
			
			$fl = $this->session->get('alert');
			$this->session->delete('alert');
			
			$this->template->content = View::factory('cards/history')
				->bind('key', $key)
				->bind('catdTypelist', $catdTypelist)
				->bind('alert', $fl)
				->bind('mode', $mode)
				->bind('filter', $filter)
				->bind('loads', $loads)//данные о заргузке карты в контроллеры
				->bind('pagination', $pagination);
		} else {
			
			$this->redirect('cards');
		}
	}
	
	
	
	
	
	/*
	11.02.2024 
	Просмотр свойств идентификатора
	11.08.2024 добавлена обработка свойства rfidmode - типа идентификатора
	*/
	public function action_savecard()
	{
		//echo Debug::vars('106', $_POST); exit;
		$validation=Validation::factory($_POST);
		
		$validation->rule('idcard','not_empty') 
					->rule('carddatestart','not_empty')
					->rule('carddateend','not_empty')
					//->rule('cardisactive','not_empty')
					//->rule('id_cardtype','not_empty')
					->rule('id','not_empty')
					->rule('note','max_length', array(':value', 50))
			;
		if($validation->check()){
			//обновление данных карты
			$key=new Keyk(Arr::get($validation, 'idcard'));
			$key->timestart=Arr::get($validation, 'carddatestart');
			$key->timeend=Arr::get($validation, 'carddateend');
			$key->note=Arr::get($validation, 'note');
			$key->rfidmode=Arr::get($validation, 'rfidmode');
			$key->is_active=0;
			if(Arr::get($validation, 'cardisactive') == 'on') $key->is_active=1;
			
			if($key->update()==0){
				
				$alert=__('card.updateOk', array(':idcard'=>Arr::get($validation, 'idcard')));
				$arrAlert[]=array('actionResult'=>0, 'actionDesc'=>$alert);
			} else {
				$alert=__('card.updateErr', array(':idcard'=>Arr::get($validation, 'idcard')));
				$arrAlert[]=array('actionResult'=>2, 'actionDesc'=>$alert);
			}
			
		} else {
			
			//отказ в обновлении карты
			$alert=__('card.errDataForUpdate', array(':mess'=>implode(",", $validation->errors('upload'))));
			$arrAlert[]=array('actionResult'=>2, 'actionDesc'=>$alert);
			//echo Debug::vars('316 valid err', $arrAlert); exit;
		}
		Session::instance()->set('arrAlert',$arrAlert);	
		$this->redirect('cards/edit/'.Arr::get($validation, 'idcard'));
	}
	
	
	/*
	11.01.2024
	Повторная загрузку карты контроллеры
	*/
	public function action_reload()
	{
		$id=$this->request->param('id');
		$card = Model::factory('Card')->getCard($id);
		
		Model::factory('Card')->reload($id);
		
		Session::instance()->set('alert', __('card.reloadOk', array(':id_card'=>$id)));
		$this->redirect('cards/load/'.$id);
	}
	
}
