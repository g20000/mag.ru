<?php
// смотрим можно ли 
if ($user['rankname']!='admin' && $user['rankname']!='support' && $user['rankname']!='shipper') {
	exit('You are not admin!');
}
?>


	<div class="modal fade addPkgModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Новое задание!</h4>
				</div>
				<div class="modal-body">
					<form>
						<h5>Магазин</h5>
						<input type="hidden" id="drop_id" val="">
						<input class="form-control input-sm" placeholder="" list="shopDataList" id="shop-input"  onchange="checkShopUrl(this);" onkeyup="checkShopUrl(this);">
						<input type="hidden" id="shop-url">
						<input type="hidden" id="shop-id">
						<datalist id="shopDataList">
						</datalist>
						<h5>Название товара</h5>
						<input class="form-control input-sm" placeholder="" id="item">

						<div class="content">
							<div class="row">
								<div class="col-xs-6">
									<h5>Цена</h5>
									<input class="form-control input-sm" id="price" placeholder="" type="number">
								</div>
								<div class="col-xs-6">
									<h5>Валюта</h5>
									<select class="form-control input-sm" id="currency">
										<option value="eur" selected>Евро €</option>
										<option value="usd">Доллар $</option>
										<option value="gbp">Фунт £</option>
										<option value="cny">Китайская Йена ¥</option>
										<option value="jpy">Японская Йена ¥</option>
									</select>
								</div>
							</div>
						</div>
						
						
						<h5 class="hidden">Заметка</h5>
						<textarea placeholder="" class="hidden form-control input-sm"></textarea>
						<?php 
							$q = "SELECT * FROM `users` WHERE `rank` = 5 ORDER BY name ASC";
							$res = $db->query($q);
							if (isset($res[0])) {
								foreach($res as $v) {
									echo '<option value="'.$v->id.'">'.$v->first_name.' '.$v->middle_name.' '.$v->last_name.'</option>';
								}									
							}
						?>
						
						<div class="content">
							
							<div class="row">
								<div class="col-xs-12">
									<h5>ФИО Сотрудника</h5>
									<div class="checkbox"><label><input type="checkbox" checked=""> Отослать сотруднику</label></div>
								</div>
							</div>
						</div>

						<div class="content">
							<div class="row">
								<div class="col-xs-12">
									<h5>Дата прихода</h5>
									<input placeholder="dd/mm/yyyy" id="datepicker" class="form-control input-sm">
								</div>
							</div>
						</div>
					</form>
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
					<button type="button" class="btn btn-success" onclick="createPackage();">Создать</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->



<script>

	function checkShopUrl(sObj) {
		
		$.ajax({
			url: '<?php echo $cfg['options']['siteurl'];?>/gears/ajax.searchShop.php',
			method: 'POST',
			dataType: 'JSON',
			data: { shop_name: $(sObj).val() },
			success: function(data) {
				if (data.type === 'error') {
					notify('error',data.text);
					console.log(data);
				} else {
					if (data.shops.length>0) {
						$('#shop-url').val(data.shops[0].shop_url);
						$('#shop-id').val(data.shops[0].id);
					}
				}
			},
			error: function(v1,v2,v3) {
				console.log(v1,v2,v3);
			}
		});			
		
	}

	function deleteDrop(id) {
		if (confirm('Sure to delete user with ID:'+id)===true) {
			$('table').find('tr').each(function(){
				if ($(this).data('user-id')===id) {
					$(this).remove();
					$.ajax({
						url: '<?php echo $cfg['options']['siteurl'];?>/gears/ajax.deleteUser.php',
						method: 'POST',
						dataType: 'JSON',
						data: {
							userid: id
						},
						success: function(data) {
							if (data.type == 'error') {
								notify('error','Удаление',data.text);
							} else {
								notify('info','Удаление',data.text);
							}

							console.log(data);
						},
						error: function(v1,v2,v3) {
							console.log(v1,v2,v3);
						}
					})
				}
			});
		}
	}

	function createPackage() {
		
		var pkgvar = {};
		var er_flag = {iserror: false, title:'',text:''};
		if ($('#item').val()=='') {
			er_flag.iserror=true;
			er_flag.title='Ошибка!';
			er_flag.title='Имя товара!';
		};
		if ($('#price').val()=='') {
			er_flag.iserror=true;
			er_flag.title='Ошибка!';
			er_flag.title='Цена!';
		};
		if ($('#currency').val()==undefined || $('#currency').val()=='' || $('#currency').val()==-1) {
			er_flag.iserror=true;
			er_flag.title='Ошибка!';
			er_flag.title='Валюта!';
		};
		if ($('#shop-input').val()=='' || $('#shop-input').val()==undefined) {
			er_flag.iserror=true;
			er_flag.title='Ошибка!';
			er_flag.title='Магазин!';			
		}
		
		if (er_flag.iserror==true) {
			notify('error',er_flag.title, er_flag.text);
			return false;
		}
		
		
		

		
		pkgvar.action=$('#action').val();
		pkgvar.buyer_id=$('#buyer_id').val();
		pkgvar.currency=$('#currency').val();
		pkgvar.drop_id=$('#drop_id').val();
		pkgvar.item=$('#item').val();
		pkgvar.price=$('#price').val();
		pkgvar.shop_id = $('#shop-id').val();
		pkgvar.shop_name=$('#shop-input').val();
		pkgvar.shop_url=$('#shop-url').val();


		$.ajax({
			url: '<?php echo $cfg['options']['siteurl'];?>/gears/ajax.createPackage.php',
			method: 'POST',
			dataType: 'JSON',
			data: pkgvar,
			success: function(data) {
				console.log(data);
				document.location.href='<?php echo $cfg['options']['siteurl']; ?>/package/'+data.text;
			},
			error: function(v1,v2,v3) {
				console.log(v1,v2,v3);
			}
		});				
	}


	function addPkgModalShow(drop_id) {
		$('.addPkgModal').modal({
		  keyboard: false,
		  backdrop: 'static',
		  show: true,

		});
		
		$('#shopDataList').html('');
		$('.allowedShops_'+drop_id).find('span').each(function(){
			$('#shopDataList').append('<option value="'+$(this).html()+'">');
		});
		$('.addPkgModal #drop_id').val(drop_id);
	}

	function showUserFullInfo(dropId){ 
		var slider = $('body').find('tr[data-user-info='+dropId+']'); 
		if ($(slider).is(':visible')) {
			$(slider).fadeOut();
		} else {
			$(slider).fadeIn();
		}
	};
	
	$(function() {
		$( "#datepicker" ).datepicker({
			dateFormat: 'dd/mm/yy',
			firstDay: 1,
		});
	});
</script>

<h1 class="page-header">Курьеры</h1>


<div class="table-responsive">
<table class="table table-striped">
	<thead>
		<tr>
			<th>Имя</th>
			<th>Страна</th>
			<th align="center" class="text-center"><!--Завершено / В работе-->Всего пакетов</th>
			<?php if ($user['rankname']=='admin' || $user['rankname']=='support') { ?> <th>Магазины</th> <?php } ?>
			<th>Дата регистрации</th>
			<th class='text-center'>Действия</th>
		</tr>
	</thead>
	<tbody>
	<?php

		if ($user['rankname']=='shipper') {
			$q = "	SELECT u.*, d2s.shop_id, s.shop_name FROM `drops2shippers` AS d2s
					LEFT JOIN `users` AS u ON u.id = d2s.drop_id
					LEFT JOIN `shops` AS s ON s.id = d2s.shop_id
					WHERE d2s.shipper_id = ".$user['id'];
			$res = $db->query($q);
			$usersList = array();
			if (isset($res[0])) {
				foreach($res as $r) {
					if (!isset($usersList[$r->id])) { $usersList[$r->id] = $r; }
					$usersList[$r->id]->shops[] = array('id'=>$r->shop_id,'name'=>$r->shop_name);
				}
			}
			//debug($usersList);
		} else {
			$usersList = getUsersListRank(3);
		}
		if ($usersList!==false) {
			foreach($usersList as $k=>$v) {
				?>
					<tr data-user-id="<?php echo $v->id;?>">
						<?php if ($user['rankname']=='admin' || $user['rankname']=='support') { ?>
						<td style="cursor:pointer;color:<?php echo getUserColor($v->id);?>" onclick="showUserFullInfo(<?php echo $v->id;?>);"><span data-toggle="tooltip" data-placement="top" title="<?php echo getUserNote($v->id);?>"><?php echo $v->first_name.' '.$v->middle_name.' '.$v->last_name;?></span></td>
						<?php } else { ?>
						<td><?php echo $v->first_name.' '.$v->middle_name.' '.$v->last_name;?></td>
						<?php } ?>
						<td><?php echo $v->address.' '.$v->city.' '.$v->state.' '.$v->zip.' '.$v->country;?></td>
						<td align=center><?php $totalPackages = getDropCompleatePkgs($v->id) +  getDropInworkPkgs($v->id);
						echo $totalPackages;
						?>
						</td>
						<?php if ($user['rankname']=='admin' || $user['rankname']=='support') { ?>
						<td class="allowedShops_<?php echo $v->id;?>">
							<?php 
								if (isset($v->shops)) {
									foreach($v->shops as $allowshop) {
										echo '<span class="badge">'.$allowshop['name'].'</span> ';
									}
								} else {
									echo 'Нет разрешеных!';
								}
							?>
						</td>
						<?php } ?>
						<td><?php echo $v->registration_time;?></td>
						<td class="text-center">
							<?php
								if ($user['rankname']=='admin' || $user['rankname']=='support') {
							?>
								<span onclick="addPkgModalShow(<?php echo $v->id;?>);" style="cursor:pointer;"><i class="fa fa-paste text-info" data-toggle="tooltip" data-placement="top" title="New Package"></i></span>
								&nbsp;|&nbsp;
								<a href="<?php echo $cfg['options']['siteurl'];?>/user/<?php echo $v->id;?>"><i class="fa fa-cogs"  data-toggle="tooltip" data-placement="top" title="View user"></i></a>
								&nbsp;|&nbsp;
								<a href="javascript:void(0);" onclick="deleteDrop(<?php echo $v->id;?>);"><i class="fa  fa-lg fa-times text-danger"  data-toggle="tooltip" data-placement="top" title="Delete user"></i></a>
							<?php } else { ?>
								<button class="btn btn-success btn-sm" onclick="addPkgModalShow(<?php echo $v->id;?>);">Новый товар</button>
							<?php } ?>
						</td>
					</tr>
					<tr data-user-info="<?php echo $v->id;?>" style="display:none;">
						<td colspan="6">
							<?php 
								if ($user['rankname']=='admin' || $user['rankname']=='support') {
							?>

								<div class="container-fluid">
									<div class="row">
										<div class="col-md-6 col-xs-12">
											
											<div class="panel panel-default">

											<div class="panel-body"  style="margin:0;padding:0;">
											<table class="table table-condensed" style="margin:0;padding:0;">
												<tr><td>Логин</td><td><?php echo $v->name;?></td></tr>
												<tr><td>Email</td><td><?php echo $v->email;?></td></tr>
												<tr><td>XMPP</td><td><?php echo $v->xmpp;?></td></tr>
												<tr><td>Заметки</td><td><?php echo $v->about;?></td></tr>
												<tr><td>Цвет</td><td><div style="line-height:1px;margin:0;padding:0;border:0;display:inline-block;width:1em;height:1em;background-color:<?php echo !empty($v->color) ? $v->color : '#ffffff';?>;"></div></td></tr>
												
												<tr><td>Имя</td><td><?php echo $v->first_name;?></td></tr>
												<tr><td>Отчество</td><td><?php echo $v->middle_name;?></td></tr>
												<tr><td>Фамилия</td><td><?php echo $v->last_name;?></td></tr>
												
												<tr><td>Страна</td><td><?php echo $v->country;?></td></tr>
												<tr><td>Штат</td><td><?php echo $v->state;?></td></tr>
												<tr><td>Город</td><td><?php echo $v->city;?></td></tr>
												
												<tr><td>Адрес</td><td><?php echo $v->address;?></td></tr>
												<tr><td>Индекс</td><td><?php echo $v->zip;?></td></tr>
												
												<tr><td>Сотовый</td><td><?php echo $v->cell;?></td></tr>
												<tr><td>Домашний</td><td><?php echo $v->home;?></td></tr>
												
												<tr><td>Группа</td><td><?php echo getRankById($v->rank)->title;?></td></tr>
												
											</table>
											</div>
											</div>


										</div>
										
										<div class="col-md-6 col-xs-12">
											
										</div>
										
									</div>
								</div>

								<div class="container-fluid">
									<div class="row">
										 <div class="col-xs-12">
											<div class="form-group-lg" style="margin: 20px 0 20px 0;">
												<a class="btn btn-success" href="<?php echo $cfg['options']['siteurl'];?>/user/<?php echo $v->id; ?>">Редактировать</a>
											</div>
										</div>
									</div>
								</div>


							</form>
							<?php 
								}
							?>
						</td>
					</tr>

				<?php
			}
		} else {

		}

	?>
	</tbody>
</table>
</div>