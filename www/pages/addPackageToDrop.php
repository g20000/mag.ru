<?php 
//debug($route); 
?>
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
					}
					//console.log(data);
				}
			},
			error: function(v1,v2,v3) {
				console.log(v1,v2,v3);
			}
		});			
		
	}
	
	function pasteShopData(sObj) {
		$('#shop-input').val($(sObj).data('shop-name'));
	}
	
	function createPackage() {
		
		var pkgvar = {};
		var er_flag = {iserror: false, title:'',text:''};
		
		if ($('#buyer_id').val()==undefined || $('#buyer_id').val()=='' || $('#buyer_id').val()==-1) {
			er_flag.iserror=true;
			er_flag.title='Ошибка!';
			er_flag.title='Покупатель не выбран';
		} ;
		if ($('#item').val()=='') {
			er_flag.iserror=true;
			er_flag.title='Ошибка!';
			er_flag.title='Название товара!';
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
		pkgvar.drop_id=<?php echo $route->param; ?>;
		pkgvar.item=$('#item').val();
		pkgvar.price=$('#price').val();
		pkgvar.shop_id = '';
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

</script>
<h1 class="page-header">Добавить задание</h1>

<form role="form" class="clearfix" method="POST" id="createProfileForm" enctype="multipart/form-data"  onsubmit="return false;">


	<div class="container-fluid">
		<div class="row">
			<div class="col-md-4 col-xs-12">
	
				<div class="panel panel-default">
					<div class="panel-heading">Магазин</div>
					<div class="panel-body">
						<strong>Название</strong>
						<input id="shop-url" value="" type="hidden">
						<input id="shop-input" class="form-control" list="shopDataList" onchange="checkShopUrl(this);" onkeyup="checkShopUrl(this);">
						<datalist id="shopDataList">
							<?php
								$shopDataList = $db->query("SELECT * FROM `shops`");
								if (isset($shopDataList[0])) {
									foreach($shopDataList as $shopDataListValue) {
										echo '<option value="'.$shopDataListValue->shop_name.'">';
									} 
								}
								
							?>
						</datalist>
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

	</div>
	
	
	


	
	

	
				
				
						

	
	
	<div class="container-fluid">
		
		
		
		
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
					<div class="panel-heading">Задание для сотрудника</div>
					<div class="panel-body">				
						Задание
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
					<span class="pull-right btn btn-success" id="createPkg" onclick="createPackage();">Создать</span>
				</div>
			</div>
		</div>		
	</div>
	
		
		
	
	

	
	
		
</form>