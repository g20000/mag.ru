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

$country = addslashes(strip_tags(filter_input(INPUT_POST, 'country', FILTER_UNSAFE_RAW)));
$state = addslashes(strip_tags(filter_input(INPUT_POST, 'state', FILTER_UNSAFE_RAW)));

// если просят только чистых
$clear = addslashes(strip_tags(filter_input(INPUT_POST, 'isclear', FILTER_VALIDATE_BOOLEAN)));
$shop_id = addslashes(strip_tags(filter_input(INPUT_POST, 'shop_id', FILTER_VALIDATE_INT)));
//debug($clear);exit();

if ($clear==1) {
	// смотрим кто работал вообще с этим шопом
	$q = "SELECT * FROM `packages` WHERE `shop_id` = ".$shop_id;
	$d = $db->query($q);
	$usedDrops = array();
	if (isset($d[0])) {
		foreach($d as $v) {
			$usedDrops[] = $v->drop_id;
		}
	}
}


$where = Array('`rank` = 3');
if ($country!=-1 && !empty($country)) {
	$where[] = "`country` = '".$country."'";
} else {
	exit(json_encode(array('type'=>'ok','states'=>'', 'drops'=>'')));
}

if ($state!=-1 && !empty($state)) {
	$where[] = "`state` = '".$state."'";
}

$where_sql = implode(' AND ', $where);

$q = "SELECT * FROM `users` WHERE `country` = '".$country."' AND `rank` = 3;";
$all_states = $db->query($q);

// есть ли вообще такой ID
$q = "SELECT * FROM `users` WHERE ".$where_sql.";";
$drops = $db->query($q);
if (isset($drops[0])) {
	
	if ($clear==1) {
		foreach($drops as $k=>$v) {
			if (array_search($v->id, $usedDrops)!==false) {
				unset($drops[$k]);
			}
		}
	}
	
	$states = array();
	foreach($all_states as $v) {
		$states[] = $v->state;
	}
	
	$states = array_unique($states);
	sort($states);
	
	exit(json_encode(array('type'=>'ok','states'=>$states, 'drops'=>$drops)));
} else {
	exit(json_encode(array('type'=>'ok','states'=>'', 'drops'=>'')));
}
?>