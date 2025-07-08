<?php
//echo Debug::vars('2', $cards);
//echo Debug::vars('3', Session::instance());
include Kohana::find_file('views','alert');
						$user=new User();
						$acl=new Acl(true);
						if($acl->is_allowed($user->role,'organization', 'read')){
							$dis1='disabled="disbled"';
							$dis1_arr='disabled'>'disbled';
							
							$dis2='class="disabled"';
							$dis2_lighten='lighten';
							$dis3='';
						};
						if($acl->is_allowed($user->role,'organization', 'create')){
							$dis1='disabled="disbled"';
							$dis1_arr='';
							$dis2='class="disabled"';
							$dis2_lighten='lighten';
							$dis3='';
						};
						if($acl->is_allowed($user->role,'organization', 'update')){
							$dis1='';
							$dis1_arr='';
							$dis2='';
							$dis2_lighten='';
							$dis3='';
						};
						if($acl->is_allowed($user->role,'organization', 'delete')){
							$dis1='';
							$dis1_arr='';
							$dis2='';
							$dis2_lighten='';
							$dis3='';
						};
$catdTypelist = Model::factory('Card')->getcatdTypelist();//получил список типов идентификаторов

?>
<div class="onecolumn">
	<div class="header">
		<span>
		<?php 
			
			switch($mode) {
				case('new'):
					;
				break;
				
				case('edit'):
					echo __('contact.titleCardList', array( 
					':name'=> iconv('CP1251', 'UTF-8', $contact->name),
					':surname'=> iconv('CP1251', 'UTF-8', $contact->surname),
					':patronymic'=> iconv('CP1251', 'UTF-8', $contact->patronymic)));
				break;
				
				case('fired'):
					echo __('contact.titlefiredContact', array( 
					':name'=> iconv('CP1251', 'UTF-8', $contact->name),
					':surname'=> iconv('CP1251', 'UTF-8', $contact->surname),
					':patronymic'=> iconv('CP1251', 'UTF-8', $contact->patronymic)));
				break;
				default:
					echo __('form.editContact');
				break;
			}
				
			
				?>
		</span>
	<?php
	echo $topbuttonbar;	
	?>
	</div>
	<br class="clear" />
	<div class="content">
		<?php if (count($cards) > 0) { ?>
		
		
<?php
		include Kohana::find_file('views', 'paginatoion_controller_template'); 
		$sn=0;
?>


		<form id="form_data" name="form_data" action="" method="post">
			<table class="data tablesorter-blue" width="60%" cellpadding="0" cellspacing="0" id="tablesorter" >
				<thead>
					<tr>
						<!--
						<th style="width:10px">
							<input type="checkbox" id="check_all" name="check_all"/>
						</th>
						-->
						<th class="filter-false sorter-false"><?php echo __('sn'); ?></th>
						<th><?php echo __('cards.code'); ?></th>
						<th><?php echo __('cards.type'); ?></th>
						<th><?php echo __('cards.datestart'); ?></th>
						<th><?php echo __('cards.dateend'); ?></th>
						<th class="filter-false sorter-false"><?php echo __('cards.active'); ?></th>
						<th class="filter-false sorter-false"><?php echo __('cards.action'); ?></th>
						
					</tr>
				</thead>
				<tbody>
					<?php foreach ($cards as $card) { 
					$cardtype=Arr::get($catdTypelist, $card['ID_CARDTYPE']);
					$key=new Keyk($card['ID_CARD']);
					
					?>
					<tr>
						<!--
						<td>
							<input type="checkbox" />
						</td>
						-->
						<td><?php echo ++$sn; ?></td>
						<td><?php 
						
					
							echo HTML::anchor('cards/edit/' . $key->id_card, $key->id_card_on_screen);
							//echo HTML::anchor('cards/edit/' . $key->id_card, $key->id_card).' '.$key->id_card_on_screen;
							
						//вывод на экран номера карты в дtcятичном виде
						// если вывод на экран настроен как формат базы данных, то надо вывести десятичное представление.
						//если вывод на экран настроен как десятичное, то надо вывести в формате хранения в базе данных.
						if((Arr::get($cardtype, 'id') == 1) AND (Kohana::$config->load('system')->get('formatViewAll') == 1)){
		
			
			echo ' ('. $key->id_card_on_DEC.')';
					//		echo ' ('.Model::factory('Stat')->reviewKeyCode($key->id_card).')';						
							
							}?></td>
						<td><?php echo iconv('CP1251', 'UTF-8', Arr::get($cardtype, 'smallname')); ?></td> 
						<td><?php echo $key->timestart; ?></td>
						<td><?php echo $key->timeend; ?></td>
						<td><?php echo $key->is_active == 1 ? __('yes') : __('no'); ?></td>
						<td>
							<a href="javascript:" <?php echo $dis2 ?> onclick="if (confirm('<?php echo __('cards.confirmdelete'); ?>')) location.href='<?php echo URL::base() . 'contacts/deletecard/' . $key->id_card; ?>';">
								<?php echo HTML::image('images/icon_delete.png', array('title' => __('cards.delete'), 'class' => 'help '.$dis2_lighten)); ?>
							</a>
						</td>
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
			<?php
			if($contact->is_active) {
			?>
		<input type="button" <?php echo $dis1;?> value="<?php echo __('cards.create'); ?>" onclick="location.href='<?php echo URL::base() . 'contacts/addrfid/' . $contact->id_pep; ?>'" />
		<input type="button" <?php echo $dis1;?> value="<?php echo __('cards.create_grz'); ?>" onclick="location.href='<?php echo URL::base() . 'contacts/addgrz/' . $contact->id_pep; ?>'" />
		<?php }?>
	</div>
</div>
