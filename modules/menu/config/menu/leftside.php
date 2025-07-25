<?php defined('SYSPATH' OR die('No direct access allowed.'));
/**
 * 06.10.2024
 * перечень всех пунктов меню
 * если 'visible' => false, то пункт меню не будет показан
 * если 'visible' => true или отсутсвует, то пункт меню будет показан
 * */
 
return [
	'view' => 'templates/menu/bootstrap/navbar',
	'items' => [
		'home'=>[
					'url'     => '/',
					'title'   => 'home',
					'icon'    => 'icon_home.png',
					'tooltip' => 'home'
				],
		'org'=>[
					'url'     => 'companies',
					'title'   => 'companies',
					'icon'    => 'icon_companies.png',
					'tooltip' => 'Просмотр списка организаций',
					'attributes'=> ['data-method' => 'ajax'],
					'items'   => [
						'list'=>[
									'url'     => 'companies',
									'icon'    => '',
									'title'   => 'sidebar.companieslist',
									'tooltip' => 'Просмотр списка организаций',
									'visible' => true,
								],
						'add'=>[
								'url'     => 'companies/edit/0',
								'icon'    => '',
								'title'   => 'sidebar.addcompany',
								'tooltip' => 'Добавление организации'
							]
					]
				],
	
		'identity'=>[
					'url'     => 'identity',
					'icon'    => 'icon_card.png',
					'title'   => 'identity',
					'tooltip' => 'identity',
					'visible' => true, // можно запретить показ, если false
					'items'   => [
						'list'=>[
									'url'     => 'cards/select/rfid',
									'icon'    => '',
									'title'   => 'sidebar.rfid',
									'tooltip' => 'sidebar.rfid',
								],
						'cardexpired'=>[
								'url'     => 'cards/expired',
								'icon'    => '',
								'title'   => 'sidebar.cardexpired',
								'tooltip' => 'sidebar.cardexpired'
							],
					
							
					]
				],
		
		'contact'=>[
					'url'     => 'contacts',
					'icon'    => 'icon_contacts.png',
					'title'   => 'contacts',
					'tooltip' => 'contacts',
					'visible' => true, // можно запретить показ, если false
					'items'   => [
						'list'=>[
									'url'     => 'contacts/disp/activeOnlyList',
									'icon'    => '',
									'title'   => 'sidebar.contactslist',
									'tooltip' => 'sidebar.contactslist',
								],
						'add'=>[
								'url'     => 'contacts/disp/addContact',
								'icon'    => '',
								'title'   => 'sidebar.addcontact',
								'tooltip' => 'sidebar.addcontact'
							],
						'fired'=>[
								'url'     => 'contacts/disp/deletedList',
								'icon'    => '',
								'title'   => 'sidebar.deletedcontact',
								'tooltip' => 'sidebar.deletedcontact'
							]
							
					]
				],
		
		
		
		'fastreg'=>[	//быстрая регистрация в выбранную организацию
					'url'     => 'fastreg',
					'icon'    => 'icon_contacts.png',
					'title'   => 'contacts.host',
					'tooltip' => 'contacts',
					'visible' => true, // можно запретить показ, если false
					'items'   => [
						'contactslist'=>[
									'url'     => 'contacts/disp/HostOnlyList',
									'icon'    => '',
									'title'   => 'sidebar.contactslist',
									'tooltip' => 'sidebar.contactslist',
									'visible' => true,
								],
						'hostAddContact'=>[
								'url'     => 'contacts/disp/hostAddContact',
								'icon'    => '',
								'title'   => 'sidebar.addcontact',
								'tooltip' => 'sidebar.addcontact',
								'visible' => true,
							],
						'hostDeletedList'=>[
								'url'     => 'contacts/disp/hostDeletedList',
								'icon'    => '',
								'title'   => 'sidebar.deletedcontact',
								'tooltip' => 'sidebar.deletedcontact',
								'visible' => true,
							],
						'hostSetup'=>[
								'url'     => 'contacts/disp/hostSetup',
								'icon'    => '',
								'title'   => 'Настройка',
								'tooltip' => 'setup',
								'visible' => true,
							]
							
					]
					
				],
		

		'passoffice'=>[	//бюро пропусков
					'url'     => 'passoffice.passoffice',
					'icon'    => 'icon_guest.png',
					'title'   => 'passoffice.passoffice',
					'tooltip' => 'passoffice.passoffice',
					'visible' => false, // можно запретить показ, если false
					'items'   => [
						'guestslist'=>[
									'url'     => 'passoffices/guest',
									'icon'    => '',
									'title'   => 'passoffice.guestslist',
									'tooltip' => 'passoffice.guestslist',
								],
						'archive'=>[
									'url'     => 'passoffices/archive',
									'icon'    => '',
									'title'   => 'sidebar.archive',
									'tooltip' => 'sidebar.archive',
								],
						'addguest'=>[
									'url'     => 'passoffices/edit/0/issue',
									'icon'    => '',
									'title'   => 'sidebar.addguest',
									'tooltip' => 'sidebar.addguest',
								],
						'menu_events'=>[
									'url'     => 'passoffices/events',
									'icon'    => '',
									'title'   => 'passoffice.menu_events',
									'tooltip' => 'passoffice.menu_events',
								],
						'config'=>[
									'url'     => 'passoffices/config',
									'icon'    => '',
									'title'   => 'sidebar.config',
									'tooltip' => 'sidebar.config',
								],
						
							
					]
					
				],
		
			'order'=>[	//заказ пропусков
					'url'     => 'order.order',
					'icon'    => 'icon_guest.png',
					'title'   => 'Заказ пропусков',
					'tooltip' => 'order.order',
					'visible' => true, // можно запретить показ, если false
					'items'   => [
						'guestslist'=>[
									'url'     => 'order/guest',
									'icon'    => '',
									'title'   => 'Список заявок',
									'tooltip' => 'order.orderlist',
								],
						'addorder'=>[
									'url'     => 'order/archive',
									'icon'    => '',
									'title'   => 'sidebar.archive',
									'tooltip' => 'sidebar.archive',
								],
						'addguest'=>[
									'url'     => 'order/edit/0/issue',
									'icon'    => '',
									'title'   => 'sidebar.addguest',
									'tooltip' => 'sidebar.addguest',
									'visible' => false,
								],
						
						'addguest2'=>[
									'url'     => 'order/edit/0/newguest',
									'icon'    => '',
									'title'   => 'Регистрация гостя',
									'tooltip' => 'sidebar.addguest2',
								],
						'settings'=>[
									'url' => 'order/settings',
									'icon'=>'',
									'tittle'=>'Настройки',
									'tooltip'=>'sidebar.settings'
						]
						
							
					]
					
				],
		
				
		'acl'=>[
					'url'     => 'acls',
					'icon'    => 'icon_contacts.png',
					'title'   => 'acls',
					'tooltip' => 'sidebar.acls',
					'visible' => true, // можно запретить показ, если false
					
				],
				
		'monitor'=>[
					'url'     => 'monitors',
					'icon'    => 'icon_monitor.png',
					'title'   => 'monitor',
					'tooltip' => 'sidebar.monitor',
					'visible' => true, // можно запретить показ, если false
					
				],
				
		'doors'=>[
					'url'     => 'doors',
					'icon'    => 'icon_contacts.png',
					'title'   => 'doors.list',
					'tooltip' => 'doors.list',
					'visible' => true, // можно запретить показ, если false
					
				],
				
		//раздел Отчеты		
		'Reports'=>[
					'url'     => 'reports',
					'icon'    => 'export.png',
					'title'   => 'report.reports',
					'tooltip' => 'report.reports',
					'visible' => true, // можно запретить показ, если false
					'items'   => [
						'history'=>[
									'url'     => 'reports/events',
									'icon'    => '',
									'title'   => 'report.history',
									'tooltip' => 'report.history',
									'visible' => false,
								],
						'stat'=>[
									'url'     => 'mreports/stat',
									'icon'    => '',
									'title'   => 'mreport.stat',
									'tooltip' => 'mreport.stat',
									'visible' => false,
								],
						'report1'=>[
									'url'     => 'mreports/reportSelect/234',
									'icon'    => '',
									'title'   => 'mreport.report1',
									'tooltip' => 'mreport.report1',
									'visible' => true,
								],
						
						'history2'=>[
									'url'     => 'mreports/reportSelect/history2',
									'icon'    => '',
									'title'   => 'mreport.history',
									'tooltip' => 'mreport.history',
									'visible' => true,
								],
						
						'exportAllContact'=>[
									'url'     => 'mreports/reportSelect/allContactsExport',
									'icon'    => '',
									'title'   => 'mreport.allcontact',
									'tooltip' => 'mreport.allcontact',
									'visible' => true,
								],
						
						'peopleRegStat'=>[
									'url'     => 'reports/peopleRegStat',
									'icon'    => '',
									'title'   => 'report.peopleRegStat',
									'tooltip' => 'report.peopleRegStat',
									'visible' => false,
								],
						
						'identityRegStat'=>[
									'url'     => 'reports/identityRegStat',
									'icon'    => '',
									'title'   => 'report.identityRegStat',
									'tooltip' => 'report.identityRegStat',
									'visible' => false,
								],
						
						
						
							
					]
					
				],
				
				
				
		
	],
];