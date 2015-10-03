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

	function createUser() {
		
		var data4ajax = {};
		var formisok = true;
		$('#createProfileForm').find('.form-control').each(function(){
			if ($(this).attr('required')!==undefined) {
				if (this.tagName=='SELECT' && $(this).val()==-1) {
					$(this).css({'background-color':'#faa !important'});
					notify('warning','Ошибка!','Заполните '+$(this).attr('name')+' поле!');
					formisok = false;
				}
				if ($(this).val()=='') { 
					$(this).css({'background-color':'#faa !important'});
					notify('warning','Ошибка!','Заполните '+$(this).attr('name')+' поле!');
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
			url: '<?php echo $cfg['options']['siteurl'];?>/gears/ajax.createUser.php',
			method: 'POST',
			dataType: 'JSON',
			data: data4ajax,
			success: function(data) {
				if (data.type === 'error') {
					notify('error',data.text);
				} else {
					document.location.href='<?php echo $cfg['options']['siteurl'];?>/user/'+data.text;
					notify('info','Успех','Новый пользователь: '+data.text);
				}

				console.log(data);
			},
			error: function(v1,v2,v3) {
				console.log(v1,v2,v3);
			}
		});
	}
	
	function reinitfields(sObj) {
		if ($(sObj).val()==3) {
			$('.dropOnlyElement').slideDown();
		} else {
			$('.dropOnlyElement').slideUp();
		}
	}
</script>


<h1 class="page-header">Создать пользователя</h1>

<form role="form" class="clearfix" method="POST" id="createProfileForm" enctype="multipart/form-data"  onsubmit="return false;">


	
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-4 col-xs-12">
				<div class="form-group-sm">
					<label for="profile-name"><h3>Логин</h3></label>
					<input id="profile-name" type="text" value="" placeholder="" class="form-control input-sm" name="nickname" required="">
				</div>
			</div>
			<div class="col-md-4 col-xs-12">
				<div class="form-group-sm dropOnlyElement">
					<label for="profile-email"><h3>Email</h3></label>
					<input id="profile-email" type="email" value="" placeholder="" class="form-control input-sm" name="email">
				</div>
			</div>
			<div class="col-md-4 col-xs-12">
				<div class="form-group-sm">
					<label for="profile-xmpp"><h3>XMPP</h3></label>
					<input id="profile-xmpp" type="email" value="" placeholder="" class="form-control input-sm" name="xmpp">
				</div>
			</div>
		</div>
	</div>

	
	<div class="container-fluid dropOnlyElement">
		<div class="row">

			<div class="col-md-4 col-xs-12">
				<div class="form-group-sm">
					<label for="profile-first-name"><h3>Имя</h3></label>
					<input id="profile-first-name" type="text" value="" placeholder="" class="form-control input-sm" name="first_name">
				</div>
			</div>
			<div class="col-md-4 col-xs-12">
				<div class="form-group-sm">
					<label for="profile-middle-name"><h3>Отчество</h3></label>
					<input id="profile-middle-name" type="text" value="" placeholder="" class="form-control input-sm" name="middle_name">
				</div>
			</div>
			<div class="col-md-4 col-xs-12">
				<div class="form-group-sm">
					<label for="profile-last-name"><h3>Фамилия</h3></label>
					<input id="profile-last-name" type="text" value="" placeholder="" class="form-control input-sm" name="last_name">
				</div>
			</div>
		</div>
	</div>

	<div class="container-fluid dropOnlyElement">
		<div class="row">
			<div class="col-md-4 col-xs-12">
				<div class="form-group-sm">
					<label for="profile-country"><h3>Страна</h3></label>
					<input id="profile-country" type="text" value="" placeholder="" class="form-control input-sm" name="country">
				</div>
			</div>
			<div class="col-md-4 col-xs-12">		
				<div class="form-group-sm">
					<label for="profile-state"><h3>Штат</h3></label>
					<input id="profile-state" type="text" value="" placeholder="" class="form-control input-sm" name="state">
				</div>
			</div>
			<div class="col-md-4 col-xs-12">	
				<div class="form-group-sm">
					<label for="profile-city"><h3>Город</h3></label>
					<input id="profile-city" type="text" value="" placeholder="" class="form-control input-sm" name="city">
				</div>
			</div>
		</div>
	</div>
	

	<div class="container-fluid dropOnlyElement">
		<div class="row">
			<div class="col-md-6 col-xs-12">	
				<div class="form-group-sm">
					<label for="profile-address"><h3>Адрес</h3></label>
					<input id="profile-address" type="text" value="" placeholder="" class="form-control input-sm" name="address">
				</div>
			</div>
			<div class="col-md-6 col-xs-12">		
				<div class="form-group-sm">
					<label for="profile-zip"><h3>Индекс</h3></label>
					<input id="profile-zip" type="text" value="" placeholder="" class="form-control input-sm" name="zip">
				</div>
			</div>
		</div>
	</div>
				
				
	<div class="container-fluid dropOnlyElement">
		<div class="row">
			<div class="col-md-6 col-xs-12">
				<div class="form-group-sm">
					<label for="profile-cell"><h3>Сотовый</h3></label>
					<input id="profile-cell" type="text" value="" placeholder="+44 4444 12345" class="form-control input-sm" name="cell">
				</div>
			</div>
			<div class="col-md-6 col-xs-12">
				<div class="form-group-sm">
					<label for="profile-home"><h3>Домашний</h3></label>
					<input id="profile-home" type="text" value="" placeholder="+44 4441 12345" class="form-control input-sm" name="home">
				</div>
			</div>
		</div>
	</div>
					

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-4 col-xs-12">
				<div class="form-group-sm">
					<label for="profile-rank"><h3>Группа</h3></label>
					<select id="profile-rank" name="rank" required="" class="form-control input-sm" onchange="reinitfields(this);">
						<option value="-1">---</option>
						<option value="4">Админ</option>
						<option value="1">Помощник</option>
						<option value="2">Отправитель</option>
						<option value="3" selected="">Сотрудник</option>
						<option value="5">Покупатель</option>
						<option value="6">Сортировщик</option>
					</select>	
				</div>
			</div>
			<div class="col-md-4 col-xs-12">
				<div class="form-group-sm">
					<label for="profile-password1"><h3>Пароль</h3></label>
					<input id="profile-password1" type="password" value="" class="form-control input-sm" name="password1" required="">
				</div>
			</div>
			<div class="col-md-4 col-xs-12">		
				<div class="form-group-sm">
					<label for="profile-password2"><h3>Пароль (проверка)</h3></label>
					<input id="profile-password2" type="password" value="" class="form-control input-sm" name="password2" required="">
				</div>
			</div>
		</div>
	</div>
	
	<div class="container-fluid">
		<div class="row">
			 <div class="col-xs-12">
				<div class="form-group-lg" style="margin: 20px 0 140px 0;">
					<span class="pull-right btn btn-success" onclick="createUser();">Создать</span>
				</div>
			</div>
		</div>
	</div>
		
		
	
	

	
	
		
</form>