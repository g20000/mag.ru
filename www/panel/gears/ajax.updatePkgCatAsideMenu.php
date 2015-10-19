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

	if ($user['rankname']!='support' && $user['rankname']!='admin' && $user['rankname']!='shipper') {
		exit('Запрещено!');
	}

	// фильтруем входящие данные
	$itemName = trim(filter_input(INPUT_POST, 'nameItem', FILTER_UNSAFE_RAW));
	$itemId = filter_input(INPUT_POST, 'idItem', FILTER_VALIDATE_INT);
	if($itemName == ""){
		exit(json_encode(array('type'=>'error','text'=>'Заполните поля!')));
	}elseif(($itemId == false) || ($itemId == null)){
		exit(json_encode(array('type'=>'error','text'=>'Ошибка! Невозможно удалить запись!')));
	}

	$q = 'UPDATE `pkg_cat_aside_menu` SET `name`= "'.$itemName.'" WHERE pkg_cat_ddlist_id='.$itemId;
	$db->query($q);

	exit(json_encode(array('type'=>'ok','text'=>'Сохранено!')));
?>