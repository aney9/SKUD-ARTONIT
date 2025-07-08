<?php
/*
20.08.2024 тестирование класса keyk
проверка преобразований кода карты
запуск теста: C:\xampp\htdocs\crm2\application\tests\card\TestCardConvert.bat


*/
 
Class TestCardConvert extends Unittest_TestCase
{

/*

HEX 8 byte 00124CD8 0
001A 10 byte 262F8F001A 1
DEC 10 digit 0001493650 2
	

	
	hex8->hex8,
	001A->hex8
	dec->hex8,

	hex8->001A,
	001A->001A
	dec->001A,
	
	hex8->dec,
	001A->dec
	dec->dec,
*/
	public function providerStrLen()
    {
        //массив для проверки преобразования десятичного числа в шестнадцатиричное
		// $patterm, $pattern, $bf=null, $sf=null, $screen
		return array(
            array(12122121, 0, 0, 'B8F809'),
        );
    }
 
    /**
     * @dataProvider providerStrLen
     */
   
   
   
   public function test_dec00($pattern, $bf, $sf, $screen)
    {
       	$key=new Keyk($pattern);
		$result=$key->convertFormat($pattern, $bf, $sf);
		echo Debug::vars('52', $result);exit;
		
		$this->assertEquals($screen, $result);  
        
    }
	

	

}

