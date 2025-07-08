<script type="text/javascript">
	function validate()
	{
		$('#error1, #error2').hide();
		if ($('#name').val() == '') {
			$('#error1').show();
			$('#name').focus();
			return false;
		} 
	}
</script>
<?php 
//echo Debug::vars('17', $company);


// готовлю список организаций в виде иерархического дерева
	//	$org_tree_local = Model::Factory('Company')->getOrgListForOnce(Arr::get($company, 'ID_ORG'));// я получил список организаций.
	//	echo Debug::vars('63', $org_tree_local); //exit;
	//	echo Model::Factory('treeorg')->make_tree($org_tree_local, 1);//формирую иерархический список
		
		
//echo Debug::vars('18', $parents);
//echo Debug::vars('19', $alert);
//echo Debug::vars('20', $acl);


//набор параметров, определяющий поведение кнопок для разных ролей
						$user=new User();
						$acl=new Acl(true);
						if($acl->is_allowed($user->role,'organization', 'read')){
							$dis1='disabled="disbled"';
							$dis2='class="disabled"';
							$dis2_lighten='lighten';
							$dis3='';
						};
						if($acl->is_allowed($user->role,'organization', 'create')){
							$dis1='disabled="disbled"';
							$dis2='class="disabled"';
							$dis2_lighten='lighten';
							$dis3='';
						};
						if($acl->is_allowed($user->role,'organization', 'update')){
							$dis1='';
							$dis2='class="disabled"';
							$dis2_lighten='lighten';
							$dis3='';
						};
						if($acl->is_allowed($user->role,'organization', 'delete')){
							$dis1='';
							$dis2='class="disabled"';
							$dis2_lighten='lighten';
							$dis3='';
						};



$company=new Company($company['ID_ORG']);
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
		<span class="error"><?php echo $company ? __('company.title') . ': ' . iconv('CP1251', 'UTF-8', $company->name) : __('company.new'); ?></span>
		<?php if (isset($company->id_org)) { 
		
		?>
		<div class="switch">
			<table cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td>
						<a href="javascript:" class="left_switch active"><?php echo __('company.data'); ?></a>
					</td>

					<td>
						<?php echo HTML::anchor('companies/acl/' . $company->id_org, __('company.acl'), array('class' => 'middle_switch')); ?>
					</td>
					<td>
						<?php echo HTML::anchor('companies/people/' . $company->id_org, __('company.contacts'), array('class' => 'right_switch')); ?>
					</td>
				</tr>
			</tbody>
			</table>
		</div>
		<?php } ?>
	</div>
	<br class="clear" />
	<div class="content">
		<form action="companies/save" method="post" onsubmit="return validate()">
			<?php echo Form::hidden('hidden', 'form_sent') . Form::hidden('id', $company->id_org); ?>
			<p>
				<label for="name"><?php echo __('company.name'); ?></label>
				<br />
				<input type="text" <?php echo $dis1;?> 
				id="name" 
				name="name" 
				size="50" 
				value="<?php echo iconv('CP1251', 'UTF-8', $company->name); ?>" 
				maxlength=<?php echo constants::ORGNAME_LENGHT ;?>
				pattern="<?php echo constants::FIO_VALID ;?>" 
				required
				/>
				<br />
				<span class="error" id="error1" style="color: red; display: none;"><?php echo __('company.emptyname'); ?></span>
			</p>
			<br />
			
			<p>
				<label for="code"><?php echo __('company.code'); ?></label>
				<br />
				<input type="text" <?php echo $dis1;?> id="code" name="code" size="50" disabled value="<?php echo iconv('CP1251', 'UTF-8', $company->divcode); ?>" />
				<br />
				<span class="error" id="error2" style="color: red; display: none;"><?php echo __('company.emptycode'); ?></span>
			</p>
			
			<br />
			<p>
				<?php echo Form::label('parent', __('company.parent')); 
				//echo Debug::vars('82', $org_tree);?>
				<br />
				
				
				<select name="parent" <?php echo $dis1;?>>
								<option></option>
								<?php 
								$tree=new Tree();
									echo $tree->out_options($tree->array_to_tree($org_tree), $company->id_parent); 
								?>
				</select>
			</p>
			<br />

			<br />
			<br />
			<input type="submit"  <?php echo $dis1;?> value="<?php echo __('button.save'); ?>" />
			&nbsp;&nbsp;
			<!-- <input type="button" <?php echo $dis1;?> value="<?php echo __('button.cancel'); ?>" onclick="document.forms[0].reset();" /> 
			&nbsp;&nbsp;
			<input type="button" value="<?php echo __('button.backtolist'); ?>" onclick="location.href='<?php echo URL::base(); ?>companies'" />-->
			&nbsp;&nbsp;
			<?php
				if($company){ ?>
						<input type="button" <?php echo $dis1;?> value="<?php echo __('button.addpeople'); ?>" onclick="location.href='<?php echo URL::base().'contacts/edit/0?id_org='.$company->id_org;?>'" />
				<?php } ?>
			
			
		</form>
	
		
		
	</div>
</div>
