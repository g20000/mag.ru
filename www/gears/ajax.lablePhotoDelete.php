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

if ($user['rankname']!='labler' && $user['rankname']!='admin') {
	exit('Запрещено!');
}

// фильтруем входящие данные
$img_id = filter_input(INPUT_POST, 'img_id', FILTER_VALIDATE_INT);
$pkg_id = filter_input(INPUT_POST, 'pkg_id', FILTER_VALIDATE_INT);

if (!isset($img_id) || !$img_id || !is_int($img_id)) {
	exit(json_encode(array('type'=>'error','text'=>'IMG_ID is empty')));
}

if (!isset($pkg_id) || !$pkg_id || !is_int($pkg_id)) {
	exit(json_encode(array('type'=>'error','text'=>'PKG_ID is empty')));
}


$q = "SELECT * FROM `uploads` WHERE `id` = ".$img_id.";";
$res = $db->query($q);

// смотрим можно ли 
if (isset($res[0])) {
	// убиваем пользователя
	$db->query("DELETE FROM `uploads` WHERE id = ".$img_id.";");
	$db->query("DELETE FROM `pkg_statuses` WHERE `pkg_id` = ".$pkg_id." AND `status_text` = 'labled';");
	unlink($cfg['realpath'].'/upload/'.$res[0]->filename);
} else {
	exit(json_encode(array('type'=>'error','text'=>'Доступ запрещен!')));
}





exit(json_encode(array('type'=>'ok','text'=>'ok')));


?>