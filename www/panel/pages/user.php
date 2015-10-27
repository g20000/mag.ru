<?php
// смотрим можно ли 
if ($user['rankname']!='admin') {
	exit('You are not admin!');
}
?>
<script>

	function validateEmail(email) { 
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	} 

	function editUser() {

		var data4ajax = {};
		var formisok = true;
		$('#editProfileForm').find('.form-control').each(function(){
			if ($(this).attr('required')!==undefined) {
				if (this.tagName=='SELECT' && $(this).val()==-1) {
					$(this).css({'background-color':'#faa !important'});
					notify('warning','Надо заполнить!','Надо заполнить поле '+$(this).attr('name')+' !');
					formisok = false;
				}
				if ($(this).val()=='') { 
					$(this).css({'background-color':'#faa !important'});
					notify('warning','Надо заполнить!','Надо заполнить поле '+$(this).attr('name')+' !');
					formisok = false;
				}
			}
			
			data4ajax[$(this).attr('name')] = $(this).val();		
			
		});
		
		if (formisok === false) {
			return false;
		}
		
		//console.log(data4ajax);return false;

		$.ajax({
			url: '<?php echo $cfg['options']['siteurl'];?>/gears/ajax.editUser.php',
			method: 'POST',
			dataType: 'JSON',
			data: data4ajax,
			success: function(data) {
				if (data.type === 'error') {
					notify('error',data.text);
				} else {
					//notify('info','Success','User info saved');
					alert('Успешно обновлено!');
					document.location.reload();
				}

				//console.log(data);
			},
			error: function(v1,v2,v3) {
				console.log(v1,v2,v3);
			}
		});
	}
</script>

<?php
$userProfile = getUserInfoById($route->param);
//debug($userProfile);

?>

<h1 class="page-header">Аккаунт: <?php echo $userProfile->name; ?></h1>

<form role="form" class="clearfix" method="POST" id="editProfileForm" enctype="multipart/form-data"  onsubmit="return false;">

	<input id="uid" class="form-control" type="hidden" value="<?php echo $userProfile->id;?>" name="uid">
	
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6 col-xs-12">
				<div class="form-group-sm">
					<label for="profile-name"><h3>Логин</h3></label>
					<input id="profile-name" type="text" value="<?php echo $userProfile->name;?>" placeholder="" class="form-control input-sm" name="nickname" required="">
				</div>
				<div class="form-group-sm">
					<label for="profile-email"><h3>Email</h3></label>
					<input id="profile-email" type="email" value="<?php echo $userProfile->email;?>" placeholder="" class="form-control input-sm" name="email">
				</div>
				<div class="form-group-sm">
					<label for="profile-xmpp"><h3>XMPP</h3></label>
					<input id="profile-xmpp" type="email" value="<?php echo $userProfile->xmpp;?>" placeholder="" class="form-control input-sm" name="xmpp">
				</div>
			</div>
			<?php //if ($userProfile->rank == intval(3)) { ?>
			<div class="col-md-6 col-xs-12">
				<div class="form-group-sm">
					<label for="profile-note"><h3>Заметки</h3></label>
					<textarea id="profile-note" name="note" class="form-control"><?php echo $userProfile->about;?></textarea>
				</div>
					<div class="color-select-edit">
						<input style="position:absolute;left:-1000px;" type="color" name="color" id="profile-color" value="<?php echo !empty($userProfile->color) ? $userProfile->color : '#ffffff';?>" onchange="$('#selected-color').css({'background-color':$(this).val()});">
						
						<label for="profile-color" class="auto-tip" data-title="Selected color" data-position="bottom" data-placement="bottom"><div style="display:inline-block;background-color: <?php echo !empty($userProfile->color) ? $userProfile->color : '#ffffff';?>;width:40px;height:40px;border-radius:40px;" id="selected-color"></div></label>
						<div style="display:inline-block;background-color: red;width:40px;height:40px;border-radius:40px;" onclick="selectColor($(this).css('background-color'));"></div>
						<div style="display:inline-block;background-color: blue;width:40px;height:40px;border-radius:40px;" onclick="selectColor($(this).css('background-color'));"></div>
						<div style="display:inline-block;background-color: green;width:40px;height:40px;border-radius:40px;" onclick="selectColor($(this).css('background-color'));"></div>
						<div style="display:inline-block;background-color: purple;width:40px;height:40px;border-radius:40px;" onclick="selectColor($(this).css('background-color'));"></div>
						<div style="display:inline-block;background-color: #FFB900;width:40px;height:40px;border-radius:40px;" onclick="selectColor($(this).css('background-color'));"></div>
						<div style="display:inline-block;background-color: magenta;width:40px;height:40px;border-radius:40px;" onclick="selectColor($(this).css('background-color'));"></div>
						<div style="display:inline-block;background-color: #D0D0D0;width:40px;height:40px;border-radius:40px;" onclick="selectColor($(this).css('background-color'));"></div>
					</div>
			</div>
			<?php //} ?>
		</div>
	</div>

	<?php //if ($userProfile->rank == intval(3)) { ?>
	<div class="container-fluid">
		<div class="row">
			<div class="hidden-xs col-md-12">
				<hr class="edit-gr-border">
			</div>
			<div class="col-md-6">

				<div class="form-group-sm">
					<label for="profile-first-name"><h3>Имя</h3></label>
					<input id="profile-first-name" type="text" value="<?php echo $userProfile->first_name;?>" placeholder="" class="form-control input-sm" name="first_name">
				</div>


				<div class="form-group-sm">
					<label for="profile-middle-name"><h3>Отчество</h3></label>
					<input id="profile-middle-name" type="text" value="<?php echo $userProfile->middle_name;?>" placeholder="" class="form-control input-sm" name="middle_name">
				</div>


				<div class="form-group-sm">
					<label for="profile-last-name"><h3>Фамилия</h3></label>
					<input id="profile-last-name" type="text" value="<?php echo $userProfile->last_name;?>" placeholder="" class="form-control input-sm" name="last_name">
				</div>
			</div>
			<div class="col-md-6">

					<div class="form-group-sm">
						<label for="profile-country"><h3>Страна</h3></label>
						<input id="profile-country" type="text" value="<?php echo $userProfile->country;?>" placeholder="" class="form-control input-sm" name="country">
					</div>

	
					<div class="form-group-sm">
						<label for="profile-state"><h3>Штат</h3></label>
						<input id="profile-state" type="text" value="<?php echo $userProfile->state;?>" placeholder="" class="form-control input-sm" name="state">
					</div>


					<div class="form-group-sm">
						<label for="profile-city"><h3>Город</h3></label>
						<input id="profile-city" type="text" value="<?php echo $userProfile->city;?>" placeholder="" class="form-control input-sm" name="city">
					</div>

			</div>
			<div class="hidden-xs col-md-12">
				<hr class="edit-gr-border">
			</div>
		</div>
	</div>

	<div class="container-fluid">

	</div>
	

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group-sm">
					<label for="profile-cell"><h3>Сотовый</h3></label>
					<input id="profile-cell" type="text" value="<?php echo $userProfile->cell;?>" placeholder="+44 4444 12345" class="form-control input-sm" name="cell">
				</div>


				<div class="form-group-sm">
					<label for="profile-home"><h3>Домашний</h3></label>
					<input id="profile-home" type="text" value="<?php echo $userProfile->home;?>" placeholder="+44 4441 12345" class="form-control input-sm" name="home">
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-group-sm">
					<label for="profile-address"><h3>Адрес</h3></label>
					<input id="profile-address" type="text" value="<?php echo $userProfile->address;?>" placeholder="" class="form-control input-sm" name="address">
				</div>
			
	
				<div class="form-group-sm">
					<label for="profile-zip"><h3>Индекс</h3></label>
					<input id="profile-zip" type="text" value="<?php echo $userProfile->zip;?>" placeholder="" class="form-control input-sm" name="zip">
				</div>
			</div>

		</div>
	</div>
	<?php //} /* show only 4 drop */ ?>

	<div class="container-fluid">
		<div class="row">
			<div class="hidden-xs col-md-12">
				<hr class="edit-gr-border">
			</div>
			<div class="col-md-6">
				<div class="form-group-sm">
					<label for="profile-rank"><h3>Группа</h3></label>
					<select id="profile-rank" name="rank" required="" class="form-control input-sm">
						<option value="-1">---</option>
						<option value="4" <?php echo $userProfile->rank == '4' ? 'selected' : '';?>>Админ</option>
						<option value="1" <?php echo $userProfile->rank == '1' ? 'selected' : '';?>>Помощник</option>
						<option value="2" <?php echo $userProfile->rank == '2' ? 'selected' : '';?>>Отправитель</option>
						<option value="3" <?php echo $userProfile->rank == '3' ? 'selected' : '';?>>Сотрудник</option>
						<option value="5" <?php echo $userProfile->rank == '5' ? 'selected' : '';?>>Покупатель</option>
					</select>	
				</div>
			</div>
			<div class="col-md-6">

				<div class="form-group-sm">
					<label for="profile-password1"><h3>Пароль</h3></label>
					<input id="profile-password1" type="password" value="" class="form-control input-sm" name="password1">
				</div>

		
				<div class="form-group-sm">
					<label for="profile-password2"><h3>Проверка пароля</h3></label>
					<input id="profile-password2" type="password" value="" class="form-control input-sm" name="password2">
				</div>
				<div class="form-group-lg" style="margin: 20px 0 140px 0;">
					<span class="pull-right btn btn-success" onclick="editUser();">Редактировать</span>
				</div>
			</div>
		</div>
	</div>
	
	<div class="container-fluid">
		<div class="row">
			 <div class="col-xs-12">

			</div>
		</div>
	</div>
		
		
	
	

	
	
		
</form>