<?php
// смотрим можно ли 
if ($user['rankname']!='labler') {
	exit('Access not permitted!');
}
?>

<h1 class="page-header">Входящие</h1>


<div class="table-responsive">
<table class="table table-striped">
	<thead>
		<tr>
			<th>#</th>
			<th>Товар</th>
			<th>Заметки</th>
			<th>Описание</th>
			<th class="text-center">Статус</th>
			<th>Трек</th>
			<th>Отправитель</th>
			<th>Сотрудник</th>
			<th>Покупатель</th>
			<th>Дата создания</th>
			<th class='text-center'>Действия</th>
		</tr>
	</thead>
	<tbody>
	<?php

		$pkg = getLablerPackages('inbox');
		if ($pkg!==false) {
			//debug($pkg);
			foreach($pkg as $k=>$v) {
				if (!is_array($v)) {
					$pkg_status = getPackageStatus($v->id);
				?>
					<tr data-user-id="<?php echo $v->id;?>">
						<td><?php echo $v->id;?></td>
						<td style="max-width:220px;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;"><?php echo $v->item;?></td>
						<td><?php foreach (getPackageNotes($v->id) as $n_user_type=>$n_text) { echo '<strong>'.$n_user_type.'</strong><p>'.(isset($n_text['public'])?$n_text['public']:'').'</p>'; };?></td>
						<td><?php echo $v->action;?></td>
						<td class="text-center"><?php echo iconPkgStatuses($v->status_text);?></td>
						<td><?php echo $v->track_type.' '.getTrackCheckLink($v->track_type,$v->track_num);?></td>
						<td><?php echo getFullUserNameById($v->shipper_id);?></td>
						<td><?php echo getFullUserNameById($v->drop_id);?></td>
						<td><?php echo getFullUserNameById($v->buyer_id);?></td>
						<td><?php echo $pkg_status->time;?></td>
						<td class="text-center">
							<a href="<?php echo $cfg['options']['siteurl'];?>/lablerPkgInfo/<?php echo $v->id;?>/inbox"><i class="fa fa-cogs"></i></a>
						</td>
					</tr>

				<?php
				} else {
				?>
					<tr data-user-id="<?php echo $gv->id;?>" class="bg-unread">
						<td><?php echo $v[0]->id;?></td>
						<td style="max-width:220px;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;"><?php foreach($v as $gv) { echo $gv->item.'<br>'; } ?></td>
						<td><?php foreach($v as $gv) { foreach (getPackageNotes($gv->id) as $n_user_type=>$n_text) { echo '<strong>'.$n_user_type.'</strong><p>'.(isset($n_text['public'])?$n_text['public']:'').'</p>'; }; } ?></td>
						<td><?php foreach($v as $gv) { echo $gv->action.'<br>'; } ?></td>
						<td class="text-center"><?php foreach($v as $gv) { echo iconPkgStatuses($gv->status_text).'<br />'; } ?></td>
						<td><?php foreach($v as $gv) { echo $gv->track_type.' '.getTrackCheckLink($gv->track_type,$gv->track_num).'<br>'; } ?></td>
						<td><?php foreach($v as $gv) { echo getFullUserNameById($gv->shipper_id).'<br>'; } ?></td>
						<td><?php foreach($v as $gv) { echo getFullUserNameById($gv->drop_id).'<br>'; } ?></td>
						<td><?php foreach($v as $gv) { echo getFullUserNameById($gv->buyer_id).'<br>'; } ?></td>
						<td><?php foreach($v as $gv) { $groupItemPkgStatus = getPackageStatus($gv->id); echo $groupItemPkgStatus->time."<br>"; } ;?></td>
						<td class="text-center">
							<a href="<?php echo $cfg['options']['siteurl'];?>/lablerPkgInfo/<?php echo $gv->id;?>/inbox"><i class="fa fa-cogs"></i></a>
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