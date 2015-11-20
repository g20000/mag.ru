<?php
include_once('../gears/config.php');
include_once($cfg['realpath'].'/gears/headers.php');
include_once($cfg['realpath'].'/gears/bootstrap.php');
include_once($cfg['realpath'].'/gears/functions.php');


include_once($cfg['realpath'].'/gears/l18n.php');
include_once($cfg['realpath'].'/gears/db.php');

//header('Content-type: application/json');

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

if ($user['rankname']!='support' && $user['rankname']!='admin') {
	exit('Запрещено!');
}

$pkg_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$euro_val = filter_input(INPUT_POST, 'euro', FILTER_VALIDATE_INT);

if(($pkg_id == null) || ($pkg_id == false)){
	exit(json_encode(array('type'=>'error','text'=>'Ошибка передачи данных серверу')));
}

if(($euro_val == null) || ($euro_val == false)){
	exit(json_encode(array('type'=>'error','text'=>'Неверное или пустое значение поля Euro! В поле ввода должны присутствовать только цифры!')));
}

$db->query("UPDATE `pkg_description` SET `euro` = '".$euro_val."' WHERE `pkg_id` = ".$pkg_id);

	exit(json_encode(array('type'=>'ok','text'=>'Сохранено')));
?>