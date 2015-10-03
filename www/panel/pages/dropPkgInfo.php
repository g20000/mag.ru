<?php
// смотрим можно ли 
if ($user['rankname']!='drop') {
	exit('Access not permitted!');
}

?>



<script>


	function deletePhoto(id) {
		console.log('DELETE IMAGE '+id);
		$.ajax({
			url: '<?php echo $cfg['options']['siteurl'] ?>/gears/ajax.photoDelete.php',
			data: { img_id: id, },
			dataType: 'JSON',
			type: 'POST',
			success: function(data) { if (data.type!='ok') { console.log(data); } else { document.location.reload(); } },
			error: function(v1,v2,v3) { console.log(v1,v2,v3); },
		});		
	}

	function acceptNewPkg(id) {
		$.ajax({
			url: '<?php echo $cfg['options']['siteurl'] ?>/gears/ajax.dropAcceptPkg.php',
			data: { pkg_id: id },
			dataType: 'JSON',
			method: 'POST',
			success: function(data) {
				console.log(data.text);
				if (data.type=='ok') {
					notify('info',data.type,data.text);
					$('.myModal').modal('toggle');
				} else {
					notify('error',data.type,data.text);
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
					notify('info','Замечание!',data.text);
				} else {
					notify('error','Замечание!',data.text);
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
		if (count($pkg_info)>1) { echo '<h2>Item '.intval($k+1).'</h2><hr>'; }
		$pkg_statuses = getPackageStatuses($v->id);
		//debug($pkg_statuses);
?>
	<div class="container-fluid pkg-item">
		
		<div class="row">
			<div class="col-md-6 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading"><strong class="panel-title">Информация о товаре</strong></div>
					<div class="panel-body">
						
						<div class="container-fluid">
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Магазин:</div>
								<div class="col-xs-8"><?php echo getShopLinkById($v->shop_id);?></div>
							</div>
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Ваше имя:</div>
								<div class="col-xs-8"><?php echo getFullUserNameById($v->drop_id); ?></div>
							</div>
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Ваш адрес:</div>
								<div class="col-xs-8"><?php $toAddr = getUserInfoById($v->drop_id);  echo $toAddr->address.' '.$toAddr->city.', '.$toAddr->state.' '.$toAddr->country; ?></div>
							</div>							
<?php
/*
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Send to person:</div>
								<div class="col-xs-8"><?php echo getFullUserNameById($v->buyer_id); ?></div>
							</div>
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Send to address:</div>
								<div class="col-xs-8"><?php $toAddr = getUserInfoById($v->buyer_id); echo $toAddr->address.' '.$toAddr->city.', '.$toAddr->state.' '.$toAddr->country.' '.$toAddr->zip; ?></div>
							</div>
 */
?>					
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
					<div class="panel-heading"><strong class="panel-title">Треки</strong></div>
					<div class="panel-body">
						
						<div class="container-fluid">
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Статус:</div>
								<div class="col-xs-8"><?php echo iconPkgStatuses($pkg_statuses[0]->status_text).' '.readablePkgStatuses($pkg_statuses[0]->status_text);?></div>
							</div>
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Тип:</div>
								<div class="col-xs-8"><?php echo $v->track_type;?></div>
							</div>
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Номер:</div>
								<div class="col-xs-8"><?php echo getTrackCheckLink($v->track_type,$v->track_num);?></div>
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
											<div class="col-xs-4"><?php echo readableUserStatuses($n_user_type); ?> note</div>
											<div class="col-xs-8">
												<?php
													if ($user['rankname']==$n_user_type) {
														echo '<textarea class="form-control" id="'.$user['rankname'].'Note'.$v->id.'">'.(isset($n_text['public'])?$n_text['public']:'').'</textarea>';
														echo '<button onClick="saveNote('.$v->id.',\''.$user['rankname'].'\');" class="btn btn-sm btn-success pull-right" style="margin: 10px 0 10px 0;">Сохранить</button>';
													} else {
														echo $n_text['public'];
													}
												?>
											</div>
										</div>
							<?php 
									}
								} else {
									echo '<textarea class="form-control" id="'.$user['rankname'].'Note'.$v->id.'"></textarea>';
									echo '<button onClick="saveNote('.$v->id.',\''.$user['rankname'].'\');" class="btn btn-sm btn-success pull-right" style="margin: 10px 0 10px 0;">Сохранить</button>';
								}
								
							?>
						</div>

					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-heading"><strong class="panel-title">Действия</strong></div>
					<div class="panel-body">
						
						<div class="container-fluid">
							
							<?php if ($pkg_statuses[0]->status_text=='new') { ?>
							
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Accept task:</div>
								<div class="col-xs-8">
									<span class="btn btn-success btn-sm" onclick="acceptNewPkg(<?php echo $v->id;?>)">Принять</span>
								</div>
							</div>
							
							<?php } elseif($pkg_statuses[0]->status_text=='todrop' || $pkg_statuses[0]->status_text=='labled') { ?>

								<?php 
								if ($pkg_statuses[0]->status_text=='labled') { ?>
									<div class="row" style="margin-bottom: 1em;">
										<div class="col-xs-4">Лейбл:</div>
										<div class="col-xs-8"><?php echo getLabelPDFForDrop($v->id); ?></div>
									</div>
								<?php } ?>
							
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Загрузить фото:</div>
								<div class="col-xs-8">
									<form class="form-inline upload-form drop-box" onsubmit="return false;" role="form" id="upload-form-<?php echo $v->id; ?>" data-form-id="<?php echo $v->id; ?>" method="post" action="<?php echo $cfg['options']['siteurl'] ?>/gears/dropUpload.fallback.php" data-file-api-url="<?php echo $cfg['options']['siteurl'] ?>/gears/dropUpload.php" enctype="multipart/form-data">
										<input type="hidden" name="upload_type" value="<?php echo $route->action; ?>" />
										<div class="form-group">
											<label class="auto-tip btn btn-warning btn-sm" data-title="Загрузить сюда" data-position="bottom" data-placement="bottom">
												Upload photos
												<input type="file" class="hidden file-input" data-pkgid="<?php echo $v->id; ?>" id="file-input-<?php echo $v->id; ?>" name="my-file" />
											</label>
										</div>
										<div class="form-group">
											<button type="submit" class="send-btn btn btn-primary btn-sm " data-pkgid="<?php echo $v->id; ?>">Отправить</button>
										</div>
									</form>

									<div class="row">
										<div class="upload-panels" style="padding:15px;">
											<?php echo getItemPhotos($v->id, $route->action); ?>
										</div>
									</div>
								</div>
							</div>
							
							<?php } elseif($pkg_statuses[0]->status_text=='accepted') { 
									echo 'Ждем подтверждения';
								}  elseif($pkg_statuses[0]->status_text=='ondrop') {
									echo 'Ждем сортировки';
								}
							?>
							
						</div>

					</div>
				</div>				
			
			</div>
			

		</div>


		

	</div>
<?php

	}
	
	} else {
		echo '<h3>Нет товара!</h3>';
	}
	
	
?>