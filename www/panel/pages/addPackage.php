<?php
// смотрим можно ли 
if ($user['rankname']!='admin' && $user['rankname']!='support' && $user['rankname']!='shipper') {
	exit('You are not admin!');
}
?>
<script>

	var pkg_vars = {};

	function validateEmail(email) { 
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	} 

	function setpkgvar(key, value) {
		pkg_vars[key] = value;
	}

	function pasteShopData(obj) {
		var shop_id = $(obj).data('shop-id');
		var shop_name = $(obj).data('shop-name');
		var shop_url = $(obj).data('shop-url');
		$('#shop-input').val(shop_name);
		$('#shop-inputURL').val(shop_url);
		$('#shop-id').val(shop_id);
		setpkgvar('shop_id',shop_id);
		setpkgvar('shop_url',shop_url);
		setpkgvar('shop_name',shop_name);
		$('#next1').attr('disabled',null);
	}

	function searchShop(str) {
		
		setpkgvar('shop_name',str);
		
		if (str.length!=0) { $('#next1').attr('disabled',null); } else { $('#next1').attr('disabled','disabled'); }
		if ($('#shop-inputURL').val().length!=0) { $('#next1').attr('disabled',null); } else { $('#next1').attr('disabled','disabled'); }
		
		if (str.length<2) {
			$('#shop_helper').html(' ');
			return false;
		}
		
		$.ajax({
			url: '<?php echo $cfg['options']['siteurl'];?>/gears/ajax.searchShop.php',
			method: 'POST',
			dataType: 'JSON',
			data: { shop_name: str },
			success: function(data) {
				if (data.type === 'error') {
					notify('error',data.text);
					console.log(data);
				} else {
					if (data.shops.length>0) {
						// взяли позицию подсказчика из даты
						var helper_position_x = $('#shop_helper').data('position-x');
						var helper_position_y = $('#shop_helper').data('position-y');
						// показали
						$('#shop_helper').slideDown().html(' ');
						// выставили на место
						$('#shop_helper').css({'position':'absolute','top':helper_position_y,'left':helper_position_x,'z-index':99999});
						// добавим список шопов
						for(i in data.shops) {
							$('#shop_helper').append('<li class="shop-helper-item list-group-item" data-id="'+data.shops[i].id+'" data-shop-name="'+data.shops[i].shop_name+'" data-shop-url="'+data.shops[i].shop_url+'">'+data.shops[i].shop_name+'</li>');
						}
						// добавим изменение при наведении
						$('#shop_helper li').hover(function(){  $(this).toggleClass('helper-item-hover'); });
						// добавим бинд на клик
						$('#shop_helper .shop-helper-item').bind('click', function(){
							$('#next1').attr('disabled',null);
							$('#shop-input').val($(this).data('shop-name'));
							$('#shop-inputURL').val($(this).data('shop-url'));
							$('#shop-id').val($(this).data('id'));
							setpkgvar('shop_id',$(this).data('id'));
							setpkgvar('shop_url',$(this).data('shop-url'));
							setpkgvar('shop_name',$(this).data('shop-name'));							
						});
					} else {
						$('#shop_helper').html(' ');
					}
					//console.log(data);
				}
			},
			error: function(v1,v2,v3) {
				console.log(v1,v2,v3);
			}
		});	
	}

	function searchDrops(what) {
		$('#next2').attr('disabled','disabled');
		var drop_country = $('#drop-country').val();
		var drop_state = $('#drop-state').val();
		var isonlyclear = $('#onlyclear').is(':checked');
		$('#drop-info').html('');
		if (pkg_vars.shop_id != undefined) { var shop = pkg_vars.shop_id; } else { var shop = -1; }
		$.ajax({
			url: '<?php echo $cfg['options']['siteurl'];?>/gears/ajax.searchDrops.php',
			method: 'POST',
			dataType: 'JSON',
			data: { country: drop_country, state: drop_state, isclear: isonlyclear, shop_id: shop },
			success: function(data) {
				//console.log(data);
				if (what==='country') {
					$('#drop-state').html('');
					$('#drop-state').append('<option value="-1">Все штаты</option>');
					for(i in data.states) {
						$('#drop-state').append('<option value="'+data.states[i]+'">'+data.states[i]+'</option>');
					}
				}
				$('#drop-select').html('');
				for(i in data.drops) {
					$('#drop-select').append('<option value="'+data.drops[i].id+'">'+data.drops[i].first_name+' '+data.drops[i].middle_name+' '+data.drops[i].last_name+'</option>')
				}
			},
			error: function(v1,v2,v3) {
				console.log(v1,v2,v3);
			}
		});
	}

	function getDropInfo() {
		var drop_id = $('#drop-select option:selected').val();
		
		if (drop_id!=undefined) { $('#next2').attr('disabled',null); } else { $('#next2').attr('disabled','disabled'); }
		
		setpkgvar('drop_id',drop_id);
		if (pkg_vars.shop_id == undefined) { shop_id = 'EMPTYORNEW'; } else { shop_id=pkg_vars.shop_id; }
		if ($('#drop-select option:selected').length>1) {
			$('#drop-select option:selected').removeAttr('selected');
			return false;
		}
		//console.log(drop_id);
		$.ajax({
			url: '<?php echo $cfg['options']['siteurl'];?>/gears/ajax.getDropInfo.php',
			method: 'POST',
			dataType: 'JSON',
			data: { id: drop_id, shop_id: shop_id },
			success: function(data) {
				//console.log(data);
				$('#drop-info').html(data.info);
			},
			error: function(v1,v2,v3) {
				console.log(v1,v2,v3);
			}
		});		
	}

	function showStep(nval,cval) {
		$('#step'+cval).slideUp();
		$('#step'+nval).slideDown();
		// clear next form if we step prev and then next
	}

	function createPackage() {
		
		var er_flag = {iserror: false, title:'',text:''};
		
		if ($('#buyer_id').val()==undefined || $('#buyer_id').val()=='' || $('#buyer_id').val()==-1) {
			er_flag.iserror=true;
			er_flag.title='Ошибка!';
			er_flag.title='Покупатель не выбран';
		} ;
		if ($('#item').val()=='') {
			er_flag.iserror=true;
			er_flag.title='Ошибка!';
			er_flag.title='Название товара';
		};
		if ($('#price').val()=='') {
			er_flag.iserror=true;
			er_flag.title='Ошибка!';
			er_flag.title='Нет цены';
		};
		if ($('#currency').val()==undefined || $('#currency').val()=='' || $('#currency').val()==-1) {
			er_flag.iserror=true;
			er_flag.title='Ошибка!';
			er_flag.title='Не выбрана валюта';
		};
		
		if (er_flag.iserror==true) {
			notify('error',er_flag.title, er_flag.text);
			return false;
		}
		
		setpkgvar('buyer_id',$('#buyer_id').val());
		setpkgvar('action',$('#action').val());
		setpkgvar('item',$('#item').val());
		setpkgvar('price',$('#price').val());
		setpkgvar('currency',$('#currency').val());
		
		$.ajax({
			url: '<?php echo $cfg['options']['siteurl'];?>/gears/ajax.createPackage.php',
			method: 'POST',
			dataType: 'JSON',
			data: pkg_vars,
			success: function(data) {
				console.log(data);
				document.location.href='<?php echo $cfg['options']['siteurl']; ?>/packages';
			},
			error: function(v1,v2,v3) {
				console.log(v1,v2,v3);
			}
		});				
	}

	<?php
		// ставится в /pages/index.php в самый низ
		$_page_scripts = "
			$(function () {
				var helper_position = $('#shop_helper').position();
				$('#shop_helper').hide().html('');
				$('#shop_helper').attr({'data-position-x':helper_position.left, 'data-position-y':helper_position.top});
				$('#shop-input').bind('blur',function(){ $('#shop_helper').slideUp('fast'); });
				$('#shop-input').bind('focus',function(){ searchShop($('#shop-input').val()); });
				$('#shop-inputURL').bind('keyup',function(){ if ($('#shop-inputURL').val().length!=0 && $('#shop-input').val().length!=0) { $('#next1').attr('disabled',null); } else { $('#next1').attr('disabled','disabled'); } });
			});
		";
	?>
	
</script>


<h1 class="page-header">Add package</h1>

<form role="form" class="clearfix" method="POST" id="createProfileForm" enctype="multipart/form-data"  onsubmit="return false;">


	<div class="container-fluid" id="step1">
		<div class="row">
			<div class="col-md-4 col-xs-12">
	
				<div class="panel panel-default">
					<div class="panel-heading">Магазин</div>
					<div class="panel-body">
						<input id="shop-id" name="shop-id" value="" type="hidden">
						<strong>Название магазина</strong>
						<input id="shop-input" onkeyup="searchShop($('#shop-input').val());" class="form-control">
						<ul class="list-group list-unstyled el-input-helper" id="shop_helper"></ul><br>
						<strong>Адрес URL</strong>
						<input id="shop-inputURL" class="form-control" onkeyup="setpkgvar('shop_url',$(this).val());">
						
					</div>
				</div>
			
			</div>

			<div class="col-md-8 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading">Магазины</div>
					<div class="panel-body" id="shop-statistic">
						<?php
							echo getAllShops();
						?>
					</div>
				</div>								
			</div>
			
			
		</div>
		<div class="row clearfix">
			<div class="col-xs-12 clearfix">
				<button class="btn btn-success pull-right" id="next1" disabled="" onclick="showStep(2,1);">Далее</button>
			</div>
		</div>
	</div>
	
	
	
	<div class="container-fluid" id="step2" style="display:none;">
		<div class="row">			
			<div class="col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading">Сотрудник</div>
					<div class="panel-body">
						<div class="container-fluid">
							<div class="row">
								<div class="col-md-3 col-xs-12">
									<strong>Country</strong>
									<select class="form-control" id="drop-country" onchange="searchDrops('country');">
										<option value="-1">Страна</option>
										<?php
											$drops_cc = $db->query('SELECT `country` FROM `users` WHERE `country` != \'\' GROUP BY `country` ORDER BY `country` ASC');
											if ($drops_cc) {
												foreach($drops_cc as $v) {
													echo '<option value="'.$v->country.'">'.$v->country.'</option>';
												}
											}
										?>
									</select>
									<div style="margin:1em 0 1em 0;padding:0;">
										<label for="onlyclear"><input type="checkbox" onchange="searchDrops('state');" id="onlyclear"> <small>Показать только тех кто не работал с этим магазином</small></label>
									</div>
								</div>
								<div class="col-md-3 col-xs-12">
									<strong>Штат</strong>
									<select class="form-control" id="drop-state" onchange="searchDrops('state');"></select>
								</div>
								<div class="col-md-6 col-xs-12">
									<strong>Сотрудник</strong>
									<select class="form-control" multiple id="drop-select" onchange="getDropInfo()"></select>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-xs-12">
									<strong>Информация о сотруднике</strong>
									<div id="drop-info">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	
		<div class="row clearfix">
			<div class="col-xs-12 clearfix">
				<button class="btn btn-success pull-right" disabled="" id="next2" onclick="showStep(3,2);">Далее</button>
			</div>
		</div>
	</div>	


	
	

	
	

	
	

	
				
				
						

	
	
	<div class="container-fluid" id="step3" style="display: none;">
		
		
		
		

		<div class="row">
			<div class="col-md-3 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading">Покупатель</div>
					<div class="panel-body">
						<select class="form-control" id="buyer_id">
							<?php 
								$q = "SELECT * FROM `users` WHERE `rank` = 5 ORDER BY name ASC";
								$res = $db->query($q);
								if (isset($res[0])) {
									foreach($res as $v) {
										echo '<option value="'.$v->id.'">'.$v->first_name.' '.$v->middle_name.' '.$v->last_name.'</option>';
									}									
								}
							?>
						</select>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">Информация для сотрудника</div>
					<div class="panel-body">				
						Информация
						<textarea class="form-control" id="action"></textarea>
					</div>
				</div>
			</div>
			<div class="col-md-9 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading">Товар</div>
					<div class="panel-body">
						Название
						<input type="text" class="form-control" id="item"><br>
						Цена
						<input type="text" class="form-control" id="price"><br>
						Валюта
						<select class="form-control" id="currency">
							<option value="usd">Доллар $</option>
							<option value="eur">Евро €</option>
							<option value="gbp">Фунт £</option>
							<option value="cny">Китайская Йена ¥</option>
							<option value="jpy">Японская Йена ¥</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			 <div class="col-xs-12">
				<div class="form-group-lg" style="margin: 20px 0 140px 0;">
					<span class="pull-right btn btn-success" id="next3" onclick="createPackage();">Создать</span>
				</div>
			</div>
		</div>		
	</div>
	
		
		
	
	

	
	
		
</form>