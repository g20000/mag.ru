<?php
// смотрим можно ли 
if ($user['rankname']!='admin' && $user['rankname']!='support' && $user['rankname']!='shipper') {
	exit('Access not permitted!');
}
?>
<script>

	function rgb2hex(rgb) {
		if (rgb=='') {
			return '';
		}
		if (/^#[0-9A-F]{6}$/i.test(rgb)) return rgb;

		rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
		function hex(x) {
			return ("0" + parseInt(x).toString(16)).slice(-2);
		}
		if (rgb!=undefined) {
			return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
		} else {
			return '';
		}
	}


	function selectColor(color) {
		$('#selected-color').css({'background-color':color});
		$('#profile-color').val(rgb2hex(color));
	}

	function saveColor(colorVal) {
		colorVal = rgb2hex(colorVal);
		
		$.ajax({
			url: '<?php echo $cfg['options']['siteurl']; ?>/gears/ajax.savePkgColor.php',
			data: {
				id: <?php echo $route->param; ?>,
				color: colorVal,
			},
			dataType: 'JSON',
			method: 'POST',
			success: function(data){
				if (data.type=='ok') {
					notify('info',data.type,data.text);
				} else {
					notify('error',data.type,data.text);
				}
			},
			error: function(v1,v2,v3) {
				console.log(v1,v2,v3);
			}
		});
	}

	function validateEmail(email) { 
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	} 

	function saveNote(pkg_id, text, type) {
		$.ajax({
			url: '<?php echo $cfg['options']['siteurl'] ?>/gears/ajax.saveNote.php',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id: pkg_id,
				text: text,
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
	
	function saveTrack(itemId) {
		console.log(itemId);
		if ($('#trackType'+itemId).val() == -1) {
			notify('warning','Надо выбрать тип доставки!');
			return false;
		}
		if ($('#trackNum'+itemId).val() == '') {
			notify('warning','Надо ввести трек!');
			return false;
		}
		$.ajax({
			url: '<?php echo $cfg['options']['siteurl'] ?>/gears/ajax.saveTrack.php',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id: itemId,
				num: $('#trackNum'+itemId).val(),
				type: $('#trackType'+itemId).val()
			},
			success: function(data) {
				console.log(data);
				if (data.type=='ok') {
					console.log(data);
					//notify('info','Note!',data.text);
					document.location.href = '/dropslist';
				} else {
					notify('error','Замечание!',data.text);
				}
			},
			error: function(v1,v2,v3) {
				console.log(v1,v2,v3);
			}
		});
	}
	
	function changePackageStatus(itemId) {
		$.ajax({
			url: '<?php echo $cfg['options']['siteurl'] ?>/gears/ajax.changePackageStatus.php',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id: itemId,
				statusKind: $('#status_changer').val()
			},
			success: function(data) {
				if (data.type=='ok') {
					//notify('info','Note!',data.text);
					document.location.reload();
				} else {
					notify('error','Замечание!',data.text);
				}
			},
			error: function(v1,v2,v3,data) {
				console.log(data);
				console.log(v1,v2,v3);
			}
		});
	}
	
	function deletePkg(pkg_id) {
		$.ajax({
			url: '<?php echo $cfg['options']['siteurl'] ?>/gears/ajax.deletePkg.php',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id: pkg_id
			},
			success: function(data) {
				if (data.type=='ok') {
					notify('info','Note!',data.text);
					document.location.href="<?php echo $cfg['options']['siteurl']; ?>/packages";
				} else {
					notify('error','Замечание!',data.text);
				}
			},
			error: function(v1,v2,v3) {
				console.log(v1,v2,v3);
			}
		});	
	}
	
	function changeEuro(pkg_id){
		$.ajax({
			url: '<?php echo $cfg['options']['siteurl'] ?>/gears/ajax.changeEuro.php',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id: pkg_id,
				euro: $('#euro').val()
			},
			success: function(data) {
				if (data.type=='ok') {
					//notify('info','Note!',data.text);
					document.location.reload();
				} else {
					notify('error','Замечание!',data.text);
				}
			},
			error: function(v1,v2,v3,data) {
				console.log(data);
				console.log(v1,v2,v3);
			}
		});
	}
	
	$(document).ready(function() {
		if ($('#sureDeleteTask').is('checked')) { $('#deleteTaskBtn').toggleClass('disabled'); }
		$('#sureDeleteTask').bind('click', function(){ $('#deleteTaskBtn').toggleClass('disabled'); }); 
		$('#deleteTaskBtn').bind('click', function(){ if (confirm('Точно удалить трек\nи всю его информацию?')) {
			deletePkg($(this).data('pkg-id'));
		} }); 
	});

</script>

<?php

	$pkg_info = getPackageInfo($route->param);

	if (count($pkg_info)>1) { 
		echo '<h1 class="page-header">Информация о группе товаров</h1>'; 
	} elseif ($pkg_info!=false) {
		$pkg_statuses = getPackageStatuses($route->param);
		echo '<div class="clearfix" style="margin: 0 0 2em 0;">';
		echo '<h1 class="page-header">Информация</h1>';
		if(isset($pkg_statuses[0]->status_text)){
			if ($pkg_statuses[0]->status_text=='new' || $pkg_statuses[0]->status_text=='approve') {
				echo '<div class="pull-right"><span class="btn btn-danger btn-xs" onclick="deletePkg('.$route->param.')"><span class="fa fa-times text-white"></span></span></div>'; 
			}
		}		
	} else {
		echo '<div class="clearfix" style="margin: 0 0 2em 0;">';
		echo '<h1 class="page-header">Нет страницы</h1>'; 		
	}

	


	if ($pkg_info!=false) foreach($pkg_info as $k=>$v) {
		$pkg_statuses = getPackageStatuses($v->id);
		//debug($pkg_statuses);
		
		if (count($pkg_info)>1) {
			echo '<div class="clearfix" style="margin: 0 0 2em 0;">';
			echo '<h2 style="display:inline;">Товар '.intval($k+1).'</h2>'; 
			if ($pkg_statuses[0]->status_text=='new') {
				echo '<div class="pull-right"><span class="btn btn-danger btn-xs" onclick="deletePkg('.$v->id.')"><span class="fa fa-times text-white"></span></span></div>'; 
			}
		}
		echo '</div>';
		
		$pkg_notes = getPackageNotes($v->id);

?>
	<div class="container-fluid">
		
		<div class="row">
			<div class="col-md-6 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading"><strong class="panel-title">Информация</strong></div>
					<div class="panel-body">
						
						<div class="container-fluid">
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Магазин:</div>
								<div class="col-xs-8"><?php echo getShopNameById($v->shop_id);?></div>
							</div>
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Сотрудник:</div>
								<div class="col-xs-8">
									<?php echo getLinkToUserProfile($v->drop_id); ?>
									<?php 
										if ($user['rankname']=='admin' || $user['rankname']=='labler' || $user['rankname']=='support' || $user['rankname']=='shipper') {
											$dropAddr = getUserProfile($v->drop_id);
											if ($dropAddr!==false) {
												echo ' | <small>'.$dropAddr->address.', '.$dropAddr->state.' '.$dropAddr->country.' '.$dropAddr->city.' '.$dropAddr->zip.'</small>';
											}
										}
									?>

								</div>
							</div>
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Отправитель:</div>
								<div class="col-xs-8"><?php echo getLinkToUserProfile($v->shipper_id); ?></div>
							</div>
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Сортировщик:</div>
								<div class="col-xs-8"><?php echo getLinkToUserProfile($v->labler_id); ?></div>
							</div>
						</div>

					</div>
				</div>
				<?php 
					if ($user['rankname']=='admin') { 
						$pkgColor=getPkgColor($v->id);
				?>
				<div class="panel panel-default">
					<div class="panel-heading"><strong class="panel-title">Цвет</strong></div>
					<div class="panel-body">
						<input style="position:absolute;left:-1000px;" type="color" name="color" id="profile-color" value="<?php echo !empty($userProfile->color) ? $userProfile->color : '#ffffff';?>" onchange="$('#selected-color').css({'background-color':$(this).val()});">
										
						<label for="profile-color" class="auto-tip" data-title="Selected color" data-position="bottom" data-placement="bottom"><div style="display:inline-block;background-color: <?php echo $pkgColor;?>;width:20px;height:18px;border:1px solid black;" id="selected-color"></div></label>
						<div style="display:inline-block;background-color: red;width:20px;height:18px;" onclick="selectColor($(this).css('background-color'));"></div>
						<div style="display:inline-block;background-color: blue;width:20px;height:18px;" onclick="selectColor($(this).css('background-color'));"></div>
						<div style="display:inline-block;background-color: green;width:20px;height:18px;" onclick="selectColor($(this).css('background-color'));"></div>
						<div style="display:inline-block;background-color: purple;width:20px;height:18px;" onclick="selectColor($(this).css('background-color'));"></div>
						<div style="display:inline-block;background-color: yellow;width:20px;height:18px;" onclick="selectColor($(this).css('background-color'));"></div>
						<div style="display:inline-block;background-color: magenta;width:20px;height:18px;" onclick="selectColor($(this).css('background-color'));"></div>
						<div style="display:inline-block;background-color: white;width:20px;height:18px;" onclick="selectColor($(this).css('background-color'));"></div>
						<span class="fa fa-lg fa-times" style="vertical-align:top;padding-top:2px;" onclick="selectColor('');"></span>
						<br><span class="btn btn-success btn-sm pull-right" onclick="saveColor($('#selected-color').css('background-color'));">Сохранить</span>
					</div>
				</div>
				<?php } ?>
			</div>
			
			<div class="col-md-6 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading"><strong class="panel-title">Информация</strong></div>
					<div class="panel-body">
						
						<div class="container-fluid">
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Товар:</div>
								<div class="col-xs-8"><?php echo $v->item;?></div>
							</div>
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Euro:</div>
								<div class="col-xs-8"><?php echo $v->euro;?>
								</div>
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
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Статус:</div>
								<div class="col-xs-8"><?php echo readablePkgStatuses($pkg_statuses[0]->status_text);?></div>
							</div>
							<div class="row" style="margin-bottom: 1em;">
							<?php if($user['rankname']=='admin') { ?>
								<div class="col-xs-4">Изменить euro:</div>
								<div class="col-xs-8">
									<input type="text" class="form-control" id="euro">
									<button onClick="changeEuro('<?php echo $v->id; ?>');" class="btn btn-sm btn-info pull-right" style="margin: 10px 0 0 0;">Изменить euro</button>
								</div>
							<?php } ?>
						</div>
							<?php if (($user['rankname']=='admin' || $user['rankname']=='support') && $pkg_statuses[0]->status_text=='approve') { ?>
								<script>
									function approvePack(pkg_id){
										$.ajax({
											url: '<?php echo $cfg['options']['siteurl'] ?>/gears/ajax.approvePkg.php',
											type: 'POST',
											dataType: 'JSON',
											data: {
												id: pkg_id,
												status: 'new'
											},
											success: function(data) {
												console.log(data);
												if (data.type=='ok') {
													//notify('info','Note!',data.text);
													document.location.href="<?php echo $cfg['options']['siteurl']; ?>/package/<?php echo $v->id; ?>";
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
								<div class="row" style="margin-bottom: 1em;">
									<div class="col-xs-4">Одобрить:</div>
									<div class="col-xs-8 text-right"><span class="btn btn-sm btn-success" onclick="approvePack(<?php echo $v->id; ?>);">одобрить</span></div>
								</div>
							<?php } ?>
						</div>

					</div>
				</div>
				<?php if ($user['rankname']=='admin') { ?>
				<div class="panel panel-default">
					<div class="panel-heading"><strong class="panel-title">Приватная пометка</strong></div>
					<div class="panel-body clearfix">
						<textarea class="form-control" id="private_note"><?php echo isset($pkg_notes['admin']['private']) ? $pkg_notes['admin']['private'] : ''; ?></textarea><br>
						<span class="pull-right btn btn-success btn-sm" onclick="saveNote(<?php echo $v->id;?>, $('#private_note').val(), 'private')">Сохранить</span>
					</div>
				</div>
				<?php } ?>
			</div>
		
		</div>

		<div class="row">
			
			<div class="col-md-6 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading"><strong class="panel-title">Треки</strong> <small class="pull-right">Новые сверху</small></div>
					<div class="panel-body">
						<div class="container-fluid">
							<?php 
							$tracks = getPkgTracks($v->id); 
							if (isset($tracks[0])) {
								foreach($tracks as $track_list) { 
							?>
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4"><?php echo $track_list->track_type;?></div>
								<div class="col-xs-8"><?php echo getTrackCheckLink($track_list->track_type,$track_list->track_num);?></div>
							</div>
							<?php }} ?>
						</div>
					</div>							
				</div>	


				<div class="panel panel-default">
					<div class="panel-heading"><strong class="panel-title">Добавить</strong></div>
					<div class="panel-body">
						<div class="row" style="margin-bottom: 1em;">
							<div class="col-xs-4">Тип:</div>
							<div class="col-xs-8">
								<select class="form-control" id="trackType<?php echo $v->id; ?>">
									<option value="-1">--- Выберите ---</option>
									<option value="dhl">DHL</option>
									<option value="dhlexpress">DHL EXPRESS</option>
									<option value="dpd">DPD</option>
									<option value="hermes">HERMES</option>
									<option value="omest">OMEST</option>
									<option value="usps">USPS</option>
									<option value="fedex">FedEX</option>									
								</select>
							</div>
						</div>
						<div class="row" style="margin-bottom: 1em;">
							<div class="col-xs-4">Track number:</div>
							<div class="col-xs-8">
								<input type="text" class="form-control" id="trackNum<?php echo $v->id; ?>">
								<button onClick="saveTrack(<?php echo $v->id; ?>, '<?php echo $user['rankname'];?>');" class="btn btn-sm btn-info pull-right" style="margin: 10px 0 0 0;">Подтвердить</button>
							</div>
						</div>
					</div>
				</div>
				
				<?php 
				// тут все фотки и лейблы
				if ($user['rankname']=='admin') {
				?>
					<div class="panel panel-default">
						<div class="panel-heading"><strong class="panel-title">Загрузки</strong></div>
						<div class="panel-body">
							
							<?php 
								$qUploads = $db->query("SELECT * FROM `uploads` WHERE `pkg_id` = ".$v->id." ORDER BY `time` DESC");
								if (isset($qUploads[0])) {
									foreach($qUploads as $vUploads) {
							?>
										<div class="row" style="margin-bottom: 1em;">
											<div class="col-xs-4"><?php echo getUserNameById($vUploads->user_id); ?></div>
											<div class="col-xs-8">
												<?php
												if (preg_match("/\.pdf$/is", $vUploads->filename)) {
													echo "<a href='".$cfg['options']['siteurl'].'/upload/'.$vUploads->filename."'>PDF файл</a><br>";
												} else {
													echo "<img src='".$cfg['options']['siteurl'].'/upload/'.$vUploads->filename."' height=120>"."<br>";
												}
												echo $vUploads->time
												?>
											</div>
										</div>
							<hr>
										
										

							<?php			
									}
								}
							?>
							
						

						</div>
					</div>
				<?php 
				}
				?>
				
			</div>
	
			<div class="col-md-6 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading"><strong class="panel-title">Заметки</strong></div>
					<div class="panel-body">
						
						<div class="container-fluid">
							<?php
								if (!empty($pkg_notes)) {
									foreach($pkg_notes as $n_user_type=>$note_item) {
							?>						
										<div class="row" style="margin-bottom: 1em;">
											<div class="col-xs-4"><?php echo $n_user_type; ?> заметка</div>
											<div class="col-xs-8">
												<?php
													if ($user['rankname']==$n_user_type) {
														echo '<textarea class="form-control" id="'.$user['rankname'].'Note'.$v->id.'">'.(isset($note_item['public']) ? $note_item['public'] : '').'</textarea>';
														echo '<button onClick="saveNote('.$v->id.',$(\'#'.$user['rankname'].'Note'.$v->id.'\').val(),\'public\');" class="btn btn-sm btn-success pull-right" style="margin: 10px 0 10px 0;">Сохранить</button>';
													} else {
														echo isset($note_item['public']) ? $note_item['public'] : '';
													}
												?>
											</div>
										</div>
							<?php 
									}
								} else {
									echo '<textarea class="form-control" id="'.$user['rankname'].'Note'.$v->id.'"></textarea>';
									echo '<button onClick="saveNote('.$v->id.',$(\'#'.$user['rankname'].'Note'.$v->id.'\').val(),\'public\');" class="btn btn-sm btn-success pull-right" style="margin: 10px 0 10px 0;">Сохранить</button>';
								}
								
							?>
						</div>

					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading"><strong class="panel-title">Статусы</strong></div>
					<div class="panel-body">
						
						<?php foreach($pkg_statuses as $sv) { ?>
						
						<div class="container-fluid">
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-6"><?php echo $sv->time;?></div>
								<div class="col-xs-6"><?php echo readablePkgStatuses($sv->status_text);?></div>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
				
				<?php 
					if ($user['rankname']=='admin') {
				?>
						<div class="panel panel-default">
							<div class="panel-heading"><strong class="panel-title">Удалить</strong></div>
							<div class="panel-body">
								<div class="container-fluid">
									<div class="row">
										<div class="col-xs-6"><span class="btn btn-danger disabled" id="deleteTaskBtn" data-pkg-id="<?php echo $v->id; ?>">Удалить</span></div>
										<div class="col-xs-6"><label style="margin-top: 0.5em;"><input type="checkbox" id="sureDeleteTask" value="delete_task"> Точно?</label></div>
									</div>
								</div>
							</div>
						</div>						
				<?php
					}
				
				?>
			</div>
		</div>
	</div>
<?php
	}	
?>