<?php
include_once('../gears/config.php');
include_once($cfg['realpath'].'/gears/headers.php');
include_once($cfg['realpath'].'/gears/bootstrap.php');
include_once($cfg['realpath'].'/gears/functions.php');


include_once($cfg['realpath'].'/gears/l18n.php');
include_once($cfg['realpath'].'/gears/db.php');

$_ts = microtime_float();


// смотрим язык пользователя
$cfg['ln'] = getClientLang();

// создаем инстанс подключения к базе
$db = new dbClass($cfg['db']);

// забираем из базы опции и кладем их в конфиг
$options = $db->query("SELECT * FROM options");

// кладем в конфиг все что забрали из базы (все опции)
if (isset($options[0])) { 
	foreach($options as $k=>$v) {
		$cfg['options'][$v->option]=$v->value;
	}
}
unset($options);

// смотрим на авторизацию
include($cfg['realpath'].'/gears/auth_init.php');

$id = addslashes(strip_tags(filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT)));
$shop_id = addslashes(strip_tags(filter_input(INPUT_POST, 'shop_id', FILTER_UNSAFE_RAW)));

$dropshopinfo = 'На работал с этим магазином!';
if (is_numeric($shop_id)) {
	$q = "SELECT * FROM `packages` WHERE `drop_id` = ".$id." AND `shop_id` = ".$shop_id.";";
	$res = $db->query($q);
	if (isset($res[0])) {
		$dropshopinfo = 'Работал с этим магазином '.count($res).' раз';
	}
}



$q = "SELECT * FROM `users` WHERE `id` = '".$id."' AND `rank` = 3;";
$drop = $db->query($q);
if (isset($drop[0])) {
	
	$html = '
		<table class="table">
			<tr>
				<td>'.$drop[0]->first_name.' '.$drop[0]->middle_name.' '.$drop[0]->last_name.'</td>
				<td>'.$drop[0]->city.'</td>
				<td class="person_'.$drop[0]->status.'">'.$drop[0]->status.'</td>
			</tr>
			<tr><td colspan=3>'.$dropshopinfo.'</td></tr>
		</table>
	';
	
	
	
	exit(json_encode(array('type'=>'ok','info'=>$html)));
} else {
	exit(json_encode(array('type'=>'error','text'=>'Сотрудник не найден!')));
}
?>