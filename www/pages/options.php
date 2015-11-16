<!--
<script>
	$('#buildPackMenu').on('click', function( e ) {
		e.preventDefault();
		$.get($(this).attr('href'), function(data) {
			console.log(data);
		});
	});
</script>
-->
<h1 class="page-header">Опции</h1>
<a href="<?php echo $cfg['options']['siteurl']; ?>/buildPackagesMenu" class="btn btn-info">Настройка списка товаров</a>