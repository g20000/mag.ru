<!--
	TODO: Сделать уведомление пользователю об успешном добавлении категории
-->

<script>
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

<h1 class="page-header">Редактирование пунктов товаров выпадающего списка меню</h1>

<form>
  <div class="form-group">
    <label for="newMenuItem">Введите название нового пункта меню</label>
    <input type="text" class="form-control" id="newMenuItem" placeholder="Пункт меню">
  </div>
  <div class="form-group">
    <label for="exampleInputFile">Файл изображения</label>
    <input type="file" id="exampleInputFile">
  </div>
  <div class="checkbox">
    <label>
      <input type="checkbox" value="true"> Иконка меню по умолчанию
    </label>
  </div>
  <button type="button" class="btn btn-default" onclick="addCategory()">Добавить</button>
</form>

<hr>

<h1 class="page-header">Список существующих пунктов меню</h1>

<?php
	$menuItemList = getPackCategoriesForAsideMenu();
	if(isset($menuItemList)){
		foreach($menuItemList as $u){
			?>
			<p><span contenteditable="true"><?php echo $u->name ?></span><button type="button" class="btn btn-danger btn-xs" onclick="deleteCategory(<?php echo $u->pkg_cat_ddlist_id ?>)">Удалить</button>
			<button type="button" class="btn btn-success btn-xs" onclick="editCategory(<?php echo $u->pkg_cat_ddlist_id ?>,<?php echo "'".$u->name."'" ?>)">Сохранить</button></p>
			<?php
		}
	}
?>