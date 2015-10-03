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

if ($user['rankname']!='admin') exit('asd');

$id = addslashes(strip_tags(filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT)));
if (!isset($id)) { exit(json_encode(array('type'=>'error','text'=>'ID пустой!'))); }


if ($id!=$user['id']) { exit(json_encode(array('type'=>'error','text'=>'Попытка взлома?!'))); }

$q = "
	SELECT p.id, p.drop_id, p.*, p.action, pd.currency, pd.item, pd.price
	FROM `pkg_admin_approve` AS pa
	LEFT JOIN `packages` AS p ON p.id = pa.pkg_id
	LEFT JOIN `pkg_description` AS pd ON pd.pkg_id = p.id
";
$pkg = $db->query($q);
if (isset($pkg[0])) {
	
	foreach ($pkg as $k=>$v) {
		exit(json_encode(array('type'=>'ok','text'=>$v)));
	}
	
}


?>