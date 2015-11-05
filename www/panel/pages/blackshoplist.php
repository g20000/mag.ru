<?php
// смотрим можно ли 
if ($user['rankname']!='admin' && $user['rankname']!='support' && $user['rankname']!='shipper') {
	exit('You are not admin!');
}
?>
<script>
	$(document).ready(function(){
		$('#saveBSList').click(function(){

			var l = $('#list').val();
			console.log(l);
			$.ajax({
				url: '<?php echo $cfg['options']['siteurl'];?>/gears/ajax.blackshopslistsave.php',
				method: 'POST',
				dataType: 'JSON',
				data: {
					text: l
				},
				success: function(data) {
					if (data.type == 'error') {
						console.log(data);
						notify('error','Сохранили',data.text);
					} else {
						console.log(data);
						notify('info','Сохранили',data.text);
					}

					console.log(data);
				},
				error: function(v1,v2,v3,data) {
					console.log(data);
					console.log(v1,v2,v3);
				}
			})

		});		
	});
	


</script>

<h1 class="page-header">Черные списки магазинов</h1>
<?php if ($user['rankname']=='admin') :?>
    <textarea class="form-control" id="list"><?php echo isset($cfg['options']['bslist']) ? $cfg['options']['bslist'] : '';?></textarea>
    <span class="btn btn-success pull-right" id="saveBSList">Сохранить</span>
<?php else: ?>
	<span>
		<?php
			//echo $cfg['options']['bslist'];
			$buf = $cfg['options']['bslist'];
			$buf = str_replace("&#10;", " ", $buf);
			$buf = str_replace(",", " ", $buf);
			//echo $cfg;
			//exit();
			$blackShopArray = explode(" ", $buf);
			//var_dump($blackShopArray);
			//exit();
			//$blackShopArray = preg_split("~[|!,\s\n\b\Q\E]+~", $cfg['options']['bslist']);
			//$blackShopArray = preg_split("~[^а-яА-Яa-zA-Z0-9.]+~", $cfg['options']['bslist']);
			//$blackShopArray = preg_split('~[|!,\s\n\]+~', $cfg['options']['bslist']);
			//$blackShopArray = preg_split('/[\s,]+/(['<br>'])', $cfg['options']['bslist'], 0, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
			//$blackShopArray = str_split($cfg);
			$blackShopColumn = '';
			foreach($blackShopArray as $shop){
				if($shop != ""){
					$blackShopColumn .= $shop . '<br/>';
				}
			}
			//var_dump($blackShopArray);
			echo $blackShopColumn;
		?>
	</span>
<?php endif ?>  