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
	
	function stop_executeDeletingSubCategory(id){
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
	
	function executeDeletingSubCategory(event){
		var menuId = $('#menuItem').val();
		$.ajax({
					url: '<?php echo $cfg['options']['siteurl']; ?>/gears/ajax.deleteSubCategory.php',
					type: 'POST',
					dataType: 'JSON',
					data: {
						idItem:event.data.idSubCat,
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
		$('.deleteSubCatModal .modal-footer .btn-success').off("click");
	}
	
	function stop_deleteSubCategory(id){		
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
	
	function deleteSubCategory(id){		
		/*$.confirm({
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
		});*/
		$('.deleteSubCatModal').modal({
		  keyboard: false,
		  backdrop: 'static',
		  show: true,
		});
		$('.deleteSubCatModal .modal-footer .btn-success').on("click", {idSubCat: id}, executeDeletingSubCategory);
	}
	
	function executeSavingSubCategory(event){
		var id = event.data.idSubCat;
		var nameSelect = "span#" + event.data.idSubCat;
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
		$('.editSubCatModal .modal-footer .btn-success').off("click");
	}
	
	function editSubCategory(idCat){
		/*$.confirm({
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
		});*/
		$('.editSubCatModal').modal({
		  keyboard: false,
		  backdrop: 'static',
		  show: true,
		});
		$('.editSubCatModal .modal-footer .btn-success').on("click", {idSubCat: idCat}, executeSavingSubCategory);
		
	}
</script>

<div class="modal fade editSubCatModal"><!--modal fade editSubCatModal-->
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><h1>Подтверждение изменений<h1></h4>
			</div>
			<div class="modal-body">
				<p>Сохранить изменения?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Нет</button>
				<button type="button" class="btn btn-success btn-sm" data-dismiss="modal">Да</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade deleteSubCatModal"><!--modal fade deleteSubCatModal-->
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><h1>Подтверждение удаления<h1></h4>
			</div>
			<div class="modal-body">
				<p>Вы решили удалить пункт. <br />После удаления его нельзя будет восстановить! Продолжаем?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Закрыть</button>
				<button type="button" class="btn btn-success btn-sm" data-dismiss="modal">Удалить</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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
    <input type="text" class="form-control options-input" id="newMenuItem" placeholder="Пункт меню">
  </div>
  <button type="button" class="btn btn-default" onclick="addSubCategory()">Добавить</button>
</form>

<hr>

<h2 class="page-header">Список существующих подкатегорий товаров</h2>
<section id="subCategories"></section>
