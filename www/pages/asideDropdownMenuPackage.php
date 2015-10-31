<?php
if(isset($_POST['replaceIcon'], $_POST['catId']))
{
	$id = intval($_POST['catId']);
	
	$filePath = __DIR__.'/../../design/img/ico/'.$id.'.png';
	if(isset($_FILES['newIcon'], $_FILES['newIcon']['tmp_name']) AND is_file($_FILES['newIcon']['tmp_name']))
	{
		if (move_uploaded_file($_FILES['newIcon']['tmp_name'], $filePath))
		{
			$iconSourcePath = $_FILES['newIcon']['name'];
			$path_parts = pathinfo($iconSourcePath);
			echo $path_parts['basename'];
			
			$q = "UPDATE `pkg_cat_aside_menu` SET `img_source`= '".$path_parts['filename']."' WHERE pkg_cat_ddlist_id=".$id;
			//$q = "INSERT INTO `pkg_cat_aside_menu` (img_source) VALUES('".$path_parts['basename']."') WHERE";
			$db->query($q);
			echo "Иконка успешно изменена\n";
		}
	}
}
?>

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
    <input type="text" class="form-control options-input" id="newMenuItem" placeholder="Пункт меню">
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
			<p class="option-button-group"><span contenteditable="true" id=<?php echo $u->pkg_cat_ddlist_id ?>><?php echo $u->name ?></span><button type="button" class="btn btn-danger btn-xs option-delete" onclick="deleteCategory(<?php echo $u->pkg_cat_ddlist_id ?>)">Удалить</button>
			<button type="button" class="btn btn-success btn-xs option-save" onclick="editCategory(<?php echo $u->pkg_cat_ddlist_id ?>)">Сохранить</button></p>
			<form action="" method="POST" enctype="multipart/form-data">
				<input type=file name="newIcon">
				<input type="hidden" name="catId" value="<?php echo $u->pkg_cat_ddlist_id ?>">
				<input type="submit" name="replaceIcon" value="Изменить иконку">
			</form>
			</p>
			<?php
		}
	}
?>