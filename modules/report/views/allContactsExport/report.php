<?php
//echo $topbuttonbar;
    //echo Debug::vars('6', $report); exit;
   // echo Debug::vars('7', $user);// exit;
	
	$org=new Company($user->id_orgctrl);
	
?>

<div class="onecolumn">
	<div class="header">
		<span><?php echo __('Отчет Экспорт контактов организации "'.iconv('CP1251', 'UTF-8',$org->name).'".');
		?></span>
<?php
	
	//echo Debug::vars('11', $org);//exit;
	
$data=array();
if(isset($report)) $data=$report;//если установлен $report, то беру значения из перемнной.
$ruid='allContactsExport';
	?>
	</div>
	<br class="clear">
    <div class="content">
        <form action="mreports/makeReport" method="post" onsubmit="return validate()">
        <?php
			//передаю RUID отчета
			
			
			echo Form::hidden('id_report', $ruid);
			echo Form::hidden('fileName', 'Список контактов');

			echo Form::submit(NULL, __('button.allContactsExport'));
            echo Form::close();
        ?>


    </div>

	<div class="content">


		<?php 

//вывод содержимого файла на экран		
		
		echo Form::open('mreports/export');

		echo Form::submit('savecsv', __('button.savecsv'));
	
	
		
		
		$t1=microtime(true);
		if (count($data) > 0) { 
		exit;
		  $tempFile=new tempCSV;
		  $tempFile->getFile($data->fileName);
		  
		  echo Debug::vars('60', $data->fileName);//exit;
		  //echo Debug::vars('62', Kohana::find_file('/',$data->fileName, 'tmp'));exit;
		  echo Debug::vars('62', $tempFile, $tempFile->getRow());exit;
		  
		
		$showRowCount=200;
		$rowCount=count($data->rowData);
		echo __('<p>Отчет содержит :count записей.</p>', array(':count'=>$rowCount));
		
		$dataRow=array();
		$dataRow=$data->rowData;
		// если количество записей большое, то на экран вывожу только первые showRowCount записей
		if($rowCount>$showRowCount){
			echo __('Будут показаны первые :showRowCount записей. Нажмите Экспорт чтобы получить весь отчет.', array(':showRowCount'=>$showRowCount));
			$dataRow=array_slice($dataRow,0, $showRowCount);
		}
		
		//echo Debug::vars('72', $dataRow);exit;
		?>
		<table class="data" width="100%" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
							
					<?php
					
					foreach($data->titleColumn  as $key=>$value)
					{
					    //echo Debug::vars('81', $key, $value); exit;
					    echo '<th>'. $value.'</th>'; 
					}
					
					?>
				</tr>
			</thead>
			<tbody>
				<?php 
				
				foreach ($dataRow as $h) {
                // echo Debug::vars('74', $h);exit;
				
				
				  echo '<tr>'; 
				  /*  echo '<td>'. Arr::get($h, 'ORGNAME').'</td>';
					//echo '<td>'. Arr::get($h, 'NAME').' '.Arr::get($h, 'SURNAME').' '.Arr::get($h, 'PATRONYMIC').'</td>';
					echo '<td>'. Arr::get($h, 'FIO').'</td>';
					echo '<td>'. Arr::get($h, 'ID_CARD').'</td>';
					echo '<td>'. Arr::get($h, 'ACNAME').'</td>';
					echo '<td>'. Arr::get($h, 'TIME_STAMP').'</td>'; */
					foreach($h as $key2=>$value2)
					{
						echo '<td>'. $value2.'</td>';
					}
				echo '</tr>';
			
				
				} 
				?>
			</tbody>
		</table>
		<?php 
		//echo __('Время выполнения :timeexec сек.', array(':timeexec'=>(microtime(true)-$t1)));
		} else { ?>
		<div style="margin: 100px 0; text-align: center;">
			<?php echo __('history.empty'); ?><br><br>
		</div>
		<?php } ?>
			<?php 	
			

			echo Form::close();
			?>
	</div>
</div>
