<?php
if ($user['rankname']=='drop') {
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
						<button type="button" class="btn btn-primary">Accept</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
		<script>
			var isNewPkg=false;	
			function checkNewPkg() {
				if (isNewPkg==true) { return false; }
				$.ajax({
					url: '<?php echo $cfg['options']['siteurl'] ?>/gears/ajax.checkNewPkgDrop.php',
					data: { id: <?php echo $user['id']; ?> },
					dataType: 'JSON',
					method: 'POST',
					success: function(data) {
						if (data.type=='error' || data.type!='ok') {
							notify('error',data.type,data.text);
							isNewPkg=false;
						} else {
							isNewPkg=true;
							var pkgView = '';
							pkgView += '<strong>'+data.text.item+'</strong><br>';
							pkgView += data.text.action+"<br>";
							pkgView += data.text.price+' '+data.text.currency+"<br>";
							$('.myModal .modal-body').html(pkgView);
							$('.myModal .btn-primary').bind('click',function(){
								acceptNewPkg(data.text.id);
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
			
			function acceptNewPkg(id) {
				$.ajax({
					url: '<?php echo $cfg['options']['siteurl'] ?>/gears/ajax.dropAcceptPkg.php',
					data: { pkg_id: id },
					dataType: 'JSON',
					method: 'POST',
					success: function(data) {
						console.log(data.text);
						if (data.type=='ok') {
							document.location.href='<?php echo $cfg['options']['siteurl'] ?>/dropPkgInfo/'+id+'/inbox';
						} else {
							notify('error',data.type,data.text);
						}
					},
					error: function(v1,v2,v3) {
						console.log(v1,v2,v3);
					}
				});
			}
			
		</script>
<?php
}
?>