<!DOCTYPE HTML> 
<html> 
<head> 
  
</head>
<body>

<div class="onecolumn">
	<div class="header">
		<div id="search"<?php if (isset($hidesearch)) echo ' style="display: none;"'; ?>>
			<form action="contacts/search" method="post">
				<input type="text" class="search noshadow" title="<?php echo __('search'); ?>" name="q" id="q" value="<?php if (isset($filter)) echo $filter; ?>" />
			</form>
		</div>
		
		

		<?php if (isset($company)) { ?>

		<?php } ?>
	</div>
	<br class="clear"/>
	<div class="content">

		<?php if (count($people) > 0) { ?>
		<form id="form_data" name="form_data" action="" method="post">
			<table class="" width="100%" cellpadding="0" cellspacing="0" id="tablesorter" >
				<thead>
					<tr>
						<?php if(Kohana::$config->load('config_newcrm')->get('contactListIdView', true)) echo '<th>'.__('contacts.id_pep').'</th>'?>
						<th><?php echo __('contact.active'); ?></th>
						<th><?php echo __('contacts.compareacl'); ?></th>
						<?php if(Kohana::$config->load('config_newcrm')->get('contactListTabNumView')) echo '<th>'.__('contacts.code').'</th>'?>
						<th><?php echo __('contacts.name'); ?></th>
						
						<th><?php echo __('contacts.company'); ?></th>
						<th><?php echo __('contacts.action'); ?></th>
						
					</tr>
				</thead>
				<tbody>
					<?php foreach ($people as $pep) { 
					
					$peppep=new Contact(Arr::get($pep,'ID_PEP'));
					$org = new Company($peppep->id_org);
					?>
					<tr>
						<!--
						<td>
							<input type="checkbox" />
						</td>
						-->
						<?php if(Kohana::$config->load('config_newcrm')->get('contactListIdView', true)) echo '<td>'.$peppep->id_pep.'</td>'?>
						
						<td><?php echo Arr::get($pep,'IS_ACTIVE')? 'Да':'Нет'; ?></td>
						
						<td><?php switch(Model::factory('contact')->check_acl($peppep->id_pep)){

							case 0:
								echo __('acl.equalDefaultOrg');//совпадает с умолчательной
							break;

							case 1:
								echo '<b>'.__('acl.moreTheDefaultOrg').'</b>';//отличается, больше чем в умолчательной
							break;

							case 2:
								echo '<b>'.__('acl.lessTheDefaultOrg').'</b>';// отличается, меньше чем в умолчательной
							break;
						}							
						
						
						if(Kohana::$config->load('config_newcrm')->get('contactListTabNumView')) echo '<td>'.$peppep->tabnum.'</td>'?>
						
						<td><?php 
						//echo Debug::vars('115', $pep);
						if (Auth::instance()->logged_in('admin') && $peppep->id_pep <>1)
						    echo HTML::anchor('contacts/edit/' . $peppep->id_pep, iconv('CP1251', 'UTF-8', $peppep->surname. ' '.$peppep->name . ' ' . $peppep->patronymic));
							else
							    echo iconv('CP1251', 'UTF-8', $peppep->surname. ' '.$peppep->name . ' ' . $peppep->patronymic);
							//    if($org->flag & 1) echo HTML::image('/images/icon_guest2.png', array('width'=>'16'));
						
						?></td>

						<td><?php 
						
						if (Auth::instance()->logged_in('admin') && $peppep->id_pep <>1)
						    echo HTML::anchor('companies/edit/' . $peppep->id_org, iconv('CP1251', 'UTF-8', $org->name)); 
							else 
							    echo iconv('CP1251', 'UTF-8',  $org->name);
						?></td>
						<?php
						//набор параметров, определяющий поведение кнопок для разных ролей
						$user=new User();
						$acl=new Acl(true);
						if($acl->is_allowed($user->role,'organization', 'read')){
							$dis1='';
							$dis2='class="disabled"';
							$dis2_lighten='lighten';
							$dis3='';
						};
						if($acl->is_allowed($user->role,'organization', 'create')){
							$dis1='';
							$dis2='class="disabled"';
							$dis2_lighten='lighten';
							$dis3='';
						};
						if($acl->is_allowed($user->role,'organization', 'update')){
							$dis1='';
							$dis2='';
							$dis2_lighten='';
							$dis3='';
						};
						if($acl->is_allowed($user->role,'organization', 'delete')){
							$dis1='';
							$dis2='';
							$dis2_lighten='';
							$dis3='';
						};
						?>
						<td>
							<?php 
							    
	//редактировать						    
						//	 echo HTML::anchor('contacts/edit/'.$peppep->id_pep, HTML::image('images/icon_edit.png', array('title' => __('tip.edit'), 'class' => 'help', 'alt'=>'edit'.$dis1))
							    
						//	     );

							 ?>
							
							<?php if ($peppep->is_active == 1) { 
//уволить							
						//	 echo HTML::anchor('#javascript:',
						//	     HTML::image('images/icon_delete.png', array('title' => __('tip.fired'), 'class' => 'help', 'alt'=>'edit')),
						//	     array('onclick'=>'if (confirm('. __('contacts.confirmSetNotActive').')) location.href=\''. URL::base() . 'contacts/fired/' . $peppep->id_pep.'\'')
						//	     );
							?>
										<a href="javascript:" <?php echo $dis2 ?> onclick="if (confirm('<?php echo __('contacts.confirmSetNotActive'); ?>')) location.href='<?php echo URL::base() . 'contacts/fired/' . $pep['ID_PEP']; ?>';">
										 <?php // echo HTML::image('images/icon_delete.png', array('title' => __('tip.fired'), 'class' => 'help '.$dis2_lighten)); ?>
								</a>
								<?php } else {?>
									<a href="javascript:" <?php echo $dis2 ?> onclick="if (confirm('<?php echo __('contacts.restore'); ?>')) location.href='<?php echo URL::base() . 'contacts/restore/' . Arr::get($pep,'ID_PEP'); ?>';">
										<?php //echo HTML::image('images/restore_16.png', array('title' => __('tip.restore'), 'class' => 'help '.$dis2_lighten)); ?>
								</a>
								
								
								<?php }
							?>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<div id="chart_wrapper" class="chart_wrapper"></div>
		<!-- End bar chart table-->
		</form>
		<?php echo $pagination; ?>
		<?php } else { ?>
		<div style="margin: 100px 0; text-align: center;">
			<?php echo __('contacts.empty'); ?><br /><br />
		</div>
		<?php } ?>
	</div>
</div>
