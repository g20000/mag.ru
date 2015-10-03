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

function chkit($var) {
	if (is_numeric($var)) {
		$out = $var;
	} else {
		exit(json_encode(array('type'=>'error','text'=>'ID\'s is not int!')));
	}
	return $out;
}

foreach(array_keys($_POST) as $key) {
    if(filter_has_var(INPUT_POST, $key)) {
        $ids = filter_input(INPUT_POST, $key, FILTER_CALLBACK, array('options' => 'chkit'));
		// addslashes(strip_tags(filter_input(INPUT_POST, $key, FILTER_UNSAFE_RAW)))
    }
}

if (!isset($ids) || !$ids || empty($ids)) {
	exit(json_encode(array('type'=>'error','text'=>'ID\'s пусто')));
}


// смотрим можно ли 
if ($user['rankname']!='admin' && $user['rankname']!='support' && $user['rankname']!='shipper') {
	exit(json_encode(array('type'=>'error','text'=>'Вам нельзя это делать!')));
}


// есть ли вообще такой ID и одинаковые ли скупы
foreach($ids as $v) {
	$q = "SELECT * FROM `packages` WHERE `id` = ".$v.";";
	$isIDexist = $db->query($q);
	// если нету такого ИД
	if (!isset($isIDexist[0])) {
		exit(json_encode(array('type'=>'error','text'=>'Некоторые ID не найдены!')));
	}
	// проверяем на идентичность скупов
	if (!isset($chkskup)) {
		$chkskup = $isIDexist[0]->buyer_id; 
	} elseif ($chkskup!=$isIDexist[0]->buyer_id) {
		exit(json_encode(array('type'=>'error','text'=>'Получатели должны быть одинаковые!')));
	} 
}

$preHash = array();
$preWhere = array();
foreach($ids as $k=>$v) {
	$preHash[] = "id".$v;
	$preWhere[] = '`id` = '.$v;
}

$hash = md5(implode('',$preHash));
$where = implode(' OR ',$preWhere);

$q = "UPDATE `packages` SET `group_hash` = '".$hash."' WHERE ".$where;
$db->query($q);

exit(json_encode(array('type'=>'ok','text'=>'ok')));



?>