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


// смотрим можно ли 
if ($user['rankname']!='admin' && $user['rankname']!='support') {
	exit(json_encode(array('type'=>'error','text'=>'Вы не админ!')));
}


/** ============================================================================================================ **/



// фильтруем входящие данные

$shop_id = addslashes(strip_tags(filter_input(INPUT_POST, 'shop_id', FILTER_VALIDATE_INT)));

//exit(json_encode(array($name, $email, $pass1, $pass2, $rank)));

if (!isset($shop_id) || !$shop_id || empty($shop_id)) {
	exit(json_encode(array('type'=>'error','text'=>'ID пусто')));
}


$pkgs = getPackages(false);
if ($pkgs!==false) {
	foreach($pkgs as $pkg) {
		if (getPackageStatus($pkg->id)->status_text!='compleate' && $pkg->shop_id==$shop_id) {
			exit(json_encode(array('type'=>'error','text'=>'Этот магазин используется при доставке сейчас.<br>Дождитесь завершения.')));
		}
	}
}

$q = "DELETE FROM `drops2shippers` WHERE `shop_id` = ".$shop_id;
$db->query($q);
$q = "DELETE FROM `shops` WHERE `id` = ".$shop_id;
$db->query($q);

exit(json_encode(array('type'=>'ok','text'=>'Магазин удален')));



?>