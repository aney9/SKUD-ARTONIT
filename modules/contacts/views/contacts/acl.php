<?php
/*
Эта страница выводит список категорий доступа для указаного пипла
*/
 
//echo Debug::vars('89', $contact);
//echo Debug::vars('90', $contact_acl);

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
		<span>
		
		<?php 
			
			switch($mode) {
				case('new'):
					;
				break;
				
				case('edit'):
					echo __('contact.titleEditContact', array( 
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
		<?php if ($contact) {
			echo $topbuttonbar;
			
		} ?>
	</div>
	<br class="clear" />
	<div class="content">
		<form action="contacts/saveACL" method="post" onsubmit="return validate()">
			<input type="hidden" name="hidden" value="form_sent" />
			<input type="hidden" name="id" value="<?php echo $contact->id_pep; ?>" />
		
		<div style="padding-left: 15px">
		
		Категории доступа<br>	
		<div style="padding-left: 15px">
		
		<?php
		//echo Form::hidden('contact_acl', json_encode($contact_acl));
		//echo Debug::vars('185', $contact_acl);
		$column=4;// количество колонок в таблице. Если категорий доступа больше, чем колонок, то остальные будут выводиться ниже построчно
		$accessNameList=AccessName::getList();//список всех категорий доступа
		
		$aaa=array_chunk($accessNameList, $column );//разбиваю массив на подмассивы длиной $column
		
		$res=array();//вспомогательный массив из id категорий доступа
		
			$res=array();
				foreach($contact_acl as $key=>$value)
				{
					$res[]=Arr::get($value, 'ID_ACCESSNAME');
					
				}
				
				
				
				$aclsForCurrentUser = Model::factory('company')->getListAccessNameForCurrentUser(Arr::get(Auth::instance()->get_user(), 'ID_PEP'));
				echo Form::hidden('contact_acl_befor', json_encode($res));//список категорий доступа ДО редактирования
				echo Form::hidden('user_acl_anabled', json_encode($aclsForCurrentUser));//список категорий доступа ДО редактирования
				
				
				
		?>
		
		
					<div>
					<table>
					<?php for ($j=0; $j<count($aaa); $j++){ //начинаю перебор массивово с перечнями категорий доступа
					  
						echo '<tr>';

 										
						foreach (Arr::get($aaa, $j) as $key=>$value) //вывод построчный
						{
							echo '<td>';
														
							   // echo Debug::vars('174', $key, $value); exit;
							if(in_array(Arr::get($value, 'ID_ACCESSNAME'), $aclsForCurrentUser)){
    							echo Form::checkbox('aclList['.Arr::get($value, 'ID_ACCESSNAME').']', Arr::get($value, 'ID_ACCESSNAME'), in_array (Arr::get($value, 'ID_ACCESSNAME'), $res))
    							.' '
		                      . iconv('CP1251', 'UTF-8', Arr::get($value, 'NAME', ''));

							} else {
							    
							    echo Form::checkbox('aclList['.Arr::get($value, 'ID_ACCESSNAME').']', Arr::get($value, 'ID_ACCESSNAME'), in_array (Arr::get($value, 'ID_ACCESSNAME'), $res), array("disabled"=>"disabled"))
							    .' '
		                          . iconv('CP1251', 'UTF-8', Arr::get($value, 'NAME', ''));
							}
					 
							
							echo '</td>';
						}
						echo '</tr>';
		}
						?>
						
		  
		 
					</table>
					</div>
<div>
			<br />
				<?php
			if($contact->is_active) {
			?>
			<input type="submit" <?php echo $dis1 ?> value="<?php echo __('button.save'); ?>" />
			<!--
			<input type="button" value="<?php echo __('button.cancel'); ?>" onclick="document.forms[0].reset()" />
			<input type="button" value="<?php echo __('button.backtolist'); ?>" onclick="location.href='<?php echo URL::base(); ?>contacts'" />
			-->
			<?php }?>
		</form>
	</div>
</div>
Таблица точек прохода и категорий доступа
<?php
$doorAll=array();//перечень всех точек прохода, через которые может ходить контакт. Этот список затем можно вывести на печать.
$outDoorList=array();//массив для вывода на печать

//$contact_acl - список категорий доступа, разрешенных контакту.
foreach($contact_acl as $key=>$value){
	$access=new Access(Arr::get($value,'ID_ACCESSNAME'));
	$result=$access->getDoorIdList();//список точек прохода для указанной категории доступа.
	
	if( $result== 0){//если запрос выполнен успешно, то собираю массивы
		$doorList[Arr::get($value,'ID_ACCESSNAME')]=$access->dataResult;
		$doorAll=array_merge($doorAll, $access->dataResult);//формирую список все точек прохода путем слияния массивов. Предполагаю, что должны остаться только уникальные id точек прохода.
	//	echo Debug::vars('238', $doorList, $doorAll); //exit;
	} else {
		echo Debug::vars('242',$result, $access->actionDesc); exit;
	}
	
	
}
//echo Debug::vars('245',$doorList); //exit;
//echo Debug::vars('245',$doorAll); //exit;
//echo Debug::vars('249', array_values($doorAll)); exit;
// теперь строю таблицу
echo Form::open('reports/doorList');
//echo Form::open('reports/savecsv');
?>
	
	
	</table>
	<?php
		include Kohana::find_file('views', 'paginatoion_controller_template'); 
		$sn=0;
?>
	<table class="data tablesorter-blue" width="60%" cellpadding="0" cellspacing="0" id="tablesorter" >
			<thead>
				<tr>
					<th>№</th>
					<th>Название двери</th>
					<?php
						foreach($contact_acl as $key=>$value){//названия категорий доступа в заголовке таблицы
							echo '<th>';
							$an=new Access(Arr::get($value,'ID_ACCESSNAME'));
								echo iconv('CP1251', 'UTF-8',$an->name);
							echo '</th>';
						}
					
					?>
					<th>Повтор</th>
					
				</tr>
				</thead>
				<tbody>
				<?php
				$i=0;
			foreach(array_unique($doorAll) as $key=>$value){	
				$m=0;// счетчик количества повторов точки доступа в категориях
				echo '<tr>';
					echo '<td>'.++$i.'</td>';
					$door=new Door($value);
					echo '<td>'. iconv('CP1251', 'UTF-8',$door->name).' ('.$door->id.')</td>';//тут надо название двери
					
					$outDoorList[]=array('sn'=>$i, 'id_door'=>$door->id, 'name'=>$door->name);
					foreach($contact_acl as $key=>$value){
						echo '<td>';
							
							//echo Debug::vars(in_array($door->id, Arr::get($doorList,Arr::get($value,'ID_ACCESSNAME'))));
							if(in_array($door->id, Arr::get($doorList,Arr::get($value,'ID_ACCESSNAME')))){
								echo HTML::image('images\icon_accept.png');
								$m++;
							} else {
								echo HTML::image('images\icon_delete.png');
					}
						echo '</td>';
						
					}
					echo '<td>';
					//echo HTML::image('images\icon_delete.png');	
						if($m>1) echo HTML::image('images\icon_warning.png');
					
				echo '</tr>';
			}
				?>
			</tbody>
			</table>
		
	</table>
			<?php
				echo Form::hidden('id_pep', $contact->id_pep); 
		
				echo Form::submit('savecvs', __('button.savecsv'));
	
				echo Form::submit('savexls', __('button.savexlsx'));
	
				echo Form::submit('savepdf', __('button.savepdf'));
	
		
				echo Form::hidden('doorList', serialize($doorAll)); 
			//echo Debug::vars('314', $outDoorList);exit;
				echo Form::hidden('outDoorList', iconv('CP1251', 'UTF-8', serialize($outDoorList))); 
				
				
			echo Form::close();
			?>
</div>
			