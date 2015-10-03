<?php
// смотрим можно ли 
if ($user['rankname']!='admin' && $user['rankname']!='support') {
	exit('You are not admin!');
}
?>
<script>
function deleteFastReply(id) {
	if (confirm('Sure to Reply?')===true) {
		$('table').find('tr').each(function(){
			
			if ($(this).data('replyid')===id) {
				$(this).remove();
				$.ajax({
					url: '<?php echo $cfg['options']['siteurl'];?>/gears/ajax.deleteFastReply.php',
					method: 'POST',
					dataType: 'JSON',
					data: {
						replyid: id
					},
					success: function(data) {
						if (data.type == 'error') {
							notify('error','Цепочка удалена',data.text);
						} else {
							notify('info','Цепочка удалена',data.text);
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

<h1 class="page-header">Быстрые ответы</h1>

<div class="clearfix">
<div class="pull-right" style="margin: 1em 0 1em 1em;"><a href="<?php echo $cfg['options']['siteurl']; ?>/addFastReply" class="btn btn-info">Добавить быстрый ответ</a></div>
</div>

<div class="table-responsive">
<table class="table table-striped">
	<thead>
		<tr>
			<th>Text</th>
			<th class='text-center'>Действия</th>
		</tr>
	</thead>
	<tbody>
	<?php

		$replyList = getReplysList();
		if ($replyList!==false) {
			foreach($replyList as $k=>$v) {
				?>
					<tr data-replyid="<?php echo $v->id;?>">
						<td><?php echo $v->text;?></td>
						<td class="text-center">
							<a href="<?php echo $cfg['options']['siteurl'];?>/fastReply/<?php echo $v->id;?>"><i class="fa fa-list"></i></a>
							&nbsp;|&nbsp;
							<a href="javascript:void(0);" onclick="deleteFastReply(<?php echo $v->id;?>);"><i class="fa  fa-lg fa-times text-danger"></i></a>
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