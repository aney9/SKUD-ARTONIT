<?php

return array
(
	'contCount'						=> 'Найдено всего :totalCount контактов. Будут показаны первые :contCount контактов. При необходимости пользуйтесь поиском.',
	'npp'						=> '№ п/п',
	'id_dev'						=> 'id по базе данных СКУД',
	'identity'						=> 'Идентификаторы',
	'Lic'						=> 'Лицензия: Lic',
	'welcome'						=> 'Добро пожаловать',
    'settings'						=> 'Настройки',
    'logout'            			=> 'Выход',
	'home'							=> 'Домой',
	'companies'						=> 'Организации',
	'objects'						=> 'Объекты',
	'contacts'						=> 'Контакты',
	'contacts'						=> 'Контакты',
	'cards'							=> 'Карты',
	'users'							=> 'Пользователи',
	'roles'							=> 'Роли',
	'tip.edit'						=> 'Редактировать',
	'tip.acl'						=> 'Права доступа',
	'tip.delete'					=> 'Удалить',
	'tip.restore'					=> 'Восстановить',
	'tip.view'						=> 'Посмотреть',
	'tip.fired'						=> 'Уволить',
	'tip.notAllowed'				=> 'Нет прав',
	
	
	'search'						=> 'Поиск',
	'yes'							=> 'Да',
	'no'							=> 'Нет',
	'eventlog'						=> 'Журнал',
    'actions'                       => 'Действия',
	
	'system.version'				=> 'Ver.',
	
	'eventlog.alarmlog'				=> 'Нарушения режима',
	'eventlog.full'					=> 'Все события',
	'eventlog.title'				=> 'Журнал событий',
	'eventlog.title_alarm'			=> 'Журнал тревожных событий',
	'eventlog.select'				=> 'Выбор',
	'eventlog.photo'				=> 'Фото',
	'eventlog.event_id'				=> 'ID события',
	'eventlog.event'				=> 'Событие',
	'eventlog.source'				=> 'Источник',
	'eventlog.timestamp'			=> 'Дата/время события',
	'eventlog.card_id'				=> 'ID карты',
	'eventlog.info'					=> 'Инфо',
	'eventlog.load_date'			=> 'Дата загрузки карты',
	'eventlog.tab_num'				=> 'Табельный номер',
	'eventlog.org_parent'			=> 'Родительская организация',
	'eventlog.error_maybe'			=> 'Анализ события',
	'eventlog.to_do'				=> 'Выполненные действия',
	'eventlog.accessname'			=> 'Категория доступа пользователя',
	'eventlog.user'					=> 'ФИО',
	'eventlog.analityc_0'			=> 'Событие обработано правильно.',
	'eventlog.analityc_1'			=> 'Ошибка! Карта НЕ имеет право прохода через эту точку прохода!',
	'eventlog.analityc_2'			=> 'Ошибка! Карта имеет право ходить через эту точку прохода!',
	'eventlog.analityc_3'			=> 'Событие обработано правильно.',
	'eventlog.analityc_4'			=> 'Событие обработано правильно.',
	'eventlog.analityc_5'			=> 'Событие обработано правильно. На момент прохода карта не загружена в контроллер.',
	'eventlog.analityc_-1'			=> 'Анализ события не производится.',
	'eventlog.day_for_list'			=> 'Вывести данные за последние часы',
	'eventlog.count_event_alarm'	=> 'Найдено записей с тревожными событиями: ',
	'eventlog.count_event_all'		=> 'Найдено записей с событиями: ',
    'eventlog.filters.button'       => 'Фильтры <span style="font-size: 18px">&#8661;</span>',
    'eventlog.filters.submit'       => 'Применить фильтр',

	'queue.full'				=> 'Очередь загрузки',
	'queue.short'				=> 'Очередь',
	'queue.title'				=> 'Список очередей загрузок в контроллеры',
	'queue.title_list'			=> 'Список загрузки',
	'queue.door_name'			=> 'Точка прохода',
	'queue.door_count'			=> 'Количество карт в очереди загрузки, шт.',
	'queue.door_id'				=> 'ID точки прохода',
	'queue.door_isactive'		=>'Дверь активна',
 	'queue.controller_name'		=>'Название контроллера',
 	'queue.controller_isactive'	=>'Контроллер активен',
	'queue.controller_id'		=>'ID контроллера',
 	'queue.desc'				=>'Прим.',
 	'queue.list'				=>'Лист загрузки',
	'queue.id_card'				=>'Код карты',
	'queue.id_cardindev'		=>'Код карты',
	'queue.id_dev'				=>'Код карты',
	'queue.load_result'			=>'Результат загрузки',
	'queue.attempt'				=>'Количество попыток',
	'queue.count_attempt'		=>'Количество попыток записи',
	'queue.desc_err'			=>'Сообщения о загрузке',
	'queue.list_card_for_load_mess'	=>'Список карт для записи в контроллеры (с сообщениями об ошибках)',
	'queue.list_card_for_load_common'	=>'Список карт для записи в контроллеры. Сводные данные.',
	'queue.list_card_for_del_common'	=>'Список карт для удаления из контроллеров. Сводные данные.',
	'queue.list_card_for_del_empty'	=>'Нет карт для удаления',
	'queue.list_card_for_load_empty'	=>'Нет карт для записи',
	'queue.desc_1'				=>'При штатной (правильной) работе системы очередь загрузки должна быть пустой, не содержать записей.<br>Карты не будут загружаться в  устройства если:<ol>
		<li>устройство состояние имеет значение "Не активно".</li>
		<li>возникли ошибки в процессе загрузки карт в устройства. Сообщения об ошибках отображены в таблице ":tables" в разделе ":column". Для успешной загрузки карт ошибки необходимо устранить.</li>
		<li>количество попыток записи превысило заданное значение. Количество попыток записи указано в таблицах в разделе ":attempt".</li>
		</ol>',
	'stat.queue.no_result'	=>'Сообщений о результатах загрузки нет.',
	
	
	'stat.is_active'=>'Активно',
	'stat.not_is_active'=>'Неактивно',
	
	
	
	
	
	'queue.action_name'			=>'Операция',
	'queue.action_1'			=>'Записать',
	'queue.action_2'			=>'Удалить',
	'queue.count_filter'		=>'Найдено записей: ',
	'queue.start_load_controller'		=>'Загрузить карты в выбранный контроллер',
	'queue.stop_load_controller'	=>'Остановить загрузку карт в выбранный контроллер',
	'queue.start_load_card_in_controller' =>'Загрузить выбранные карты в контроллер',
	'queue.stop_load_card_in_controller' =>'Прекратить загрузку выбранных карты в контроллер',
	'queue.form.header1'		=>'Всего очередей ',
	
	'stat'						=>'Статистика',
	'stat.about'				=>'О системе',
	'stat.title'				=>'Отчет о системе',
	'stat.title.common'			=>'Общая информация о системе',
	'stat.title.que_mess'		=>'Состояние очереди загрузки',
	'stat.title.que_but'		=>'Очередь загрузки',
	'stat.title.header1'		=>'Очередь загрузки',
	'stat.title.header2'		=>'Очередь загрузки',
	
	
	
	'stat.header1'				=>'Параметр',
	'stat.header2'				=>'Значение',
	'stat.header5'				=>'Контактов',
	'stat.header8'				=>'Карт',
	'stat.header11'				=>'Категорий доступа',
	'stat.header14'				=>'Контроллеров',
	'stat.header17'				=>'Точек прохода',
	'stat.card_count'			=>'Количество карт в очереди',
	'stat.result_load'			=>'Результат загрузки',
	'stat.door_is_active'		=>'Активность точки прохода',
	'stat.header20'				=>'ID точки прохода',
	'stat.header21'				=>'Точек прохода',
	'stat.count_people_na'		=>'Из них неактивных',
	'stat.event_err_count'		=>'Количество ошибок в работе системы за последние 24 часа',
	'stat.event_card_count'		=>'Количество событий (с картами) за последние 24 часа',
	
	
	'stat.form1'				=>'О системе',
	'stat.form2'				=>'Контроллеры',
	'stat.form3'				=>'События',
	'stat.form4'				=>'Сохранить отчет в файл',
	
	
	'stat.device.title'			=>'Информация о контроллерах СКУД',
	'stat.device.id_door'		=>'ID точки прохода',
	'stat.device.door_name'		=>'Точка прохода',
	'stat.device.door_card_count'		=>'Количество карт',
	
	'stat.events.title'			=>'Информация о событиях',
	'stat.events.header1'		=>'ID точки прохода',
	'stat.events.header2'		=>'Точка прохода',
	'stat.events.header3'		=>'Всего событий с картой',
	'stat.events.header4'		=>'Аварийных событий',
	'stat.events.header5'		=>'% ошибок',
	'stat.common.desc_1'		=>'События типа :eventtype.',
	'stat.common.desc_2'		=>'Количество событий, не соответсвующих категориям доступа.',
	'stat.common.desc_3'		=>'События с датой, больше текущей, могли попасть в систему из-за неправильной установки часов контроллера СКУД. Эти события рекомендуется удалить.',
	'stat.common.desc_4'		=>'Карт с кодами "0000000000" в базе данных быть не должно. Если они имеются, то обратитесь к разработчику за консультацией.',
	
	'stat.event_in_future'		=>'Количество событий с датой больше текущей.',
	'stat.card_as_null'			=>'Карты с кодами "0000000000"',
	'stat.filename_no_ver'		=>'Файл отсутствует или не найден по указанному пути (:path)',
	'stat.filename_no_size'		=>'Нет данных.',
	'stat.app_name'				=>'Название приложения',
	'stat.app_version'			=>'Версия приложения',
	'stat.app_size'				=>'Размер приложения',
	'stat.app_about'			=>'Информация о приложениях',
	
	'event.desc.46'				=>'"Неизвестная карточка"',
	'event.desc.47'				=>'"Сотрудник не пропущен по времени"',
	'event.desc.48'				=>'"Сотрудник не пропущен повторно"',
	'event.desc.50'				=>'"Действительная карточка"',
	'event.desc.65'				=>'"Недействительная карточка"',
	
	
	
	
	'stat.report.header1'		=>'Отчет о состоянии системы СКУД Артонит Сити',
	'stat.report.header2'		=>'www.artonit.ru',
	
	'report.title'				=> 'Отчет Учет рабочего времени для контакта :surname :name  :patronymic за период c :timefrom по :timeTo',
	'report.date'				=> 'Дата',
	'report.org'				=> 'Отдел',
	'report.pepname'			=> 'ФИО',
	'report.time_in'			=> 'Пришел',
	'report.lateness'			=> 'Опоздал',
	'report.time_out'			=> 'Ушел',
	'report.deviation'			=> 'Недоработал',
	'report.time_work'			=> 'Пробыл на работе',
	'report.fio'				=> ':surname :name :patronymic',
	'report.doorlist.title'				=> 'Отчет перечень точек прохода для контакта :surname :name  :patronymic',
	'report.datestamp'			=>'Отчет подготовлен :timestamp',
	'report.datestart'			=>'Дата начала отчета',
	'report.dateend'			=>'Дата завершения отчета',
	
	
	'sidebar.companieslist'			=> 'Список организаций',
	'sidebar.addcompany'			=> 'Добавить организацию',
    'sidebar.objectslist'			=> 'Список объектов',
    'sidebar.addobject'			    => 'Добавить объект',
	'sidebar.groups'				=> 'Группы организаций',
	'sidebar.groupadd'				=> 'Добавить группу',
	'sidebar.contactslist'			=> 'Список контактов',
	'sidebar.addcontact'			=> 'Добавить контакт',
	'sidebar.cardslist'				=> 'Список карт',
	'sidebar.userlist'				=> 'Список пользователей',
	'sidebar.adduser'				=> 'Добавить пользователя',
    'sidebar.roleslist' 			=> 'Список ролей',
    'sidebar.addrole'			    => 'Добавить роль',
	'sidebar.stats.about'			=> 'Отчет о системе',
	'sidebar.stats.device'			=> 'Точки прохода',
	'sidebar.stats.events'			=> 'Статистика по событиям',

	
	'groups.title'					=> 'Группы организаций',
	'groups.id'						=> 'Код',
	'groups.name'					=> 'Наименование',
	'groups.desc'					=> 'Описание',
	'groups.qty'					=> 'Кол-во организаций',
	'groups.action'					=> 'Действия',
	'groups.deleted'				=> 'Группа удалена',
	'groups.none'					=> 'Группы не найдены',
	'groups.add'					=> 'Создать новую группу',
	'groups.confirmdelete'			=> 'Вы действительно хотите удалить группу?',

	'group.title'					=> 'Группа',
	'group.name'					=> 'Наименование',
	'group.emptyname'				=> 'Введите наименование группы',
	'group.description'				=> 'Описание',
	'group.choose'					=> 'Не выбрана',
	'group.saved'					=> 'Группа создана',
	'group.updated'					=> 'Группа сохранена',
	'group.common'					=> 'Параметры',
	'group.list'					=> 'Организации',
	'group.acl'						=> 'Права доступа',
	'group.ingroup'					=> 'Уже в группе',
	'group.available'				=> 'Доступны',
	'group.include'					=> '&lt; Включить',
	'group.includeall'				=> '&lt;&lt; Включить все',
	'group.exclude'					=> 'Исключить &gt;',
	'group.excludeall'				=> 'Исключить все &gt;&gt;',
	'group.listsaved'				=> 'Изменения сохранены',
	'group.newgroup'				=> 'Новая группа',
	'group.acltitle'				=> 'Права доступа для группы',
	'group.aclsaved'				=> 'Права доступа сохранены',

	'acl.userid'					=> 'Код',
	'acl.username'					=> 'Пользователь',
	'acl.nousers'					=> 'Пользователи не найдены',
	'acl.view'						=> 'Просмотр',
	'acl.edit'						=> 'Изменение',
	'acl.addnew'					=> 'Создание',
	'acl.delete'					=> 'Удаление',
	'acl.group'						=> 'Группа',
	'acl.confirmdelete'				=> 'Вы действительно хотите удалить пользователя?',
	'acl.groupwork'					=> 'Работа с организациями',
	'acl.contactwork'				=> 'Работа с контактами',
	'acl.cardwork'					=> 'Работа с картами',

	'users.title'					=> 'Пользователи',
	'users.username'				=> 'Имя пользователя',
	'users.email'					=> 'Электронная почта',
	'users.fullname'				=> 'Имя, фамилия',
	'users.action'					=> 'Действия',
	'users.confirmdelete'			=> 'Вы действительно хотите удалить пользователя?',

	'user.title'					=> 'Пользователь',
	'user.emptyname'				=> 'Введите имя',
	'user.emptypatronymic'			=> 'Введите отчество',
	'user.emptypost'				=> 'Введите должность',														   
	'user.login'					=> 'Имя пользователя',
	'user.password'					=> 'Пароль (оставьте поле пустым, если не хотите менять пароль',
	'user.saved'					=> 'Пользователь сохранен',
	'user.deleted'					=> 'Пользователь удален',
	'user.saveerror'				=> 'Ошибка сохранения',
	'user.data'						=> 'Персональные данные',
	'user.acl'						=> 'Права доступа',

	'contacts.title'				=> 'Контакты',
	'contacts.code'					=> 'Табельный номер',
	'contacts.name'					=> 'Имя, Фамилия',
	'contacts.post'					=> 'Должность',
	'contacts.company'				=> 'Организация',
	'contacts.action'				=> 'Действия',
	'contacts.phone'				=> 'Телефон',
	'contacts.empty'				=> 'Поиск не дал результатов. Укажите фамилию для поиска.',
	'contacts.confirmdelete'		=> 'Вы действительно хотите удалить контакт?',
	'contacts.confirmSetNotActive'	=> 'Контакт будет уволенный.\r\nКонтакт можно восстановить в разделе Контакты - Уволенные.',
	'contacts.restore'				=> 'Вы действительно хотите восстановить контакт?',

	'contact.title'					=> 'Контакт',
	'contact.surname'				=> 'Фамилия',
	'contact.surname'				=> 'Фамилия',
	'contact.name'					=> 'Имя',
	'contact.patronymic'			=> 'Отчество',
	'contact.datebirth'				=> 'Дата рождения',
	'contact.numdoc'				=> 'Номер и серия документа',
	'contact.datedoc'				=> 'Дата выдачи документа',
	'contact.workstart'				=> 'Начало рабочего дня',
	'contact.workend'				=> 'Окончание рабочего дня',
	'contact.active'				=> 'активный',
	'contact.flag'					=> 'уровень доступа',
	'contact.peptype'				=> 'директор',
	'contact.post'					=> 'Должность',
	'contact.login'					=> 'Логин',
	'contact.password'				=> 'Пароль',
	'contact.tabnum'				=> 'Табельный номер',
	'contact.emptysurname'			=> 'Введите фамилию',
	'contact.emptyname'				=> 'Введите имя',
	'contact.emptypatronymic'		=> 'Введите отчество',
	'contact.emptypost'				=> 'Введите должность',											   
	'contact.emptydatebirth'		=> 'Введите дату рождения',
	'contact.wrongdatebirth'		=> 'Введите дату рождения в формате "ДД.ММ.ГГГГ"',
	'contact.emptydatedoc'			=> 'Введите дату выдачи документа',
	'contact.wrongdatedoc'			=> 'Введите дату выдачи документа в формате "ДД.ММ.ГГГГ"',
	'contact.emptyworkstart'		=> 'Введите время начала рабочего дня',
	'contact.wrongworkstart'		=> 'Введите время начала рабочего дня в формате "ЧЧ:ММ"',
	'contact.emptyworkend'			=> 'Введите время окончания рабочего дня',
	'contact.wrongworkend'			=> 'Введите время окончания рабочего дня в формате "ЧЧ:ММ"',
	'contact.emptytabnum'			=> 'Введите табельный номер',
	'contact.emptylogin'			=> 'Введите логин',
	'contact.emptypassword'			=> 'Введите пароль',
	'contact.wrongdate'				=> 'Неверная дата',
	'contact.wrongtime'				=> 'Неверное время',
	'contact.saved'					=> 'Контакт сохранен',
	'contact.updated'				=> 'Контакт обновлен',
	'contact.deleted'				=> 'Контакт удален',
	'contact.setNotActiveOK'		=> 'Контакт :surname :name :patronymic отмечен как УВОЛЕННЫЙ',
	'contact.setNotActiveErr'		=> 'Ошибка при попытке пометить контакт :surname :name :patronymic как УВОЛЕННЫЙ',
	'contact.deleteOK'				=> 'Контакт :surname :name :patronymic удален',
	'contact.deleteErr'				=> 'Ошибка при удалении контакта :surname :name :patronymic',
	'contact.setIsActiveOK'			=> 'Контакт :surname :name :patronymic помечен как активный',
	'contact.setIsActiveErr'		=> 'Ошибка при попытке пометить контакт :surname :name :patronymic как АКТИВНЫЙ',
	'contact.titleEditContact'		=> 'Редактирование контакта :surname :name :patronymic',
	'contact.titleEditContactAddId'		=> 'Добавление идентификатора  контакту :surname :name :patronymic',
	'contact.titleCardList'			=> 'Список идентификаторов контакта :surname :name :patronymic',
	'contact.titlefiredContact'		=> 'Просмотр удаленного контакта :surname :name :patronymic',
	
	'contacts.compareacl'			=>'Категория доступа',
	'contact.worktime'			=>'Рабочее время',
	
	
	'contact.email'					=> 'Адрес электронной почты',
	'contact.company'				=> 'Организация',
	'contact.common'				=> 'Свойства',
	'contact.pay'					=> 'Оплаты',
	'contact.history'				=> 'Журнал событий',
	'contact.history_24'			=> 'Журнал событий за последние сутки',
	'contact.card'					=> 'Карта',
	'contact.cardlist'				=> 'Список карт',
	'contact.cardid'				=> 'Код идентификатора',
	'contact.cardstore'				=> 'Запомнить код',
	'contact.new'					=> 'Новый контакт',
	'contact.acl'					=> 'Категории доступа',
	'contact.grz'					=> 'Государтсвенный регистрационный знак',
	'contact.grz_model'				=> 'Марка, модель',
	'contact.note'					=> 'Служебные записи',
	'contact.addOK'					=> 'Новый контакт добавлен успешно',
	'contact.updateOk'				=> 'Контакт обновлен успешно',
	'contact.key_occuped'			=>'Отказ в выдаче карты. Карта :idcard зарегистрирована на контакт '.HTML::anchor('guests/edit/:id_pep/guest_mode', ':surname :name :patronymic'),
	'contact.wait_hex8_number'		=>'Ожидаю ввод карты в формате HEX (например, 0A5E9704)',
	'contact.wait_dec10_number'		=>'Ожидаю ввод карты в формате DEC (десятичное числа до 10 цифр)',
	'contact.wait_001A_number'		=>'Ожидаю ввод карты в формате 001A (6 знаков HEX и окончание 001A. Например, 15A699001A)',
	'contact.wait_001A_number_'		=>'Ожидаю ввод карты в формате 001A ('.constants::MAX_VALUE_001A.' знаков HEX и окончание 001A. Например, 15A699001A)',
	'contact.wait_not_point_number'	=>'Ошибка! Проверьте настройки формата регистрационного считывателя.',
	'contact.check_reg_device_setting'		=>'Проверьте настройки регистрационного комплекта. Ожидается настройка HEX либо DEC',
	'contact.addRfidOk'				=>'Регистрация карты :id_card выполнена успешно',
	'contact.validKeyErr'			=>'Ошибка валидации пользователя с id_pep :id_pep. :desc',
	
	
	'contacts.id_pep'				=>'id_pep',
	'contacts.titleSearch'			=>'Результат поиска по фильтру \':filter\'',
	'contacts.count_identificator_rfid'			=>'Наличие идентификатора RFID',
	'contacts.count_identificator_grz'			=>'Наличие идентификатора ГРЗ',
	'contacts.host'			=>'Быстрая регистрация',
	
	'guests'						=> 'Гости',
	'guest.new'						=> 'Добавить нового гостя',
	'sidebar.guestslist'			=> 'Список гостей',
	'sidebar.addguest'				=> 'Добавить гостя',
	'guests.regcard'				=> 'Регистрация карты',
	'guests.title'					=> 'Список гостей',
	'guests.note'					=> 'Прим.',
	'guests.config_title'			=> 'Конфигурация режима Гость',
	'guest.dateregistration'		=>'Дата регистрации гостя',
	'guest.title'					=>'Гость',
	'guest.forceexit'				=>'Отметка о выходе',
	'guest.reissue'					=>'Выдать карту',
	'guest.reissuealert'			=>'Отметка о выходе поставлена успешно',
	'guest.titleinArchive'			=>'Гость в архиве',
	'guests.titleinArchive'			=>'Список гостей в архиве',
	'guest.registration'			=>'Регистрация гостя',
	'guest.confirmdelete'			=>'Будут удалены гость и история его посещений. Удалить?',
	
	'guest.addOK'					=>'Гость :surname :name :patronymic табельный номер :tabnum зарегистрирован успешно.',
	'guest.addErr'					=>'Гость :surname :name :patronymic не зарегистрирован. Ошибка.',
	
	'guest.adddocOK'				=>'Номер документа :numdoc для :surname :name :patronymic зарегистрирован успешно.',
	'guest.adddocErr'				=>'Номер документа :numdoc для :surname :name :patronymic не зарегистрирован. Ошибка.',
	
	'guest.forceexitOK'				=>'Отметка о выходе для гостя :surname :name :patronymic поставлена успешно.',
	'guest.forceexitErr'			=>'Отметка о выходе для гостя :surname :name :patronymic НЕ поставлена.',
	
	'guest.addRfidOk'				=>'Карта :id_card зарегистрирован успешно.',
	'guest.addRfidErr'				=>'Карта :id_card не зарегистрирована в базе данных. Ошибка.',
	
	'guest.delOnTabNumOk'			=>'Гость с табельным номером :tabnum удален успешно.',
	'guest.delOnTabNumErr'			=>'Гость с табельным номером :delOnTabNum не удален. Ошибка.',
	
	'guest.addTabNumOk'				=>'Табельный номер для  :surname :name :patronymic зарегистрирован успешно :tabnum.',
	'guest.addTabNumErr'			=>'Табельный номер для  :surname :name :patronymic :tabnum не зарегистрирован. Ошибка.',
	
	'guest.key_occuped'				=>'Отказ в выдаче карты. Карта :idcard зарегистрирована на гостя '.HTML::anchor('guests/edit/:id_pep/guest_mode', ':surname :name :patronymic'),
	'guest.key_occuped_contact'		=>'Отказ в выдаче карты. Карта :idcard принадлежит контакту :surname :name :patronymic',
	'guest.delOnIdPepOk'			=> 'Гость :surname :name :patronymic удален успешно',
	'guest.countGuest'				=>'(всего гостей на территории :count)',
	'guest.countArchive'			=>'(всего гостей в архиве :count)',
	
	
	'err_mess'						=>'Сообщение об ошибке',
	
	
	'sidebar.archive'				=> 'Архив гостей',
	'sidebar.config'				=> 'Настройка',
	'sidebar.deletedcontact'		=> 'Уволенные контакты',
	'sidebar.rfid'					=> 'RFID',
	'sidebar.grz'					=> 'ГРЗ',
	'sidebar.uhf'					=> 'UHF',
	'sidebar.cardexpired'			=> 'Срок действия истек',

	'companies.title'				=> 'Организации',
	'companies.id'					=> 'Код',
	'companies.name'				=> 'Наименование',
	'companies.code'				=> 'Код подразделения',
	'companies.action'				=> 'Действия',
	'companies.parent'				=> 'Вышестоящий отдел',
	'companies.access'				=> 'Уровень доступа',
	'companies.confirmdelete'		=> 'Вы действительно хотите удалить организацию?',
	'companies.countChildren'		=> 'Количество нижестоящих компаний',
	'companies.countContact'		=> 'Количество контактов в компаний',
	'companies.addOk'				=> 'Организция name успешно добавлена в организацию parentName',
	'companies.addValidationErr'	=> 'Получена ошибка name',
	'companies.addDbErr'			=> 'При добавлении организации name возникла ошибка в базе данных. Детали см. лог-файл.',

    'companies.updateOk'				=> 'Обновление организации name выполнено успешно',
	'companies.updateValidationErr'	=> 'Получена ошибка name',
	'companies.updateDbErr'			=> 'При добавлении организации name возникла ошибка в базе данных. Детали см. лог-файл.',
	'companies.is_guest'			=> 'Гостевая',

    'objects.title'                 => 'Объекты',
    'objects.name'                  => 'Название объекта',
    'objects.config_server'         => 'Сервер',
    'objects.config_bdpath'         => 'Путь к БД',
    'objects.config_bdfile'         => 'Файл базы данных',

	'company.title'					=> 'Организация',
	'company.name'					=> 'Наименование организации',
	'company.code'					=> 'Код подразделения',
	'company.guest'					=> 'гостевая организация',
	'company.accessname'			=> 'Уровень доступа по умолчанию',
	'company.parent'				=> 'Родительская организация',
	'company.emptyname'				=> 'Введите название организации',
	'company.emptycode'				=> 'Введите код подразделения',
	'company.saved'					=> 'Организация сохранена',
	'company.updated'				=> 'Организация обновлена',
	'company.deleted'				=> 'Организация удалена',
	'company.group'					=> 'Группа',
	'company.new'					=> 'Новая организация',
	'company.data'					=> 'Свойства',
	'company.contacts'				=> 'Список контактов',
	'company.acl'					=> 'Категории доступа',

	'button.save'					=> 'Сохранить',
	'button.cancel'					=> 'Отмена',
	'button.backtolist'				=> 'Вернуться к списку',
	'button.backtocardlist'			=> 'Вернуться к списку карт',
	'button.addpeople'				=> 'Добавить контакт',
	'button.totalDelete'			=> 'Удалить контакт',
	'button.savecsv'				=> 'Экспорт csv',
	'button.savexlsx'				=> 'Экспорт xlsx',
	'button.savepdf'				=> 'Экспорт pdf',
	'button.report1'				=> 'Подготовить отчет Рабочего времени',
	'button.reportEvents'			=> 'Подготовить отчет Журнал событий',
	'Reports'						=> 'Отчеты',
	'report.history'				=> 'Отчет журнал событий',
	'report.reports'					=> 'Отчеты',
	

	'settings.title'				=> 'Настройки',
	'settings.language'				=> 'Выберите язык',
	'settings.listsize'				=> 'Размер страницы',
	'settings.company_columns'		=> 'Показывать следующие колонки в списке организаций',
	'settings.showphone'			=> 'Показывать телефоны в списке сотрудников',
	'settings.updated'				=> 'Настройки сохранены',
	'settings.password'				=> 'Введите новый пароль (оставьте поле пустым, если не хотите менять пароль)',
	'settings.place'				=> 'Название объекта',
	

	'acl.title'						=> 'Права доступа для пользователя <i>:user</i>',
	'acl.company_view'				=> 'Просмотр организаций',
	'acl.company_edit'				=> 'Редактирование организаций',
	'acl.contact_view'				=> 'Просмотр контактов',
	'acl.contact_edit'				=> 'Редактирование контактов',
	'acl.saved'						=> 'Права доступа сохранены',
	
	'acl.equalDefaultOrg'			=>'По умолчанию',
	'acl.moreTheDefaultOrg'			=>'Отличается',
	'acl.lessTheDefaultOrg'			=>'Отличается',

	'load.date'						=> 'Дата',
	'load.device'					=> 'Точка прохода',
	'load.status'					=> 'Результат',
	'load.in_order'	=> 'Стоит в очереди на загрузку',
	'load.insert'	=> 'Дата записи идентификатора в контроллер',
	

	'device.is_disable'			=> 'Устройство не активно. Карты не загружаются.',
	'device.title'				=> 'Название устройства.',
	'device.name'				=> 'Название устройства.',
	'device.ip'					=> 'IP адрес устройства.',
	'device.port'				=> 'Сетевой порт устройства.',
	'device.is_active'			=> 'Устройство активно.',
	'device.emptyname'			=> 'Название устройства не может быть пустым.',
	'device.ip_empty'			=> 'Укажите IP адрес устройства.',
	'device.ipFormatError'		=> 'Неправильный формат IP адреса.',
	'device.ipPortEmpty'		=> 'Укажите IP порт устройства.',
	'device.ipPortFormatError'	=> 'Неправильный формат IP порта. Значение должно быть .',
	'device.card_not_in_order_from_load'=>'Нет',
	'device.card_in_order_from_load'=>'Да',
	'device.no_data_about_load_time'=>'Нет данных',
	'device.no_data_about_load_result'=>'Нет данных',
	'device.no_data_about_load_time_stamp'=>'Нет данных',

	
	
	'history.date'					=> 'Дата/время',
	'history.event'					=> 'Событие',
	'history.device'				=> 'Устройство',
	'history.empty'					=> 'Записи не найдены',
	'history.any'					=> 'Параметры события',
	'history.no'					=> '',
	'history.add_accessname'		=> 'Добавлена категория доступа',
	'history.change_org'			=> 'Переведен в организацию',
	'history.eventadd'				=> 'Дополнительные данные',
	'history.doorname'				=> 'Точка прохода',
	

	'payment.date'					=> 'Дата/время',
	'payment.place'					=> 'Место',
	'payment.service'				=> 'Назначение',
	'payment.sum'					=> 'Сумма',
	'payment.card'					=> 'Карта',

	'common.no'						=> 'Нет',
	'common.yes'					=> 'Да',

	'of'							=> ' из ',

	'surname'							=> 'фамилию',
	'username'							=> 'имя пользователя',
	'password'							=> 'пароль',
	':field must be less than :param1 characters long'	=> ':field должен быть короче :param1 символов',
	'email address'						=> 'адрес электронной почты',
	':field must be a email address'	=> ':field имеет неверный формат',
	':field must not be empty'			=> 'введите :field',
	':field must be at least :param1 characters long'	=> ':field должен быть не короче :param1 символов',
	
	'error.username.username_available' => 'имя пользователя занято',
	'error.email.email_available'		=> 'адрес электронной почты занят',
	
	
	'form.editContact'				=>'Редактирование контакта',
	'form.select_file_enabled_in_edit_mode'				=>'Выбор файла с изображением доступен только в режиме редактирования контакта',
	
	
	'doors.title'						=> 'Точка прохода ',
	'doors.common'						=> 'Свойства',
	'doors.contactlist'					=> 'Сотрудники',
	'doors.KeyCount'					=> 'Количество сотрудников  :count',
	'doors.list'						=> 'Точки прохода',
	'doors'								=> 'Точки прохода',
	
	
	'door.name'							=> 'Название точки прохода',
	'door.parentname'					=> 'Название контроллера СКУД',
	'door.is_active'					=> 'Активность',
	'door.title'						=> 'Точка прохода',
    
    'template.Auth'                     =>'Авторизация :auth',
    'template.Role'                     =>'Роль :role',
    'template.DB'                        =>'БД :db',
    'template.Mode'                      =>'Режим редактирования :mode',
    'template.id_pep'                   =>'id_pep :id_pep',
);