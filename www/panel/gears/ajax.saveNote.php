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

// фильтруем входящие данные

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$text = addslashes(strip_tags(filter_input(INPUT_POST, 'text', FILTER_UNSAFE_RAW)));
$type = filter_input(INPUT_POST, 'type', FILTER_UNSAFE_RAW);
if ($type!='private' && $type!='public') { $type = 'private'; }


if (!isset($id) || !$id || $id <= 0 || empty($id) || !is_int($id)) {
	exit(json_encode(array('type'=>'error','text'=>'ID is empty')));
}

// есть ли вообще такой ID
$q = "SELECT * FROM `packages` WHERE `id` = ".$id.";";
$isIDexist = $db->query($q);
if (!isset($isIDexist[0])) {
	exit(json_encode(array('type'=>'error','text'=>'Товар не найден!')));
}

$q = "SELECT * FROM `pkg_notes` WHERE `pkg_id` = ".$id." AND `user_type` = '".$user['rankname']."' AND `type` = '".$type."';";
$isIDexist = $db->query($q);
if (!isset($isIDexist[0])) {
	$q = "INSERT INTO `pkg_notes` VALUES (NULL, ".$id.", '".$user['rankname']."', '".$text."', '".$type."');";
	$db->query($q);
	exit(json_encode(array('type'=>'ok','text'=>'Создано!')));
} else {
	$q = "UPDATE `pkg_notes` SET `note` = '".$text."' WHERE `pkg_id` = ".$id." AND `user_type` = '".$user['rankname']."' AND `type` = '".$type."';";
	$db->query($q);
	exit(json_encode(array('type'=>'ok','text'=>'Сохранено!')));
}


exit(json_encode(array('type'=>'ok','text'=>'ok')));



?>