<?php
/*
8.01.2024 тестирование методов работы с контактами:
1. добавление дочерних организаций.
2. перемещение организаций
3. удаление организаций.


*/
 
Class TestCompany_step2 extends Unittest_TestCase
{
    
// C:\xampp\htdocs\citycrm\application\tests>c:\xampp\php\phpunit.bat GuestTest.php		

//https://habr.com/ru/articles/56289/

//https://phpunit-documentation-russian.readthedocs.io/ru/latest/organizing-tests.html описание на русском языке

//https://habr.com/ru/companies/vk/articles/549698/#12
	

	
	 public function test_24_Login()//проверка добавления организаций в родителя 723 (это Артсек)
	{
		$url = 'http://localhost/crm2/login';
			$request = Request::factory($url)
				->method('POST')
				->post('username', 'ADMIN')
				->post('password', '333')
				;
		$response = $request->execute();
				
		$this->assertEquals(200, $response->status());

	
	} 
		
		
	public function test_40_Dash()//
	{
				
			$url = 'http://localhost/crm2/dashboard';
			$request = Request::factory($url);
			$response = $request->execute();
			//echo Debug::vars('46', $response->status());exit;
				
		$this->assertEquals(200, $response->status());

	
	}
	
	public function testLog()//проверка добавления организаций в родителя 723 (это Артсек)
	{
		$url = 'http://localhost/crm2/dashboard/log';
			$request = Request::factory($url);
			$response = $request->execute();
			//echo Debug::vars('46', $response->status());exit;
				
		$this->assertEquals(500, $response->status());

	
	}

	public function testCompaniesSearch()//проверка добавления организаций в родителя 723 (это Артсек)
	{
		$url = 'http://127.0.0.1/crm2/companies/search';
		$request = Request::factory($url)
			->method('POST')
			->post('q', 'test')
			;
			$response = $request->execute();
			//echo Debug::vars('46', $response->status());exit;
				
		$this->assertEquals(200, $response->status());

	
	}

	public function testCompanies()//проверка добавления организаций в родителя 723 (это Артсек)
	{
		$url = 'http://127.0.0.1/crm2/companies';
			$request = Request::factory($url);
			$response = $request->execute();
			//echo Debug::vars('46', $response->status());exit;
				
		$this->assertEquals(200, $response->status());

	
	}

	public function testContacts()//проверка 
	{
		$url = 'http://127.0.0.1/crm2/contacts';
			$request = Request::factory($url);
			$response = $request->execute();
			//echo Debug::vars('46', $response->status());exit;
				
		$this->assertEquals(200, $response->status());

	
	}

	public function testSelectRfid()//проверка 
	{
		$url = 'http://127.0.0.1/crm2/cards/select/rfid';
			$request = Request::factory($url);
			$response = $request->execute();
			//echo Debug::vars('46', $response->status());exit;
				
		$this->assertEquals(200, $response->status());

	
	}
	
	//массив проверок
	
	public function providerStrLen()
    {
        //массив для проверки преобразования десятичного числа в шестнадцатиричное
		return array(
            array('11111111', 200),//этот идентификатор есть в БД, должна открыться панель редактирования
            array('ABCDEF00', 302),//этого идентификатора нет в базе, поэтому переход на 302
            array('9876543210', 302),//длина строго 10, но его нет в БД
            array('98765432107777777', 302),// 302 - это значит, что ошибка обработана и в ответ получено перенаправление на другой ресурс.
            array('Дорога передача!', 400),//код 400 - синтаксическая ошибка в запросе
            array('', 302),
            array('!@#$%^&**(', 302),
            array('%CE983%20%CC%CC%2099', 200),
        );
    }
 
    /**
     * @dataProvider providerStrLen
     */
	 
	
	public function testSelectCardsEdit($key, $result)//проверка 
	{
		//$url = 'http://127.0.0.1/crm2/cards/edit/-11111111';
		$url = 'http://127.0.0.1/crm2/cards/edit/'.$key;
			$request = Request::factory($url);
			$response = $request->execute();
							
		$this->assertEquals($result, $response->status());

	
	}
	
	
	//массив проверок 
	
	public function provider2()
    {
        //массив для проверки преобразования десятичного числа в шестнадцатиричное
		return array(
            array('11111111', 302),//этот идентификатор есть в БД, должна открыться панель редактирования
            array('ABCDEF00', 302),//этого идентификатора нет в базе, поэтому переход на 302
            array('9876543210', 302),//длина строго 10, но его нет в БД
            array('98765432107777777', 302),// 302 - это значит, что ошибка обработана и в ответ получено перенаправление на другой ресурс.
            array('Дорога передача!', 400), //набор 4
            array('', 302),
            array('!@#$%^&**(', 302),
            array('570', 200),
            array('760', 200),
        );
    }
 
    /**
     * @dataProvider provider2
     */
	 
	
	public function testSelectCompaniesEdit($key, $result)//проверка 
	{
		//$url = 'http://127.0.0.1/crm2/cards/edit/-11111111';
		$url = 'http://127.0.0.1/crm2/companies/edit/'.$key;
			$request = Request::factory($url);
			$response = $request->execute();
							
		$this->assertEquals($result, $response->status());

	
	}
	
	public function testContactsEdit()//проверка 
	{
		$url = 'http://127.0.0.1/crm2/contacts/edit/0';
			$request = Request::factory($url);
			$response = $request->execute();
			//echo Debug::vars('46', $response->status());exit;
				
		$this->assertEquals(302, $response->status());
	}
	
	public function testMreportsReportSelect()//проверка 
	{
		$url = 'http://127.0.0.1/crm2/mreports/reportSelect/234';
			$request = Request::factory($url);
			$response = $request->execute();
			//echo Debug::vars('46', $response->status());exit;
				
		$this->assertEquals(200, $response->status());

	
	}
	
	
	public function test_213_end()//выход из авторизации
	{
		$url = 'http://127.0.0.1/crm2/logout';
			$request = Request::factory($url);
			$response = $request->execute();
			//echo Debug::vars('46', $response->status());exit;
				
		$this->assertEquals(302, $response->status());

	
	}
	
	
	public function provider3()
    {
        //массив для проверки удаления карты
		return array(
            array('11111111-', 302),//этот идентификатор есть в БД, должна открыться панель редактирования
            array('ABCDEF00', 302),//этого идентификатора нет в базе, поэтому переход на 302
            array('9876543210', 302),//длина строго 10, но его нет в БД
            array('98765432107777777', 302),// 302 - это значит, что ошибка обработана и в ответ получено перенаправление на другой ресурс.
            array('Дорога передача!', 400), //набор 4
            array('', 302),
            array('!@#$%^&**(', 302),
            array('\'select *\'', 400),//http://127.0.0.1/crm2/cards/delete/'select *'
            array('570', 302),
            array('760', 302),
        );
    }
 
    /**
     * @dataProvider provider3
     */
	 
	
	public function testCardsDelete($key, $result)//проверка 
	{
			$url = 'http://127.0.0.1/crm2/cards/delete/'.$key;
			$request = Request::factory($url);
			$response = $request->execute();
							
		$this->assertEquals($result, $response->status());

	
	}
	

public function provider4()
    {
        //массив для проверки удаления карты
		return array(
            array('11111111-', 302),//этот идентификатор есть в БД, должна открыться панель редактирования
            array('ABCDEF00', 302),//этого идентификатора нет в базе, поэтому переход на 302
            array('9876543210', 302),//длина строго 10, но его нет в БД
            array('98765432107777777', 302),// 302 - это значит, что ошибка обработана и в ответ получено перенаправление на другой ресурс.
            array('Дорога передача!', 400), //набор 4
            array('', 302),
            array('!@#$%^&**(', 302),
            array('\'select *\'', 400),//http://127.0.0.1/crm2/contacts/edit/'select *'
            array('570', 302),
            array('760', 302),
        );
    }
 
    /**
     * @dataProvider provider4
     */
	 
	
	public function testСontactsEdit2($key, $result)//проверка 
	{
			$url = 'http://127.0.0.1/crm2/contacts/edit/'.$key;
			$request = Request::factory($url);
			$response = $request->execute();
							
		$this->assertEquals($result, $response->status());

	
	}
	


}
