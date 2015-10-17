<!--
	TODO: Сделать уведомление пользователю об успешном добавлении категории
-->

<script>
	function addCategory() {
		console.log($('#newMenuItem').val());
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
		/*var checkedCount = 0;
		var preGrouping = [];
		$('.group-chkbox').each(function(){
			if ($(this).is(':checked')) { checkedCount++; preGrouping.push($(this).data('pkg-id')); }
		});
		if (checkedCount>=2) {
			if (confirm('Точно удалить группу?')) {
				console.log(preGrouping);
				$.ajax({
					url: '<?php echo $cfg['options']['siteurl']; ?>/gears/ajax.groupPkgs.php',
					type: 'POST',
					dataType: 'JSON',
					data: {ids:preGrouping},
					success: function(data) {
						console.log(data);
						if (data.type=='error') {
							notify(data.type, data.type, data.text);
						} else {
							document.location.reload();
						}
					},
					error: function(v1,v2,v3) {
						alert('Ошибка!\nПопробуйте позже.');
						console.log(v1,v2,v3);
					}
				}); 
			}
		} else {
			alert('Как минимум две должны быть выбраны!');
		}*/
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
			echo '<p>'.$u->name.'</p><button type="button" class="btn btn-danger btn-xs"></button>';
			//echo '<button type="button" class="btn btn-danger btn-xs"></button>';
		}
	}
?>