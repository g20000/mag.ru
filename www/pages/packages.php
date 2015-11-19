<?php
// смотрим можно ли 
if ($user['rankname']!='admin' && $user['rankname']!='support' && $user['rankname']!='shipper') {
	exit('You are not admin!');
}

// watch for group checkboxes
$_page_scripts = "
	
	$(document).ready(function(){
		$(document).on('change', '.group-chkbox',  function() {
			var isCurrent = $(this).is(':checked');
			var isElse = false;
			var checkedCount = 0;
			$('.group-chkbox').each(function(){
				if ($(this).is(':checked')) { isElse = true; checkedCount++; }
			});
			if (checkedCount>=2) {
				$('.groupPkgBtn').removeAttr('disabled');
			} else {
				$('.groupPkgBtn').attr('disabled','disabled');
			}
		});
		
	});
";

?>
<script>
	$( document ).ready(function() {
	});

	function groupPkgs() {
		var checkedCount = 0;
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
		}


	}
	
	function ungroupPkgs(group_hash) {
		if (confirm('Are You sure to ungroup packages?')) {
				$.ajax({
					url: '<?php echo $cfg['options']['siteurl']; ?>/gears/ajax.unGroupPkgs.php',
					type: 'POST',
					dataType: 'JSON',
					data: {hash:group_hash},
					success: function(data) {
						document.location.reload();
					},
					error: function(v1,v2,v3) {
						alert('Ошибка!\nПопробуйте позже.');
						console.log(v1,v2,v3);
					}
				}); 
		}
	}
		
	function changePackageStatus(itemName, itemId) {
		var targetOption = '#' + itemId;
		if(itemName != "NO_INSTANCE_OR_NOT_ADMIN"){
			$.ajax({
				url: '<?php echo $cfg['options']['siteurl'] ?>/gears/ajax.changePackageStatus.php',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id: itemId,
					statusKind: $(targetOption).val()
				},
				success: function(data) {
					if (data.type=='ok') {
						//notify('info','Note!',data.text);
						document.location.reload();
					} else {
						notify('error','Замечание!',data.text);
					}
				},
				error: function(v1,v2,v3,data) {
					console.log(data);
					console.log(v1,v2,v3);
				}
			});
		}else{
			return;
		}
	}
	
	function changeAllPackageStatus(){
		var packagesStatusesAndIds = [];
		var packageStatusAndId = [];
		var targetOption;
		var packageId;
		$('tr').each(function(){
			targetOption = '#' + $(this).children('td').eq(8).text();
			packageId = $(this).children('td').eq(8).text();
			packageStatusAndId.push(packageId);//первый элемент - id
			packageStatusAndId.push($(targetOption).val());//второй элемент - значение выпадающего списка
			packagesStatusesAndIds.push(packageStatusAndId);
			packageStatusAndId = [];
		});
		console.log(packagesStatusesAndIds);
		$.ajax({
			url: '<?php echo $cfg['options']['siteurl'] ?>/gears/ajax.changePackageStatus.php',
			type: 'POST',
			dataType: 'JSON',
			data: {
				statusesAndIds: packagesStatusesAndIds
			},
			success: function(data) {
				if (data.type=='ok') {
					notify('info','Note!',data.text);
					document.location.href = '../';
					console.log(data.info);
				} else {
					notify('error','Замечание!',data.text);
				}
			},
			error: function(v1,v2,v3,data) {
				console.log(data);
				console.log(v1,v2,v3);
			}
		});
	}
</script>
<h1 class="page-header">Packages</h1>

<div class="pull-right" style="margin: 1em 0 1em 1em;">
	<span class="btn btn-warning groupPkgBtn" disabled="disabled" onclick="groupPkgs();">Группировать</span>
</div>

<div class="table-responsive">
<table class="table table-striped">
	<thead>
		<tr>
			<th>№</th>
			<th>Треки</th>
			<?php if($user['rankname']=='shipper') { ?>
				<th>Товар</th>
			<?php } ?>
			<th>Курьер</th>
			<th class="text-center">Статус</th>
			<th>Дата создания</th>
			<?php if($user['rankname']!='shipper') { ?>
			    <th>Товар</th> 
				<th>Отправитель</th>
				<?php if($user['rankname']!='admin') { ?>
					<th>Описание</th>
					<th>Покупатель</th>
					<th>Сортировщик</th>
					<th>Заметки</th>
					<th class='text-center'>Действия</th>
				<?php } ?>
			<?php } ?>
			<th></th>
			<th>Id</th>
		</tr>
	</thead>
	<tbody>
	<?php
		$pkg = getPackagesWithSortByDrop();
		if ($pkg!==false) {
			foreach($pkg as $k=>$v) {
				if (!is_array($v)) {
					$pkg_status = getPackageStatus($v->id);
				?>
					<tr data-user-id="<?php echo $v->id;?>">
						<td width="30px" style="max-width:220px;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;"><?php echo $v->pkg_drop_number;?></td>							
						<td><?php echo $v->track_type.' '.getTrackCheckLink($v->track_type,$v->track_num);?></td>
						<?php if($user['rankname']=='shipper') { ?>
							<td style="max-width:220px;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;"><?php echo $v->item;?></td>						
						<?php } ?>
						<td><?php 
								$res = getLinkToUserProfile($v->drop_id);
								$input_endoding = mb_detect_encoding($res);
								echo iconv($input_endoding, "UTF-8", $res);
							?>
						</td>
						<td>
							<?php if($user['rankname']=='admin'): ?>
								<select class="status_change" id="<?php echo $v->id ?>">
									<option value="norecipient" <?php if((isset($v->status_text))&&($v->status_text == "norecipient")) echo "selected = selected";?>>Получатель не найден</option>
									<option value="processing" <?php if((isset($v->status_text))&&($v->status_text == "processing")) echo "selected = selected";?>>Обработка</option>
									<option value="ondelivery" <?php if((isset($v->status_text))&&($v->status_text == "ondelivery")) echo "selected = selected";?>>На доставке</option>
									<option value="delivered" <?php if((isset($v->status_text))&&($v->status_text == "delivered")) echo "selected = selected";?>>Доставлено</option>
									<option value="resent" <?php if((isset($v->status_text))&&($v->status_text == "resent")) echo "selected = selected";?>>Переотправлен</option>
									<option value="refund" <?php if((isset($v->status_text))&&($v->status_text == "refund")) echo "selected = selected";?>>Возврат</option>
									<option value="filial" <?php if((isset($v->status_text))&&($v->status_text == "filial")) echo "selected = selected";?>>Филиал</option>
									<option value="shoprefund" <?php if((isset($v->status_text))&&($v->status_text == "shoprefund")) echo "selected = selected";?>>Возврат магазином</option>
								</select>
							<?php else: ?>
								<?php if((isset($v->status_text))&&($v->status_text == "norecipient")) echo "Получатель не найден";?>
								<?php if((isset($v->status_text))&&($v->status_text == "processing")) echo "Обработка";?>
								<?php if((isset($v->status_text))&&($v->status_text == "ondelivery")) echo "На доставке";?>
								<?php if((isset($v->status_text))&&($v->status_text == "delivered")) echo "Доставлено";?>
								<?php if((isset($v->status_text))&&($v->status_text == "resent")) echo "Переотправлен";?>
								<?php if((isset($v->status_text))&&($v->status_text == "refund")) echo "Возврат";?>
								<?php if((isset($v->status_text))&&($v->status_text == "filial")) echo "Филиал";?>
								<?php if((isset($v->status_text))&&($v->status_text == "shoprefund")) echo "Возврат магазином";?>
							<?php endif; ?>
						</td>
						<td  width="170px"><?php if(isset($pkg_status->time)){ 
									echo $pkg_status->time;
								  }
							?>
						</td>
						
						<?php if($user['rankname']=='admin') { ?>
							<td style="max-width:220px;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;"><?php echo $v->item;?></td>						
							<td><?php echo getLinkToUserProfile($v->shipper_id);?></td>
						<?php } ?>
												
						<td class="text-center">
							<a href="<?php echo $cfg['options']['siteurl'];?>/package/<?php echo $v->id;?>"><i class="fa fa-cogs"></i></a> | 
							<input type="checkbox" class="group-chkbox" data-placement="left" data-toggle="tooltip" data-pkg-id="<?php echo $v->id; ?>" title="Отметить для группировки">
						</td>			
						<td <?php if($user['rankname']=='admin'){ echo 'style="background-color:'.getPkgColor($v->id).' !important;"'; };?>><?php echo $v->id;?></td>
					</tr>

				<?php
				} else {
				?>
					<tr data-user-id="<?php echo $gv->id;?>" class="bg-unread">
						<td><?php echo $v[0]->id;?></td>
						<td style="max-width:220px;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;"><?php foreach($v as $gv) { echo "<span "; if($user['rankname']=='admin'){ echo 'style="background-color:'.getPkgColor($gv->id).' !important;"'; } echo ">".$gv->item.'</span><br>'; } ?></td>
						<td><?php foreach($v as $gv) { foreach (getPackageNotes($gv->id) as $n_user_type=>$n_text) { echo '<strong>'.$n_user_type.'</strong><p>'.(isset($n_text['public'])?$n_text['public']:'').'</p>'; }; } ?></td>
						<td><?php foreach($v as $gv) { echo $gv->action.'<br>'; } ?></td>
						<td class="text-center"><?php foreach($v as $gv) { echo iconPkgStatuses($gv->status_text).'<br />'; } ?></td>
						<td><?php foreach($v as $gv) { echo $gv->track_type.' '.$gv->track_num.'<br>'; } ?></td>
						<td><?php foreach($v as $gv) { echo getLinkToUserProfile($gv->shipper_id).'<br>'; } ?></td>
						<td><?php foreach($v as $gv) { echo getLinkToUserProfile($gv->drop_id).'<br>'; } ?></td>
						<td><?php foreach($v as $gv) { echo getLinkToUserProfile($gv->buyer_id).'<br>'; } ?></td>
						<td><?php foreach($v as $gv) { echo getLinkToUserProfile($gv->labler_id).'<br>'; } ?></td>
						<td><?php foreach($v as $gv) { $groupItemPkgStatus = getPackageStatus($gv->id); echo $groupItemPkgStatus->time."<br>"; } ;?></td>
						<td class="text-center">
							<a href="<?php echo $cfg['options']['siteurl'];?>/package/<?php echo $gv->id;?>"><i class="fa fa-cogs"></i></a> | 
							<i class="fa fa-expand" data-placement="left" data-toggle="tooltip" title="Разгруппировать" onclick="ungroupPkgs('<?php echo $gv->group_hash; ?>');"></i>
						</td>
					</tr>					
				<?php
				}
			}
		} else {

		}

	?>
	</tbody>
</table>
<div class="pull-right" style="margin: 1em 0 1em 1em;">
	<span class="btn btn-warning savePkgBtn" onclick="changeAllPackageStatus()">Сохранить</span>
</div>
</div>