<?php

//echo Debug::vars('3', $door); exit;
$door=new Door($id_door);
//echo Debug::vars('5', $door);//exit;
$forsave=array();
?>
<script type="text/javascript">


 
  	$(function() {		
  		$("#tablesorter").tablesorter({ headers: { 7:{sorter: false}},  widgets: ['zebra']});
		
  	});	
	
</script>

<style>
.tree{
  --spacing : 1.5rem;
  --radius  : 10px;
}
.tree li{
  display      : block;
  position     : relative;
  padding-left : calc(2 * var(--spacing) - var(--radius) - 2px);
}

.tree ul{
  margin-left  : calc(var(--radius) - var(--spacing));
  padding-left : 0;
}

</style>
<?php if (isset($alert)) { ?>
<div class="alert_success">
	<p>
		<img class="mid_align" alt="success" src="images/icon_accept.png" />
		<?php echo $alert; ?>
	</p>
</div>
<?php } ?>
<div class="onecolumn">
	<div class="header">

		<span><?php echo __('doors.title').' '.iconv('windows-1251','UTF-8',$door->name); ?></span>
		<?php 
			echo $topbuttonbar;
		?>
	</div>


	<br class="clear"/>
	<div class="content">
	<?php
	include Kohana::find_file('views', 'paginatoion_controller_template'); 
	echo Form::open('doors/export');
				//echo __('doors.KeyCount', array(':count'=>count($enable_card_list))).'<br>';
				echo Form::hidden('id_door', $door->id ); 
		
				//echo Form::submit('savecvs', __('button.savecsv'), array('disabled'=>'disabled'));
				//echo Form::submit('savecvs', __('button.savecsv'));
	
				//echo Form::submit('savexls', __('button.savexlsx') , array('disabled'=>'disabled'));
	
				echo Form::submit('savepdf', __('button.savepdf'));
	
		
			
			?>

			<table class="data tablesorter-blue" width="100%" cellpadding="0" cellspacing="0" id="tablesorter" >
				<thead>
					<tr>
						<!--
						<th style="width:10px">
							<input type="checkbox" id="check_all" name="check_all"/>
						</th>
						-->
						<?php

						echo '<th>' . __('npp') . '</th>';
						//echo '<th>' . __('id_pep') . '</th>';
						
						echo '<th>' . __('contact.name') . '</th>';
					
						echo '<th>' . __('contact.company') . '</th>';
						echo '<th>' . __('contact.post') . '</th>';
						
						?>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i=1;
					$t1=microtime(true);
					foreach ($door->getContactList() as $key=>$value) 
					{ 
					//echo Debug::vars('97', $value);exit;
					    $contact=new Contact($value);
					    $org=new Company($contact->id_org);
					?>
						<tr>
						
							<?php 
							echo '<td>'.$i++.'</td>';
							echo '<td align="center">' . HTML::anchor('contacts/edit/'.$contact->id_pep, iconv('windows-1251','UTF-8', $contact->surname.' '.$contact->name.' '.$contact->patronymic)) .' ('.$contact->id_pep.')'. '</td>';
							echo '<td align="center">' . HTML::anchor('companies/edit/'.$org->id_org, iconv('windows-1251','UTF-8',$org->name)). '</td>';
							echo '<td align="center">' .iconv('windows-1251','UTF-8', $contact->post).'</td>';

							//echo '<td align="center">' . HTML::anchor('cards/edit/'.Arr::get($value, 'ID_CARD'), $card->id_card_on_screen). '</td>';
							//echo '<td align="center">' . Arr::get($value, 'LOAD_TIME') . '</td>';
							//echo '<td align="center">' . Arr::get($value, 'LOAD_RESULT') . '</td>';
							$forsave[$contact->id_pep]['fio']=iconv('windows-1251','UTF-8', $contact->surname.' '.$contact->name.' '.$contact->patronymic);
							$forsave[$contact->id_pep]['org']=iconv('windows-1251','UTF-8',$org->name);
							$forsave[$contact->id_pep]['post']=iconv('windows-1251','UTF-8', $contact->post);
						
							?>
						</tr>
					<?php 
					} ?>
				</tbody>
			</table>
			<div id="chart_wrapper" class="chart_wrapper"></div>
		<!-- End bar chart table-->

		<?php 	
			//echo Debug::vars('129',$forsave);exit;
			echo Form::hidden('forsave', serialize($forsave));
			echo Form::close();
			//echo Debug::vars('135', microtime(true)-$t1);
			?>

	</div>
</div>
