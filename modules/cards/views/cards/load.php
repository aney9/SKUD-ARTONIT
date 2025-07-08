<?php
include Kohana::find_file('views','alert');
 if (isset($alert)) { ?>
<div class="alert_success">
	<p>
		<img class="mid_align" alt="success" src="images/icon_accept.png" />
		<?php echo $alert; ?>
	</p>
</div>
<?php } ?>
<div class="onecolumn">
	<div class="header">
		<span>
			<?php 
			
			switch($mode) {
				case('new'):
					;
				break;
				
				case('edit'):
					echo __('card.titleLoad', array (':id_card'=>$key->id_card, ':id_card_on_DEC'=>$key->id_card_on_DEC));
				break;
				
				case('fired'):
					
				break;
				default:
					echo __('form.editCard');
				break;
			}
				
				
				?>
		</span>
		<div class="switch">
			<table cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td>
						<?php echo HTML::anchor('/cards/edit/' . $key->id_card, __('card.common'), array('class' => 'left_switch ')); ?>
					</td>

					<td>
						<?php echo HTML::anchor('cards/load/' . $key->id_card, __('card.load'), array('class' => 'middle_switch active')); ?>
					</td>
					<td>
						<?php echo HTML::anchor('cards/history/' . $key->id_card, __('card.history'), array('class' => 'right_switch')); ?>
					</td>
				</tr>
			</tbody>
			</table>
		</div>
	</div>
	<br class="clear" />
	<div class="content">
		<div id="cardinfo">
		<form action="cards/loadcard" method="post" onsubmit="return validatecard()">
			<input type="hidden" name="hidden" value="form_sent" />
			<input type="hidden" name="id" value="<?php echo $key->id_pep; ?>" />
			<input type="hidden" name="id_cardtype" value="1" />


			<br />

			<div id="cardhistory">
			<br />
			<h3><?php echo __('cards.loadhistory'); ?></h3>
			<?php
		include Kohana::find_file('views', 'paginatoion_controller_template'); 
		$sn=0;
?>
			<table class="data tablesorter-blue" width="60%" cellpadding="0" cellspacing="0" id="tablesorter" >
				<thead>
					<tr>
						<th class="filter-false sorter-false"><?php echo __('sn'); ?></th>
						<th style=""><?php echo __('queue.door_id'); ?></th>
						<th style=""><?php echo __('load.device'); ?></th>
						<th style="width:20%"><?php echo __('load.date'); ?></th>
						<th style=""><?php echo __('load.status'); ?></th>
						<th style=""><?php echo __('load.insert'); ?></th>
						<th style=""><?php echo __('load.in_order'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($loads as $load) { ?>
					<tr>
						<td><?php echo ++$sn; ?></td>
						<td><?php echo Arr::get($load,'ID_DEV'); ?></td>
						<td><?php echo iconv('CP1251', 'UTF-8', Arr::get($load,'NAME')); ?></td>
						<td><?php echo  Arr::get($load,'LOAD_TIME')? Arr::get($load,'LOAD_TIME') : __('device.no_data_about_load_time') ;?></td>
						<td><?php echo  (iconv('CP1251', 'UTF-8', Arr::get($load,'LOAD_RESULT')))? iconv('CP1251', 'UTF-8', Arr::get($load,'LOAD_RESULT')) : __('device.no_data_about_load_result') ;?></td>
						<td><?php echo  Arr::get($load,'TIME_STAMP')? Arr::get($load,'TIME_STAMP') : __('device.no_data_about_load_time_stamp') ;?></td>
						<td><?php echo  Arr::get($load,'OPERATION')? __('device.card_in_order_from_load') : __('device.card_not_in_order_from_load') ;?></td>
					</tr>
					<?php } ?>
					<?php if (count($loads) == 0) { ?>
					<tr> 
						<td colspan="5" style="text-align: center; line-height: 300%">
							<?php echo __('cards.nohistory'); ?>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<br />

					<input type="button" value="<?php echo __('cards.reload'); ?>" onclick="reload('<?php echo $key->id_card; ?>')" />
					<br />
				</div>


			<br />
			<br />
			

		</form>
		</div>
	</div>
</div>
