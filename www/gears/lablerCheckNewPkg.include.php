<?php
if ($user['rankname']=='labler') {
?>
	<div class="modal fade myModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">New task!</h4>
					</div>
					<div class="modal-body">						
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary">View</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
		<script>
			var isNewPkg=false;	
			function checkNewPkg() {
				
				$.ajax({
					url: '<?php echo $cfg['options']['siteurl'] ?>/gears/ajax.checkNewPkgLabler.php',
					data: { id: <?php echo $user['id']; ?> },
					dataType: 'JSON',
					method: 'POST',
					success: function(data) {
						if (isNewPkg==true || '<?php echo $route->param;?>'==data.text.id) { return false; }
						if (data.type=='error' || data.type!='ok') {
							notify('error',data.type,data.text);
							isNewPkg=false;
						} else {
							isNewPkg=true;
							var pkgView = '';
							pkgView += 'Пользователь <strong>'+data.text.userInfo+'</strong> получил товар<br>';
							pkgView += '<strong>'+data.text.item+'</strong><br>';
							pkgView += data.text.action+"<br>";
							pkgView += data.text.price+' '+data.text.currency+"<br>";
							$('.myModal .modal-body').html(pkgView);
							$('.myModal .btn-primary').bind('click',function(){
								document.location.href='<?php echo $cfg['options']['siteurl'];?>/lablerPkgInfo/'+data.text.id+'/inbox';
							});
							$('.myModal').modal('toggle');
							//console.log(data.text);
						}
					},
					error: function(v1,v2,v3) {
						//console.log(v1,v2,v3);
					}
				});
			}
			
			var lookTimer = setInterval(function(){
				checkNewPkg();
			}, 2000);
			
			
		</script>
<?php 
}
?>