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

$id = filter_input(INPUT_POST, 'uid', FILTER_VALIDATE_INT);
$p['name'] = addslashes(strip_tags(filter_input(INPUT_POST, 'nickname', FILTER_UNSAFE_RAW)));
$p['email'] = strip_tags(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
$p['xmpp'] = strip_tags(filter_input(INPUT_POST, 'xmpp', FILTER_VALIDATE_EMAIL));
$pass1 = addslashes(filter_input(INPUT_POST, 'password1', FILTER_UNSAFE_RAW));
$pass2 = addslashes(filter_input(INPUT_POST, 'password1', FILTER_UNSAFE_RAW));
$p['rank'] = strip_tags(filter_input(INPUT_POST, 'rank', FILTER_VALIDATE_INT));


$p['first_name'] = addslashes(strip_tags(filter_input(INPUT_POST, 'first_name', FILTER_UNSAFE_RAW)));
$p['middle_name'] = addslashes(strip_tags(filter_input(INPUT_POST, 'middle_name', FILTER_UNSAFE_RAW)));
$p['last_name'] = addslashes(strip_tags(filter_input(INPUT_POST, 'last_name', FILTER_UNSAFE_RAW)));
$p['country'] = addslashes(strip_tags(filter_input(INPUT_POST, 'country', FILTER_UNSAFE_RAW)));
$p['state'] = addslashes(strip_tags(filter_input(INPUT_POST, 'state', FILTER_UNSAFE_RAW)));
$p['city'] = addslashes(strip_tags(filter_input(INPUT_POST, 'city', FILTER_UNSAFE_RAW)));
$p['address'] = addslashes(strip_tags(filter_input(INPUT_POST, 'address', FILTER_UNSAFE_RAW)));
$p['zip'] = addslashes(strip_tags(filter_input(INPUT_POST, 'zip', FILTER_UNSAFE_RAW)));
$p['cell'] = addslashes(strip_tags(filter_input(INPUT_POST, 'cell', FILTER_UNSAFE_RAW)));
$p['home'] = addslashes(strip_tags(filter_input(INPUT_POST, 'home', FILTER_UNSAFE_RAW)));

$p['about'] = addslashes(strip_tags(filter_input(INPUT_POST, 'note', FILTER_UNSAFE_RAW)));
$p['color'] = addslashes(strip_tags(filter_input(INPUT_POST, 'color', FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^#?[0-9A-F]+$/i")))));





if (!isset($id) || !$id || $id <= 0 || empty($id) || !is_int($id)) {
	exit(json_encode(array('type'=>'error','text'=>'ID пусто')));
}

if (!isset($p['name']) || !$p['name'] || empty($p['name'])) {
	exit(json_encode(array('type'=>'error','text'=>'Имя пусто')));
}

if (!isset($p['email']) || !$p['email'] || empty($p['email'])) {
	$p['email'] = '';
}

if (!isset($p['xmpp']) || !$p['xmpp'] || empty($p['xmpp'])) {
	$p['xmpp'] = '';
}

if (!isset($p['rank']) || !$p['rank'] || $p['rank'] === -1 || empty($p['rank']) || !is_numeric($p['rank'])) {
	exit(json_encode(array('type'=>'error','text'=>'Группа не указана')));
}

if ($pass1 != '' || $pass2 != '' ) {
	if ($pass1 !== $pass2) {
		exit(json_encode(array('type'=>'error','text'=>'Оба пароля должны быть одинаковые')));
	} else {
		$p['password'] = md5($pass1);
	}
}

// смотрим можно ли 
if ($user['rankname']!='admin') {
	exit(json_encode(array('type'=>'error','text'=>'Вы не админ!')));
}

// есть ли вообще такой ID
$q = "SELECT * FROM `users` WHERE `id` = ".$id.";";
$isIDexist = $db->query($q);
if (!isset($isIDexist[0])) {
	exit(json_encode(array('type'=>'error','text'=>'Пользователь не найден!')));
}


// если такое имя уже есть
$q = "SELECT * FROM `users` WHERE `name` = '".$p['name']."' AND `id` != ".$id.";";
$isnameexist = $db->query($q);
if (isset($isnameexist[0])) {
	exit(json_encode(array('type'=>'error','text'=>'Имя занято!')));
}


$q = array();
foreach($p as $k=>$v) {
	$q[] = "`".$k."` = '".$v."'";
}
$q = 'UPDATE `users` SET '.implode(', ',$q).' WHERE id = '.$id;

$db->query($q);

exit(json_encode(array('type'=>'ok','text'=>'ok')));



?>