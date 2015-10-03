<?php
// смотрим можно ли 
if ($user['rankname']!='admin' && $user['rankname']!='support') {
	exit('You are not admin!');
}
?>

<h1 class="page-header">Товары</h1>


<div class="table-responsive">
<table class="table table-striped">
	<thead>
		<tr>
			<th>#</th>
			<th>Товар</th>
			<th class="text-center">Статус</th>
			<th>Заметки</th>
			<th>Примечание</th>
			<th>Треки</th>
			<th>Отправитель</th>
			<th>Сотрудник</th>
			<th>Покупатель</th>
			<th>Сортировщик</th>
			<th>Дата создания</th>
			<th class='text-center'>Действия</th>
		</tr>
	</thead>
	<tbody>
	<?php
		$pkg = getUserPackages($route->param);
		if ($pkg!==false) {
			//debug($pkg);
			foreach($pkg as $k=>$v) {
				if (!is_array($v)) {
					$pkg_status = getPackageStatus($v->id);
				?>
					<tr data-user-id="<?php echo $v->id;?>">
						<td <?php if($user['rankname']=='admin'){ echo 'style="background-color:'.getPkgColor($v->id).' !important;"'; };?>><?php echo $v->id;?></td>
						<td><?php echo $v->item;?></td>
						<td class="text-center"><?php echo iconPkgStatuses($pkg_status->status_text);?></td>
						<td><?php foreach (getPackageNotes($v->id) as $n_user_type=>$n_text) { 
							
							if (isset($n_text['public'])) echo '<strong>'.$n_user_type.'</strong><p>'.$n_text['public'].'</p>'; 
							
						};?></td>
						<td><?php echo $v->action;?></td>
						<td><?php echo $v->track_type.' '.$v->track_num;?></td>
						<td><?php echo getLinkToUserProfile($v->shipper_id);?></td>
						<td><?php echo getLinkToUserProfile($v->drop_id);?></td>
						<td><?php echo getLinkToUserProfile($v->buyer_id);?></td>
						<td><?php echo getLinkToUserProfile($v->labler_id);?></td>
						<td><?php echo $pkg_status->time;?></td>
						<td class="text-center">
							<a href="<?php echo $cfg['options']['siteurl'];?>/package/<?php echo $v->id;?>"><i class="fa fa-cogs"></i></a>													
						</td>
					</tr>

				<?php
				} else {
				?>
					<tr data-user-id="<?php echo $gv->id;?>" class="bg-unread">
						<td><?php echo $v[0]->id;?></td>
						<td style="max-width:220px;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;"><?php foreach($v as $gv) { echo "<span "; if($user['rankname']=='admin'){ echo 'style="background-color:'.getPkgColor($gv->id).' !important;"'; } echo ">".$gv->item.'</span><br>'; } ?></td>
						<td><?php foreach($v as $gv) { $groupItemPkgStatus = getPackageStatus($gv->id); echo iconPkgStatuses($groupItemPkgStatus->status_text)."<br>"; } ?></td>
						<td><?php foreach($v as $gv) { foreach (getPackageNotes($gv->id) as $n_user_type=>$n_text) { echo '<strong>'.$n_user_type.'</strong><p>'.$n_text['public'].'</p>'; }; } ?></td>
						<td><?php foreach($v as $gv) { echo $gv->action.'<br>'; } ?></td>
						<td><?php foreach($v as $gv) { echo $gv->track_type.' '.$gv->track_num.'<br>'; } ?></td>
						<td><?php foreach($v as $gv) { echo getLinkToUserProfile($gv->shipper_id).'<br>'; } ?></td>
						<td><?php foreach($v as $gv) { echo getLinkToUserProfile($gv->drop_id).'<br>'; } ?></td>
						<td><?php foreach($v as $gv) { echo getLinkToUserProfile($gv->buyer_id).'<br>'; } ?></td>
						<td><?php foreach($v as $gv) { echo getLinkToUserProfile($gv->labler_id).'<br>'; } ?></td>
						<td><?php foreach($v as $gv) { $groupItemPkgStatus = getPackageStatus($gv->id); echo $groupItemPkgStatus->time."<br>"; } ;?></td>
						<td class="text-center">
							<a href="<?php echo $cfg['options']['siteurl'];?>/package/<?php echo $gv->id;?>"><i class="fa fa-cogs"></i></a>									
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