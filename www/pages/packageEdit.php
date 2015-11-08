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
	
	function executeDeletingPackage(event){
		var subMenuId = $('#subMenuItem').val();
		$.ajax({
			url: '<?php echo $cfg['options']['siteurl']; ?>/gears/ajax.deletePackageFromDDList.php',
			type: 'POST',
			dataType: 'JSON',
			data: {
				idItem:event.data.idSubCat,
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
		$('.deletePackageModal .modal-footer .btn-success').off("click");
	}
	
	function deletePackage(id){		
		/*$.confirm({
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
		});*/
		$('.deletePackageModal').modal({
		  keyboard: false,
		  backdrop: 'static',
		  show: true,
		});
		$('.deletePackageModal .modal-footer .btn-success').on("click", {idSubCat: id}, executeDeletingPackage);
	}
	
	function executeSavingPackage(event){
		var id = event.data.idPack;
		var selectedName = "span#" + event.data.idPack;
		var selectedPercent = "span#percentCell" + event.data.idPack;
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
		$('.editPackageModal .modal-footer .btn-success').off("click");
	}
	
	function editPackage(idPackage){
		/*$.confirm({
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
		});*/
		$('.editPackageModal').modal({
		  keyboard: false,
		  backdrop: 'static',
		  show: true,
		});
		$('.editPackageModal .modal-footer .btn-success').on("click", {idPack: idPackage}, executeSavingPackage);
	}
</script>

<div class="modal fade editPackageModal"><!--modal fade editSubCatModal-->
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

<div class="modal fade deletePackageModal"><!--modal fade deleteSubCatModal-->
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