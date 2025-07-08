<?php 
//echo Debug::vars('2', $group); exit;
if ($alert) { ?>
<div class="alert_success">
	<p>
		<img class="mid_align" alt="success" src="images/icon_accept.png" />
		<?php echo $alert; ?>
	</p>
</div>
<?php } ?>
<div class="onecolumn">
	<div class="header">
		<div id="search"<?php if (isset($hidesearch)) echo ' style="display: none;"'; ?>>
			<form action="settings/auth" method="post">
			<?php 
			if(!Session::instance()->get('canModSetting')){?>
				<input type="text" class="search noshadow" title="<?php echo __('setting.auth'); ?>" name="llog" id="llog" value="<?php if (isset($filter)) echo $filter; ?>" />
			<?php } else {
				
				echo Form::submit(NULL, 'OUT');
			}?>
			</form>
		</div>
		<span><?php echo __('setting.main_title');?></span>

		<?php
			include Kohana::find_file('views\Setting','topbuttonbar');
		?>

	</div>
	<br class="clear"/>
	<div class="content">
	
	<?php 
	
	//$group='main';
	if(Session::instance()->get('canModSetting') OR 1){
	 ?>
		<fieldset>
						<legend><?php echo __('setting.'.$group); ?></legend>
						<div>
							
							<?php 
							echo Form::open('settings/save');
							echo Form::hidden('group', $group);
							foreach($mainConfg as $key=>$value){
								echo $key.' '.__('setting.'.$key).' '.Form::input('key['.$key.']', Arr::get($mainConfg, $key), array('value'=>$value)). Kohana::message('messmain', $key);
								echo '<br>';
								}
								
								 echo Form::submit(NULL, 'Save');
								echo '<br>';
							echo Form::close();
							?>
							
					

						</div>
		</fieldset>
		<?php }
	
	echo Kohana::message('messmain', 'formatDescription');
	?>
		<table class="tablesorter-blue">
		<thead>
		<tr>
						<th>Параметр конфигурации</th>
						<th>Выбор значения</th>
						<th>Пояснения</th>
						
					</tr>
		</thead>
		<tbody>
		<?php
		$list=array(
		'0'=>'HEX 8 byte 00124CD8',
		'1'=>'001A 10 byte 262F8F001A',
		'2'=>'DEC 10 digit 0001493650',
		'4'=>'ГРЗ A123BC45'
		);
	//формат хранения номера RFID в базе данных СКУД.
			echo '<tr>';
				echo '<td>baseFormatRfid </td>';
				echo '<td>';
				
					foreach($list as $key=>$value){
						
						echo Form::radio('baseFormatRfid', $key).$value.'<br>';
						
					}
				echo '</td>';
				echo '<td>'.' '.Kohana::message('messmain', 'descBaseFormatRfid.'.$key).'</td>';
			echo '</tr>';
		//}
	//формат ввода номера RFID на регистрационном считыателе
	$list=array(
		'0'=>'HEX 8 byte 00124CD8',
		'1'=>'001A 10 byte 262F8F001A',
	
		);
	
	echo '<tr>';
				echo '<td>regFormatRfid</td>';
				echo '<td>';
					//.Form::select('key['.$key.']', $list, Arr::get($mainConfg, $key), array('value'=>$value)).
					foreach($list as $key=>$value){
						
						echo Form::radio('regFormatRfid', $key).$value.'<br>';
						
					}
				echo '</td>';
				echo '<td>'.' '.Kohana::message('messmain', 'descBaseFormatRfid.'.$key).'</td>';
			echo '</tr>';
	
	$list=array(
		'0'=>'Выводить',
		'1'=>'Не выводить',
	
		);
	
	echo '<tr>';
				echo '<td>screenFormatRFID</td>';
				echo '<td>';
					//.Form::select('key['.$key.']', $list, Arr::get($mainConfg, $key), array('value'=>$value)).
					foreach($list as $key=>$value){
						
						echo Form::radio('screenFormatRFID', $key).$value.'<br>';
						
					}
				echo '</td>';
				echo '<td>'.' '.Kohana::message('messmain', 'descBaseFormatRfid.'.$key).'</td>';
			echo '</tr>';
	$list=array(
		'0'=>'Пометить как НЕ активный, из базы данных не удалять.',
		'1'=>'Сразу удалять из базы данных.',
	
		);
		echo '<tr>';
				echo '<td>howDeletePeople</td>';
				echo '<td>';
					//.Form::select('key['.$key.']', $list, Arr::get($mainConfg, $key), array('value'=>$value)).
					foreach($list as $key=>$value){
						
						echo Form::radio('selectKey', $key).$value.'<br>';
						
					}
				echo '</td>';
				echo '<td>'.' '.Kohana::message('messmain', 'descBaseFormatRfid.'.$key).'</td>';
			echo '</tr>';
		
		
		echo '<tr>';
				echo '<td>iphost</td>';
				echo '<td>';
					//.Form::select('key['.$key.']', $list, Arr::get($mainConfg, $key), array('value'=>$value)).
					echo Form::input('iphost', 'iphost');
				echo '</td>';
				echo '<td>'.' '.Kohana::message('messmain', 'descBaseFormatRfid.'.$key).'</td>';
			echo '</tr>';
		
			echo '<tr>';
				echo '<td>lic</td>';
				echo '<td>';
					//.Form::select('key['.$key.']', $list, Arr::get($mainConfg, $key), array('value'=>$value)).
					echo Form::input('odbcname', 'odbcname');
				echo '</td>';
				echo '<td>'.' '.Kohana::message('lic', 'lic').'</td>';
			echo '</tr>';
		
		echo '<tr>';
				echo '<td>odbcname</td>';
				echo '<td>';
					//.Form::select('key['.$key.']', $list, Arr::get($mainConfg, $key), array('value'=>$value)).
					echo Form::input('odbcname', 'odbcname');
				echo '</td>';
				echo '<td>'.' '.Kohana::message('messmain', 'descBaseFormatRfid.'.$key).'</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>orgname </td>';
				echo '<td>';
					//.Form::select('key['.$key.']', $list, Arr::get($mainConfg, $key), array('value'=>$value)).
					echo Form::input('orgname ', 'orgname');
				echo '</td>';
				echo '<td>'.' '.Kohana::message('messmain', 'descBaseFormatRfid.'.$key).'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>sysVer  </td>';
				echo '<td>';
					//.Form::select('key['.$key.']', $list, Arr::get($mainConfg, $key), array('value'=>$value)).
					echo Form::input('sysVer  ', 'sysVer');
				echo '</td>';
				echo '<td>'.' '.Kohana::message('messmain', 'descBaseFormatRfid.'.$key).'</td>';
			echo '</tr>';
			
			
	?>
	</tbody>
		</table>
			
			
	

	</div>
</div>
