<!--
	TODO: Сделать уведомление пользователю об успешном добавлении категории
-->

<script>
	function showSubCatForMenuItem(){
		console.log($('#menuItem').val());
		var menuItemId = $('#menuItem').val();
		console.log(menuItemId);
		$('#subCategories').html("");
		$.ajax({
					url: '<?php echo $cfg['options']['siteurl']; ?>/gears/ajax.showSubPkgCategories.php',
					type: 'POST',
					dataType: 'JSON',
					data: {itemId:menuItemId},
					success: function(data) {
						if (data.type=='error') {
							notify(data.type, data.type, data.text);
						} else{
							//console.log("'<?=implode($_POST) ?>'");
							console.log(data.text);
							$('#subCategories').html(data.text);
							//document.location.reload();
						}
					},
					error: function(v1,v2,v3) {
						alert('Ошибка!\nПопробуйте позже.');
						console.log(v1,v2,v3);
					}
				});
		//$.post("'<?php echo $cfg['options']['siteurl']; ?>/gears/ajax.showSubPkgCategories.php'", {itemId:menuItemId});
		//console.log("'<?=$_POST['itemId'] ?>'");		
	}

	function addCategory() {
		var menuItem = $('#newMenuItem').val();
		$.ajax({
					url: '<?php echo $cfg['options']['siteurl']; ?>/gears/ajax.addPckCatAsideMenu.php',
					type: 'POST',
					dataType: 'JSON',
					data: {itemName:menuItem},
					success: function(data) {
						console.log(data);
						if (data.type=='error') {
							notify(data.type, data.type, data.text);
						} else{
							document.location.reload();
						}
					},
					error: function(v1,v2,v3) {
						alert('Ошибка!\nПопробуйте позже.');
						console.log(v1,v2,v3);
					}
				});
	}
	
	function deleteCategory(id){
		$.ajax({
					url: '<?php echo $cfg['options']['siteurl']; ?>/gears/ajax.deletePkgCatAsideMenu.php',
					type: 'POST',
					dataType: 'JSON',
					data: {idItem:id},
					success: function(data) {
						console.log(data);
						if (data.type=='error') {
							notify(data.type, data.type, data.text);
						} else{
							document.location.reload();
						}
					},
					error: function(v1,v2,v3) {
						alert('Ошибка!\nПопробуйте позже.');
						console.log(v1,v2,v3);
					}
				});
	}
	
	function editCategory(idCat, nameCat){
		var id = idCat;
		var name = nameCat;
		console.log(idCat);
		console.log(nameCat);
		$.ajax({
					url: '<?php echo $cfg['options']['siteurl']; ?>/gears/ajax.updatePkgCatAsideMenu.php',
					type: 'POST',
					dataType: 'JSON',
					data: {
							idItem: idCat,
							nameItem: nameCat
							
					},
					success: function(data) {
						console.log(data);
						if (data.type=='error') {
							notify(data.type, data.type, data.text);
						} else{
							document.location.reload();
						}
					},
					error: function(v1,v2,v3) {
						alert('Ошибка!\nПопробуйте позже.');
						console.log(v1,v2,v3);
					}
				});
	}
</script>

<h1 class="page-header">Редактирование подкатегории товаров</h1>

<form>
  <p>Выберите пункт меню для категории товаров</p>
  <select id="menuItem" onchange="showSubCatForMenuItem()">
  	<?php
		//echo $_SESSION;
		$itemList = getPackCategoriesForAsideMenu();
		if(isset($itemList)){
			foreach($itemList as $u){
				?>
					<option value="<?php echo $u->pkg_cat_ddlist_id ?>"><?php echo $u->name ?></option>
					<?php
				}
			}
	?>
  </select>
  <div class="form-group">
    <label for="newMenuItem">Введите название новой подкатегории товаров</label>
    <input type="text" class="form-control" id="newMenuItem" placeholder="Пункт меню">
  </div>
  <button type="button" class="btn btn-default" onclick="addCategory()">Добавить</button>
</form>

<hr>

<h2 class="page-header">Список существующих подкатегорий товаров</h2>
<!--<?php
	$menuItemList = getSubPkgCategForDDList();
	if(isset($menuItemList)){
		foreach($menuItemList as $u){
			?>
			<p><span contenteditable="true"><?php echo $u->name ?></span><button type="button" class="btn btn-danger btn-xs" onclick="deleteCategory(<?php echo $u->pkg_cat_ddlist_id ?>)">Удалить</button>
			<button type="button" class="btn btn-success btn-xs" onclick="editCategory(<?php echo $u->pkg_cat_ddlist_id ?>,<?php echo "'".$u->name."'" ?>)">Сохранить</button></p>
			<?php
		}
	}
?>-->
<section id="subCategories"></section>
