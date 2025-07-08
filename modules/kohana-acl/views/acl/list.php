<script type="text/javascript">


 
  	$(function() {		
  		$("#tablesorter").tablesorter({ headers: { 7:{sorter: false}},  widgets: ['zebra']});
  		$("#tablesorter_ruleList").tablesorter({widgets: ['zebra']});
  		$("#tablesorter_resource").tablesorter({widgets: ['zebra']});
		
  	});	
	
</script>
 
<?php if (isset($alert)) { ?>
<div class="alert_success">
	<p>
		<img class="mid_align" alt="success" src="images/icon_accept.png" />
		<?php echo $alert; ?>
	</p>
</div>
<?php } ?>
<div class="onecolumn">
		
	
	
	<br class="clear"/>
	<div class="content">
	
	<?php
	$acl=Model::factory('Aclm');
	//echo Debug::vars('29', $acl->getRoles());
	//echo Debug::vars('30', $acl->getResources());
	//echo Debug::vars('31', $acl->getRules());
	//echo Debug::vars('32', $acl->getUsers());
	
 // вывод списка ролей
	if (count($acl->getRoles()) > 0) { 
	    //делаю вспомогательный массив ролей
	    $roleParent=array();
	    foreach ($acl->getRoles() as $key=>$value) { 
	      
	        $roleParent[Arr::get($value, 'id')]=Arr::get($value, 'name');
	    }
	    
	    //делаю вспомогательный массив ресурсов
	    foreach ($acl->getResources() as $key=>$value) {
	        // $roleParent[Arr::get($value, 'id')]['id']=Arr::get($value, 'id');
	        $resourceList[Arr::get($value, 'id')]=Arr::get($value, 'name');
	    }
	    
	    
	  
	
	    ?>
		Список ролей
			<table class="data tablesorter-blue" width="100%" cellpadding="0" cellspacing="0" id="tablesorter" >
				<thead>

					<?php 
				echo '<tr>';
					echo '<th>'.__('id').'</th>';
					echo '<th>'.__('name').'</th>';
					echo '<th>'.__('parent_id').'</th>';
					echo '<th>'.__('description').'</th>';
					echo '<th>'.__('edit').'</th>';
					echo '<th>'.__('delete').'</th>';
				echo '</tr>';
					?>
				</thead>
				<tbody>
					<?php foreach ($acl->getRoles() as $key=>$value) { 
					 echo Form::open('acls/editItem', array('onsubmit'=>'return confirm("Вы действительно хотите изменить или удалить роль?")'));
					echo '<tr>';
					   echo '<td>'.Form::input('id',Arr::get($value,'id')).'</td>';
						echo '<td>'.Form::input('name', Arr::get($value,'name')).'</td>';
						//echo '<td>'.Form::input('parent_id', Arr::get($value,'parent_id')).'</td>';
						echo '<td>'.Form::select('parent_id', $roleParent,  Arr::get($value,'parent_id')).'</td>';
						echo '<td>'.Form::input('description', Arr::get($value,'description')).'</td>';
						echo '<td>'.Form::submit('todo', 'updateRole').'</td>';
						echo '<td>'.Form::submit('todo', 'deleteRole', array('onclick' => "if (confirm('" . __('objects.confirmdelete') )).'</td>';
					
					echo '</tr>';
					echo Form::close();
					}
					?>
				</tbody>
			</table>
		
		<?php 
	  
	  
	       } else { ?>
		<div style="margin: 100px 0; text-align: center;">
			<?php echo __('empty'); ?><br /><br />
		</div>
		<?php } 	
		
		echo 'Добавить роль';
		
		?>
		<table class="data tablesorter-blue" width="100%" cellpadding="0" cellspacing="0" id="tablesorter" >
				<thead>

					<?php 
				echo '<tr>';
				
					echo '<th>'.__('name').'</th>';
					echo '<th>'.__('parent_id').'</th>';
					echo '<th>'.__('description').'</th>';
					echo '<th>'.__('add').'</th>';
					
				echo '</tr>';
					?>
				</thead>
				<tbody>
					<?php  
					echo Form::open('acls/editItem');
					echo '<tr>';
					
						echo '<td>'.Form::input('name', 'name_value').'</td>';
						echo '<td>'.Form::select('parent_id', $roleParent).'</td>';
						echo '<td>'.Form::input('description', 'description_value').'</td>';
		
						echo '<td>'.Form::submit('todo', 'addRole').'</td>';
						
					echo '</tr>';
					echo Form::close();
					?>
				</tbody>
			</table>
		
		<?php 
	 
	  
		
		
		
		
	//вывод списка ресурсов	
 	if (count($acl->getResources()) > 0) {
 	// echo Debug::vars('141', $acl->getResources());exit;   
	 foreach($acl->getResources() as $key=>$value){
		 $resourceList[Arr::get($value, 'id')] = Arr::get($value, 'name');
		 
	 }
//	 echo Debug::vars('146', $resourceList);
 	  ?>
 	<h1>Список ресурсов</h1>
	
		<form id="form_data" name="form_data" action="acls/editItem" method="post">
			<table class="data tablesorter-blue" width="100%" cellpadding="0" cellspacing="0" id="tablesorter_resource" >
				<thead>

					<?php 
				echo '<tr>';
					echo '<th>'.__('id').'</th>';
					echo '<th>'.__('name').'</th>';
					echo '<th>'.__('parent_id').'</th>';
					echo '<th>'.__('description').'</th>';
				echo '</tr>';
					?>
				</thead>
				<tbody>
					<?php foreach ($acl->getResources() as $key=>$value) { 
					echo '<tr>';
						echo '<td>'.Form::radio('id', Arr::get($value,'id')).Arr::get($value,'id').'</td>';
						echo '<td>'.Form::input('name', Arr::get($value,'name')).'</td>';
						echo '<td>'.Form::select('parent_id', $resourceList, Arr::get($value,'parent_id') ).' ('.Arr::get($value,'parent_id').')</td>';
						echo '<td>'.Form::input('description', Arr::get($value,'description')).'</td>';
					echo '</tr>';
					}
					echo '<tr>';
						echo '<td>'.Form::submit('todo', 'deleteResource').'</td>';
						echo '<td></td>';
					
						echo '<td></td>';
						echo '<td>'.Form::submit('todo', 'updateResource').'</td>';
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
		<?php } ?>
		
		<h1>Добавить Новый ресурс</h1>
		<?php echo Form::open('acls/editItem');?>
		<table class="data tablesorter-blue" width="100%" cellpadding="0" cellspacing="0" id="tablesorter" >
				<thead>

					<?php 
					
			echo '<tr>';
					//echo '<th>'.__('id').'</th>';
					echo '<th>'.__('name').'</th>';
					echo '<th>'.__('parent_id').'</th>';
					echo '<th>'.__('description').'</th>';
				echo '</tr>';
				
					?>
				</thead>
				<tbody>
					<?php  
					
					echo '<tr>';
					   	echo Form::hidden('id', -1);
					   	echo '<td>'.Form::input('name').'</td>';
						echo '<td>'.Form::select('parent_id', $resourceList).'</td>';
						echo '<td>'.Form::input('description').'</td>';
					echo '</tr>';
					
					?>
				</tbody>
		</table>
		<?php 
		echo '<td>'.Form::submit('todo', 'addResource').'</td>';
		echo Form::close();
		?>
		
		
		<?
	//вывод списка правил	
		if (count($acl->getRules()) > 0) { ?>
		<h1><br>Список правил<br></h1>
		<form id="form_data" name="form_data" action="acls/editItem" method="post">
			<table class="data tablesorter-blue" width="100%" cellpadding="0" cellspacing="0" id="tablesorter_ruleList" >
				<thead>

					<?php 
				echo '<tr>';
					
					echo '<th>'.__('id').'</th>';
					echo '<th>'.__('type').'</th>';
					echo '<th>'.__('role_id').'</th>';
					echo '<th>'.__('resource_id').'</th>';
					echo '<th>'.__('privilege').'</th>';
					//echo '<th>'.__('edit').'</th>';
					//echo '<th>'.__('delete').'</th>';
				echo '</tr>';
					?>
				</thead>
				<tbody>
					<?php foreach ($acl->getRules() as $key=>$value) { 
				   // echo Form::open('acls/editItem', array('onsubmit'=>'return confirm("Вы действительно хотите изменить или удалить правило?")'));
					echo '<tr>';
					echo '<td>'.Form::radio('id', Arr::get($value,'id')).Arr::get($value,'id').'</td>';
					echo '<td>'.Form::select('type', array('allow'=>'allow', 'deny'=>'deny'), Arr::get($value,'type') ).'</td>';
					   //echo '<td>'.Arr::get(array('allow'=>'allow', 'deny'=>'deny'), Arr::get($value,'type') ).'</td>';
						
					   echo '<td>'.Form::hidden('role_id', Arr::get($value,'role_id')), Arr::get($roleParent,  Arr::get($value,'role_id')).'</td>';
					   echo '<td>'.Form::hidden('resource_id', Arr::get($value,'resource_id')), Arr::get($resourceList, Arr::get($value,'resource_id')).'</td>';
						echo '<td>'.Form::select('privelege', array('read'=>'read', 'create'=>'create', 'update'=>'update', 'delete'=>'delete'), Arr::get($value,'privilege')).'</td>';
						//echo '<td>'.Arr::get(array('read'=>'read', 'create'=>'create', 'update'=>'update', 'delete'=>'delete'), Arr::get($value,'privilege')).'</td>';
						
					echo '</tr>';
					//echo Form::close();
					}
					echo '<tr>';
						echo '<td>'.Form::submit('todo', 'deleteRule').'</td>';
						echo '<td></td>';
						echo '<td></td>';
						echo '<td></td>';
						echo '<td>'.Form::submit('todo', 'updateRule').'</td>';
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
		
		
		
		
		<h1>Добавить новое правило</h1>
		<table class="data tablesorter-blue" width="100%" cellpadding="0" cellspacing="0" id="tablesorter" >
				<thead>

					<?php 
					
				echo '<tr>';
					echo '<th>'.__('type').'</th>';
					echo '<th>'.__('role_id').'</th>';
					echo '<th>'.__('resource_id').'</th>';
					echo '<th>'.__('privelege').'</th>';
					echo '<th>'.__('add').'</th>';
					
				echo '</tr>';
				
					?>
				</thead>
				<tbody>
					<?php  
					echo Form::open('acls/addItem');
					echo '<tr>';
					    echo '<td>'.Form::select('type', array('allow'=>'allow', 'deny'=>'deny')).'</td>';
						echo '<td>'.Form::select('role_id', $roleParent).'</td>';
						echo '<td>'.Form::select('resource_id', $resourceList).'</td>';
						echo '<td>'.Form::select('privelege', array('read'=>'read', 'create'=>'create', 'update'=>'update', 'delete'=>'delete')).'</td>';
						echo '<td>'.Form::submit('todo', 'addRule').'</td>';
						
					echo '</tr>';
					echo Form::close();
					?>
				</tbody>
		</table>
		
			
			
<h1>Версия 2</h1><h3>(Read Create Update Delete)</h3>		
			<table class="data tablesorter-blue" width="100%" cellpadding="0" cellspacing="0" id="tablesorter" >
			<thead>

					<?php 
					$acl2=ACL::instance(true); 
				echo '<tr>';
					echo '<th>'.__('id').'</th>';
					echo '<th>'.__('role').'</th>';
					foreach ($resourceList as $key3=>$value3){
					    echo '<th>'.$value3.'('.$key3.')</th>';
					}
					
				
				echo '</tr>';
					?>
				</thead>
				<tbody>
					<?php foreach ($roleParent as $key=>$value) { 
					   
        					echo '<tr>';
        					   echo '<td>'.$key.'</td>';
        					   echo '<td>'.$value.'('.$key.')</td>';
        					   foreach ($resourceList as $key2=>$value2){
        					   echo '<td>'.($acl2->is_allowed($value, $value2, 'read')? 'R':'_')
        					           .($acl2->is_allowed($value, $value2, 'create')? 'C':'_')
        					           .($acl2->is_allowed($value, $value2, 'update')? 'U':'_')
        					           .($acl2->is_allowed($value, $value2, 'delete')? 'D':'_').'</td>';
        					   }
        					           echo '</tr>';
					    
					}
					?>
				</tbody>
		</table>


		<?php 
		
	//вывод списка пользователей	
		if (count($acl->getUsers()) > 0) { ?>
		Список пользователей и их роли
		<form id="form_data" name="form_data" action="" method="post">
			<table class="data tablesorter-blue" width="100%" cellpadding="0" cellspacing="0" id="tablesorter" >
				<thead>

					<?php 
				echo '<tr>';
					echo '<th>'.__('user_id').'</th>';
					echo '<th>'.__('role_id').'</th>';
					echo '<th>'.__('delete').'</th>';
				echo '</tr>';
					?>
				</thead>
				<tbody>
					<?php foreach ($acl->getUsers() as $key=>$value) { 
					    echo Form::open('acls/editItem', array('onsubmit'=>'return confirm("Вы действительно хотите изменить или удалить правило?")'));
					echo '<tr>';
					echo '<td>'.Form::hidden('user_id', Arr::get($value,'user_id')).Arr::get($value,'user_id').'</td>';
	       				echo '<td>'.Arr::get($roleParent,  Arr::get($value,'role_id')).'</td>';
						echo '<td>'.Form::submit('todo', 'deleteUserRole').'</td>';
					echo '</tr>';
					echo Form::close();
					}
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
		<?php } ?>
		Добавить нового пользователя и его роль
		<table class="data tablesorter-blue" width="100%" cellpadding="0" cellspacing="0" id="tablesorter" >
				<thead>

					<?php 
					
				echo '<tr>';
					echo '<th>'.__('user_id').'</th>';
					echo '<th>'.__('role_id').'</th>';
					echo '<th>'.__('add').'</th>';
					
				echo '</tr>';
				
					?>
				</thead>
				<tbody>
					<?php  
					echo Form::open('acls/addItem');
					echo '<tr>';
						echo '<td>'.Form::input('user_id').'</td>';
						//echo '<td>'.Form::input('role_id', 'role_id').'</td>';
						echo '<td>'.Form::select('role_id', $roleParent).'</td>';
						echo '<td>'.Form::submit('todo', 'addUserRole').'</td>';
						
					echo '</tr>';
					echo Form::close();
					?>
				</tbody>
			</table>
		
		
		
		
		
	</div>
</div>
