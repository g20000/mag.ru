<!--
	TODO: Сделать уведомление пользователю об успешном добавлении категории
-->

<script>

	$(window).load(function () {
		showSubCatForMenuItem();
		//$('#subMenuItem').load(showPackages());
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
							console.log(data.type, data.text);
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
							$('#subCategories').html(data.text);
						}
					},
					error: function(v1,v2,v3) {
						alert('Ошибка!\nПопробуйте позже.');
						console.log(v1,v2,v3);
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