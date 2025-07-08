<?php defined('SYSPATH') or die('No direct script access.');
/**
Настройка системы: ввод и просмотр разных параметров.

*/
class Controller_mreports extends Controller_Template { 
		
	public $view = 'result';//view для показа результата
	public $template = 'template';
	
	public function before()
	{
		parent::before();
	}


	
	
	
	/*
	17.10.2024 шаблон тестового отчета
	*/
	
	public function action_stat()
	{
	
		//echo Debug::vars('29 report stat');exit;
		
		$report=new Report();
		
			
		Session::instance()->set('report', $report);
		$content = View::factory('report')
			->bind('report', $report)
			;
        $this->template->content = $content;
	}
	
	
	/*
	21.10.2024 отчет для Щербинки. Вывод окна с выбором параметров.
	*/
	
	public function action_reportSelect()
	{
		//echo Debug::vars('46', $this->request->param('id'));exit;
		//echo Debug::vars('47', $this->user);exit;

		$content = View::factory($this->request->param('id').'/report')
			->bind('user', $this->user)
			;
        $this->template->content = $content;
	}
	
	
	/*
	18.10.2024 отчет 
	*Report Unic ID, далее RUID
	*Модель отчета формируется как RUID_report
	*Модель должна лежать в папке Model/RUID/report.php 
	*Метод подготовки отчета имеет фиксированное название getReport.
	* в метод передаются все данные POST и id текущего авторизованного пользователя
	*вывод результат происходит в view view/RUID/result.php 
	*/

	
	public function action_makeReport()
	{
		//echo Debug::vars('29 report stat', $_POST);exit;
		
		$post=Validation::factory($_POST)
			->rule('id_report', 'not_empty')
			;
			
		if($post->check()){
		
			//echo Debug::vars('71', Arr::get($post, 'id_report'), $this);exit;
			$ruid=Arr::get($post, 'id_report');// получаю report UID - уникальный идентификатор отчета.
			$report=Model::factory($ruid.'_report')->getReport($post,$this->user);
		
			//echo Debug::vars('84', $report);exit;
			//сохраняю отчет в сессию. Результат должен быть записан в файл сессии на сервере http
			Session::instance()->delete('report');
			
			Session::instance()->set('report', $report);
			
			if(isset($report->view)) $this->view=$report->view;
			
			$content = View::factory($ruid.'/'.$this->view)
				->bind('report', $report)
				->bind('user', $this->user)
				;
		} else {
			
			$message=implode(",", $post->errors('mreports'));
			$content = View::factory('reportError', array(
			'mess'=> $message,
			));
		}
		
		
        $this->template->content = $content;
	}
	
	
	/*
		18.10.2024 экспорт результата
		экспортируется уже подготовленный ранее файл
	*/
	public function action_export()
	{
		//echo Debug::vars('76',$_POST, Session::instance()->id());exit;
		if(Arr::get($_POST, 'savecsv'))
		{
			/**2.03.2025 
			*
			*
			*/
			$report=Session::instance()->get('report');//из сессия "достаю" параметры отчета
			Session::instance()->delete('report');//очищаю сессию от отчета
			echo Debug::vars('121', $report);exit;
			$csv=new ExportCsv($report);
			//echo Debug::vars('118', $csv->filename);exit;
			if($csv->makeOk) 
			{
				echo Debug::vars('125', $this);exit;
				$content = Model::Factory('mreport')->send_file($csv->filename);//передача файла через браузер
				//удалить файл с диска, чтобы не занимал место
			} else {
				echo Debug::vars('129', $this);exit;
				$this->redirect($this->request->referrer());
			}
		};
		
		/** 2.03.2025 экспорт осуществляется средствами ExportXlsx
		*
		*
		*/
		if(Arr::get($_POST, 'savexls'))
		{
			$report=Session::instance()->get('report');//из сессия "достаю" параметры отчета
			Session::instance()->delete('report');//очищаю сессию от отчета
			$csv=new ExportXlsx($report);//беру отчет из сессии.
			
		}
		
		
		/*экспорт файла в pdf. Имеется возможность (для отладки) сначала вывести отчет на экран,
		*
		*
		*/
		if(Arr::get($_POST, 'savepdf')) 
		{
			/*  $reportData=new ExportPdf(Session::instance()->get('report'));
			 $csv->makeReport();
			 $csv->sendFile(); */
		 	$report=Session::instance()->get('report');//из сессия "достаю" параметры отчета
			Session::instance()->delete('report');//очищаю сессию от отчета
			
			if(Kohana::find_file('views','exportpdf'))
				{
				// $content=View::Factory('report')
				// ->bind('report', $report); 
				
				$content=View::Factory('exportpdf')
					->bind('reportData', $report)
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
					$dompdf->stream($report->fileName.'_'.date('Y-m-d_H_i_s'));//отправка файла через браузер.
				} else {
					//echo Debug::vars('49', $content);//exit;
						$this->template->content = $content;
				}
						 
		}
		
	}
	
}
