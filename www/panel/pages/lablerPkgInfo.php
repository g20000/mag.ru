<?php
// смотрим можно ли 
if ($user['rankname']!='labler') {
	exit('Access not permitted!');
}

// принимаем файл pdf, тут не нужна мультизагрузка
$fileMeta = isset($_FILES['my-pdf-file']) ? $_FILES['my-pdf-file'] : false;

$upload_type = filter_input(INPUT_POST, 'upload_type', FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^[A-Z]+$/i")));
$pkg_id = filter_input(INPUT_POST, 'pkgid', FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^\d+/")));

if ($fileMeta!==false && isset($pkg_id) && !empty($pkg_id)) {
	$filename = $user['id'].'_label_'.time().'.pdf';
	$q = "INSERT INTO `uploads` VALUES(NULL, ".$pkg_id.", ".$user['id'].", '".$filename."', 'labled', '".date("Y-m-d H:i:s", time())."');";
	$db->query($q);
	$q = "INSERT INTO `pkg_statuses` VALUES(NULL, ".$pkg_id.", '".date("Y-m-d H:i:s", time())."', 'labled');";
	$db->query($q);
	$q = "UPDATE `packages` SET `labler_id` = ".$user['id']." WHERE `id` = ".$pkg_id.";";
	$db->query($q);
	move_uploaded_file($fileMeta['tmp_name'], $cfg['realpath'].'/upload/'.$filename);
	echo "<script>document.location.href='".$cfg['options']['siteurl']."/".$route->value."/".$route->param."/".$route->action."';</script>";
	exit();
}



?>



<script>

	function deletePhoto(lablePhotoId) {
		$.ajax({
			url: '<?php echo $cfg['options']['siteurl'] ?>/gears/ajax.lablePhotoDelete.php',
			type: 'POST',
			dataType: 'JSON',
			data: {
				img_id: lablePhotoId,
				pkg_id: <?php echo $route->param; ?>,
			},
			success: function(data) {
				if (data.type=='ok') {
					document.location.reload();
				} else {
					notify('error','Ошибка',data.text);
				}
			},
			error: function(v1,v2,v3) {
				console.log(v1,v2,v3);
			}
		});
	}
	
	function saveNote(pkg_id, type) {
		$.ajax({
			url: '<?php echo $cfg['options']['siteurl'] ?>/gears/ajax.saveNote.php',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id: pkg_id,
				text: $('#'+type+'Note'+pkg_id).val(),
				type: type
			},
			success: function(data) {
				console.log(data);
				if (data.type=='ok') {
					notify('info','Заметка!',data.text);
				} else {
					notify('error','Заметка!',data.text);
				}
			},
			error: function(v1,v2,v3) {
				console.log(v1,v2,v3);
			}
		});
	}

</script>

<?php
	$pkg_info = getPackageInfo($route->param);

	if (count($pkg_info)>1) { echo '<h1 class="page-header">Группа товаров</h1>'; } else { echo '<h1 class="page-header">Информация о товаре</h1>'; }

	if (isset($pkg_info[0])) {
	foreach($pkg_info as $k=>$v) {
		if (count($pkg_info)>1) { echo '<h2>Товар '.intval($k+1).'</h2><hr>'; }
		$pkg_statuses = getPackageStatuses($v->id);
		//debug($pkg_statuses);
?>
	<div class="container-fluid pkg-item">
		
		<div class="row">
			<div class="col-md-6 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading"><strong class="panel-title">Информация</strong></div>
					<div class="panel-body">
						
						<div class="container-fluid">
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Магазин:</div>
								<div class="col-xs-8"><?php echo getShopLinkById($v->shop_id);?></div>
							</div>
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Сотрудник:</div>
								<div class="col-xs-8"><?php echo getFullUserNameById($v->drop_id); ?></div>
							</div>
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Адрес:</div>
								<div class="col-xs-8"><?php $toAddr = getUserInfoById($v->drop_id);  echo $toAddr->address.' '.$toAddr->city.', '.$toAddr->state.' '.$toAddr->country; ?></div>
							</div>							
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Покупатель:</div>
								<div class="col-xs-8"><?php echo getFullUserNameById($v->buyer_id); ?></div>
							</div>
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Адрес покупателя:</div>
								<div class="col-xs-8"><?php $toAddr = getUserInfoById($v->buyer_id); if($toAddr!==false) { echo $toAddr->address.' '.$toAddr->city.', '.$toAddr->state.' '.$toAddr->country.' '.$toAddr->zip; } ?></div>
							</div>
						</div>

					</div>
				</div>
			</div>
			
			<div class="col-md-6 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading"><strong class="panel-title">Товар</strong></div>
					<div class="panel-body">
						
						<div class="container-fluid">
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Отправитель:</div>
								<div class="col-xs-8"><?php echo getUserNameById($v->shipper_id);?> &nbsp;&nbsp;<a href="<?php echo $cfg['options']['siteurl']; ?>/newchat/<?php echo $v->shipper_id; ?>/new"><i class="fa fa-envelope"></i></a></div>
							</div>
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Товар:</div>
								<div class="col-xs-8"><?php echo $v->item;?></div>
							</div>
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Цена:</div>
								<div class="col-xs-8"><?php echo $v->price.' '.$v->currency;?></div>
							</div>
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Дата создания:</div>
								<div class="col-xs-8"><?php echo $pkg_statuses[count($pkg_statuses)-1]->time;?></div>
							</div>
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Описание:</div>
								<div class="col-xs-8"><?php echo $v->action;?></div>
							</div>
						</div>

					</div>
				</div>
			</div>
		
		</div>

		<div class="row">
			
			<div class="col-md-6 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading"><strong class="panel-title">Tracks</strong></div>
					<div class="panel-body">
						
						<div class="container-fluid">
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Статус:</div>
								<div class="col-xs-8"><?php echo iconPkgStatuses($pkg_statuses[0]->status_text).' '.readablePkgStatuses($pkg_statuses[0]->status_text);?></div>
							</div>
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Трек:</div>
								<div class="col-xs-8"><?php echo $v->track_num;?></div>
							</div>
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Тип:</div>
								<div class="col-xs-8"><?php echo $v->track_type;?></div>
							</div>
						</div>

					</div>
				</div>
			</div>
	
			<div class="col-md-6 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading"><strong class="panel-title">Заметки</strong></div>
					<div class="panel-body">
						
						<div class="container-fluid">
							<?php
								$pkg_notes = getPackageNotes($v->id);
								if (!empty($pkg_notes)) {
									foreach($pkg_notes as $n_user_type=>$n_text) {
							?>						
										<div class="row" style="margin-bottom: 1em;">
											<div class="col-xs-4"><?php echo readableUserStatuses($n_user_type); ?> заметка</div>
											<div class="col-xs-8">
												<?php
													if ($user['rankname']==$n_user_type) {
														echo '<textarea class="form-control" id="'.$user['rankname'].'Note'.$v->id.'">'.$n_text['public'].'</textarea>';
														echo '<button onClick="saveNote('.$v->id.',\''.$user['rankname'].'\');" class="btn btn-sm btn-success pull-right" style="margin: 10px 0 10px 0;">Созранить</button>';
													} else {
														echo isset($n_text['public']) ? $n_text['public'] : '';
													}
												?>
											</div>
										</div>
							<?php 
									}
								} else {
									echo '<textarea class="form-control" id="'.$user['rankname'].'Note'.$v->id.'"></textarea>';
									echo '<button onClick="saveNote('.$v->id.',\''.$user['rankname'].'\');" class="btn btn-sm btn-success pull-right" style="margin: 10px 0 10px 0;">Созранить</button>';
								}
								
							?>
						</div>

					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-heading"><strong class="panel-title">Действия</strong></div>
					<div class="panel-body">
						
						<div class="container-fluid">
							
							
							
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Загрузить лейбл:</div>
								<div class="col-xs-8">
									
									<form id="pdf-form" class="form-inline" method="post" action="<?php echo $cfg['options']['siteurl'] ?>/<?php echo $route->value; ?>/<?php echo $route->param; ?>/<?php echo $route->action; ?>" enctype="multipart/form-data">
										<input type="hidden" name="upload_type" value="<?php echo $route->action; ?>" />
										<input type="hidden" name="pkgid" value="<?php echo $v->id; ?>" />
										<div class="form-group">
											<label class="auto-tip btn btn-warning btn-sm" data-title="Загружать ЗВА сюда" data-position="bottom" data-placement="bottom">
												Upload PDF
												<input type="file" class="hidden" id="file-input-<?php echo $v->id; ?>" name="my-pdf-file" onchange="$('#pdf-form').submit();" />
											</label>
										</div>
									</form>

									<div class="row">
										<div class="upload-panels" style="padding:15px;">
											<?php echo getLabelPDF($v->id, $route->action); ?>
										</div>
									</div>
								</div>
							</div>
							
							
						</div>

					</div>
				</div>				
			
			</div>
			

		</div>


		

	</div>
<?php

	}
	
	} else {
		echo '<h3>Нет товаров!</h3>';
	}
	
	
?>