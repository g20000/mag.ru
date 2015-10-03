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

$id = filter_input(INPUT_POST, 'img_id', FILTER_VALIDATE_INT);

if (!isset($id) || !$id || !is_int($id)) {
	exit(json_encode(array('type'=>'error','text'=>'ID is empty')));
}


$q = "SELECT * FROM `uploads` WHERE `id` = ".$id.";";
$res = $db->query($q);

// смотрим можно ли 
if (isset($res[0]) && ($user['rankname']=='admin' || $res[0]->user_id==$user['id'] )) {
	// убиваем пользователя
	$db->query("DELETE FROM `uploads` WHERE id = ".$id.";");
	$db->query("DELETE FROM `pkg_statuses` WHERE `pkg_id` = ".$res[0]->pkg_id." AND `status_text` = '".$res[0]->status_text."';");
	unlink($cfg['realpath'].'/upload/'.$res[0]->filename);
} else {
	exit(json_encode(array('type'=>'error','text'=>'Запрещено!')));
}





exit(json_encode(array('type'=>'ok','text'=>'ok')));


?>