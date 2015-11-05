<?php
// смотрим можно ли 
if ($user['rankname']!='admin' && $user['rankname']!='support') {
	exit('You are not admin!');
}
?>
<script>

	function selectShop(shop_id, drop_id) {
		$.ajax({
			url: '<?php echo $cfg['options']['siteurl'];?>/gears/ajax.selectDropShop.php',
			data: {
				drop_id: drop_id, 
				shop_id: shop_id,
				shipper_id: <?php echo $route->param; ?>,
			},
			dataType: 'JSON',
			method: 'POST',
			success: function(data) {
				if (data.type == 'error') {
					notify('error','Ошибка',data.text);
				} else {
					notify('info','Ошибка',data.text);
					$('.shopSelector_'+shop_id+'_'+drop_id).toggleClass('text-danger').toggleClass('text-success');
				}

				console.log(data);
			},
			error: function(v1,v2,v3) {
				console.log(v1,v2,v3);
			}
			
		});		
	}

	function enableDrop(id) {
		var action = '';
		if ($('#dropShopSelect_'+id).is(':visible')) {
			$('#dropShopSelect_'+id).hide();
			action = false;
			$('.btnSelectDrop4').toggleClass('btn-success').toggleClass('btn-info');
			// turn off all shops
		} else {
			$('#dropShopSelect_'+id).show();
			$('.btnSelectDrop4').toggleClass('btn-success').toggleClass('btn-info');
			action = true;
			// turn on all possible shops
		}

		$.ajax({
			url: '<?php echo $cfg['options']['siteurl'];?>/gears/ajax.selectDropShopMass.php',
			data: {
				drop_id: id, 
				shipper_id: <?php echo $route->param; ?>,
				action: action,
			},
			dataType: 'JSON',
			method: 'POST',
			success: function(data) {
				if (data.type == 'error') {
					notify('error','Ошибка',data.text);
				} else {
					notify('info','Ошибка',data.text);
					for(i in data.shops) {
						$('.shopSelector_'+data.shops[i]+'_'+id).removeClass('text-danger').addClass('text-success');
					}
				}

				console.log(data);
			},
			error: function(v1,v2,v3) {
				console.log(v1,v2,v3);
			}
			
		});				
	}

</script>

<div class="panel panel-default">
	<div class="panel-heading">Выбор</div>
	<div class="panel-body">
		<p>Выбор курьера для отправителя</p>
	</div>
	<table class="table">
		<thead>
			<tr>
				<th>Курьер</th>
				<th>Адрес</th>
				<th>Выполнено / В процессе</th>
				<th>Выбрать</th>
			</tr>
		</thead>
		<tbody>
<?php 

	$q = "SELECT * FROM `shops` ORDER BY `shop_name`";
	$shops = $db->query($q);
	
	$drops = getUsersListRank(3);
	foreach($drops as $drop) {
		
		// смотрим было ли что-то выбрано?
		$q = "SELECT * FROM `drops2shippers` WHERE `drop_id` = ".$drop->id." AND `shipper_id` = ".$route->param;
		$dropshopselector = $db->query($q);
		if (isset($dropshopselector[0])) { 
			$isdropselected = 'btn-success'; 
			$isShowShops = 'table-row'; 
		} else { 
			$isdropselected = 'btn-info'; 
			$isShowShops = 'none'; 
		}
		
		$totalPackages = getDropCompleatePkgs($drop->id) + getDropInworkPkgs($drop->id);
		echo '<tr><td>'.$drop->first_name.' '.$drop->middle_name.' '.$drop->last_name.'</td><td>'.$drop->address.' '.$drop->city.' '.$drop->state.' '.$drop->zip.' '.$drop->country.'</td><td align=center>'.$totalPackages.'</td><td align=center><span class="btn '.$isdropselected.' btn-xs btnSelectDrop'.$drop->id.'" onclick="enableDrop('.$drop->id.');">select</span></td></tr>';
		echo '<tr id="dropShopSelect_'.$drop->id.'" style="display:'.$isShowShops.';"><td colspan=4>';
		if (isset($shops[0])) {
			// перечисляем все шопы
			foreach($shops as $shop) {
				// если в этом дропе есть выбранные шопы
				if (isset($dropshopselector)) {
					// перечисляем все выбранные
					$color = 'text-danger';
					foreach($dropshopselector as $v) {
						if ($v->shop_id==$shop->id) {
							$color = 'text-success';
						}
					}
				} else {
					// если выбраных шопов нет то показываем обычные
					$color = 'text-danger';
				}
				echo '<span class="badge cursor-pointer '.$color.' shopSelector_'.$shop->id.'_'.$drop->id.'" onclick="selectShop('.$shop->id.', '.$drop->id.');">'.$shop->shop_name.'</span> ';
			}
			
		}

		echo '</td></tr>';
	}
?>
		</tbody>
	</table>
</div>