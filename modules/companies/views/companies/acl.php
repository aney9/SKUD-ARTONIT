<?php
/*
Эта страница выводит список категорий доступа для указаной организации
*/
?>
<script language="javascript">
	function validate()
	{
		$('.error').hide();
		if ($('#surname').val() == '') {
			$('#error1').show();
			$('#surname').focus();
			return false;
		}
		var ymd = $('#datebirth').val();
		if (ymd == '') {
			$('#error21').show();
			return false;
		}
		if (!ymd.match(/^\d{4}-\d{2}-\d{2}$/)) {
			$('#error22').show();
			return false;
		}
		ymd = ymd.split('-');
		if (ymd[1] > 12 || ymd[1] < 1 || ymd[2] > 31 || ymd[2] < 1) {
			$('#error23').show();
			return false;
		}
		ymd = $('#datedoc').val(); 
		if (ymd == '') {
			$('#error31').show();
			return false;
		}
		if (!ymd.match(/^\d{4}-\d{2}-\d{2}$/)) {
			$('#error32').show();
			return false;
		}
		ymd = ymd.split('-');
		if (ymd[1] > 12 || ymd[1] < 1 || ymd[2] > 31 || ymd[2] < 1) {
			$('#error33').show();
			return false;
		}
		var hm = $('#workstart').val();
		if (hm == '') {
			$('#error41').show();
			$('#workstart').focus();
			return false;
		}
		if (!hm.match(/^\d{2}:\d{2}$/) && !hm.match(/^\d{2}:\d{2}:\d{2}$/)) {
			$('#error42').show();
			$('#workstart').focus();
			return false;
		}
		hm = hm.split(':');
		if (hm[0] > 23 || hm[1] > 59 || (hm.length == 3 && hm[2] > 59)) {
			$('#error43').show();
			$('#workstart').focus();
			return false;
		}
		hm = $('#workend').val();
		if (hm == '') {
			$('#error51').show();
			$('#workend').focus();
			return false;
		}
		if (!hm.match(/^\d{2}:\d{2}$/) && !hm.match(/^\d{2}:\d{2}:\d{2}$/)) {
			$('#error52').show();
			$('#workend').focus();
			return false;
		}
		hm = hm.split(':');
		if (hm[0] > 23 || hm[1] > 59 || (hm.length == 3 && hm[2] > 59)) {
			$('#error53').show();
			$('#workstart').focus();
			return false;
		}
		if ($('#tabnum').val() == '') {
			$('#error6').show();
			$('#tabnum').focus();
			return false;
		} else if ($('#login').val() == '') {
			$('#error7').show();
			$('#login').focus();
			return false;
		} else if ($('#password').val() == '') {
			$('#error8').show();
			$('#password').focus();
			return false;
		}
	}
</script>

<?php 
//echo Debug::vars('89', $company);
//echo Debug::vars('90', $company_acl);
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
		<span><?php echo $company ? __('company.title') . ': ' . iconv('CP1251', 'UTF-8', Arr::get($company, 'NAME')) . ' ' . iconv('CP1251', 'UTF-8', Arr::get($company, 'SURNAME')) : __('company.new'); ?></span>
		<?php if ($company) { ?>
		<div class="switch">
			<table>
			<tbody>
				<tr>
					<td>
						<?php echo HTML::anchor('companies/edit/' . Arr::get($company, 'ID_ORG'), __('company.data'), array('class' => 'left_switch')); ?>
					</td>
					<td>
						<a href="javascript:" class="middle_switch active"><?php echo __('company.acl'); ?></a>
					</td>
					
					<td>
						<?php echo HTML::anchor('companies/people/' . Arr::get($company,'ID_ORG'), __('company.contacts'), array('class' => 'right_switch')); ?>
					</td>
				</tr>
			</tbody>
			</table>
		</div>
		<?php } ?>
	</div>
	<br class="clear" />
	<div class="content">
		<form action="companies/saveACL" method="post" onsubmit="return validate()">
			<input type="hidden" name="hidden" value="form_sent" />
			<input type="hidden" name="id" value="<?php echo Arr::get($company, 'ID_ORG'); ?>" />
			Категории доступа<br>	
 
		<div style="padding-left: 15px">
		
		<?php
			$column=4;// количество колонок в таблице. Если категорий доступа больше, чем колонок, то остальные будут выводиться ниже построчно
			$accessNameList=AccessName::getList();//список категорий доступа
			
			$aaa=array_chunk($accessNameList, $column );//разбиваю массив на подмассивы длиной $column
			
			$res=array();//вспомогательный массив из id категорий доступа
				foreach($company_acl as $key=>$value)
				{
					$res[]=Arr::get($value, 'ID_ACCESSNAME');
				}

		?>
				<div>

				<fieldset>
					<legend><?php echo __('Недоступные категории доступа'); ?></legend>
		
					<table>
					<?php for ($j=0; $j<count($aaa); $j++){ //начинаю перебор массивово с перечнями категорий доступа
					  
						echo '<tr>';
						
 										
						foreach (Arr::get($aaa, $j) as $key=>$value) //вывод построчный
						{
							
														
							   // echo Debug::vars('174', $key, $value); exit;
							if(in_array(Arr::get($value, 'ID_ACCESSNAME'), $aclsForCurrentUser)){
    						

							} else {
						echo '<td>';	    
							    echo Form::checkbox('aclList['.Arr::get($value, 'ID_ACCESSNAME').']', Arr::get($value, 'ID_ACCESSNAME'), in_array (Arr::get($value, 'ID_ACCESSNAME'), $res), array("disabled"=>"disabled"))
							    .' '
		                          . iconv('CP1251', 'UTF-8', Arr::get($value, 'NAME', ''));
							echo '</td>';
							}
					 
							
						
						}
						echo '</tr>';
		}
						?>
						
		  
		 
					</table>
					</fieldset>

					<fieldset>
					<legend><?php echo __('Доступные категории доступа'); ?></legend>
					<table>
					<?php for ($j=0; $j<count($aaa); $j++){ //начинаю перебор массивово с перечнями категорий доступа
					  
						echo '<tr>';

 										
						foreach (Arr::get($aaa, $j) as $key=>$value) //вывод построчный
						{
							
														
							   // echo Debug::vars('174', $key, $value); exit;
							if(in_array(Arr::get($value, 'ID_ACCESSNAME'), $aclsForCurrentUser)){
							echo '<td>';	
    							echo Form::checkbox('aclList['.Arr::get($value, 'ID_ACCESSNAME').']', Arr::get($value, 'ID_ACCESSNAME'), in_array (Arr::get($value, 'ID_ACCESSNAME'), $res))
    							.' '
		                      . iconv('CP1251', 'UTF-8', Arr::get($value, 'NAME', ''));
							echo '</td>';
							} else {
							    
							    // echo Form::checkbox('aclList['.Arr::get($value, 'ID_ACCESSNAME').']', Arr::get($value, 'ID_ACCESSNAME'), in_array (Arr::get($value, 'ID_ACCESSNAME'), $res), array("disabled"=>"disabled"))
							    // .' '
		                          // . iconv('CP1251', 'UTF-8', Arr::get($value, 'NAME', ''));
							}
					 
							
							
						}
						echo '</tr>';
		}
						?>
						
		  
		 
					</table>
					</fieldset>
					
					</div>
					
			<?php
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
					
			<input type="submit" <?php echo $dis1;?> value="<?php echo __('button.save'); ?>" />
			&nbsp;&nbsp;
			<!-- <input type="button" value="<?php echo __('button.cancel'); ?>" onclick="document.forms[0].reset()" />
			&nbsp;&nbsp;
			<input type="button" value="<?php echo __('button.backtolist'); ?>" onclick="location.href='<?php echo URL::base(); ?>companies'" /> -->
			</div>
		</form>
		
		</div>
	
</div>



<div class="onecolumn">
	<div class="header">
		<span><?php echo $company ? __('Наследование прав доступа') . ': ' . iconv('CP1251', 'UTF-8', Arr::get($company, 'NAME')) . ' ' . iconv('CP1251', 'UTF-8', Arr::get($company, 'SURNAME')) : __('company.new'); ?></span>

	</div>
	<br class="clear" />
	<div class="content">
	<?php echo Kohana::message('anymess', 'companyAclInheritDesc');?>
	<fieldset>
		<legend><?php echo __('Установка прав доступа'); ?></legend>
		<?php

		echo Form::open('/Accesss');
		
			// echo Form::checkbox('addForContact', 1, true).__('Добавить контакту категории доступа родительской организации к уже имеющимся категориям доступа.').'<br>';
			// echo Form::checkbox('addForChild', 1, true).__('Удалить у контакта категории доступа, не входящие в категории доступа родительской организации.').'<br><br>';
			// echo Form::checkbox('addForChild', 1, true).__('Обрабаывать вложенные группы.').'<br>';
			// echo Form::hidden('id_org', Arr::get($company, 'ID_ORG'));
			echo '<br>';
			echo Form::radio('todo','add',true).__('Добавить контакту категории доступа родительской организации к уже имеющимся категориям доступа.').'<br>';
			echo Form::radio('todo','del').__('Удалить у контакта категории доступа, не входящие в категории доступа родительской организации.').'<br><br>';
			echo Form::checkbox('makeForForChild', 1, true).__('Обрабаывать вложенные группы.').'<br>';
			echo Form::hidden('id_org', Arr::get($company, 'ID_ORG'));
			
			echo Form::submit('aclStart', __('Начать'));
		echo Form::close()
		?>
									
	</fieldset>
	</div>
</div>