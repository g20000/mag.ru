<?php
// смотрим можно ли 
if ($user['rankname']!='admin' && $user['rankname']!='support' && $user['rankname']!='shipper') {
	exit('You are not admin!');
}

// watch for group checkboxes
$_page_scripts = "
	
	$(document).ready(function(){
		$(document).on('change', '.group-chkbox',  function() {
			var isCurrent = $(this).is(':checked');
			var isElse = false;
			var checkedCount = 0;
			$('.group-chkbox').each(function(){
				if ($(this).is(':checked')) { isElse = true; checkedCount++; }
			});
			if (checkedCount>=2) {
				$('.groupPkgBtn').removeAttr('disabled');
			} else {
				$('.groupPkgBtn').attr('disabled','disabled');
			}
		});
		
	});
";

?>
<script>
	function groupPkgs() {

		var checkedCount = 0;
		var preGrouping = [];
		$('.group-chkbox').each(function(){
			if ($(this).is(':checked')) { checkedCount++; preGrouping.push($(this).data('pkg-id')); }
		});
		if (checkedCount>=2) {
			if (confirm('Точно удалить группу?')) {
				console.log(preGrouping);
				$.ajax({
					url: '<?php echo $cfg['options']['siteurl']; ?>/gears/ajax.groupPkgs.php',
					type: 'POST',
					dataType: 'JSON',
					data: {ids:preGrouping},
					success: function(data) {
						console.log(data);
						if (data.type=='error') {
							notify(data.type, data.type, data.text);
						} else {
							document.location.reload();
						}
					},
					error: function(v1,v2,v3) {
						alert('Ошибка!\nПопробуйте позже.');
						console.log(v1,v2,v3);
					}
				}); 
			}
		} else {
			alert('Как минимум две должны быть выбраны!');
		}


	}
	
	function ungroupPkgs(group_hash) {
		if (confirm('Are You sure to ungroup packages?')) {
				$.ajax({
					url: '<?php echo $cfg['options']['siteurl']; ?>/gears/ajax.unGroupPkgs.php',
					type: 'POST',
					dataType: 'JSON',
					data: {hash:group_hash},
					success: function(data) {
						//console.log(data);
						document.location.reload();
					},
					error: function(v1,v2,v3) {
						alert('Ошибка!\nПопробуйте позже.');
						console.log(v1,v2,v3);
					}
				}); 
		}


	}	
</script>
<h1 class="page-header">Packages</h1>

<div class="pull-right" style="margin: 1em 0 1em 1em;">
	<!--<a href="<?php echo $cfg['options']['siteurl']; ?>/addPackage" class="btn btn-info">Добавить</a>-->
	<span class="btn btn-warning groupPkgBtn" disabled="disabled" onclick="groupPkgs();">Группировать</span>
</div>

<div class="table-responsive">
<table class="table table-striped">
	<thead>
		<tr>
			<th>#</th>
			<th>Треки</th>
			<th>Сотрудник</th>
			<th class="text-center">Статус</th>
			<th>Дата создания</th>
			<?php if($user['rankname']!='shipper') { ?>
				<th>Товар</th>
				<th>Отправитель</th>
				<?php if($user['rankname']!='admin') { ?>
					<th>Описание</th>
					<th>Покупатель</th>
					<th>Сортировщик</th>
					<th>Заметки</th>
					<th class='text-center'>Действия</th>
				<?php } ?>
			<?php } ?>
		</tr>
	</thead>
	<tbody>
	<?php

		$pkg = getPackages();
		if ($pkg!==false) {
			//debug($pkg);
			foreach($pkg as $k=>$v) {
				if (!is_array($v)) {
					$pkg_status = getPackageStatus($v->id);
				?>
					<tr data-user-id="<?php echo $v->id;?>">
						<td <?php if($user['rankname']=='admin'){ echo 'style="background-color:'.getPkgColor($v->id).' !important;"'; };?>><?php echo $v->id;?></td>
						<td><?php echo $v->track_type.' '.getTrackCheckLink($v->track_type,$v->track_num);?></td>
						<td><?php echo getLinkToUserProfile($v->drop_id);?></td>
						<td class="text-center">
							<?php 
								if(isset($v->status_text)){ 
									echo iconPkgStatuses($v->status_text);
								}
							?>
						</td>
						<td><?php if(isset($pkg_status->time)){ 
									echo $pkg_status->time;
								  }
							?>
						</td>
						
						<?php if($user['rankname']=='admin') { ?>
							<td style="max-width:220px;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;"><?php echo $v->item;?></td>

							<td><?php echo getLinkToUserProfile($v->shipper_id);?></td>

							<!--<td><?php echo $v->action;?></td>-->




							<!--<td><?php echo getLinkToUserProfile($v->buyer_id);?></td>-->
							<!--<td><?php echo getLinkToUserProfile($v->labler_id);?></td>-->

	<!--						<td><?php foreach (getPackageNotes($v->id) as $n_user_type=>$n_text) { 
								echo '<strong>'.$n_user_type.'</strong>';
								if (isset($n_text['public'])) { echo '<div class="tbl-notes"><p>'.$n_text['public'].'</p></div>'; }
							};?></td>-->
						<?php } ?>
						
						<td class="text-center">
							<a href="<?php echo $cfg['options']['siteurl'];?>/package/<?php echo $v->id;?>"><i class="fa fa-cogs"></i></a> | 
							<input type="checkbox" class="group-chkbox" data-placement="left" data-toggle="tooltip" data-pkg-id="<?php echo $v->id; ?>" title="Отметить для группировки">
						</td>
						
						
					</tr>

				<?php
				} else {
				?>
					<tr data-user-id="<?php echo $gv->id;?>" class="bg-unread">
						<td><?php echo $v[0]->id;?></td>
						<td style="max-width:220px;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;"><?php foreach($v as $gv) { echo "<span "; if($user['rankname']=='admin'){ echo 'style="background-color:'.getPkgColor($gv->id).' !important;"'; } echo ">".$gv->item.'</span><br>'; } ?></td>
						<td><?php foreach($v as $gv) { foreach (getPackageNotes($gv->id) as $n_user_type=>$n_text) { echo '<strong>'.$n_user_type.'</strong><p>'.(isset($n_text['public'])?$n_text['public']:'').'</p>'; }; } ?></td>
						<td><?php foreach($v as $gv) { echo $gv->action.'<br>'; } ?></td>
						<td class="text-center"><?php foreach($v as $gv) { echo iconPkgStatuses($gv->status_text).'<br />'; } ?></td>
						<td><?php foreach($v as $gv) { echo $gv->track_type.' '.$gv->track_num.'<br>'; } ?></td>
						<td><?php foreach($v as $gv) { echo getLinkToUserProfile($gv->shipper_id).'<br>'; } ?></td>
						<td><?php foreach($v as $gv) { echo getLinkToUserProfile($gv->drop_id).'<br>'; } ?></td>
						<td><?php foreach($v as $gv) { echo getLinkToUserProfile($gv->buyer_id).'<br>'; } ?></td>
						<td><?php foreach($v as $gv) { echo getLinkToUserProfile($gv->labler_id).'<br>'; } ?></td>
						<td><?php foreach($v as $gv) { $groupItemPkgStatus = getPackageStatus($gv->id); echo $groupItemPkgStatus->time."<br>"; } ;?></td>
						<td class="text-center">
							<a href="<?php echo $cfg['options']['siteurl'];?>/package/<?php echo $gv->id;?>"><i class="fa fa-cogs"></i></a> | 
							<i class="fa fa-expand" data-placement="left" data-toggle="tooltip" title="Разгруппировать" onclick="ungroupPkgs('<?php echo $gv->group_hash; ?>');"></i>
						</td>
					</tr>					
				<?php
				}
			}
		} else {

		}

	?>
	</tbody>
</table>
</div>