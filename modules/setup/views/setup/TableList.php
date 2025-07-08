<?php
//echo Debug::vars('2', $tableList); //exit;
//echo Debug::vars('3', $tableListCheck); //exit;
//echo Debug::vars('4', $procedureList); //exit;
//echo Debug::vars('5', $procedureListCheck); //exit;

?>
<script type="text/javascript">
     
  	$(function() {		
  		$("#tablesorter_ge").tablesorter({sortList:[[0,0]], headers: {}});
  	});	
</script>			

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo __('Свойства базы данных СКУД');?></h3>
	</div>
	
	<div class="panel-body">
			Подключение к базе данных:
		<?php
		echo Form::open('Checkdb/worker');
			$_connectName='fb';
			$about=Model::factory('Parkdb')->aboutDB($_connectName);
			echo Debug::vars('22', $about);
			
		?>
		<table class="table table-striped table-hover table-condensed">


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


		Таблицы
			<table id="tablesorter_ge" class="table table-striped table-hover table-condensed tablesorter">
			<thead>
				<tr>
					<th><?echo __('№ п/п');?></th>
					<th><?echo __('Таблица');?></th>
					<th><?echo __('Описание');?></th>
					<th><?echo __('Наличие таблицы.');?></th>
					<th><?echo __('Добавить таблицу');?></th>
					<th><?echo __('Удалить таблицу');?></th>
					<th><?echo __('Добавить данные');?></th>
					<th><?echo __('Удалить данные');?></th>
				</tr>
			</thead>
			<tbody>
			<?php 
			$i=0;
			foreach($tableList as $key=>$value)
			{
			echo '<tr>';
					echo '<td>'.++$i.'</td>';
					
					echo '<td>';
						echo $value;
					echo '</td>';
					echo '<td>';
					
						$_data=Arr::get(Model::factory('Parkdb')->aboutTable($value), 'RDB$DESCRIPTION');
						//echo Debug::vars('42', iconv('windows-1251','UTF-8', $_data ));
						echo iconv('windows-1251','UTF-8', $_data );
					
					echo '</td>';
					echo '<td>';
					echo Arr::get($tableListCheck, $value)? HTML::image('static/images/green-check.png', array('alt' => 'true')) : 'false';
					
					//echo Debug::vars(Arr::get($tableListCheck, $value));
					echo '</td>';
					echo '<td>'.Form::button('addTable', 'Добавить таблицу', array('value'=>$value, 'disabled'=>'disabled')).'</td>';
					echo '<td>'.Form::button('delTable', 'Удалить таблицу', array('value'=>$value, 'disabled'=>'disabled')).'</td>';
					if(Arr::get($tableListCheck, $value))
					{
						//проверка, что для этой таблицы есть данных для записи (может и не быть)
						//echo Debug::vars('51', $value, $dataList, in_array($value, $dataList));//exit;
						if(in_array($value, $dataList))
						{
							echo '<td>'.Form::button('addTableData', 'Добавить данные', array('value'=>$value, 'disabled'=>'disabled')).'</td>';
						} else {
							//echo '<td>'.Form::button('addTableData', 'Добавить данные', array('disabled'=>'disabled')).'</td>';
							echo '<td>-</td>';
						}
						echo '<td>'.Form::button('delTableData', 'Удалить данные', array('value'=>$value, 'disabled'=>'disabled')).'</td>';
					} else 
					{
						echo '<td>-</td>';
						echo '<td>-</td>';
							
					}
				echo '</tr>';	
			}	
			?>
			</tbody>
		</table>	

<?php
	echo Form::button('addAllTable', 'Добавить все таблицы, процедуры и генераторы', array('value'=>$value));
	echo Form::button('delAllTable', 'Удалить все таблицы, процедуры и генераторы', array('value'=>$value));

?>	
		<h2>Процедуры</h2>
		<table id="tablesorter_ge2" class="table table-striped table-hover table-condensed tablesorter">
		<thead>
			<tr>
				<th><?echo __('№ п/п');?></th>
				<th><?echo __('Название процедуры');?></th>
				<th><?echo __('Прецедура установлена.');?></th>
				<th><?echo __('Добавить процедуру');?></th>
				<th><?echo __('Удалить процедуру');?></th>
			</tr>
		</thead>
		<tbody>
		<?php 
		$i=0;
		foreach($procedureList as $key=>$value)
		{
		echo '<tr>';
				echo '<td>'.++$i.'</td>';
				
				echo '<td>'.$value.'</td>';

				echo '<td>';
				echo Arr::get($procedureListCheck, $value)? HTML::image('static/images/green-check.png', array('alt' => 'true')) : 'false';
				echo '</td>';
				//echo '<td>'.Form::button('addProcedure', 'Добавить процедуру', array('value'=>$value, 'disabled'=>'disabled')).'</td>';
				//echo '<td>'.Form::button('delProcedure', 'Удалить процедуру', array('value'=>$value, 'disabled'=>'disabled')).'</td>';
				echo '<td>'.Form::button('addProcedure', 'Добавить процедуру', array('value'=>$value)).'</td>';
				echo '<td>'.Form::button('delProcedure', 'Удалить процедуру', array('value'=>$value)).'</td>';
				
			echo '</tr>';	
		}	
		?>
		</tbody>
	</table>		
	
		<h2>Триггеры</h2>
		<table id="tablesorter_ge3" class="table table-striped table-hover table-condensed tablesorter">
		<thead>
			<tr>
				<th><?echo __('№ п/п');?></th>
				<th><?echo __('Название триггера');?></th>
				<th><?echo __('Тригггер установлен.');?></th>
				<th><?echo __('Добавить триггер');?></th>
				<th><?echo __('Удалить триггер');?></th>
			</tr>
		</thead>
		<tbody>
		<?php 
		$i=0;
		foreach($triggerList as $key=>$value)
		{
		echo '<tr>';
				echo '<td>'.++$i.'</td>';
				
				echo '<td>'.$value.'</td>';

				echo '<td>';
				echo Arr::get($triggerListCheck, $value)? HTML::image('static/images/green-check.png', array('alt' => 'true')) : 'false';
				echo '</td>';
				
				echo '<td>'.Form::button('addTrigger', 'Добавить триггер', array('value'=>$value)).'</td>';
				echo '<td>'.Form::button('delTrigger', 'Удалить триггер', array('value'=>$value)).'</td>';
				
			echo '</tr>';	
		}	
		?>
		</tbody>
	</table>		
	
		
		
	<?php

	echo Form::close();
	?>	
	</div>
</div>

	

