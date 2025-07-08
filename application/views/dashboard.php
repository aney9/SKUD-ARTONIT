<?php 



//область для быстрого тестирования. Эта область показывается при обращении по адресу http://127.0.0.1/crm2/
if (false)
{
	//вывод версий всех модулей
// список зарегистрированных модулей
$modules = Kohana::modules();
//echo Debug::vars('6', $modules);
?>
<table class="table table-hover">
	<thead>
	<tr>
		<th>Название модуля</th>
		<th>Версия модуля</th>
		<th>Дата обновления</th>
		<th>Справка</th>
	</tr>
	</thead>
	<tbody>

<?
foreach($modules as $key=>$value)
{
	echo '<tr>';
		//echo Debug::vars('10', $key, $value);
//	echo Debug::vars('11', Kohana::find_file('/config'.$key, 'config'));
	//	echo Debug::vars('11', Kohana::find_file($key.'/config', 'config')->get('buld'));
			$conf=Kohana::$config->load($key.'\config');
			echo '<td>'.$key.'</td>';
			echo '<td>'.Arr::get($conf, 'build','---').'</td>';
			echo '<td>'.Arr::get($conf, 'builddate','---').'</td>';
			echo '<td>'.HTML::anchor('guide/'.$key, $key).'</td>';
		echo '</tr>';
	//echo Debug::vars('14', $conf);
}
?>
</tbody>
</table>

<?php
}?>