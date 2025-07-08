<?php
//echo Debug::vars('2', $cards);
$catdTypelist = Model::factory('Card')->getcatdTypelist();//получил список типов идентификаторов
?>
<div class="onecolumn">
	<div class="header">
		<span><?php echo __('contact.cardlist') . ' - ' . iconv('CP1251', 'UTF-8', $contact['NAME'] . ' ' . $contact['SURNAME']); ?></span>
<?php if ($contact) {
			echo $topbuttonbar;
			
		} ?>
	</div>
	<br class="clear" />
	<div class="content">
		<?php if (count($cards) > 0) { ?>
		<form id="form_data" name="form_data" action="" method="post">
			<table class="data" width="100%" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<!--
						<th style="width:10px">
							<input type="checkbox" id="check_all" name="check_all"/>
						</th>
						-->
						<th style="width:20%"><?php echo __('cards.code'); ?></th>
						<th style="width:30%"><?php echo __('cards.id_cardtype'); ?></th>
						<th style="width:30%"><?php echo __('cards.datestart'); ?></th>
						<th style="width:30%"><?php echo __('cards.dateend'); ?></th>
						<th style="width:20%"><?php echo __('cards.active'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php 
					//echo Debug::vars('34', $cards);//exit;
					foreach ($cards as $card) { 
					$cardtype=Arr::get($catdTypelist, $card['ID_CARDTYPE']);
					$key=new Keyk($card['ID_CARD']);?>
					<tr>
						<!--
						<td>
							<input type="checkbox" />
						</td>
						-->
						<td><?php echo $key->id_card.' ('.$key->id_card_on_DEC.')'; ?></td>
						<td><?php echo iconv('CP1251', 'UTF-8', Arr::get($cardtype, 'smallname')); ?></td> 
						<td><?php echo $key->timestart ; ?></td>
						<td><?php echo $key->timeend; ?></td>
						<td><?php echo $key->is_active == 1 ? __('yes') : __('no'); ?></td>

					</tr>
					<?php } ?>
				</tbody>
			</table>
			<div id="chart_wrapper" class="chart_wrapper"></div>
		<!-- End bar chart table-->
		</form>
		<?php } else { ?>
			<div style="margin: 100px 0; text-align: center;">
				<?php echo __('cards.none'); ?><br /><br />
			</div>
		<?php } ?>
		<br />
		<?php if (!$contact) {?>
		<input type="button" value="<?php echo __('cards.create'); ?>" onclick="location.href='<?php echo URL::base() . 'guests/addcard/' . $contact['ID_PEP']; ?>'" />
		<input type="button" value="<?php echo __('cards.create_grz'); ?>" onclick="location.href='<?php echo URL::base() . 'guests/addgrz/' . $contact['ID_PEP']; ?>'" />
		<?php } ?>
		
	</div>
</div>
