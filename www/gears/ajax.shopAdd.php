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


$shop_name = filter_input(INPUT_POST, 'shop_name', FILTER_SANITIZE_STRING);
$shop_url = filter_input(INPUT_POST, 'shop_url', FILTER_VALIDATE_URL);

if (!isset($shop_name) || empty($shop_name) || $shop_name==false || $shop_name=='') { exit(json_encode(array('type'=>'error','text'=>'Имя пустое'))); }
if (!isset($shop_url) || empty($shop_url) || $shop_url==false || $shop_url=='') { exit(json_encode(array('type'=>'error','text'=>'URL пустой'))); }


$q = "INSERT INTO `shops` VALUES(NULL, '".$shop_name."', '".$shop_url."');";
$id = $db->query($q);
exit(json_encode(array('type'=>'ok','text'=>'Магазин '.$id[0].' Добавлен!', 'id'=>$id[0])));