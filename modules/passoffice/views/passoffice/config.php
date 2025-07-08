<style>
input[disabled='disabled']{
  color: #999;     
}
</style>
<?php 
/*
Страница настроек режимов работы Гостя

*/
//echo Debug::vars('6', $org_tree);
//echo Debug::vars('7', $guestConfig);
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

		<span><?php echo __('passoffice.config_title', array(':name'=>$guestConfig->name)); if (isset($company)) echo ' - ' . iconv('CP1251', 'UTF-8', $company['NAME']); ?></span>
		<?php if (isset($company)) { ?>

		<?php } ?>
	</div>
	<br class="clear"/>
	<div class="content">
	
		
		<form action="passoffices/saveconfig" method="post" onsubmit="return validate()">
		
		
			<table class="data" width="100%" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<!--
						<th style="width:10px">
							<input type="checkbox" id="check_all" name="check_all"/>
						</th>
						-->
						<th style="width:15%"><?php echo __('passoffice.confname'); ?></th>
						<th style="width:45%"><?php echo __('passoffice.confcode'); ?></th>
						
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							Название Бюро пропусков
						</td>
						<td>
							<?php 
                           		echo Form::input('po_name', $guestConfig->name );
                    		?>
						</td>
					</tr>
					<tr>
						<td>
							ID_ORG гостевой организации
						</td>
						<td>
							<select name="idOrgGuest"  >
								<option></option>
								<?php
								$tree=new Tree();
								echo $tree->out_options($tree->array_to_tree($org_tree),$guestConfig->idOrgGuest);
								?>
							</select>
							<?php echo __('id_org=_id_org', array('_id_org'=>$guestConfig->idOrgGuest));?>

							
							
						</td>
					</tr>
					<tr>
						<td>
							ID_ORG Архива гостевой организации
						</td>
						<td>
							<select name="idOrgGuestArchive">
								<option></option>
								<?php
								$tree=new Tree();
									echo $tree->out_options($tree->array_to_tree($org_tree),$guestConfig->idOrgGuestArchive);
								?>
							</select>
							<?php echo __('id_org=_id_org', array('_id_org'=>$guestConfig->idOrgGuestArchive));
							
							?>
							
							
						</td>
					</tr>
						
				</tbody>
			</table>
			<div id="chart_wrapper" class="chart_wrapper"></div>
		<!-- End bar chart table-->
		<br />
		<?php 
		echo Form::hidden('po_id', $guestConfig->id);
		//echo Form::hidden('po_name', $guestConfig->name);
		//echo Form::submit(null,__('button.save') );
		?>
		
			<input type="submit" value="<?php echo __('button.save'); ?>" />
			&nbsp;&nbsp;
			<input type="button" value="<?php echo __('button.cancel'); ?>" onclick="document.forms[0].reset()" />
			&nbsp;&nbsp;
			<input type="button" value="<?php echo __('button.backtolist'); ?>" onclick="location.href='<?php echo URL::base(); ?>contacts'" />
		</form>
		
		
		<?php
		//вывод списка бюро пропусков	
		if (true) { 
		
		$poList=Model::factory('Passofficem')->getPassOfficeList();
		//echo Debug::vars('122', $poList);
	
		?>
		<h1><br>Список бюро пропусков<br></h1>
		<form id="form_data" name="form_data" action="passoffices/editItem" method="post">
			<table class="data tablesorter-blue" width="100%" cellpadding="0" cellspacing="0" id="tablesorter_ruleList" >
				<thead>

					<?php 
				echo '<tr>';
					echo '<th>'.__('id').'</th>';
					echo '<th>'.__('name').'</th>';
					echo '<th>'.__('idOrgGuest').'</th>';
					echo '<th>'.__('idOrgGuestArchive').'</th>';
					echo '<th>'.__('add').'</th>';
					
				echo '</tr>';
					?>
				</thead>
				<tbody>
					<?php foreach ($poList as $key=>$value) {
							$po=new Passoffice;
							$po->init($value['id']);
				   	echo '<tr>';
					echo '<td>'.Form::radio('id', Arr::get($value,'id')).$po->id.' '.$po->name.' '.$po->idOrgGuest.' '.$po->idOrgGuestArchive.' '.$po->is_active.'</td>';
					echo Form::hidden('id', $po->id);
					echo '<td>'.Form::input('name', $po->name ).'</td>';
					?>
					<td>
						<select name="idOrgGuest"  >
								<option></option>
								<?php
								$tree=new Tree();
								echo $tree->out_options($tree->array_to_tree($org_tree),$po->idOrgGuest);
						
								?>
							</select>
							
						</td>
						<td>
						<select name="idOrgGuestArchive"  >
								<option></option>
								<?php
								$tree=new Tree();
								echo $tree->out_options($tree->array_to_tree($org_tree),$po->idOrgGuestArchive);
								?>
							</select>
							
						</td>
						<?php
						echo '<td>'.Form::checkbox('is_active', 1, ($po->is_active==1)? true:false).$po->is_active.'</td>';
					echo '</tr>';
					//echo Form::close();
					}
					echo '<tr>';
						echo '<td>'.Form::submit('todo', 'deletePassoffice').'</td>';
						echo '<td></td>';
						echo '<td></td>';
						echo '<td></td>';
						echo '<td>'.Form::submit('todo', 'updatePassoffice').'</td>';
					echo '</tr>';
					?>
				</tbody>
			</table>
			<div id="chart_wrapper" class="chart_wrapper"></div>
		<!-- End bar chart table-->

		</form>
	
		<?php } else { ?>
		<div style="margin: 100px 0; text-align: center;">
			<?php echo __('empty'); ?><br /><br />
		</div>
		<?php }
	?>
		
		
		
		
		<h1>Добавить новое бюро пропусков</h1>
		<table class="data tablesorter-blue" width="100%" cellpadding="0" cellspacing="0" id="tablesorter" >
				<thead>

					<?php 
					
				echo '<tr>';
					echo '<th>'.__('id').'</th>';
					echo '<th>'.__('name').'</th>';
					echo '<th>'.__('idOrgGuest').'</th>';
					echo '<th>'.__('idOrgGuestArchive').'</th>';
					echo '<th>'.__('add').'</th>';
					
				echo '</tr>';
				
					?>
				</thead>
				<tbody>
					<?php  
					echo Form::open('passoffices/addpassoffice');
					echo '<tr>';
					    echo '<td>'.Form::input('po_name').'</td>';
					    echo '<td>'.Form::input('po_name').'</td>';
						
						?>
						<td>
						<select name="idOrgGuest"  >
								<option></option>
								<?php
								$tree=new Tree();
								echo $tree->out_options($tree->array_to_tree($org_tree),1);
								?>
							</select>
						</td>
						<td>
						<select name="idOrgGuestArchive"  >
								<option></option>
								<?php
								$tree=new Tree();
								echo $tree->out_options($tree->array_to_tree($org_tree),1);
								?>
							</select>
						</td>
							<?php
						echo '<td>'.Form::submit('todo', 'addPassOffice').'</td>';
						
					echo '</tr>';
					echo Form::close();
					?>
				</tbody>
		</table>
		
		

	</div>
</div>
