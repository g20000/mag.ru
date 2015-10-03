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

if (($user['rankname']!='admin')&&($user['rankname']!='shipper')) exit();


$text = addslashes(strip_tags(filter_input(INPUT_POST, 'text', FILTER_SANITIZE_SPECIAL_CHARS)));
if ($text===false || $text===NULL) { $text=''; }

$q = "SELECT * FROM `options` WHERE `option` = 'bslist'";
$check = $db->query($q);
if (!isset($check) || $check==false) {
	$db->query("INSERT INTO `options` VALUES (NULL, 'bslist', '".$text."')");
} else {
	$q = "UPDATE `options` SET `value` = '".$text."' WHERE `option` = 'bslist'";
	$db->query($q);
}

exit(json_encode(array('type'=>'ok','text'=>'Сохранено!')));


?>