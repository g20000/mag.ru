<?php
// смотрим можно ли 
if ($user['rankname']!='admin' && $user['rankname']!='support') {
	exit('You are not admin!');
}
?>
<script>
function deleteShipper(id) {
	if (confirm('Sure to delete user with ID:'+id)===true) {
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

<h1 class="page-header">Отправители</h1>

<div class="pull-right" style="margin: 1em 0 1em 1em;"><a href="<?php echo $cfg['options']['siteurl']; ?>/addUser" class="btn btn-info">Создать нового</a></div>
<div style="clear: both"></div>

<div class="table-responsive">
<table class="table table-striped">
	<thead>
		<tr>
			<th>#</th>
			<th>Имя</th>
			<th>Jabber</th>			
			<th>Дата регистарции</th>
			<th class='text-center'>Действия</th>
		</tr>
	</thead>
	<tbody>
	<?php

		$usersList = getUsersListRank(2);
		if ($usersList!==false) {
			foreach($usersList as $k=>$v) {
				?>
					<tr data-user-id="<?php echo $v->id;?>">
						<td><?php echo $v->id;?></td>
						<td><?php echo $v->first_name.' '.$v->middle_name.' '.$v->last_name;?></td>
						<td><?php echo $v->xmpp;?></td>
						<td><?php echo $v->registration_time;?></td>
						<td class="text-center">
								<a href="<?php echo $cfg['options']['siteurl'];?>/shopDropSelector/<?php echo $v->id;?>"><i class="fa fa-list-ol"></i></a>
								&nbsp;|&nbsp;
								<a href="<?php echo $cfg['options']['siteurl'];?>/user/<?php echo $v->id;?>"><i class="fa fa-cogs"></i></a>
								&nbsp;|&nbsp;
								<a href="javascript:void(0);" onclick="deleteShipper(<?php echo $v->id;?>);"><i class="fa  fa-lg fa-times text-danger"></i></a>
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