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

$action = addslashes(strip_tags(filter_input(INPUT_POST, 'action', FILTER_UNSAFE_RAW)));
//$buyer_id = addslashes(strip_tags(filter_input(INPUT_POST, 'buyer_id', FILTER_VALIDATE_INT)));
$currency = addslashes(strip_tags(filter_input(INPUT_POST, 'currency', FILTER_UNSAFE_RAW)));
$drop_id = addslashes(strip_tags(filter_input(INPUT_POST, 'drop_id', FILTER_VALIDATE_INT)));
$item = addslashes(strip_tags(filter_input(INPUT_POST, 'item', FILTER_UNSAFE_RAW)));
$euro = addslashes(strip_tags(filter_input(INPUT_POST, 'euro', FILTER_VALIDATE_INT)));
$price = addslashes(strip_tags(filter_input(INPUT_POST, 'price', FILTER_VALIDATE_INT)));

$shop_id = addslashes(strip_tags(filter_input(INPUT_POST, 'shop_id', FILTER_VALIDATE_INT)));
$shop_name = addslashes(strip_tags(filter_input(INPUT_POST, 'shop_name', FILTER_UNSAFE_RAW)));
$shop_url = addslashes(strip_tags(filter_input(INPUT_POST, 'shop_url', FILTER_SANITIZE_URL)));

$holder = addslashes(strip_tags(filter_input(INPUT_POST, 'holder', FILTER_UNSAFE_RAW)));
$receivedate = addslashes(strip_tags(filter_input(INPUT_POST, 'receivedate', FILTER_UNSAFE_RAW)));
$moneydivider = addslashes(strip_tags(filter_input(INPUT_POST, 'moneydivider', FILTER_UNSAFE_RAW)));
$sendtodropname = addslashes(strip_tags(filter_input(INPUT_POST, 'sendtodropname', FILTER_VALIDATE_BOOLEAN)));



if (!isset($sendtodropname) || !$sendtodropname || empty($sendtodropname)) {
	$sendtodropname = 1;
}

if (!isset($receivedate) || !$receivedate || empty($receivedate) || !preg_match("/\d{2}\/\d{2}\/\d{4}/is", $receivedate)) {
	$receivedate = date("d/M/Y",time()+60*60*24*5); // if empty then +5 days
}


$moneydividerArr = array('percent','fiftyfifty','forward','tobuyer');
if (!isset($moneydivider) || !$moneydivider || empty($moneydivider) || !in_array($moneydividerArr, $moneydividerArr)) {
	$receivedate = date("Y-m-d H:i:s",time()+60*60*24*5); // if empty then +5 days
}

if (!isset($holder) || !$holder || empty($holder)) {
	$holder = '';
}

if (!isset($action) || !$action || empty($action)) {
	$action = '';
}
/*
if (!isset($buyer_id) || empty($buyer_id)) {
	exit(json_encode(array('type'=>'error','text'=>'Buyer is empty')));
}
*/
if (!isset($currency) || !$currency || $currency === -1 || empty($currency)) {
	exit(json_encode(array('type'=>'error','text'=>'Заполните валюту')));
}

if (!isset($drop_id) || empty($drop_id)) {
	exit(json_encode(array('type'=>'error','text'=>'Сотрудник не указан!')));
}

if (!isset($item) || !$item || empty($item)) {
	exit(json_encode(array('type'=>'error','text'=>'Имя товара пусто!')));
}

if (!isset($price) || empty($price)) {
	exit(json_encode(array('type'=>'error','text'=>'Цена не указана!')));
}

if (!isset($shop_name) || empty($shop_name)) {
	exit(json_encode(array('type'=>'error','text'=>'Имя магазина не указано!')));
}

if (!isset($shop_url) || empty($shop_url)) {
	$shop_url = '';
}


if (!isset($shop_id) || empty($shop_id)) {
	
	$q = "SELECT * FROM `shops` WHERE `shop_name` = '".$shop_name."' OR `shop_url` = '".$shop_url."';";
	$shop_info = $db->query($q);
	//debug($shop_id[0]->shop_url);
	if (isset($shop_info[0])) {
		$shop_id = $shop_info[0]->id;
		$shop_url = $shop_info[0]->shop_url;
	} else {
		if (!preg_match("/^http(s)?:\/\//i", $shop_url)) { $shop_url = 'http://'.$shop_url; }
		$q = "INSERT INTO `shops` VALUES (NULL,'".$shop_name."','".$shop_url."');";
		$shop_id = $db->query($q);
		$shop_id = $shop_id[0];	
	}
	
}

//$q = "INSERT INTO `packages` VALUES (NULL,'',".$shop_id.",".$drop_id.",".$user['id'].",".$buyer_id.",NULL,'".$action."');";
$q = "INSERT INTO `packages` VALUES (NULL,'',".$shop_id.",".$drop_id.",".$user['id'].",NULL,NULL,'".$action."');";
$pkg_id = $db->query($q);
$pkg_id = $pkg_id[0];

//INSERT INTO `pkg_description` VALUES (NULL,28,'Сигареты парламент','2','eur','','1','','2015-11-24 22:08:08')
$q = "INSERT INTO `pkg_description` VALUES (NULL,".$pkg_id.",'".$item."','".$euro."','".$price."','".$currency."','".$holder."','".$sendtodropname."','".$moneydivider."','".$receivedate."');";
$pkg_description_id = $db->query($q);

$q = "INSERT INTO `pkg_statuses` VALUES (NULL,".$pkg_id.",'".date("Y-m-d H:i:s", time())."','processing');";//new заменен на processing
$pkg_status_id = $db->query($q);

$q = "INSERT INTO `pkg_admin_approve` VALUES(NULL, '".date("Y-m-d H:i:s", time())."', ".$user['id'].", ".$pkg_id.");";
$pkg_admin_approve_id = $db->query($q);

exit(json_encode(array('type'=>'ok','text'=>$pkg_id)));


?>