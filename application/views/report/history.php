<div class="onecolumn">
	<div class="header">
		<span><?php echo __('contact.history') ; ?></span>
<?php
	//echo $topbuttonbar;
    //echo Debug::vars('6', ::get('reportdatestart'));
    //echo Debug::vars('6', ::get('reportdateend'));
    //echo Debug::vars('6', $data); exit;
//$data=array();
	?>
	</div>
	<br class="clear" />
    <div class="content">
        <form action="reports/getEventPeriod" method="post" onsubmit="return validate()">

            <table cellspacing="5" cellpadding="5">
                <tbody>
                <tr>
                    <th align="right" style="padding-right: 10px;">
                        <label for="reportdatestart"><?php echo __('report.datestart'); ?></label>
                    </th>
                    <td>
                        <div style="padding-bottom: 10px;">

                            <input type="text" size="12" name="reportdatestart" id="carddatestart" value="<?php
                            echo Cookie::get('reportdatestart', Date::formatted_time('now', "d.m.Y"));														?>" />
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
                            echo Cookie::get('reportdateend', Date::formatted_time('tomorrow', "d.m.Y"));														?>" />
                            <br>
                            <span class="error" id="error3" style="color: red; display: none;"><?php echo __('report.wrongendtime'); ?></span>
                        </div>
                    </td>
                </tr>


                </tbody>
            </table>

            <?php

            
           // echo Form::hidden('id_pep', $contact['ID_PEP']);
            
            //echo Form::hidden('todo', 'wtOncePep');
            echo Form::submit(NULL, __('button.reportEvents'));
            echo Form::close();
			echo __('Внимание! при подготовке отчета выбирается не более 10000 событий. При необходимости получить отчет большего размера разбейте его на несколько частей.');
          	
            ?>


    </div>

	<div class="content">


		<?php 

		echo __('Надено :count событий', array(':count'=>count($data)));
		echo Form::open('reports/saveHistoryXlsx');
		$forsave=array();

		
		echo Form::submit('savexlsx', __('button.savexlsx'));
		echo Form::hidden('dateFrom', Cookie::get('reportdatestart', date('d.m.Y')));
        echo Form::hidden('dateTo', Cookie::get('reportdateend', date('d.m.Y')));
		
		$titleTH=array();
		//$titleTH[]= __('eventlog.event_id');
		$titleTH[]= __('history.date');
		$titleTH[]= __('history.doorname');
		$titleTH[]= __('history.event');
		$titleTH[]= __('contacts.name');
		$titleTH[]= __('contacts.post');
		echo Form::hidden('titleTH', serialize($titleTH));
		//echo Form::hidden('id_pep', Arr::get($contact, 'ID_PEP'));
		
		if (count($data) > 0) { ?>
		<table class="data" width="100%" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
							
					<?php
					
					foreach($titleTH as $key=>$value)
					{
					    //echo Debug::vars('81', $key, $value); exit;
					    echo '<th>'. $value.'</th>'; 
					}
					
					?>
				</tr>
			</thead>
			<tbody>
				<?php 
				$t1=microtime(true);
				foreach ($data as $h) {
                   // echo Debug::vars('81',Arr::get($h, 'ID_EVENT')); exit;
                   
                    //$event=new EventMonitor(Arr::get($h, 'ID_EVENT'));
                    $event=new AkrihinMonitor(Arr::get($h, 'ID_EVENT'));
					//echo Debug::vars('109',$event );exit;

				echo '<tr>';
				//echo '<td>'. $event->id_event.Debug::vars('109',$event ).'</td>';
				//echo '<td>'. $event->id_event.'</td>';
				    echo '<td>'. $event->timestamp.'</td>';
					echo '<td>'. iconv('CP1251', 'UTF-8', $event->eventPlace).'</td>';
					echo '<td>'. iconv('CP1251', 'UTF-8',$event->name).'</td>';
					echo '<td>'. $event->note.'</td>';
					echo '<td>'. $event->addData.'</td>';
					 
					//$forsave[Arr::get($h, 'ID_EVENT')][]=$event->id_event;
					$forsave[Arr::get($h, 'ID_EVENT')][]=$event->timestamp;
					$forsave[Arr::get($h, 'ID_EVENT')][]=iconv('CP1251', 'UTF-8', $event->eventPlace);
					$forsave[Arr::get($h, 'ID_EVENT')][]=iconv('CP1251', 'UTF-8', $event->name).')';
					$forsave[Arr::get($h, 'ID_EVENT')][]=$event->note;
					$forsave[Arr::get($h, 'ID_EVENT')][]=$event->addData;
					
				echo '</tr>';
				} 
				?>
			</tbody>
		</table>
		<?php 
		echo Debug::vars('132', (microtime(true)-$t1));
		} else { ?>
		<div style="margin: 100px 0; text-align: center;">
			<?php echo __('history.empty'); ?><br><br>
		</div>
		<?php } ?>
			<?php 	
			//echo Debug::vars('129',serialize($forsave));exit;
			echo Form::hidden('forsave', serialize($forsave));
			
			
			echo Form::close();
			?>
	</div>
</div>
