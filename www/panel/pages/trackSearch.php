<?php

$search = addslashes(strip_tags(filter_input(INPUT_POST, 's', FILTER_UNSAFE_RAW)));

if (!isset($search) || empty($search)) {
	echo '<h2>Search string is empty!</h2>';
	echo '<form class="form-group" action="'.$cfg['options']['siteurl'].'/trackSearch" method="POST">
						<input type="text" name="s" class="form-control input-sm" placeholder="Поиск...">&nbsp;&nbsp;
						<input type="submit" class="form-control btn btn-default" value="Искать">
					</form>';
} else {
?>
	<h2>Search results!</h2>
	<form class="form-group container-fluid" action="<?php echo $cfg['options']['siteurl']; ?>/trackSearch" method="POST">
		<div class="row">
			<div class="col-sm-10">
				<input type="text" name="s" class="form-control" placeholder="Искать..." value="<?php echo $search; ?>">
			</div>
			<div class="col-sm-2">
				<input type="submit" class="form-control btn btn-default" value="Искать">
			</div>
		</div>
	</form>
	
<?php	

	$q = "SELECT * FROM `trackers` WHERE `track_num` = '".$search."';";
	$res = $db->query($q);
	if (isset($res[0])) {
		foreach($res as $rv) {

?>

<h1 class="page-header">Packages</h1>

<div class="table-responsive">
<table class="table table-striped">
	<thead>
		<tr>
			<th>#</th>
			<th>Товар</th>
			<th>Статус</th>
			<th>Заметки</th>
			<th>Описание</th>
			<th class="text-center">Статус</th>
			<th>Трек</th>
			<th>Отправитель</th>
			<th>Сотрудник</th>
			<th>Покупатель</th>
			<th>Дата создания</th>
			<th class='text-center'>Действие</th>
		</tr>
	</thead>
	<tbody>
	<?php

		$pkg = getPackages($rv->pkg_id);
		if ($pkg!==false) {
			//debug($pkg);
			foreach($pkg as $k=>$v) {
				if (!is_array($v)) {
					$pkg_status = getPackageStatus($v->id);
				?>
					<tr data-user-id="<?php echo $v->id;?>">
						<td><?php echo $v->id;?></td>
						<td style="max-width:220px;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;"><?php echo $v->item;?></td>
						<td><?php echo $pkg_status->status_text;?></td>
						<td><?php foreach (getPackageNotes($v->id) as $n_user_type=>$n_text) { echo '<strong>'.$n_user_type.'</strong><p>'.$n_text.'</p>'; };?></td>
						<td><?php echo $v->action;?></td>
						<td class="text-center"><?php echo iconPkgStatuses($v->status_text);?></td>
						<td><?php echo $v->track_type.' '.$v->track_num;?></td>
						<td><?php echo getFullUserNameById($v->shipper_id);?></td>
						<td><?php echo getFullUserNameById($v->drop_id);?></td>
						<td><?php echo getFullUserNameById($v->buyer_id);?></td>
						<td><?php echo $pkg_status->time;?></td>
						<td class="text-center">
							<?php if ($user['rankname']=='drop') { $pkgPage = 'dropPkgInfo'; } ?>
							<a href="<?php echo $cfg['options']['siteurl'];?>/<?php echo $pkgPage; ?>/<?php echo $v->id;?>"><i class="fa fa-cogs"></i></a>
						</td>
					</tr>

				<?php
				} else {
				?>
					<tr data-user-id="<?php echo $gv->id;?>" class="bg-unread">
						<td><?php echo $v[0]->id;?></td>
						<td style="max-width:220px;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;"><?php foreach($v as $gv) { echo $gv->item.'<br>'; } ?></td>
						<td><?php foreach($v as $gv) { $groupItemPkgStatus = getPackageStatus($gv->id); echo $groupItemPkgStatus->status_text."<br>"; } ?></td>
						<td><?php foreach($v as $gv) { foreach (getPackageNotes($gv->id) as $n_user_type=>$n_text) { echo '<strong>'.$n_user_type.'</strong><p>'.$n_text.'</p>'; }; } ?></td>
						<td><?php foreach($v as $gv) { echo $gv->action.'<br>'; } ?></td>
						<td class="text-center"><?php foreach($v as $gv) { echo iconPkgStatuses($gv->status_text).'<br />'; } ?></td>
						<td><?php foreach($v as $gv) { echo $gv->track_type.' '.$gv->track_num.'<br>'; } ?></td>
						<td><?php foreach($v as $gv) { echo getFullUserNameById($gv->shipper_id).'<br>'; } ?></td>
						<td><?php foreach($v as $gv) { echo getFullUserNameById($gv->drop_id).'<br>'; } ?></td>
						<td><?php foreach($v as $gv) { echo getFullUserNameById($gv->buyer_id).'<br>'; } ?></td>
						<td><?php foreach($v as $gv) { $groupItemPkgStatus = getPackageStatus($gv->id); echo $groupItemPkgStatus->time."<br>"; } ;?></td>
						<td class="text-center">
							<?php $pkgPage='package'; if ($user['rankname']=='drop') { $pkgPage = 'dropPkgInfo'; } if ($user['rankname']=='buyer') { $pkgPage = 'buyerPkgInfo'; } ?>
							<a href="<?php echo $cfg['options']['siteurl'];?>/<?php echo $pkgPage; ?>/<?php echo $gv->id;?>"><i class="fa fa-cogs"></i></a>
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

<?php

			
		}
	}



}

?>