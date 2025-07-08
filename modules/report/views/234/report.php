<?php
/**Форма для выбора месяцев, за которые надо подготовить отчет.
 * номер отчета 234
 * 19.11.2024 
 * 
 */
?>
	<br class="clear">
	<div class="content">
	<?php
		
		echo Form::open('mreports/makeReport');
		//echo Debug::vars('6', date('Y-m')); exit;
		?>
		 
			<br>
			<fieldset>
				<legend><?php echo __('Месяцы'); ?></legend>
				<?php
					echo Kohana::message('report234', 'aboutReport234');

				echo '<br>';
					echo Form::input("monceList", null, array('id'=>'monthselect', 'type'=>'text'));
				
				
				$n=0;

				?>
							
			</fieldset>				
			<?php 
			
			echo Form::hidden('id_report', '234');
			echo Form::submit('button', __('button.makeReport'));
				
		echo Form::close();
			?>
</div>
