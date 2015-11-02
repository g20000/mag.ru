<?php
	if ($user['rankname']=="admin") {
		
		$delete = addslashes(strip_tags(filter_input(INPUT_POST, 'delete', FILTER_VALIDATE_INT)));
		if (isset($delete) && !empty($delete)) {
			$q = "DELETE FROM `news` WHERE `id` = ".$delete.";";
			$db->query($q);
			exit("<script>document.location.href='".$cfg['options']['siteurl']."/news';</script>");
		}
		
		$title = addslashes(strip_tags(filter_input(INPUT_POST, 'title', FILTER_UNSAFE_RAW)));
		$text = addslashes(strip_tags(filter_input(INPUT_POST, 'text', FILTER_UNSAFE_RAW)));
		$time = date("Y-m-d H:i:s", time());
		
		if (!empty($title) && !empty($text)) {
			$q = "INSERT INTO `news` VALUES(NULL, '".$title."','".$text."','".$time."');";
			$db->query($q);
			exit("<script>document.location.href='".$cfg['options']['siteurl']."/news';</script>");
		}
		
?>

<form class="form-group clearfix" method="post">
		<label for="n_title">Заголовок</label>
		<input id="n_title" class="form-control options-input" name="title"> <br>
		<label for="n_text">Текст</label>
		<textarea id="n_text" class="form-control options-input" name="text"></textarea><br>
		<input class="pull-left btn btn-success btn-sm" value="Опубликовать" type="submit"><br>
	</form>

<?php
	}
	
	$q = "SELECT * FROM `news` ORDER BY `time` DESC";
	$news = $db->query($q);
	if (isset($news[0])) {
		echo '<hr>';
		foreach($news as $k=>$v) {
?>
			<div class="col-sm-9 col-sm-offset-3 col-md-3 main" style="margin-left: 0px !important; padding-left: 0px !important">
				<div class="panel panel-default">
					<div class="panel-heading"><strong class="panel-title"><?php echo $v->title; ?></strong><?php if($user['rankname']=='admin'){ echo '<form method="post" class="pull-right" id="delete_form_'.$v->id.'"><input type="hidden" name="delete" value="'.$v->id.'"><i onclick="$(\'#delete_form_'.$v->id.'\').submit();" class="cursor-pointer fa fa-times"></i></form>'; } ?></div>
					<div class="panel-body">
						<p><?php echo $v->text; ?></p>
					</div>
					<div class="panel-footer clearfix"><small class="pull-right"><?php echo $v->time; ?></small></div>
				</div>
			</div>
<?php
		}
	}
	
?>
