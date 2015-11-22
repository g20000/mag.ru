<?php
// смотрим можно ли 
if ($user['rankname']!='admin' && $user['rankname']!='support' && $user['rankname']!='shipper') {
	exit('You are not admin!');
}
?>
<script>
	$(document).ready(function(){
		$('#saveRList').click(function(){

			var l = $('#list').val();
			console.log(l);
			$.ajax({
				url: '<?php echo $cfg['options']['siteurl'];?>/gears/ajax.receptlistsave.php',
				method: 'POST',
				dataType: 'JSON',
				data: {
					text: l
				},
				success: function(data) {
					if (data.type == 'error') {
						notify('error','Сохранен',data.text);
					} else {
						notify('info','Сохранен',data.text);
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

<h1 class="page-header">Правила работы</h1>
<?php if ($user['rankname']=='admin') :?>
	<textarea class="form-control" id="list"><?php echo isset($cfg['options']['rlist']) ? $cfg['options']['rlist'] : '';?></textarea>
	<span class="btn btn-success pull-right" id="saveRList">Сохранить</span>
<?php else: ?>
	<span>
		<?php
		$buf = $cfg['options']['rlist'];
		$buf = str_replace("&#10;", " ", $buf);
		$buf = str_replace(",", " ", $buf);
		$receiptArray = explode(" ", $buf);
		$receiptColumn = '';
		foreach($receiptArray as $receipt){
			if($receipt != ""){
				$receiptColumn .= $receipt . '<br/>';
			}
		}
		echo $receiptColumn;
		?>
	</span>
<?php endif ?>