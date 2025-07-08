<?php
/*
16.05.2024 Панель с кнопками в разделе настроек.
Панель формируется на основе тут же формируемого массива.
недостающие параметры передаются из контроллера

*/

if(!isset($id_pep)) $id_pep=0;
//if(!isset($mode)) $mode='';
$battArray=array(
	'edit'=>array(
			'anchor'=>'passoffices/edit/' . $id_pep,
			'messOnbatton'=>__('contact.common'),
			'class'=>'left_switch',
			'disabled'=>'disabled',		
			'tittle'=>'Свойства контакта',		
					
	),
	'acl'=>array(
			'anchor'=>'passoffices/acl/' . $id_pep,
			'messOnbatton'=>__('contact.acl'),
			'class'=>'middle_switch',
			'disabled'=>'disabled',	
			'tittle'=>'Список категорий доступа',			
	),
	
	'cardlist'=>array(
				'anchor'=>'passoffices/cardlist/' . $id_pep,
				'messOnbatton'=>__('contact.cardlist'),
				'class'=>'middle_switch',
				'disabled'=>'disabled',	
				'tittle'=>'Управление всеми конфигурациями',			
	),
	
	/* 'worktime'=>array(
				'anchor'=>'passoffices/worktime/' . $id_pep,
				'messOnbatton'=>__('contact.worktime'),
				'class'=>'middle_switch',
				'disabled'=>'disabled',	
				'tittle'=>'Учет рабочего времени',			
	), */
	
	'history'=>array(
				'anchor'=>'passoffices/history/' . $id_pep,
				'messOnbatton'=>__('contact.history'),
				'class'=>'middle_switch',
				'disabled'=>'disabled',	
				'tittle'=>'Журнал событий',			
	),
	
	/* 'test'=>array(
				'anchor'=>'',
				'messOnbatton'=>__('contact.test'),
				'class'=>'right_switch',
				'disabled'=>'disabled',	
				'tittle'=>'test',			
	),
	 */
	
	
);

?>
	
	<div class="switch">
			<table cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
				<?php 
					foreach($battArray as $key=>$value)
					{
	
					echo '<td>';
					$isActive='';
					
						if($key==$_is_active) $isActive =' active' ;
						//echo Debug::vars('76', $key, $_is_active, $isActive);exit;
				
								echo HTML::anchor(Arr::get($value,'anchor'), Arr::get($value,'messOnbatton'), array('class' => Arr::get($value,'class').$isActive, 'disabled'=>Arr::get($value,'disabled'), 'title'=>Arr::get($value,'tittle'))); 
							echo '</td>';
					
						
					}
				?>
					<tr>
			</tbody>
			</table>
		</div>

