<?php defined('SYSPATH') OR die('No direct access allowed.');

class ExceptionCRM 
{
    
   // Переопределим исключение так, что параметр message станет обязательным
  public function __construct($message, $code = 0, Exception $previous = null) {
    // некоторый код 
 
    // убедитесь, что все передаваемые параметры верны
   // parent::__construct($message, $code, $previous);
      echo Debug::vars($message, $code);exit;
	
  }
 

  // Переопределим строковое представление объекта.
  public function __toString() {
    return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
  }
 
  public function customFunction() {
    echo "Мы можем определять новые методы в наследуемом классе\n";
  }
}
