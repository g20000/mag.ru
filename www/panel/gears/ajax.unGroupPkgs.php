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

$hash = addslashes(strip_tags(filter_input(INPUT_POST, 'hash', FILTER_UNSAFE_RAW)));


if (!isset($hash) || !$hash || empty($hash) || !preg_match("/^[0-9A-F]+$/i", $hash)) {
	exit(json_encode(array('type'=>'error','text'=>'Ошибка!')));
}


// смотрим можно ли 
if ($user['rankname']!='admin' && $user['rankname']!='support' && $user['rankname']!='shipper') {
	exit(json_encode(array('type'=>'error','text'=>'Запрещено!')));
}


// есть ли вообще такой ID

$q = "SELECT * FROM `packages` WHERE `group_hash` = '".$hash."';";
$isIDexist = $db->query($q);
if (!isset($isIDexist[0])) {
	exit(json_encode(array('type'=>'error','text'=>'Не найдено!')));
}

$q = "UPDATE `packages` SET `group_hash` = '' WHERE `group_hash` = '".$hash."';";
$db->query($q);

exit(json_encode(array('type'=>'ok','text'=>'Готово')));



?>