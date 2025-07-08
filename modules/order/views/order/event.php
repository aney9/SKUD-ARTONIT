<?php
//Форма отражения события в истории Гости

//echo Debug::vars('4', $contact);
//echo Debug::vars('5', $data);
//echo Debug::vars('6', $id);

?>
<div class="onecolumn">
	<div class="header">
		<span><?php echo __('passoffice.event', array(':from'=>$dateFrom,':to'=>$dateTo )); ?></span>
		
	</div>
	<br class="clear" />
	<div class="content">
		<?php
		
		if (count($data) > 0) { ?>
		<table class="data" width="100%" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					
					<th style="width:10%"><?php echo __('history.date'); ?></th>
					<th style="width:15%"><?php echo __('history.event'); ?></th>
					<th style="width:30%"><?php echo __('history.eventadd'); ?></th>
					<th><?php echo __('history.any'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php 
				//echo Debug::vars('31', $data); exit;
				foreach ($data as $key=>$h) { 
				   
				$event=new Eventguest(Arr::get($h, 'ID_EVENT')); 
				
				//echo Debug::vars('31', $event); exit;
				?>
				
				<tr>
					
					<td><?php echo date ('H:i:s d.m.Y ', strtotime($event->eventtime)); ?></td>
					<td><?php echo iconv('CP1251', 'UTF-8', $event->eventname).' ('.$event->eventtype.')'; ?></td>
					<td><?php echo iconv('CP1251', 'UTF-8', $event->eventnameadd); ?></td>
					
					<td><?php 
						echo iconv('CP1251', 'UTF-8', $event->evendesc).' ';
						if (($event->eventtype == 46) or ($event->eventtype == 50) or ($event->eventtype == 65)) echo $event->ap_is_exit ? HTML::image('static/images/redo.png', array('alt'=>'edit', 'class'=>'help', 'title'=>__('redo'), 'width'=>'16')) : HTML::image('static/images/green-check.png', array('alt'=>'edit', 'class'=>'help', 'title'=>__('enter'))); ?>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php } else { ?>
		<div style="margin: 100px 0; text-align: center;">
			<?php echo __('history.empty'); ?><br /><br />
		</div>
		<?php } ?>
		
		
	</div>
</div>
