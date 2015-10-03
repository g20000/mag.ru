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


if ($user['rankname']!='support' && $user['rankname']!='admin' && $user['rankname']!='shipper') {
	exit('Запрещено!');
}

// фильтруем входящие данные

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$type = addslashes(strip_tags(filter_input(INPUT_POST, 'type', FILTER_UNSAFE_RAW)));
$num = addslashes(strip_tags(filter_input(INPUT_POST, 'num', FILTER_UNSAFE_RAW)));

if (!isset($id) || !$id || $id <= 0 || empty($id) || !is_int($id)) {
	exit(json_encode(array('type'=>'error','text'=>'ID is empty')));
}

if (!isset($type) || !$type || $type == -1 || empty($type)) {
	exit(json_encode(array('type'=>'error','text'=>'Надо выбрать тип доставки!')));
}

if (!isset($num) || !$num || empty($num)) {
	exit(json_encode(array('type'=>'error','text'=>'Номер трека пуст!')));
}

// есть ли вообще такой ID
$q = "SELECT * FROM `packages` WHERE `id` = ".$id.";";
$isIDexist = $db->query($q);
if (!isset($isIDexist[0])) {
	exit(json_encode(array('type'=>'error','text'=>'Товар не найден!')));
}

// есть ли такой трек
$q = "SELECT * FROM `trackers` WHERE `pkg_id` = ".$id." AND `track_type` = '".$type."';";
$isIdExist = $db->query($q);
if (!isset($isIdExist[0])) {
	$q = "INSERT INTO `trackers` VALUES (NULL, ".$id.", '".$type."', '".$num."');";
	$db->query($q);
} else {
	$q = "UPDATE `trackers` SET `track_num` = '".$num."' WHERE `id` = ".$isIdExist[0]->id.";";
	$db->query($q);
}

if (($user['rankname']=='admin') && ($statusKind != '...')){
	$status = $statusKind;
}elseif ($user['rankname']=='shipper' || $user['rankname']=='admin' || $user['rankname']=='support') {
	$status = 'todrop';
} elseif($user['rankname']=='drop') {
	$status = 'tobuyer';
}

// к этой странице только шиперы и администрация имеют доступ, так что ...
$q = "INSERT INTO `pkg_statuses` VALUES (NULL, ".$id.", '".date("Y-m-d H:i:s", time())."', '".$status."');";
$db->query($q);

exit(json_encode(array('type'=>'ok','text'=>'Сохранено!')));



?>