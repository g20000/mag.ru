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

$id = addslashes(strip_tags(filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT)));
if (!isset($id)) { exit(json_encode(array('type'=>'error','text'=>'ID empty!'))); }


if ($id!=$user['id']) { exit(json_encode(array('type'=>'error','text'=>'Попытка взлома?!'))); }

$q = "
	SELECT p.id, p.drop_id, p.*, p.action, pd.currency, pd.item, pd.price
	FROM `packages` AS p 
	LEFT JOIN `pkg_description` AS pd ON pd.pkg_id = p.id 
	WHERE p.drop_id = ".$id."
";
$pkg = $db->query($q);
if (isset($pkg[0])) {
	
	foreach ($pkg as $k=>$v) {
		$currPkgStatus = getPackageStatus($v->id);

		if ($currPkgStatus->status_text!='new') {
			continue;
		} else {
			exit(json_encode(array('type'=>'ok','text'=>$v)));
		}

	}
	
}


?>