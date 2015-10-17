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
		console.log(id);
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
      <input type="checkbox"> Иконка меню по умолчанию
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
			echo '<p><span contenteditable="true">'.$u->name.'</span><button type="button" class="btn btn-danger btn-xs" onclick="deleteCategory('.$u->pkg_cat_ddlist_id.')">Удалить</button></p>';
		}
	}
?>