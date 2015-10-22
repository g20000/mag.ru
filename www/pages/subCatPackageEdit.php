<script>

	$(window).load(function () {
		showSubCatForMenuItem();
	});
	
	function showSubCatForMenuItem(){
		var menuItemId = $('#menuItem').val();
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
							$('#subCategories').html(data.text);
						}
					},
					error: function(v1,v2,v3) {
						alert('Ошибка!\nПопробуйте позже.');
						console.log(v1,v2,v3);
					}
				});		
	}

	function addSubCategory() {
		var menuId = $('#menuItem').val();
		var newMenuItemName = $('#newMenuItem').val();
		$.ajax({
					url: '<?php echo $cfg['options']['siteurl']; ?>/gears/ajax.addSubPkgCategories.php',
					type: 'POST',
					dataType: 'JSON',
					data: {
						itemName:newMenuItemName,
						parentMenuId:menuId	
					},
					success: function(data) {
						console.log(data);
						if (data.type=='error') {
							notify(data.type, data.type, data.text);
						} else{
							notify('info', 'Операция выполнена!', 'Сохранено!');
							$('#subCategories').html(data.text);
						}
					},
					error: function(v1,v2,v3) {
						alert('Ошибка!\nПопробуйте позже.');
						console.log(v1,v2,v3);
					}
				});
	}
	
	function executeDeletingSubCategory(id){
		var menuId = $('#menuItem').val();
		$.ajax({
					url: '<?php echo $cfg['options']['siteurl']; ?>/gears/ajax.deleteSubCategory.php',
					type: 'POST',
					dataType: 'JSON',
					data: {
						idItem:id,
						parentMenuId:menuId
					},
					success: function(data) {
						console.log(data);
						if (data.type=='error') {
							notify(data.type, data.type, data.text);
						} else{
							notify('info', 'Операция выполнена!', 'Сохранено!');
							$('#subCategories').html(data.text);
						}
					},
					error: function(v1,v2,v3) {
						alert('Ошибка!\nПопробуйте позже.');
						console.log(v1,v2,v3);
					}
				});
	}
	
	function deleteSubCategory(id){		
		$.confirm({
			'title'		: 'Подтверждение удаления',
			'message'	: 'Вы решили удалить пункт. <br />После удаления его нельзя будет восстановить! Продолжаем?',
			'buttons'	: {
				'Да'	: {
					'class'	: 'blue',
					'action': function(){
						executeDeletingSubCategory(id);
					}
				},
				'Нет'	: {
					'class'	: 'gray',
					'action': function(){}	// В данном случае ничего не делаем. Данную опцию можно просто опустить.
				}
			}
		});
	}
	
	function executeSavingSubCategory(idCat){
		var id = idCat;
		var nameSelect = "span#" + idCat;
		var name = $(nameSelect).text();
		var menuId = $('#menuItem').val();
		$.ajax({
			url: '<?php echo $cfg['options']['siteurl']; ?>/gears/ajax.updateSubCategory.php',
			type: 'POST',
			dataType: 'JSON',
			data: {
					idItem: id,
					parentMenuId:menuId,
					nameItem: name							
			},
			success: function(data) {
				console.log(data);
				if (data.type=='error') {
					notify(data.type, data.type, data.text);
				} else{
					notify('info', 'Операция выполнена!', 'Сохранено!');
					$('#subCategories').html(data.text);
				}
			},
			error: function(v1,v2,v3) {
				alert('Ошибка!\nПопробуйте позже.');
				console.log(v1,v2,v3);
			}
		});
	}
	
	function editSubCategory(idCat){
		$.confirm({
			'title'		: 'Подтверждение изменений',
			'message'	: 'Сохранить изменения?',
			'buttons'	: {
				'Да'	: {
							'class'	: 'blue',
							'action': function(){
								executeSavingSubCategory(idCat);
							}
				},
				'Нет'	: {
					'class'	: 'gray',
					'action': function(){}	// В данном случае ничего не делаем. Данную опцию можно просто опустить.
				}
			}
		});
	}
</script>

<h1 class="page-header">Редактирование подкатегории товаров</h1>

<form>
  <p>Выберите пункт меню для категории товаров</p>
  <select id="menuItem" onchange="showSubCatForMenuItem()">
  	<?php
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
  <button type="button" class="btn btn-default" onclick="addSubCategory()">Добавить</button>
</form>

<hr>

<h2 class="page-header">Список существующих подкатегорий товаров</h2>
<section id="subCategories"></section>
