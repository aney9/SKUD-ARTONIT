<div class="onecolumn">
	<div class="header">
		<span><?php echo __('contact.history') ; ?></span>
<?php
	//echo $topbuttonbar;
    //echo Debug::vars('6', ::get('reportdatestart'));
    //echo Debug::vars('6', ::get('reportdateend'));
    //echo Debug::vars('6', $data); exit;
    //echo Debug::vars('6', $report); exit;
$data=array();
if(isset($report)) $data=$report;
$ruid='history2';//r(eport)uid
	?>
	</div>
	<br class="clear">
    <div class="content">
        <form action="mreports/makeReport" method="post" onsubmit="return validate()">
		

            <table cellspacing="5" cellpadding="5">
                <tbody>
                <tr>
                    <th align="right" style="padding-right: 10px;">
                        <label for="reportdatestart"><?php echo __('report.datestart'); ?></label>
                    </th>
                    <td>
                        <div style="padding-bottom: 10px;">

                            <input type="text" size="12" name="reportdatestart" id="carddatestart" value="<?php
                            echo Cookie::get('reportdatestart', Date::formatted_time('now', "d.m.Y"));?>" />
                            <br>
                            <span class="error" id="error2" style="color: red; display: none;"><?php echo __('report.emptystarttime'); ?></span>
                        </div>
                    </td>
                </tr>

                <tr>
                    <th align="right" style="padding-right: 10px;">
                        <label for="reportdateend"><?php echo __('report.dateend'); ?></label>
                    </th>
                    <td>
                        <div style="padding-bottom: 10px;">
                            <input type="text" size="12" name="reportdateend" id="carddateend" value="<?php
                            echo Cookie::get('reportdateend', Date::formatted_time('tomorrow', "d.m.Y"));?>" />
                            <br>
                            <span class="error" id="error3" style="color: red; display: none;"><?php echo __('report.wrongendtime'); ?></span>
                        </div>
                    </td>
                </tr>


                </tbody>
            </table>

            <?php
			//передаю RUID отчета
			
			echo Form::hidden('id_event[]', 50);
			echo Form::hidden('id_event[]', 65);
			echo Form::hidden('id_report', $ruid);
            
           // echo Form::hidden('id_pep', $contact['ID_PEP']);
            
            //echo Form::hidden('todo', 'wtOncePep');
            echo Form::submit(NULL, __('button.reportEvents'));
            echo Form::close();
			//echo __('Внимание! при подготовке отчета выбирается не более 10000 событий. При необходимости получить отчет большего размера разбейте его на несколько частей.');
          	
            ?>


    </div>

	<div class="content">


		<?php 

		if( $data instanceof Report) {//если есть переменная дата $data типа Report, то организую вывод данных в таблицу
		
		echo Form::open('mreports/export');
		echo Form::submit('savecsv', __('button.savecsv'));
	
		//echo Debug::vars('96', $data, $data instanceof Report );exit;
		
		$t1=microtime(true);
		if ($data->totalCountRow>1) { 
		
			echo __('<p>Отчет содержит :count событий.</p>', array(':count'=>$data->totalCountRow));
			echo __('<p>Отчет подготовлен за :count секунд.</p>', array(':count'=>$data->timeExecute));
			echo __('<p>Будут показаны первые 100 событий.</p>');
			
			
			  $tempFile=new tempCSV;
			  $tempFile->getFile();
			 // echo Debug::vars('96', $tempFile->getRow());exit;
			  
			?>
			<table class="data" width="100%" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
								
						<?php
						//echo Debug::vars('109', $tempFile->getRow());exit;
						foreach($tempFile->getRow()  as $key=>$value)
						{
							echo '<th>'. $value.'</th>'; 
						}
						
						?>
					</tr>
				</thead>
				<tbody>
					<?php 
				$countMax=100;
				if(($data->totalCountRow-1) < $countMax) $countMax=$data->totalCountRow-1;		
				for($i=0; $i<$countMax; $i++)
				{
					echo '<tr>';
					foreach($tempFile->getRow() as $key=>$value)
					{
							echo '<td>'. $value.'</td>'; 
					}
					echo '</tr>';				
				}?>
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
			
			echo Form::hidden('filename', $data->fileName);
			echo Form::close();
		}
			?>
	</div>
</div>
