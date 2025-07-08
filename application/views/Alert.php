<?php
//этот файла должен выводить список сообщений (alert), которые передаются как массив.
//include Kohana::find_file('views','alert');

if (isset($arrAlert)) { 
//echo Debug::vars('5-5', $arrAlert, !empty($arrAlert));//exit;
	if(!empty($arrAlert)){
		include Kohana::find_file('views', 'alertState');

		foreach($arrAlert as $_key=>$value){
			
			echo '<div class="'.Arr::get($arrayType, Arr::get($value, 'actionResult')).'"><p>';
			echo HTML::image(Arr::get($arrayImage, Arr::get($value, 'actionResult')), array('class'=>'mid_align',  'alt'=>Arr::get($arrayAlt, Arr::get($value, 'actionResult'))));
			echo Arr::get($value, 'actionDesc');
			echo '</p></div>';
		}
		
	}
} else {
	
	//echo __('no_arrAlert');
}

?>
