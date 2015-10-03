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

if (!isset($id) || !$id || !is_int($id)) {
	exit(json_encode(array('type'=>'error','text'=>'ID пусто')));
}

// смотрим можно ли 
if ($user['rankname']!='admin' && $user['rankname']!='support' && $user['rankname']!='shipper') {
	exit(json_encode(array('type'=>'error','text'=>'У вас нет прав!')));
}

// если не админ или саппорт то проверим чей это пак
if ($user['rankname']!='admin' && $user['rankname']!='support') {
	$q = "SELECT * FROM `packages` WHERE `id` = ".$id;
	$accessPkg = $db->query($q);
	if (isset($accessPkg[0])) {
		if ($accessPkg[0]->shipper_id != $user['id']) {
			exit(json_encode(array('type'=>'error','text'=>'Это не ваш товар!')));
		}
	} else {
		exit(json_encode(array('type'=>'error','text'=>'Товар не существует!')));
	}
}

// смотрим, если отправлен (есть треки), то удалять нельзя
$pkg_statuses = getPackageStatuses($id);
if (!isset($pkg_statuses[0]) || $pkg_statuses==false && $pkg_statuses[0]->status_text!='new') {
	exit(json_encode(array('type'=>'error','text'=>'Нельзя удалить!')));
}


// убиваем
$q = "DELETE FROM `packages` WHERE id = ".$id.";";
$db->query($q);

$q = "DELETE FROM `pkg_description` WHERE `pkg_id` = ".$id.";";
$db->query($q);

$q = "DELETE FROM `pkg_notes` WHERE `pkg_id` = ".$id.";";
$db->query($q);

$q = "DELETE FROM `pkg_statuses` WHERE `pkg_id` = ".$id.";";
$db->query($q);

$q = "DELETE FROM `trackers` WHERE `pkg_id` = ".$id.";";
$db->query($q);

$q = "DELETE FROM `pkg_admin_approve` WHERE `pkg_id` = ".$id;
$db->query($q);

exit(json_encode(array('type'=>'ok','text'=>'ok')));


?>