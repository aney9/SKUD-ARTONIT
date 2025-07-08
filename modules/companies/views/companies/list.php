<script type="text/javascript">


 
  	$(function() {		
  		$("#tablesorter").tablesorter({ headers: { 7:{sorter: false}},  widgets: ['zebra']});
		
  	});	
	
</script>
<style>
.tree{
  --spacing : 1.5rem;
  --radius  : 6px;
  --radius  : 6px;
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

.tree ul li{
  border-left : 2px solid #ddd;
}

.tree ul li:last-child{
  border-color : transparent;
}

.tree ul li::before{
  content      : '';
  display      : block;
  position     : absolute;
  top          : calc(var(--spacing) / -2);
  left         : -2px;
  width        : calc(var(--spacing) + 2px);
  height       : calc(var(--spacing) + 1px);
  border       : solid #ddd;
  border-width : 0 0 2px 2px;
}

.tree summary{
  display : block;
  cursor  : pointer;
}

.tree summary::marker,
.tree summary::-webkit-details-marker{
  display : none;
}

.tree summary:focus{
  outline : none;
}

.tree summary:focus-visible{
  outline : 1px dotted #000;
}

.tree li::after,
.tree summary::before{
  content       : '';
  display       : block;
  position      : absolute;
  top           : calc(var(--spacing) / 2 - var(--radius));
  left          : calc(var(--spacing) - var(--radius) - 1px);
  width         : calc(2 * var(--radius));
  height        : calc(2 * var(--radius));
  border-radius : 50%;
  background    : #ddd;
}

.tree summary::before{
  content     : '+';
  z-index     : 1;
  background  : #696;
  color       : #fff;
  line-height : calc(2 * var(--radius) - 2px);
  text-align  : center;
}

.tree details[open] > summary::before{
  content : '−';
}
</style>
<?php 
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
		<div id="search">
			<form action="companies/search" method="post">
				<input type="text" class="search noshadow" title="<?php echo __('search'); ?>" name="q" id="q" value="<?php if (isset($filter)) echo $filter; ?>" />
			</form>
		</div>
		<span><?php echo __('companies.title'); ?></span>
	</div>

	<br class="clear"/>
	<div  class="content">
	<?php
		//echo Debug::vars('19', $org_tree);exit;
		echo 'Дерево организаций.';
		echo '<br>'.$org_tree;//прорисовка дерева орг
		
	?>
	
	</div>	
	
	
	
	
	<br class="clear"/>
	<div class="content">
	<?php
		include Kohana::find_file('views', 'paginatoion_controller_template'); 
		$sn=0;
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
		<form id="form_data" name="form_data" action="" method="post">
			<table class="data tablesorter-blue" width="100%" cellpadding="0" cellspacing="0" id="tablesorter" >
				<thead>
					<tr>
						<!--
						<th style="width:10px">
							<input type="checkbox" id="check_all" name="check_all"/>
						</th>
						-->
						<?php

						echo '<th class="filter-false sorter-false">' . __('sn') . '</th>';
						echo '<th class="filter-false">' . __('companies.id') . '</th>';
						echo '<th>' . __('companies.name') . '</th>';
						echo '<th class="sorter-false">' . __('companies.countChildren') . '</th>';
						echo '<th class="sorter-false">' . __('companies.countContact') . '</th>';
						echo '<th>' . __('companies.code') . '</th>';
						echo '<th>' . __('companies.parent') . '</th>';
						echo '<th class="filter-false sorter-false">' . __('companies.action') . '</th>';
						?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($companies as $c) {
						$t1=microtime(true);
						$company=new Company(Arr::get($c,'ID_ORG'));
						//echo Debug::vars('159', microtime(true) - $t1); exit;
						?>
					
					<tr>
						<!--
						<td>
							<input type="checkbox"/>
						</td>
						-->
						<?php 
						
						echo '<td align="center">' . ++$sn . '</td>';
						echo '<td align="center">' . $company->id_org . '</td>';
						echo '<td>' . HTML::anchor('companies/edit/' . $company->id_org, iconv('CP1251', 'UTF-8', $company->name));
							if($company->flag & 1) echo HTML::image('/images/icon_guest2.png', array('width'=>'16'));
						echo '</td>';	
					
												
						echo '<td>' . count($company->getChildIdOrg()) . '</td>';
						echo '<td>' . count($company->getChildId_pepList()) . '</td>';
						echo '<td>' . iconv('CP1251', 'UTF-8', $company->divcode) . '</td>';
						
						echo '<td>' //. Debug::vars($company)
						.HTML::anchor('companies/edit/' . $company->id_parent, iconv('CP1251', 'UTF-8', $c['PARENT'])) . '</td>';
						
						echo '<td>';
					
						
							
			echo HTML::anchor('companies/edit/' . $company->id_org, HTML::image('images/icon_edit.png', array('title' => __('tip.edit'), 'class' => 'help '.$dis1)));
						?>
						<a href="javascript:" <?php echo $dis2 ?> onclick="if (confirm('<?php echo __('companies.confirmdelete'); ?>')) location.href='<?php echo URL::base() . 'companies/delete/' . $c['ID_ORG'].'/'.$c['PARENTID']; ?>';">
						<?php echo HTML::image('images/icon_delete.png', array('title' => __('tip.delete'), 'class' => 'help '.$dis2_lighten)); ?>
							</a>
						<?php	
							echo HTML::anchor('companies/people/' . $c['ID_ORG'], HTML::image('images/icon_contacts.png', array('title' => __('tip.view'), 'class' => 'help '.$dis3)));
						?>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<div id="chart_wrapper" class="chart_wrapper"></div>
		<!-- End bar chart table-->
		</form>
		
		
	</div>
</div>
