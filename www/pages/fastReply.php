<?php 
	if ($user['rankname'] != 'admin' && $user['rankname'] != 'support') {
		exit('Access denied!');
	}
?>
<script>
	




function saveReply() {
	var repl = $('#replyText').val();
	$.ajax({
		url: '<?php echo $cfg['options']['siteurl'];?>/gears/ajax.saveFastReply.php',
		method: 'POST',
		async: true,
		dataType: 'JSON',
		data: {
			text: repl,
			replyid: <?php echo $route->param; ?>
		},
		success: function(data) {
			if (data.type === 'error') {
				notify('error','Ответ сохранен',data.text);
			} else {
				notify('info','Ответ сохранен','ok');
			}
		},
		error: function(v1,v2,v3) {
			console.log(v1,v2,v3);
		}
	});
}

</script>

<h1 class="page-header">Просмотр быстрого ответа</h1>


<?php
	$mail = getFastReply($route->param);
	//debug($mail);
	if ($mail===false) {
		echo '<div class="well well-lg text-center">No entries</div>';
	} else {
	//debug($mail);
?>


<div class="panel panel-default">
	<!-- Default panel contents -->
	<div class="panel-heading">Текст ответа</div>

	<textarea class="form-control" rows="10" id="replyText"><?php echo $mail[0]->text; ?></textarea><br>
	<span class='btn btn-success' onclick="saveReply();">Сохранить</span>

</div>

<?php 

} 

?>