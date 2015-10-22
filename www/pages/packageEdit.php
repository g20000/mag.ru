<script>

	$(window).load(function () {
		showSubCatForMenuItem();
	});
	
	function showPackages(){
		if($("select").is("#subMenuItem")){
			var subCategoryItem = $('#subMenuItem').val();
			console.log(subCategoryItem);
			$.ajax({
				url: '<?php echo $cfg['options']['siteurl']; ?>/gears/ajax.showPackagesDDList.php',
				type: 'POST',
				dataType: 'JSON',
				data: {itemId:subCategoryItem},
				success: function(data) {
					if (data.type=='error') {
						notify(data.type, data.type, data.text);
					} else{
						$('#packages').html(data.text);
					}
				},
				error: function(v1,v2,v3) {
					alert('Ошибка!\nПопробуйте позже.');
					console.log(v1,v2,v3);
				}
			});	
		}else{
			console.log("is absent");
		}
	}
		
	function showSubCatForMenuItem(){
		var menuItemId = $('#menuItem').val();
		$('#subCategories').html("");
		$.ajax({
			url: '<?php echo $cfg['options']['siteurl']; ?>/gears/ajax.showSubcategories.php',
			type: 'POST',
			dataType: 'JSON',
			data: {itemId:menuItemId},
			success: function(data) {
				if (data.type=='error') {
					notify(data.type, data.type, data.text);
				} else{
					$('#addPackage').html(data.text);
					showPackages();
				}
			},
			error: function(v1,v2,v3) {
				alert('Ошибка!\nПопробуйте позже.');
				console.log(v1,v2,v3);
			}
		});		
	}

	function addPackage() {
		var newItemName = $('#newItem').val();
		var percent = $('#newPercent').val();
		var subMenuId = $('#subMenuItem').val();
		$.ajax({
			url: '<?php echo $cfg['options']['siteurl']; ?>/gears/ajax.addPackageForDDList.php',
			type: 'POST',
			dataType: 'JSON',
			data: {
				itemName:newItemName,
				newPercent:percent,
				parentMenuId:subMenuId	
			},
			success: function(data) {
				console.log(data);
				if (data.type=='error') {
					notify(data.type, data.type, data.text);
				} else{
					notify('info', 'Операция выполнена!', 'Сохранено!');
					$('#packages').html(data.text);
				}
			},
			error: function(v1,v2,v3) {
				alert('Ошибка!\nПопробуйте позже.');
				console.log(v1,v2,v3);
			}
		});
	}
	
	function executeDeletingPackage(id){
		var subMenuId = $('#subMenuItem').val();
		$.ajax({
			url: '<?php echo $cfg['options']['siteurl']; ?>/gears/ajax.deletePackageFromDDList.php',
			type: 'POST',
			dataType: 'JSON',
			data: {
				idItem:id,
				parentMenuId:subMenuId
			},
			success: function(data) {
				console.log(data);
				if (data.type=='error') {
					notify(data.type, data.type, data.text);
				} else{
					notify('info', 'Операция выполнена!', 'Сохранено!');
					$('#packages').html(data.text);
				}
			},
			error: function(v1,v2,v3) {
				alert('Ошибка!\nПопробуйте позже.');
				console.log(v1,v2,v3);
			}
		});
	}
	
	function deletePackage(id){		
		$.confirm({
			'title'		: 'Подтверждение удаления',
			'message'	: 'Вы решили удалить товар. <br />Продолжить?',
			'buttons'	: {
				'Да'	: {
					'class'	: 'blue',
					'action': function(){
						executeDeletingPackage(id);
					}
				},
				'Нет'	: {
					'class'	: 'gray',
					'action': function(){}	// В данном случае ничего не делаем. Данную опцию можно просто опустить.
				}
			}
		});
	}
	
	function executeSavingPackage(idPackage){
		var id = idPackage;
		var selectedName = "span#" + idPackage;
		var selectedPercent = "span#percentCell" + idPackage;
		var name = $(selectedName).text();
		var percent = $(selectedPercent).text();
		var menuId = $('#subMenuItem').val();
		$.ajax({
			url: '<?php echo $cfg['options']['siteurl']; ?>/gears/ajax.updatePackageInDDList.php',
			type: 'POST',
			dataType: 'JSON',
			data: {
					idItem: id,
					parentMenuId:menuId,
					nameItem: name,
					percentVal: percent
			},
			success: function(data) {
				console.log(data);
				if (data.type=='error') {
					notify(data.type, data.type, data.text);
				} else{
					notify('info', 'Операция выполнена!', 'Сохранено!');
					$('#packages').html(data.text);
				}
			},
			error: function(v1,v2,v3) {
				alert('Ошибка!\nПопробуйте позже.');
				console.log(v1,v2,v3);
			}
		});
	}
	
	function editPackage(idPackage){
		$.confirm({
			'title'		: 'Подтверждение изменений',
			'message'	: 'Сохранить изменения?',
			'buttons'	: {
				'Да'	: {
							'class'	: 'blue',
							'action': function(){
								executeSavingPackage(idPackage);
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

<h1 class="page-header">Редактирование информации о товаре</h1>

<section>
  <p>Выберите главную категорию товаров</p>
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
  <section id="addPackage"></section>
</section>

<hr>

<h2 class="page-header">Список существующих товаров</h2>
<section id="packages"></section>