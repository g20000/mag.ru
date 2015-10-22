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
							notify('info',data.status,data.text);
							document.location.reload();
						}
					},
					error: function(v1,v2,v3) {
						alert('Ошибка!\nПопробуйте позже.');
						console.log(v1,v2,v3);
					}
				});
	}
	
	function executeDeleting(id){
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
					notify('info', data.type, data.text);
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
		$.confirm({
			'title'		: 'Подтверждение удаления',
			'message'	: 'Вы решили удалить пункт. <br />После удаления его нельзя будет восстановить! Продолжаем?',
			'buttons'	: {
				'Да'	: {
					'class'	: 'blue',
					'action': function(){
						executeDeleting(id);
					}
				},
				'Нет'	: {
					'class'	: 'gray',
					'action': function(){}	// В данном случае ничего не делаем. Данную опцию можно просто опустить.
				}
			}
		});
	}
	
	function executeSaving(idCat){
		var id = idCat;
		var nameSelect = "span#" + idCat;
		var name = $(nameSelect).text();
		$.ajax({
			url: '<?php echo $cfg['options']['siteurl']; ?>/gears/ajax.updatePkgCatAsideMenu.php',
			type: 'POST',
			dataType: 'JSON',
			data: {
					idItem: idCat,
					nameItem: name
					
			},
			success: function(data) {
				console.log(data);
				if (data.type=='error') {
					notify(data.type, data.type, data.text);
				} else{
					notify('info', data.type, data.text);
					document.location.reload();
				}
			},
			error: function(v1,v2,v3) {
				alert('Ошибка!\nПопробуйте позже.');
				console.log(v1,v2,v3);
			}
		});
	}
	
	function editCategory(idCat){
		$.confirm({
			'title'		: 'Подтверждение изменений',
			'message'	: 'Сохранить изменения?',
			'buttons'	: {
				'Да'	: {
							'class'	: 'blue',
							'action': function(){
								executeSaving(idCat);
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

<h1 class="page-header">Редактирование пунктов товаров выпадающего списка меню</h1>

<div id="myModalBox" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Заголовок модального окна -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Подтверждение</h4>
      </div>
      <!-- Основной текст сообщения -->
      <div class="modal-body">
        <p>Вы хотите сохранить изменения, сделанные в документе перед закрытием?</p>
        <p class="text-warning"><small>Если Вы не сохраните, изменения будут потеряны.</small></p>
      </div>
      <!-- Нижняя часть модального окна -->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
        <button type="button" class="btn btn-primary">Сохранить изменения</button>
      </div>
    </div>
  </div>
</div>

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
			<p><span contenteditable="true" id=<?php echo $u->pkg_cat_ddlist_id ?>><?php echo $u->name ?></span><button type="button" class="btn btn-danger btn-xs" onclick="deleteCategory(<?php echo $u->pkg_cat_ddlist_id ?>)">Удалить</button>
			<button type="button" class="btn btn-success btn-xs" onclick="editCategory(<?php echo $u->pkg_cat_ddlist_id ?>)">Сохранить</button></p>
			<?php
		}
	}
?>