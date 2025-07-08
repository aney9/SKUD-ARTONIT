<?php defined('SYSPATH') OR die('No direct script access.');

return array(

	'group'         => ':field must contain only letters',
	'digit'         => ':value должны быть только цифры',
	'id_pep'   =>  array(
         'not_empty'=> 'Пользователь не указан.',
         'digit'=> 'id пользователя должно быть десятичное числов.',
         'Model_Contact::unique_idpep'=> 'Пользователя нет или недоступен.',
      ),
	

);
