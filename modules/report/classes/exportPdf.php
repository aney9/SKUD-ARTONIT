<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
*17.10.2024
*Класс exportPdf - Класс для создания файла csv.
*вход - класс Report
*выход - ссылка на подготовленный файл.
*/

class exportPdf
{
	public $template = 'template';
	public function __construct(Report $report)
	{
				if(Kohana::find_file('views','testpdf'))
				{
				 $content=View::Factory('testpdf')
					->bind('report', $report)
				 ; 
				} else {
					throw new  Exception('Нет файла view!');
				}
					
				if(false){ // переключатель: true - делать экспорт в pdf, false - выводит отчет на экран браузера
				//if(true){ // переключатель: true - делать экспорт в pdf, false - выводит отчет на экран браузера
				
					require_once APPPATH . 'vendor/dompdf/autoload.inc.php';
					Dompdf\Autoloader::register();
								
					$dompdf = new Dompdf\Dompdf();
					$dompdf->setPaper("A4");				
					$dompdf->loadHtml($content, 'UTF-8');
					$dompdf->render();
					
			
					$color = array(0, 0, 0);
					$font = null;
					$size = 8;
					$text = "Стр. {PAGE_NUM} из {PAGE_COUNT}";

					$canvas = $dompdf->getCanvas();
					$pageWidth = $canvas->get_width();
					$pageHeight = $canvas->get_height();
					$width=10;
					$canvas = $dompdf->get_canvas();
					$canvas->page_text($pageWidth/2, $pageHeight - 40, $text, $font, $size, $color);
					$dompdf->stream();
				} else {

						$this->template->content = $content;
				}
			
				return;
		
		
	}
	
	
	
	
	
	
	
}
