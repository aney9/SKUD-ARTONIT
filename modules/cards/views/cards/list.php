<script type="text/javascript">


 
  	$(function() {		
  		$("#tablesorter").tablesorter({ headers: { 7:{sorter: false}},  widgets: ['zebra']});
		
  	});	
	
</script>
<?php 
//https://webformyself.com/sortirovka-tablic-pri-pomoshhi-plagina-tablesorter-js/?ysclid=lrgdz4nrzp693511651
// список идентификаторов
//echo Debug::vars('2', $cards); //exit;
//echo Debug::vars('2-2', $cardsList); //exit;
//echo Debug::vars('16', array_diff($cards, $cardsList));//exit;
//echo Debug::vars('12', $cardsList); //exit;
//echo Debug::vars('2', $catdTypelist); //exit;
//echo Debug::vars('3', $alert); //exit;
//echo Debug::vars('4', $filter); //exit;
//echo Debug::vars('5', $pagination); //exit;
define ('_notAllowed', "HTML::image('images/text_lock.png', array('title' => __('tip.notAllowed'), 'width'=>'32'))");
include Kohana::find_file('views','alert');
if ($alert) { ?>
<div class="alert_success">
	<p>
		<img class="mid_align" alt="success" src="images/icon_accept.png" />
		<?php echo $alert; ?>
	</p>
</div>
<?php } ?>
<div class="onecolumn">
	<div class="header">
		<div id="search"<?php if (isset($hidesearch)) echo ' style="display: none;"'; ?>>
			<form action="cards/search_any" method="post">
			
				<input type="text" class="search noshadow" title="<?php echo __('search'); ?>" name="q" id="q" value="<?php if (isset($filter)) echo $filter; ?>" />
			</form>
		</div>
		<span><?php 
		switch(Session::instance()->get('identifier')){
			case 1:
				echo __('cards.titleRFID'); 
			break;
			case 1:
				echo __('cards.titleGRZ'); 
			break;
			default:
		break;
		}			
		
	?></span>
	</div>
	<br class="clear"/>
	<div class="content">
		<?php 
		//объявление типов идентификаторов
		$cardtype=array(
			0=>'',);
	
		if (count($cards) > 0) { // если список пуст - значит, нечего показывать
		?> 
		<?php
		include Kohana::find_file('views', 'paginatoion_controller_template'); 
		$sn=0;
?>

		<form id="form_data" name="form_data" action="" method="post">
			<table class="data tablesorter-blue" width="100%" cellpadding="0" cellspacing="0" id="tablesorter" >
			<thead>
					<tr>
						<th class="filter-false sorter-false"><?php echo __('sn'); ?></th>
						<th><?php echo __('cards.code'); ?></th>
						<th><?php echo __('cards.id_cardtype'); ?></th>
						<th><?php echo __('cards.status'); ?></th>
						<th><?php echo __('cards.datestart'); ?></th>
						<th><?php echo __('cards.dateend'); ?></th>
						<th><?php echo __('cards.active'); ?></th>
						<th><?php echo __('cards.holder'); ?></th>
						<th><?php echo __('Должность'); ?></th>
						<th><?php echo __('cards.company'); ?></th>
						<th class="filter-false sorter-false"><?php echo __('cards.action'); ?></th>
					</tr>
			</thead>	
			<tbody>	
				<tr align="center">
					<?php
					/* 	echo '<td>1</td>';
						echo '<td>2</td>';
						echo '<td>22</td>';
						echo '<td>3</td>';
						echo '<td>4</td>';
						echo '<td>5</td>';
						echo '<td>6</td>';
						echo '<td>7</td>';
						echo '<td>8</td>';
						echo '<td>9</td>'; */
					
					?>
						
					</tr>
			
				
					<?php 
					$listStatus=array(0=>__('RFID'),
									1=>__('RFID Mifare'),
									2=>__('RFID Mifare Encrytped'),
									3=>__('RFID LR UHF')
									);
									
					foreach ($cards as $card) { 
						
					$key=new Keyk(Arr::get($card,'ID_CARD'));
					
					$cardtype=Arr::get($catdTypelist, $key->id_cardtype);
					$contact= new Contact($key->id_pep);
					$org= new Company($contact->id_org );
				
					
					// - признак: разрешен ли показ подробностей. Если не разрешен, то ссылки должны быть не активными.
					$is_allowed=in_array($contact->id_org, Acls::getListAllowedIdOrg());// - признак: разрешен ли показ подробностей. Если не разрешен, то ссылки должны быть не активными.
					
					echo'<tr>';
						
						echo '<td>'.++$sn.'</td>';
						echo '<td>'; 
						
						echo $is_allowed? HTML::anchor('cards/edit/' . $key->id_card, $key->id_card_on_screen) : $key->id_card_on_screen .' '.HTML::image('images/text_lock.png', array('title' => __('tip.notAllowed'), 'width'=>"32"));
		
			//если включен показ кода идентификатора, то показывю его в формате DEC				
			if((Arr::get($cardtype, 'id') == 1) AND (Kohana::$config->load('system')->get('formatViewAll') == 1)){
				echo ' ('.$key->id_card_on_DEC.')';
			}

						if($key->flag & 1) echo HTML::image('/images/icon_guest2.png', array('width'=>'16')); '</td>';
						echo '<td>'.iconv('CP1251', 'UTF-8', Arr::get($cardtype, 'smallname')).'</td>'; 
						echo '<td>'.Arr::get($listStatus, $key->status).'</td>';
						echo '<td>'.$key->timestart.'</td>';
						echo '<td>'.$key->timeend.'</td>';
						echo '<td>';
						  echo $key->is_active.' '.($key->is_active == '1') ? __('yes') : __('no');
						echo '</td>'; 
						echo '<td>'; 
							if (Auth::instance()->logged_in('admin'))
							    echo $is_allowed? HTML::anchor('contacts/edit/' . $contact->id_pep, iconv('CP1251', 'UTF-8', $contact->name . ' ' . $contact->surname)) : iconv('CP1251', 'UTF-8', $contact->name . ' ' . $contact->surname).' '.HTML::image('images/text_lock.png', array('title' => __('tip.notAllowed'), 'width'=>"32"));
							else 
								echo HTML::anchor('contacts/view/' . $contact->id_pep, iconv('CP1251', 'UTF-8', $contact->name . ' ' . $contact->surname)); 
						echo '</td>';
						echo '<td>';
							echo iconv('CP1251', 'UTF-8', $contact->post );
						echo '</td>';
						
						
						echo '<td>';
						
							if (Auth::instance()->logged_in('admin'))
							    echo $is_allowed? HTML::anchor('companies/edit/' . $org->id_org, iconv('CP1251', 'UTF-8', $org->name)) : iconv('CP1251', 'UTF-8', $org->name).' '.HTML::image('images/text_lock.png', array('title' => __('tip.notAllowed'), 'width'=>"32"));
							else 
								echo HTML::anchor('companies/view/' . $org->id_org, iconv('CP1251', 'UTF-8', $org->name)); 
								echo '</td>';
								echo '<td>';


						if($is_allowed){?>
						    <a href="javascript:" onclick="if (confirm('<?php echo __('cards.confirmdelete'); ?>')) location.href='<?php echo URL::base() . 'cards/delete/' . $key->id_card; ?>';"><?php echo HTML::image('images/icon_delete.png', array('title' => __('cards.delete'), 'class' => 'help'));?></a>
							<?php
						    
						} else {
						    echo HTML::image('images/text_lock.png', array('title' => __('tip.notAllowed'), 'width'=>"32"));
						}
						
						
						?>
						
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<div id="chart_wrapper" class="chart_wrapper"></div>
		
		</form>
		
		<?php } else { ?>
		<div style="margin: 100px 0; text-align: center;">
			<?php echo __('cards.empty'); ?><br /><br />
		</div>
		<?php } ?>
	</div>
</div>

