<?php
// смотрим можно ли 
if ($user['rankname']!='admin' && $user['rankname']!='support') {
	exit('You are not admin!');
}
?>
<script>
	
var $idown;  // Keep it outside of the function, so it's initialized once.
function downloadURL(url) {
  if ($idown && $idown.length > 0 ) {
    $idown.attr('src',url);
  } else {
    $idown = $('<iframe>', { id:'idown', src:url }).hide().appendTo('body');
  }
}	
	
function getMail(id) {
	if (confirm('Sure to delete Mail with ID:'+id)===true) {
		$('table').find('tr').each(function(){
			if ($(this).data('mail-id')===id) {
				$.ajax({
					url: '<?php echo $cfg['options']['siteurl'];?>/gears/ajax.getMail.php',
					method: 'POST',
					dataType: 'JSON',
					data: {
						mailid: id
					},
					success: function(data) {
						if (data.type == 'error') {
							notify('error','Download error',data.text);
						} else {

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

function deleteMail(id) {
	if (confirm('Sure to delete Mail with ID:'+id)===true) {
		$('table').find('tr').each(function(){
			if ($(this).data('mail-id')==id) {
				$(this).remove();
				$.ajax({
					url: '<?php echo $cfg['options']['siteurl'];?>/gears/ajax.deleteMail.php',
					method: 'POST',
					dataType: 'JSON',
					data: {
						mail_id: id
					},
					success: function(data) {
						if (data.type == 'error') {
							notify('error','Mail delete',data.text);
						} else {
							notify('info','Mail delete',data.text);
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

<h1 class="page-header">Выполненные</h1>

<div class="clearfix">
<div class="pull-right" style="margin: 1em 0 1em 1em;"><a href="<?php echo $cfg['options']['siteurl']; ?>/addMails" class="btn btn-info">Upload new mail</a></div>
</div>

<?php
	$list = getMailsList('ready');
	if (!$list) {
		echo '<div class="well well-lg text-center">No entries</div>';
	} else {
?>

<div class="table-responsive">
<table class="table table-striped">
	<thead>
		<tr>
			<th>#</th>
			<th>From</th>
			<th>To</th>
			<th>Subject</th>
			<th>Reg date</th>
			<th>Translator</th>
			<th>Thread mails</th>
			<th class='text-center'>Actions</th>
		</tr>
	</thead>
	<tbody>
	<?php

			foreach($list as $k=>$v) {
				?>
					<tr data-mail-id="<?php echo $v->id;?>" class="<?php echo getStatusColor($v->status);?>">
						<td><?php echo $v->id;?></td>
						<td><?php 
							if (!empty($v->fromName)) {
								echo $v->fromName.'<br>'.htmlspecialchars(' <').$v->fromAddr.  htmlspecialchars('>');
							} else {
								echo $v->fromAddr;
							}
						?></td>
						<td><?php 
							if (!empty($v->toName)) {
								echo $v->toName.'<br>'.htmlspecialchars(' <').$v->toAddr.  htmlspecialchars('>');
							} else {
								echo $v->toAddr;
							}
						?></td>
						<td><?php echo $v->subject;?></td>
						<td><?php echo $v->sendtime;?></td>
						<td><?php $trName = getUserInfoById($v->translator); echo $trName->name; ?></td>
						<td><?php echo mb_substr(strip_tags(imapUtf8($v->text)), 0,40); ?></td>
						<td class="text-center">
							<a href="<?php echo $cfg['options']['siteurl'];?>/mail/<?php echo $v->id;?>"><i class="fa fa-list"></i></a>
							&nbsp;|&nbsp;
							<a href="javascript:void(0);" onclick="downloadURL('<?php echo $cfg['options']['siteurl'].'/gears/ajax.getMail.php?mailid='.$v->id;?>');"><i class="fa  fa-lg fa-download text-success"></i></a>
							&nbsp;|&nbsp;
							<a href="javascript:void(0);" onclick="deleteMail(<?php echo $v->id;?>);"><i class="fa fa-lg fa-times text-danger"></i></a>
							
							
						</td>
					</tr>

				<?php
			}
		

	?>
	</tbody>
</table>
</div>

<?php } ?>
