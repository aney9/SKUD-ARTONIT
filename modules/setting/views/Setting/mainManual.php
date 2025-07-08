<?php 
//6.08.2024 страница для демонстрации и сохранения параметров конфигурации.
//echo Debug::vars('2', $group); exit;
/*
acl
guest
main
rfid
system
6.11.2024 contact параметры, необходимые при регистрации контакта
*/
//echo Debug::vars('11', $groupList);
//$group='main';
$acl=Kohana::$config->load('acl');
$mainConfg=Kohana::$config->load('main');
$system=Kohana::$config->load('system');
$guest=Kohana::$config->load('guest');
$rfid=Kohana::$config->load('rfid');
$rfid=Kohana::$config->load('rfid');
$contact=Kohana::$config->load('contact');

/*
echo Debug::vars('19', $acl);
echo Debug::vars('20', $mainConfg);
echo Debug::vars('21', $system);
echo Debug::vars('22', $guest);
echo Debug::vars('23', $rfid);
*/	
//вывод сообщения из сессии	

include Kohana::find_file('views','alert');
 ?>
<div class="onecolumn">
	<div class="header">

		<span><?php echo __('setting.main_title');?></span>



	</div>
	<br class="clear"/>
	<div class="content">

	<?php
	//вывод настроект system
	
	?>
		<fieldset>
						<legend><?php echo __('setting.system'); ?></legend>
						<div>
	<?php
		
		echo Form::open('settings/updateManual');

		echo Form::hidden('group', 'system');
?>	
		<table class="tablesorter-blue">
		<thead>
		<tr>
						<th>№ п/п</th>
						<th>Группа</th>
						<th>Параметр конфигурации</th>
						<th>Значение</th>
						<th>Выбор значения</th>
						<th>Пояснения</th>

						
					</tr>
		</thead>
		<tbody>
		<?php
		$countRow=0;
		
		$list=array(
		'0'=>'HEX 8 byte 00124CD8',
		'1'=>'001A 10 byte 262F8F001A',
//		'2'=>'DEC 10 digit 0001493650',
//		'4'=>'ГРЗ A123BC45'
		);
	//формат хранения номера RFID в базе данных СКУД.
			echo '<tr>';
				echo '<td>'.++$countRow.'</td>';
				echo '<td>system</td>';
				echo '<td>baseFormatRfid</td>';
				echo '<td>'.$system->baseFormatRfid.'</td>';
				echo '<td>';
					foreach($list as $key=>$value){
					    echo Form::radio('key[baseFormatRfid]', $key, $system->baseFormatRfid==$key, array('disabled'=>'disabled')).$value.' '.$key.'<br>';
					   
					}
				echo '</td>';
				echo '<td>'.' '.Kohana::message('tunermess', 'descBaseFormatRfid').'</td>';
				//echo '<td>'.Form::button('baseFormatRfid', 'baseFormatRfid').'</td>';
			echo '</tr>';
			
			
	//Показать все варианты номера идентификатора.
	$list=array(
		'0'=>'Не выводить',
		'1'=>'Выводить',
	
		);
			echo '<tr>';
				echo '<td>'.++$countRow.'</td>';
				echo '<td>system</td>';
				echo '<td>formatViewAll</td>';
				echo '<td>'.$system->formatViewAll .'</td>';
				echo '<td>';
					foreach($list as $key=>$value){
					    echo Form::radio('key[formatViewAll]', $key, $system->formatViewAll==$key).$value.'<br>';
					}
				echo '</td>';
				echo '<td>'.' '.Kohana::message('tunermess', 'formatViewAll').'</td>';
				//echo '<td>'.Form::button('formatViewAll', 'formatViewAll').'</td>';
			echo '</tr>';
			
			
			
		//}
	//формат ввода номера RFID на регистрационном считыателе
	$list=array(
		'0'=>'HEX 8 byte 00124CD8',
		'2'=>'DEC 10 digit 0001493650',
	
		);
		$list=array(
		'0'=>'baseFormatRfid',
		'2'=>'Вводить в формате DEC',
		
		);
	
	echo '<tr>';
				echo '<td>'.++$countRow.'</td>';
				echo '<td>system</td>';
				echo '<td>regFormatRfid</td>';
				echo '<td>'.$system->regFormatRfid.'</td>';
				echo '<td>';
					foreach($list as $key=>$value){
					    echo Form::radio('key[regFormatRfid]', $key, $system->regFormatRfid==$key).$value.'<br>';
					}
				echo '</td>';
				echo '<td>'.' '.Kohana::message('tunermess', 'regFormatRfid').'</td>';
				//echo '<td>'.Form::button('regFormatRfid', 'regFormatRfid').'</td>';
			echo '</tr>';
	
	$list=array(
		'0'=>'baseFormatRfid',
		'2'=>'Выводить в формате DEC',
		
		);
	/*
	echo '<tr>';
				echo '<td>'.++$countRow.'</td>';
				echo '<td>system</td>';
				echo '<td>screenFormatRFID</td>';
				echo '<td>'.$system->screenFormatRFID.'</td>';
				echo '<td>';
					foreach($list as $key=>$value){
					    echo Form::radio('key[screenFormatRFID]', $key, $system->screenFormatRFID==$key).$value.' '.$key.'<br>';
					}
				echo '</td>';
				echo '<td>'.' '.Kohana::message('tunermess', 'screenFormatRFID').'</td>';
				//echo '<td>'.Form::button('screenFormatRFID', 'screenFormatRFID').'</td>';
			echo '</tr>';
			
			$list=array(
		//'0'=>'HEX 8 byte 00124CD8',
		'001A'=>'001A 10 byte 262F8F001A',
		'DEC'=>'DEC 10 digit 0001493650',
		//'4'=>'ГРЗ A123BC45'
		);
	//При редактировании номера идентификатора показывать все его значения.
			 echo '<tr>';
				echo '<td>'.++$countRow.'</td>';
				echo '<td>system</td>';
				echo '<td>viewFromatForEdit</td>';
				echo '<td>'.$system->viewFromatForEdit.'</td>';
				echo '<td>';
					foreach($list as $key=>$value){
					    echo Form::radio('key[viewFromatForEdit]', $key, $system->viewFromatForEdit==$key).$value.'<br>';
					}
				echo '</td>';
				echo '<td>'.' '.Kohana::message('tunermess', 'viewFromatForEdit').'</td>';
				//echo '<td>'.Form::button('viewFromatForEdit', 'viewFromatForEdit').'</td>';
			echo '</tr>'; */
		
	?>
	</tbody>
		</table>
	<?php

		//echo Form::submit('saveConfig', 'Сохранить system');
		echo 'Параметры недоступны для удаленной настройки';
		echo Form::close();	
	?>	
		
						</div>
		</fieldset>	
	
	<fieldset>
						<legend><?php echo __('setting.main'); ?></legend>
						<div>
<?php

		echo Form::open('settings/updateManual');


		echo Form::hidden('group', 'main');
?>									
		<table class="tablesorter-blue">
		<thead>
		<tr>
						<th>№ п/п</th>
						<th>Группа</th>
						<th>Параметр конфигурации</th>
						<th>Значение</th>
						<th>Выбор значения</th>
						<th>Пояснения</th>
						
					</tr>
		</thead>
		<tbody>
		<?php
		$countRow=0;

	$list=array(
		'0'=>'Делать сотрудника неактивным (Уволить) с возможность восстановить',
		'1'=>'Удалять сотрудника из базы данных',
		);
		echo '<tr>';
        		echo '<td>'.++$countRow.'</td>';
				echo '<td>mainConfg</td>';
		        echo '<td>howDeletePeople</td>';
				echo '<td>'.$mainConfg->get('howDeletePeople', 0).'</td>';
				echo '<td>';
					//echo Form::input('key[howDeletePeople]', $mainConfg->howDeletePeople);
						foreach($list as $key=>$value){
					    echo Form::radio('key[howDeletePeople]', $key, $mainConfg->get('howDeletePeople', 0)==$key).$value.'<br>';
					}
				echo '</td>';
				echo '<td>'.' '.Kohana::message('tunermess', 'howDeletePeople.'.$key).'</td>';
				//echo '<td>'.Form::button('howDeletePeople', 'howDeletePeople').'</td>';
			echo '</tr>';
		//8.11.2024 я закоментировал этот раздел, т.к. этот параметр лучше настраивать в файле config_newcrm
		/* echo '<tr>';
        		echo '<td>'.++$countRow.'</td>';
				echo '<td>mainConfg</td>';
		        echo '<td>iphost</td>';
				echo '<td>'.$mainConfg->iphost.'</td>';
				echo '<td>';
					echo Form::input('key[iphost]', $mainConfg->iphost);
				echo '</td>';
				echo '<td>'.' '.Kohana::message('tunermess', 'descBaseFormatRfid.'.$key).'</td>';
				//echo '<td>'.Form::button('iphost', 'iphost').'</td>';
			echo '</tr>'; */
		
			/* echo '<tr>';
				echo '<td>'.++$countRow.'</td>';
				echo '<td>mainConfg</td>';
				echo '<td>lic</td>';
				echo '<td>'.$mainConfg->lic.'</td>';
				echo '<td>';
					echo Form::input('key[lic]', $mainConfg->lic, array('disabled'=>'disabled'));
				echo '</td>';
				echo '<td>'.' '.Kohana::message('lic', 'lic').'</td>';
				//echo '<td>'.Form::button('lic', 'lic', array('disabled'=>'disabled')).'</td>';
			echo '</tr>'; */
		
	/* 	echo '<tr>';
				echo '<td>'.++$countRow.'</td>';
				echo '<td>mainConfg</td>';
				echo '<td>odbcname</td>';
				echo '<td>'.$mainConfg->odbcname.'</td>';
				echo '<td>';
					echo Form::input('key[odbcname]', $mainConfg->odbcname);
				echo '</td>';
				echo '<td>'.' '.Kohana::message('tunermess', 'descBaseFormatRfid.'.$key).'</td>';
				//echo '<td>'.Form::button('odbcname', 'odbcname').'</td>';
			echo '</tr>'; */
			
			echo '<tr>';
				echo '<td>'.++$countRow.'</td>';
				echo '<td>mainConfg </td>';
				echo '<td>orgname</td>';
				echo '<td>'.$mainConfg->orgname.'</td>';
				echo '<td>';
					echo Form::input('key[orgname]', $mainConfg->orgname);
				echo '</td>';
				echo '<td>'.' '.Kohana::message('tunermess', 'descBaseFormatRfid.'.$key).'</td>';
				//echo '<td>'.Form::button('orgname', 'orgname').'</td>';
			echo '</tr>';
			/* echo '<tr>';
				echo '<td>'.++$countRow.'</td>';
				echo '<td>mainConfg</td>';
				echo '<td>sysVer</td>';
				echo '<td>'.$mainConfg->sysVer.'</td>';
				echo '<td>';
					echo Form::input('key[sysVer]', $mainConfg->sysVer, array('disabled'=>'disabled'));
				echo '</td>';
				echo '<td>'.' '.Kohana::message('tunermess', 'descBaseFormatRfid.'.$key).'</td>';
				//echo '<td>'.Form::submit('sysVer1', 'sysVer2').'</td>';
			echo '</tr>'; */
			

	?>
	</tbody>
		</table>
			
	<?php

		echo Form::submit('saveConfig', 'Сохранить main');
		echo Form::close();	
	?>		
						</div>
		</fieldset>	
	
		<fieldset>
						<legend><?php echo __('setting.contact'); ?></legend>
						<div>
<?php

		echo Form::open('settings/updateManual');


		echo Form::hidden('group', 'contact');
		$list=array(//список полей, для которых будет применяться правильно Обязателен
		'surname'=>'Фамилия',
		'name'=>'Имя',
		'patronymic'=>'Отчество',
		'post'=>'Должность',
	
		);
?>									
		<table class="tablesorter-blue">
		<thead>
		<tr>
						<th>№ п/п</th>
						<th>Группа</th>
						<th>Параметр конфигурации</th>
						<th>Значение</th>
						<th>Выбор значения</th>
						<th>Пояснения</th>
						
					</tr>
		</thead>
		<tbody>
		<?php
		$groupConfig=$contact;// объявление см. выше
		$countRow=0;

		echo '<tr>';
        		echo '<td>'.++$countRow.'</td>';
				echo '<td>Contact</td>';
		        echo '<td>fieldsRequired</td>';
				echo '<td>';
					foreach($groupConfig->fieldsRequired as $key) {
						echo $key;
						};
				echo '</td>';
				echo '<td>';
						foreach($list as $key=>$value){
							//echo Debug::vars('354', $groupConfig->fieldsRequired, $key);
							//echo Form::hidden('key[is_array]',false);
							echo Form::checkbox('key[fieldsRequired]['.$key.']', $key, array_key_exists($key, $groupConfig->fieldsRequired)).$value.'<br>';
						}
				echo '</td>';
				echo '<td>'.' '.Kohana::message('tunermess', 'fieldsRequired.'.$key).'</td>';
				
			echo '</tr>';
		

			

	?>
	</tbody>
		</table>
			
	<?php

		echo Form::submit('saveConfig', 'Сохранить contact');
		echo Form::close();	
	?>		
						</div>
		</fieldset>	
	
	

	
	<fieldset>
						<legend><?php echo __('setting.constnant'); ?></legend>
						<div>

	<table class="tablesorter-blue">
			<thead>
			<tr>
						<th>№ п/п</th>
						<th>Константа</th>
						<th>Значение</th>
			</tr>
			</thead>
			<tbody>
				<?php
				$reflectionClass = new ReflectionClass('constants');
				$constants = $reflectionClass->getConstants();
				foreach($constants as $key=>$value)
				{
				echo '<tr>';
					echo '<td>'.++$countRow.'</td>';
					echo '<td>'.$key.'</td>';
					echo '<td>'.$value.'</td>';
				echo '</tr>';
				
				}
				
				
				?>
			</tbody>
		</table>				
				</div>
		</fieldset>	
		
	<fieldset>
						<legend><?php echo __('setting.database'); ?></legend>
						<div>
						<?php

	$_connectName='fb';
			$about=Model::factory('Parkdb')->aboutDB($_connectName);
			//echo Debug::vars('22', $about);
			
		?>
		<table class="tablesorter-blue">


			<tr>
				<td>Имя подключения</td>
				<td><?php echo iconv('CP1251','UTF-8',  Arr::get($about, 'connectName')); ?></td>
			</tr>
			<tr>
				<td>Тип подключения</td>
				<td><?php echo iconv('CP1251','UTF-8', Arr::get($about, 'dsn')); ?></td>
			</tr>
			<tr>
				<td>Путь к базе данных</td>
				<td><?php echo iconv('cp866','UTF-8//IGNORE', Arr::get($about, 'pathDB'));?>
				
			</td>
			<tr>
				<td>IP</td>
				<td><?php echo iconv('cp866','UTF-8//IGNORE', Arr::get($about, 'Server'));?>
				
			</td>
			
			
			</tr>
			
			

		</table>
				</div>
		</fieldset>	
		
		

	</div>
</div>
