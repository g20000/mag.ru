<?php

// проверяем если новый чат
if (isset($route->action) && !empty($route->action)) {
	// если новый то смотрим нет ли уже такого или обратного
	if ($route->action=='new') {
		$streightHash = md5('uid'.$user['id'].'uid'.$route->param);
		$reverseHash = md5('uid'.$route->param.'uid'.$user['id']);
		$checkChat = $db->query("SELECT * FROM `chat_hashes` WHERE `hash` = '".$streightHash."' OR `hash` = '".$reverseHash."';");
		// нашли уже готовый чат
		if (isset($checkChat[0])) {
			$route->param = $checkChat[0]->hash;
			$route->action = '';
		} else {
			$db->query("INSERT INTO `chat_hashes` VALUES(NULL, '".$streightHash."', ".$user['id'].", ".$route->param." );");
			$route->param = $streightHash;
		}
		
	}
}

$chat = getChatDialog($route->param);
//debug($chat);
if (empty($chat)) $chat = array();
?>

<script>
	function sendMsg() {
		$.ajax({
			url: '<?php echo $cfg['options']['siteurl'];?>/gears/ajax.sendMsg.php',
			method: 'POST',
			dataType: 'JSON',
			data: { text: $('#textMsg').val(), hash: '<?php echo $route->param;?>'  },
			success: function(data) {
				$('#textMsg').val('');
				if (data.type=='ok') {
					document.location.reload();
				} else {
					notify('warning','Ошибка!',data.text);
				}
			},
			error: function(v1,v2,v3) {
				console.log(v1,v2,v3);
			}
		});
	}
</script>

<div class="container-fluid">

	<div class="row">
		<form class="form-group clearfix" onsubmit="return false;">
			<textarea id="textMsg" class="form-control options-input" style="margin: 0 0 10px 0;"></textarea>
			<button class="btn btn-sm btn-success pull-left" onclick="sendMsg();">Отправить</button>
		</form>
	</div>
	
	<?php
		foreach($chat as $v) {
	?>
		<div class="row <?php if ($v->is_read==0) { echo 'bg-unread'; } ?>" style="padding:1em;">

			<div class="col-md-2 col-xs-12">
				<?php echo getUserIconById($v->from_id); echo " "; echo getUserNameById($v->from_id); ?>
			</div>
			<div class="col-md-7 col-xs-12"><?php echo preg_replace("/[\n|\r|\r\n|\n\r]+/","<br><Br>",$v->text);?></div>
			<div class="col-md-2 col-xs-12 text-right"><small class="text-muted"><?php echo $v->time; ?></small></div>
			
		</div>
	<hr style="margin:0;">
	<?php
		} // end of rows
	?>
	
</div>

<?php

	setDialogReaded($route->param);

?>