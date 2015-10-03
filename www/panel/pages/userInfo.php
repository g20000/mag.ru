<?php
// смотрим можно ли 
if ($user['rankname']!='admin' && $user['rankname']!='support') {
	exit('You are not admin!');
}
?>
<script>

	function validateEmail(email) { 
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	} 

	function rgb2hex(rgb) {
		if (/^#[0-9A-F]{6}$/i.test(rgb)) return rgb;

		rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
		function hex(x) {
			return ("0" + parseInt(x).toString(16)).slice(-2);
		}
		return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
	}


	function selectColor(color) {
		$('#selected-color').css({'background-color':color});
		$('#profile-color').val(rgb2hex(color));
		console.log(color);
		console.log($('#profile-color').val());
	}

	function saveColor(colorVal) {
		$.ajax({
			url: '<?php echo $cfg['options']['siteurl']; ?>/gears/ajax.saveUserColor.php',
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

	function saveNote(noteText) {
		$.ajax({
			url: '<?php echo $cfg['options']['siteurl']; ?>/gears/ajax.saveUserNote.php',
			data: {
				id: <?php echo $route->param; ?>,
				text: noteText,
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

</script>

<?php
$userProfile = getUserInfoById($route->param);

?>

<h1 class="page-header">Информация</h1>

	<div class="container-fluid">
		
		<div class="row">
			<div class="col-md-6 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading"><strong class="panel-title">Основная</strong></div>
					<div class="panel-body">
						
						<div class="container-fluid">
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Логин:</div>
								<div class="col-xs-8"><?php echo colorText($userProfile->name, $userProfile->color);?></div>
							</div>
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Email:</div>
								<div class="col-xs-8"><?php echo $userProfile->email;?></div>
							</div>
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">XMPP:</div>
								<div class="col-xs-8"><?php echo $userProfile->xmpp;?></div>
							</div>
						</div>

					</div>
				</div>
			</div>
			
			<div class="col-md-6 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading"><strong class="panel-title">ФИО</strong></div>
					<div class="panel-body">
						
						<div class="container-fluid">
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Имя:</div>
								<div class="col-xs-8"><?php echo $userProfile->first_name;?></div>
							</div>
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Отчество:</div>
								<div class="col-xs-8"><?php echo $userProfile->middle_name;?></div>
							</div>
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Фамилия:</div>
								<div class="col-xs-8"><?php echo $userProfile->last_name;?></div>
							</div>
						</div>

					</div>
				</div>
			</div>
		
		</div>

		<div class="row">
			
			<div class="col-md-6 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading"><strong class="panel-title">Адрес</strong></div>
					<div class="panel-body">
						
						<div class="container-fluid">
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Старна:</div>
								<div class="col-xs-8"><?php echo $userProfile->country;?></div>
							</div>
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Штат:</div>
								<div class="col-xs-8"><?php echo $userProfile->state;?></div>
							</div>
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Город:</div>
								<div class="col-xs-8"><?php echo $userProfile->city;?></div>
							</div>
						</div>

					</div>
				</div>
				
				<div class="panel panel-default">
					<div class="panel-heading"><strong class="panel-title">Заметки</strong></div>
					<div class="panel-body">
						
						<div class="container-fluid">
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-2">Заметки:</div>
								<div class="col-xs-10"><textarea class="form-control" id="profile-note"><?php echo $userProfile->about; ?></textarea><br><span class="btn btn-success btn-sm pull-right" onclick="saveNote($('#profile-note').val());">Сохранить</span></div>
							</div>
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-2">Color:</div>
								<div class="col-xs-10">
									
										<input style="position:absolute;left:-1000px;" type="color" name="color" id="profile-color" value="<?php echo !empty($userProfile->color) ? $userProfile->color : '#ffffff';?>" onchange="$('#selected-color').css({'background-color':$(this).val()});">
										
										<label for="profile-color" class="auto-tip" data-title="Selected color" data-position="bottom" data-placement="bottom"><div style="display:inline-block;background-color: <?php echo !empty($userProfile->color) ? $userProfile->color : '#ffffff';?>;width:20px;height:18px;border:1px solid black;" id="selected-color"></div></label>
										<div style="display:inline-block;background-color: red;width:20px;height:18px;" onclick="selectColor($(this).css('background-color'));"></div>
										<div style="display:inline-block;background-color: blue;width:20px;height:18px;" onclick="selectColor($(this).css('background-color'));"></div>
										<div style="display:inline-block;background-color: green;width:20px;height:18px;" onclick="selectColor($(this).css('background-color'));"></div>
										<div style="display:inline-block;background-color: purple;width:20px;height:18px;" onclick="selectColor($(this).css('background-color'));"></div>
										<div style="display:inline-block;background-color: yellow;width:20px;height:18px;" onclick="selectColor($(this).css('background-color'));"></div>
										<div style="display:inline-block;background-color: magenta;width:20px;height:18px;" onclick="selectColor($(this).css('background-color'));"></div>
										<div style="display:inline-block;background-color: white;width:20px;height:18px;" onclick="selectColor($(this).css('background-color'));"></div>
										<span class="fa fa-lg fa-times" style="vertical-align:top;padding-top:2px;" onclick="selectColor('');"></span>
										<br><span class="btn btn-success btn-sm pull-right" onclick="saveColor($('#profile-color').val());">Сохранить</span>
									
								</div>
							</div>
						</div>

					</div>
				</div>				
				
			</div>
	
			<div class="col-md-6 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading"><strong class="panel-title">Адрес</strong></div>
					<div class="panel-body">
						
						<div class="container-fluid">
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Адрес:</div>
								<div class="col-xs-8"><?php echo $userProfile->address;?></div>
							</div>
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Индекс:</div>
								<div class="col-xs-8"><?php echo $userProfile->zip;?></div>
							</div>
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Сотовый:</div>
								<div class="col-xs-8"><?php echo $userProfile->cell;?></div>
							</div>
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Домашний</div>
								<div class="col-xs-8"><?php echo $userProfile->home;?></div>
							</div>
						</div>

					</div>
				</div>
				
				<div class="panel panel-default">
					<div class="panel-heading"><strong class="panel-title">Инфо</strong></div>
					<div class="panel-body">
						
						<div class="container-fluid">
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Выполнено:</div>
								<div class="col-xs-8"><?php echo getDropCompleatePkgs($userProfile->id); ?></div>
							</div>
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">В работе:</div>
								<div class="col-xs-8"><?php echo getDropInworkPkgs($userProfile->id); ?></div>
							</div>
							<div class="row" style="margin-bottom: 1em;">
								<div class="col-xs-4">Полная информация :</div>
								<div class="col-xs-8"><a href="<?php echo $cfg['options']['siteurl'];?>/fullUserPackagesInfo/<?php echo $userProfile->id;?>">>>></a></div>
							</div>
						</div>

					</div>
				</div>
				
				
			</div>
			
		</div>


		<div class="row">
			
			<div class="col-md-6 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading"><strong class="panel-title">Магазины</strong></div>
					<div class="panel-body"><?php echo getDropShops($userProfile->id); ?></div>
				</div>
			</div>
			
			<div class="col-md-6 col-xs-12"></div>
			
		</div>		
		
		<?php 
		if ($user['rankname']=='admin') {
		?>
		<div class="row">
			 <div class="col-xs-12">
				<div class="form-group-lg" style="margin: 20px 0 140px 0;">
					<a href="<?php echo $cfg['options']['siteurl'];?>/user/<?php echo $userProfile->id;?>" class="btn btn-success">Править</a>
				</div>
			</div>
		</div>
		<?php
		}
		?>
	</div>