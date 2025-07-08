<?php
/*
22.04.2024 тестирование класса card:
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
		return array(
            array(14747700, '00E10834'),
        );
    }
 
    /**
     * @dataProvider providerStrLen
     */
   public function test_decDigitToHEX8($idcard, $result)
    {
       		
		$_var=Model::Factory('Stat')->decDigitToHEX8($idcard);//привожу формат DEC к HEX8
		$this->assertEquals($_var, $result);  
        
    }
	
	public function providerStrLen2()
    {
        //массив для проверки преобразования десятичного числа в 001A
		return array(
            array(12482827, 'D09E7D001A'),
        );
    }
	/**
     * @dataProvider providerStrLen2
     */
	public function test_decDigitTo001A($idcard, $result)
    {
       		
		//$_var=Model::Factory('Stat')->decDigitToHEX8($idcard);//привожу формат DEC к HEX8
		$_var=$idcard=Model::Factory('Stat')->decDigitTo001A($idcard);//привожу формат HEX8 к 001A
		//$idcard=Model::Factory('Stat')->decDigitTo001A($idcard);//привожу формат DEC к HEX8
		$this->assertEquals($_var, $result);  
        
    }
	
		
	public function providerStrLen3()
    {
       //массив для проверки преобразования шестнадцатиричного числа в длинное десятичное
		return array(
            array('00E10834', 14747700),
        );
    }
	/**
     * @dataProvider providerStrLen3
     */
	 
	 
 	public function test_hexToDec($idcard, $result)
    {
       		
		//$_var=Model::Factory('Stat')->decDigitToHEX8($idcard);//привожу формат DEC к HEX8
		//$_var=$idcard=Model::Factory('Stat')->HEX8To001A($idcard);//привожу формат HEX8 к 001A
		$_var=$idcard=Model::Factory('Stat')->hexToDec($idcard);//привожу формат DEC к HEX8
		$this->assertEquals($_var, $result);  
        
    } 
	
	

}

