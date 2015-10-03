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

$action = filter_input(INPUT_POST, 'action', FILTER_VALIDATE_BOOLEAN);
$drop_id = filter_input(INPUT_POST, 'drop_id', FILTER_VALIDATE_INT);
$shipper_id = filter_input(INPUT_POST, 'shipper_id', FILTER_VALIDATE_INT);

if (!isset($drop_id) || empty($drop_id) || $drop_id=='' || $drop_id===false) {
	exit(json_encode(array('type'=>'error','text'=>'Надо перезагрузить страницу')));
}

if (!isset($shipper_id) || empty($shipper_id) || $shipper_id=='' || $shipper_id===false) {
	exit(json_encode(array('type'=>'error','text'=>'Надо перезагрузить страницу')));
}

// если выключили дропа
if ($action==false) {
	$q = "DELETE FROM `drops2shippers` WHERE `shipper_id` = ".$shipper_id." AND `drop_id` = ".$drop_id;
	$db->query($q);
	exit(json_encode(array('type'=>'ok','text'=>'Выбран')));
}

// выбрали все магазы
$q = "SELECT * FROM `shops`;";
$shops = $db->query($q);

// выбрали все запрещеные магазы для этого дропа
$q = "SELECT * FROM `drops2shippers` WHERE `drop_id` = ".$drop_id." AND `shipper_id` <> ".$shipper_id.";";
$disallowShops = $db->query($q);
if (isset($disallowShops[0])) {
	// формируем массив запрещеных шопов
	foreach ($disallowShops as $ds) {
		$bannedshopsarray[] = $ds->shop_id;
	}
}
//print_r($bannedshopsarray);
// перечисляем все шопы и за исключением запрещеных вставляем в базу
foreach($shops as $shop) {
	if (isset($bannedshopsarray) && array_search($shop->id,$bannedshopsarray)!==false) continue;
	$q = "INSERT INTO `drops2shippers` VALUES(NULL, ".$drop_id.", ".$shipper_id.", ".$shop->id.");";
	$id = $db->query($q);
	$dataShops[] = $shop->id;
}

exit(json_encode(array('type'=>'ok','text'=>'Выбран','shops'=>$dataShops)));

?>