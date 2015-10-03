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
$color = addslashes(strip_tags(filter_input(INPUT_POST, 'color', FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^#?[0-9A-F]+$/i")))));

if (!isset($id) || !$id || $id <= 0 || empty($id) || !is_int($id)) {
	exit(json_encode(array('type'=>'error','text'=>'ID is empty')));
}

// есть ли вообще такой ID
$q = "SELECT * FROM `packages` WHERE `id` = ".$id.";";
$isIDexist = $db->query($q);
if (!isset($isIDexist[0])) {
	exit(json_encode(array('type'=>'error','text'=>'Package not found!')));
}

$q = "SELECT * FROM pkg_color WHERE `id` = ".$id.";";
$isset = $db->query($q);
if (isset($isset[0])) {
	$q = "UPDATE `pkg_color` SET `color` = '".$color."' WHERE `id` = ".$id.";";
	$db->query($q);
	exit(json_encode(array('type'=>'ok','text'=>'Color edited!')));
} else {
	$q = "INSERT INTO `pkg_color` VALUES (NULL, ".$id.", '".$color."');";
	$nid = $db->query($q);
	exit(json_encode(array('type'=>'ok','text'=>'Color for created!')));	
}


exit(json_encode(array('type'=>'ok','text'=>'ok')));



?>