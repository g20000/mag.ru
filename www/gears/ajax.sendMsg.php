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

$text = addslashes(strip_tags(filter_input(INPUT_POST, 'text', FILTER_UNSAFE_RAW)));
$hash = addslashes(strip_tags(filter_input(INPUT_POST, 'hash', FILTER_UNSAFE_RAW)));

if (!isset($text) || empty($text)) {
	exit(json_encode(array('type'=>'error','text'=>'Нет текста!')));
}

if (preg_match("/^[0-9A-F]+$/i", $hash)==false) {
	exit(json_encode(array('type'=>'error','text'=>'Ошибка хеша!<br>'.$hash)));
}

$q = "SELECT * FROM `chat_hashes` WHERE `hash` = '".$hash."' AND (`from_id` = ".$user['id']." OR `to_id` = ".$user['id'].")";
$res = $db->query($q);
if (isset($res[0])) {
	if ($res[0]->from_id == $user['id']) { $opponent = $res[0]->to_id;} else { $opponent = $res[0]->from_id; }
	$q = "INSERT INTO `chat` VALUES (NULL, ".$user['id'].", ".$opponent .", '".date("Y-m-d H:i:s", time())."', '".$text."', 0, '".$hash."');";
	$newmsgid = $db->query($q);
	exit(json_encode(array('type'=>'ok','text'=>'Новое сообщение номер'.$newmsgid[0] )));
} else {
	exit(json_encode(array('type'=>'error','text'=>'Нет диалога!')));
}


?>