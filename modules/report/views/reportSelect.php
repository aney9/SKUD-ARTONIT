	<br class="clear">
	<div class="content">
	<?php
		echo Kohana::message('report234', 'aboutReport234');
		echo Form::open('mreports/makeReport');
		?>
		 <input type="text" name="monceList" id="monthselect">
			<br>
			<fieldset>
				<legend><?php echo __('Месяцы'); ?></legend>
				<?php
					$n=0;
					for($i=1; $i<13; $i++)
					{
						
						if($i==6) {
							echo Form::radio('howManyMonce', $i, true).$i.'<br>';
						} else {
							echo Form::radio('howManyMonce', $i).$i.'<br>';
						}
					}
				?>
							
			</fieldset>				
			<?php 
						
			echo Form::hidden('id_report', '234');
			echo Form::submit('button', __('button.makeReport'));
				
		echo Form::close();
			?>
</div>
