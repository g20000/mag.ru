<?php

function getUsersTypeOnline($rank) {
	global $db;
	$q = "SELECT COUNT(*) as cnt FROM `users` WHERE `rank` = ".$rank." AND `last_time` > '".date("Y-m-d H:i:s",time()-120)."'";
	$res = $db->query($q);
	if (isset($res[0])) {
		$out = $res[0]->cnt;
	} else {
		$out = 0;
	}
	return $out;
}

function getFinishedPacks($t) {
	$allPkgs = getPackages();
	$cf=0;
	$cu=0;
	if (isset($allPkgs[0])) {
		foreach($allPkgs as $v) {
			// if is array - it grouped package
			if (!isset($v->status_text)) {
				foreach($v as $gv) {
					if ($gv->status_text=='compleate') {
						$cf++;
					} else {
						$cu++;
					}				
				}
			} else {
				if ($v->status_text=='compleate') {
					$cf++;
				} else {
					$cu++;
				}
			}
		}
	} else {
		$cf = 0;
		$cu = 0;
	}
	return $t=='c' ? $cf : $cu;
}

?>

<div class="table-responsive">
	<h2>Пользователи онлайн</h2>
	<table class="table table-bordered table-striped table-hover table-condensed">
		<thead>
			<tr>
				<th>Тип</th>
				<th>Значение</th>
				<th>Тип</th>
				<th>Значение</th>
			</tr>
		</thead>
		<tbody>
			<tr><td width="25%">Админы:</td><td><?php echo getUsersTypeOnline(4);?></td><td width="25%">Всего:</td><td><?php echo count(getUsersListRank(4));?></td></tr>
			<tr><td>Помощники:</td><td><?php echo getUsersTypeOnline(1);?></td><td>Всего:</td><td><?php echo count(getUsersListRank(1));?></td></tr>
			<tr><td>Сортировщики:</td><td><?php echo getUsersTypeOnline(6);?></td><td>Всего:</td><td><?php echo count(getUsersListRank(6));?></td></tr>
			<tr><td>Курьеры:</td><td><?php echo getUsersTypeOnline(3);?></td><td>Всего:</td><td><?php echo count(getUsersListRank(3));?></td></tr>
			<tr><td>Покупатели:</td><td><?php echo getUsersTypeOnline(5);?></td><td>Всего:</td><td><?php echo count(getUsersListRank(5));?></td></tr>
			<tr><td>Отправители:</td><td><?php echo getUsersTypeOnline(2);?></td><td>Всего:</td><td><?php echo count(getUsersListRank(2));?></td></tr>
		</tbody>
	</table>
</div>

<div class="table-responsive">
	<h2>Статистика товаров </h2>
	<table class="table table-bordered table-striped table-hover table-condensed">
		<thead>
			<tr>
				<th>Тип</th>
				<th>Значение</th>
			</tr>
		</thead>
		<tbody>
			<tr><td width="20%">Завершено:</td><td><?php echo getFinishedPacks('c');?></td></tr>
			<tr><td>В работе:</td><td><?php echo getFinishedPacks('u');?></td></tr>
		</tbody>
	</table>
</div>


<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

<?php

	$series['date']=array();
	for ($i=60;$i>=0;$i--) {
		
		$date=date("Y-m-d",time()-$i*24*60*60);
		
		// select new by date
		$q = "SELECT * FROM `pkg_statuses` WHERE `status_text` = 'new' AND `time` > '".$date." 00:00:00' AND `time` < '".$date." 23:59:59'";
		$res = $db->query($q);
		$s1 = isset($res[0]) ? count($res[0]) : 0;

		// select todrop by date
		$q = "SELECT * FROM `pkg_statuses` WHERE `status_text` = 'todrop' AND `time` > '".$date." 00:00:00' AND `time` < '".$date." 23:59:59'";
		$res = $db->query($q);
		$s2 = isset($res[0]) ? count($res[0]) : 0;		
		
		// select to buyer by date
		$q = "SELECT * FROM `pkg_statuses` WHERE `status_text` = 'tobuyer' AND `time` > '".$date." 00:00:00' AND `time` < '".$date." 23:59:59'";
		$res = $db->query($q);
		$s3 = isset($res[0]) ? count($res[0]) : 0;		
		
		
		
		$series['date'][]=$date;
		$series['date'][]=date("Y-m-d",time()-$i*24*60*60);
		$series['series'][0][]=$s1;
		$series['series'][1][]=$s2;
		$series['series'][2][]=$s3;
	}
	
	$min = min(array_merge($series['series'][0],$series['series'][1],$series['series'][2]));
	$max = max(array_merge($series['series'][0],$series['series'][1],$series['series'][2]));

	
	$scriptsNeedToLoading[] = $cfg['options']['siteurl']."/design/Highcharts-3.0.7/js/highcharts.js";
	$scriptsNeedToLoading[] = $cfg['options']['siteurl']."/design/Highcharts-3.0.7/js/modules/exporting.js";
	
	
$to_page_scripts = "
	$(document).ready(function() {
		$('#container').highcharts({
			chart: {},
			xAxis: {
				categories: ['".implode("','", $series['date'])."'],
				min: 0,
				labels: {
					rotation: -65,
					align: 'right',
					style: {
						fontSize: '8px',
						fontFamily: 'Verdana, sans-serif'
					}
				}
			},
			yAxis: {
				min: ".$min.",
				max: ".$max.",
				title: {
					text: 'Value'
				}
			},
			title: {
				text: 'Package action statistics'
			},
			series: [{
				type: 'line',
				name: 'Package add',
				data: [".implode(",", $series['series'][0])."],
				marker: {
					enabled: false
				},
				states: {
					hover: { lineWidth: 0 }
				},
				enableMouseTracking: true
			}, {
				type: 'line',
				name: 'Send to agents',
				data: [".implode(",", $series['series'][1])."],
				marker: {
					enabled: false
				},
				states: {
					hover: {lineWidth: 0}
				},
				enableMouseTracking: true
			}, {
				type: 'line',
				name: 'Sent to buyer',
				data: [".implode(",", $series['series'][2])."],
				marker: {
					enabled: false
				},
				states: {
					hover: {lineWidth: 0}
				},
				enableMouseTracking: true
			}]
		});


		

	});
";

if (!isset($_page_head_scripts)) { $_page_head_scripts = $to_page_scripts; } else { $_page_head_scripts .= $to_page_scripts; }


?>
