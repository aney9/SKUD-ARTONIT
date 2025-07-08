#Добавление новых пунктов меня.
Реализаци меню выполнена на основе следующих скриптов:  
[Исходный код](https://github.com/anroots/kohana-menu/tree/master)  
[Описание работы модуля Меню](https://github.com/anroots/kohana-menu/wiki) 
#Шаг 1.
~~~
	'classes'    => [], // Extra classes for this menu item
			'icon'       => NULL, // Icon class for this menu item
			'siblings'   => [], // Sub-links
			'title'      => NULL, // Visible text
			'tooltip'    => NULL, // Tooltip text for this menu item
			'url'        => '#', // Relative or absolute target for this menu item (href)
			'visible'    => TRUE, // Menu item is rendered
			'attributes' => [] // Extra HTML attributes for the <li> element, assoc array
~~~			
Новые пункты меню необходимо добавить в общий список в файле \modules\menu\config\menu\leftside.php.  
~~~
	'org'=>[								//пункт в массиве меню. На экран не выводится.
					'url'     => 'companies',	//ссылка на контроллер
					'title1   => 'companies',	//Название пункта меню. При наличии i18n будет подменен на нужный текст.								
					'icon'    => 'icon_companies.png',	//иконка
					'tooltip' => 'sidebar.companieslist',
					'attributes'=> ['data-method' => 'ajax'],
					'items'   => [	//перечень подпунктов
						'list'=>[
									'url'     => 'companies', //ссылка на контроллер
									'icon'    => '', //иконка
									'title'   => 'sidebar.companieslist', //Название пункта меню. При наличии i18n будет подменен на нужный текст.	
									'tooltip' => 'sidebar.companieslist', //Всплывающая подсказка.	
									'visible' => true,	//разрешение на отображение пункта меню.
								],
						'add'=>[
								'url'     => 'companies/edit/0',
								'icon'    => '',
								'title'   => 'sidebar.addcompany',
								'tooltip' => 'sidebar.addcompany'
							]
					]
				],
~~~
 


