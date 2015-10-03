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

if ($user['rankname']!='admin' && $user['rankname']!='support') exit();

$shop_id = filter_input(INPUT_POST, 'shop_id', FILTER_VALIDATE_INT);
$drop_id = filter_input(INPUT_POST, 'drop_id', FILTER_VALIDATE_INT);
$shipper_id = filter_input(INPUT_POST, 'shipper_id', FILTER_VALIDATE_INT);

if (!isset($shop_id) || empty($shop_id) || $shop_id=='' || $shop_id===false) {
	exit(json_encode(array('type'=>'error','text'=>'Надо перезагрузить страницу')));
}

if (!isset($drop_id) || empty($drop_id) || $drop_id=='' || $drop_id===false) {
	exit(json_encode(array('type'=>'error','text'=>'Надо перезагрузить страницу')));
}

if (!isset($shipper_id) || empty($shipper_id) || $shipper_id=='' || $shipper_id===false) {
	exit(json_encode(array('type'=>'error','text'=>'Надо перезагрузить страницу')));
}

$q = "SELECT * FROM `drops2shippers` WHERE `shop_id` = ".$shop_id." AND `drop_id` = ".$drop_id." AND `shipper_id` = ".$shipper_id.";";
$isAlready = $db->query($q);
if (isset($isAlready[0])) {
	$q = "DELETE FROM `drops2shippers` WHERE `id` = ".$isAlready[0]->id;
	$db->query($q);
	exit(json_encode(array('type'=>'ok','text'=>'Убран')));
} else {
	$q = "INSERT INTO `drops2shippers` VALUES(NULL, ".$drop_id.", ".$shipper_id.", ".$shop_id.");";
	$id = $db->query($q);
	exit(json_encode(array('type'=>'ok','text'=>'Выбран')));
}
?>