<?php
$chats = getAllChats();
//debug($chats);
//debug($user);
?>
<div class="container-fluid">
	<?php
		if (isset($chats[0])) {
		foreach($chats as $v) {
			if ($v==false) continue;
	?>

	<a href="<?php echo $cfg['options']['siteurl']; ?>/chatroom/<?php echo $v[0]->hash; ?>">
		<div class="row <?php if ($v[0]->is_read==0 && $v[0]->to_id==$user['id']) { echo 'bg-unread'; } ?>" style="padding:1em;">

			<div class="col-md-3 col-xs-12">
				Чат с <?php if ($v[0]->from_id==$user['id']) { $chatwith = $v[0]->to_id; } else { $chatwith = $v[0]->from_id ; } ?>
				<?php echo getUserIconById($chatwith);echo " ";echo getUserNameById($chatwith);  ?>
			</div>
			<div class="col-md-7 col-xs-12"><?php echo preg_replace("/[\n|\r|\r\n|\n\r]+/","<br><br>",$v[0]->text);?></div>
			<div class="col-md-2 col-xs-12 text-right"><small class="text-muted"><?php echo $v[0]->time; ?></small></div>

		</div>
	</a>
	<hr style="margin: 0;">
	<?php
		} // end of rows
		} else {
			echo "<h3>Нет новых сообщений</h3>";
		}
	?>
	<h3>Вы можете создать чат с новым собеседником:</h3>
	<?php $users = getUsersNotInChat(); ?>
	<?php
		foreach($users as $v){
	?>
		<a href="#">
			<div class="row" style="padding:1em;">
				<div class="col-md-3 col-xs-12">
					<?php echo $v->name ?>
					<!--<?php echo getUserIconById($chatwith);echo " ";echo getUserNameById($chatwith);  ?>-->
				</div>
			</div>
		</a>
	<?php
		}
	?>
</div>