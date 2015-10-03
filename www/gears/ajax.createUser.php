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

$name = addslashes(strip_tags(filter_input(INPUT_POST, 'nickname', FILTER_UNSAFE_RAW)));
$email = strip_tags(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
$xmpp = strip_tags(filter_input(INPUT_POST, 'xmpp', FILTER_VALIDATE_EMAIL));
$pass1 = addslashes(filter_input(INPUT_POST, 'password1', FILTER_UNSAFE_RAW));
$pass2 = addslashes(filter_input(INPUT_POST, 'password2', FILTER_UNSAFE_RAW));
$rank = strip_tags(filter_input(INPUT_POST, 'rank', FILTER_VALIDATE_INT));
$first_name = addslashes(strip_tags(filter_input(INPUT_POST, 'first_name', FILTER_UNSAFE_RAW)));
$middle_name = addslashes(strip_tags(filter_input(INPUT_POST, 'middle_name', FILTER_UNSAFE_RAW)));
$last_name = addslashes(strip_tags(filter_input(INPUT_POST, 'last_name', FILTER_UNSAFE_RAW)));
$country = addslashes(strip_tags(filter_input(INPUT_POST, 'country', FILTER_UNSAFE_RAW)));
$state = addslashes(strip_tags(filter_input(INPUT_POST, 'state', FILTER_UNSAFE_RAW)));
$city = addslashes(strip_tags(filter_input(INPUT_POST, 'city', FILTER_UNSAFE_RAW)));
$address = addslashes(strip_tags(filter_input(INPUT_POST, 'address', FILTER_UNSAFE_RAW)));
$zip = addslashes(strip_tags(filter_input(INPUT_POST, 'zip', FILTER_UNSAFE_RAW)));
$cell = addslashes(strip_tags(filter_input(INPUT_POST, 'cell', FILTER_UNSAFE_RAW)));
$home = addslashes(strip_tags(filter_input(INPUT_POST, 'home', FILTER_UNSAFE_RAW)));



//exit(json_encode(array($name, $email, $pass1, $pass2, $rank)));

if (!isset($name) || !$name || empty($name)) {
	exit(json_encode(array('type'=>'error','text'=>'Имя не указано')));
}

if (!isset($rank) || !$rank || $rank === -1 || empty($rank)) {
	exit(json_encode(array('type'=>'error','text'=>'Группа не выбрана')));
}

if (!isset($pass1) || !$pass1 || empty($pass1)) {
	exit(json_encode(array('type'=>'error','text'=>'Пароль не введен')));
}

if (!isset($pass2) || !$pass2 || empty($pass2)) {
	exit(json_encode(array('type'=>'error','text'=>'Повтор пароля не введен')));
}

if ($pass1 !== $pass2) {
	exit(json_encode(array('type'=>'error','text'=>'Пароли не идентичны')));
}

// смотрим можно ли 
if ($user['rankname']!='admin') {
	exit(json_encode(array('type'=>'error','text'=>'Вы не админ!')));
}


// если такое имя уже есть
$q = "SELECT * FROM `users` WHERE `name` = '".$name."';";
$isnameexist = $db->query($q);
if (isset($isnameexist[0])) {
	exit(json_encode(array('type'=>'error','text'=>'Имя занято!')));
}


// добавляем пользователя
$q = "
INSERT INTO `users` 
(`id`, `name`, `email`, `xmpp`, `first_name`, `middle_name`, `last_name`, `country`, `state`, `city`, `address`, `zip`, `cell`, `home`, `password`, `color`, `status`, `rank`, `deposit`, `registration_time`, `last_time`, `about`, `sid`)
VALUES 
(NULL, '".$name."', '".$email."', '".$xmpp."', '".$first_name."', '".$middle_name."', '".$last_name."', '".$country."', '".$state."', '".$city."', '".$address."', '".$zip."', '".$cell."', '".$home."', '".md5($pass1)."', '', 'fresh', ".$rank.", '0', '".date("Y-m-d H:i:s", time())."', '', '', '');
";
$id = $db->query($q);

if (isset($id[0]) && !empty($id[0]) && !is_int($id[0])) {
	exit(json_encode(array('type'=>'ok','text'=>$id[0])));
} else {
	exit(json_encode(array('type'=>'error','text'=>'Ошибка создания')));
}


?>