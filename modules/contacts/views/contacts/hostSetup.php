<?php
include Kohana::find_file('views','alert');
?>
<div class="onecolumn">
	<div class="header">
		<span><?php echo __('Выбор организации для быстрой регистрации') ?></span>
	</div>
	<br class="clear" />
	<div class="content">
			<p>Выберите организацию для быстрой регистрации гостей</p>
								<?php
								
								$tree=new Tree();
									$org_tree = Model::Factory('Company')->getOrgListForOnce(Arr::get(Auth::instance()->get_user(), 'ID_ORGCTRL'));
									
									
									$id_org_host=Arr::get(Kohana::$config->load('system')->get('fastorder'), Arr::get(Auth::instance()->get_user(), 'ID_PEP'));
									echo Form::open('contacts/saveFastOrder');
									
									echo Form::hidden('id_pep', Arr::get(Auth::instance()->get_user(), 'ID_PEP'));
									echo '<select name="id_org">';
										echo $tree->out_options($tree->array_to_tree($org_tree), $id_org_host);
									echo '</select>';
									echo Form::submit('123','Сохранить');
									echo Form::close();
									
								?>
							</select>
	</div>
</div>
