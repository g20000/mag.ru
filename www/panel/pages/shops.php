<?php
// смотрим можно ли 
if ($user['rankname']!='admin' && $user['rankname']!='support') {
	exit('You are not admin!');
}
?>

<script>
	function addShop(){
		var name = $('#shop_name').val();
		var url = $('#shop_url').val();
		$.ajax({
			url: '<?php echo $cfg['options']['siteurl'] ?>/gears/ajax.shopAdd.php',
			data: { shop_name: name, shop_url: url },
			dataType: 'JSON',
			type: 'POST',
			success: function(data) { 
				if (data.type == 'error') {
					notify('error','Ошибка',data.text);
				} else {
					notify('info','Успех',data.text);
					$('#shopListTable tbody').append('<tr data-shop-id="'+data.id+'"><td>'+name+' </td><td style="width:8em;text-align:center;"><i class="fa fa-times text-danger cursor-pointer" onclick="deleteShop('+data.id+', \''+name+'\')"></i></td></tr>');
				}
			},
			error: function(v1,v2,v3) { console.log(v1,v2,v3); },
		});		
	}
	function deleteShop(shop_id, shop_name) {
		
		if (!confirm('Точно удалить '+shop_name)) {
			return false;
		}	
		
		$.ajax({
			url: '<?php echo $cfg['options']['siteurl'] ?>/gears/ajax.shopDelete.php',
			data: { shop_id: shop_id, },
			dataType: 'JSON',
			type: 'POST',
			success: function(data) { 
				if (data.type == 'error') {
					notify('error','Ошибка',data.text);
				} else {
					notify('info','Успех',data.text);
					$('#shopListTable tbody').find('tr').each(function(){
						if ($(this).data('shop-id')==shop_id) {
							$(this).remove();
						}
					});
				}
			},
			error: function(v1,v2,v3) { console.log(v1,v2,v3); },
		});
	}
</script>
<h1 class="page-header">Магазины</h1>

<div class="container-fluid">
	<div class="row">
		<div class="col-lg-6">
			<?php
				$q = "SELECT * FROM `shops` ORDER BY `shop_name`";
				$res = $db->query($q);
				if (isset($res[0])) {
					echo '<table class="table" id="shopListTable">';
					echo '<thead><tr><th>Shop name</th><th class="text-center">действие</th></tr></thead><tbody>';
					foreach($res as $k=>$v) {
						echo '<tr data-shop-id="'.$v->id.'">';
						echo '<td>'.$v->shop_name.' </td>';
						echo '<td style="width:8em;text-align:center;"><i class="fa fa-times text-danger cursor-pointer" onclick="deleteShop('.$v->id.', \''.$v->shop_name.'\')"></i></td>';
						echo '</tr>';
					}
					echo "</tbody></table>";
				}
			?>
		</div>
		<div class="col-lg-6">
			<form>
				<div class="form-group">
					<label class="label" for="shop-name">Название</label>
					<input class="form-control" type="text" id="shop_name"><br>
					<label class="label" for="shop-url">URL</label>
					<input class="form-control" type="url" id="shop_url" placeholder="http://" value="http://"><br>
					<span class="btn btn-success" onclick="addShop();">Добавить</span>
				</div>
			</form>
		</div>
	</div>
</div>

<?php
	
?>