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

$id = filter_input(INPUT_POST, 'userid', FILTER_VALIDATE_INT);

if (!isset($id) || !$id || !is_int($id)) {
	exit(json_encode(array('type'=>'error','text'=>'ID пусто')));
}

// смотрим можно ли 
if ($user['rankname']!='admin') {
	exit(json_encode(array('type'=>'error','text'=>'Вы не админ!')));
}


// убиваем пользователя
$q = "DELETE FROM `users` WHERE id = ".$id.";";
$db->query($q);


exit(json_encode(array('type'=>'ok','text'=>'Удален')));


?>