<?php
// смотрим можно ли 
if ($user['rankname']!='admin' && $user['rankname']!='support') {
	exit('You are not admin!');
}
?>
<script>
function deleteBuyer(id) {
	if (confirm('Точно удалить? '+id)===true) {
		$('table').find('tr').each(function(){
			if ($(this).data('user-id')===id) {
				$(this).remove();
				$.ajax({
					url: '<?php echo $cfg['options']['siteurl'];?>/gears/ajax.deleteUser.php',
					method: 'POST',
					dataType: 'JSON',
					data: {
						userid: id
					},
					success: function(data) {
						if (data.type == 'error') {
							notify('error','Удален',data.text);
						} else {
							notify('info','Удален',data.text);
						}
						
						console.log(data);
					},
					error: function(v1,v2,v3) {
						console.log(v1,v2,v3);
					}
				})
			}
		});
	}
}

</script>

<h1 class="page-header">Покупатели</h1>

<div class="pull-right" style="margin: 1em 0 1em 1em;"><a href="<?php echo $cfg['options']['siteurl']; ?>/addUser" class="btn btn-info">Создать пользователя</a></div>
<div style="clear: both"></div>

<div class="table-responsive">
<table class="table table-striped">
	<thead>
		<tr>
			<th>#</th>
			<th>Логин</th>
			<!--<th>ФИО</th>-->
			<th>Email</th>
			<!--
			<th>Страна</th>
			<th>Шатат</th>
			-->
			<!--<th>Status</th>-->
			<th>Дата регистрации</th>
			<th class='text-center'>Действия</th>
		</tr>
	</thead>
	<tbody>
	<?php

		$usersList = getUsersListRank(5);
		if ($usersList!==false) {
			foreach($usersList as $k=>$v) {
				?>
					<tr data-user-id="<?php echo $v->id;?>">
						<td><?php echo $v->id;?></td>
						<td><a href="<?php echo $cfg['options']['siteurl']; ?>/userInfo/<?php echo $v->id;?>"><?php echo colorText($v->name,getUserColor($v->id));?></a></td>
						<!--<td><?php echo $v->first_name.' '.$v->middle_name.' '.$v->last_name;?></td>-->
						<td><?php echo $v->email;?></td>
						<!--
						<td><?php echo $v->country;?></td>
						<td><?php echo $v->state;?></td>
						-->
						<!--<td class="person_<?php echo $v->status;?>"><?php echo $v->status;?></td>-->
						<td><?php echo $v->registration_time;?></td>
						<td class="text-center">
							<?php
								if ($user['rankname']=='admin') {
							?>
								<a href="<?php echo $cfg['options']['siteurl'];?>/user/<?php echo $v->id;?>"><i class="fa fa-cogs"></i></a>
								&nbsp;|&nbsp;
								<a href="javascript:void(0);" onclick="deleteUser(<?php echo $v->id;?>);"><i class="fa  fa-lg fa-times text-danger"></i></a>
							<?php } else { ?>
								<a href="<?php echo $cfg['options']['siteurl'];?>/userInfo/<?php echo $v->id;?>">View <?php echo $v->name;?></a>
							<?php } ?>
						</td>
					</tr>

				<?php
			}
		} else {

		}

	?>
	</tbody>
</table>
</div>