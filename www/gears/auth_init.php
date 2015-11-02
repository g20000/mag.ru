<?php
// выход

$post_exit = filter_input(INPUT_POST, 'exit');
$get_exit = filter_input(INPUT_GET, 'exit');

if (($post_exit && $post_exit == 1) || ($get_exit && $get_exit == 1)) {
	setcookie($cfg["cookiename"], "", time()-3600, '/');
	header('Location: '.$cfg['options']['siteurl']);
	exit();
}

$post_act = filter_input(INPUT_POST, 'act');
$post_login = addslashes(filter_input(INPUT_POST, 'login'));
$post_password = filter_input(INPUT_POST, 'password');
$post_remember = filter_input(INPUT_POST, 'remember', FILTER_VALIDATE_BOOLEAN);
$user_ip = filter_input(INPUT_SERVER, 'REMOTE_ADDR');
// если есть пост то проверяем и отдаем в json, значит это ajax из логина
if ($post_act && $post_act =='login' && $post_login && $post_password ) {
	// смотрим есть ли метка о запоминании пользователя
	$remember = $post_remember ? time()+60*60*24*30 : NULL;	
	if (empty($post_login) || empty($post_password)) {
		exit(json_encode(Array('error'=>'1','title'=>'Deny','text'=>'Неправильный логин или пароль')));
	}
	// смотрим есть ли в базе такая учетка
	$value = $db->query("SELECT * FROM users WHERE (`name` = '".$post_login."') AND (`password` = '".md5($post_password)."')");
	
	// если нет
	if (count($value)<=0) {
		// отдаем ошибку
		exit(json_encode(Array('error'=>'1','title'=>'Deny','text'=>'Неправильный логин или пароль')));
	} else {
		$hash = md5($post_login.md5($post_password).$user_ip);
		// если есть то ставим куку до конца сессии на весь домен
		setcookie($cfg["cookiename"], $hash, $remember, '/');
		// обновляем данные куки в базе
		$db->query("UPDATE `users` SET  `sid` =  '".$hash."' WHERE `id` = ".$value[0]->id.";");
		// отдаем ответ
		exit(json_encode(Array('error'=>'0','title'=>'','text'=>'all ok')));
	}
	
}

$cookie = filter_input(INPUT_COOKIE, $cfg["cookiename"]);

// если кука не пришла в PHP то показываем логин
if (!$cookie) {
	if (!file_exists('./gears/auth_index.php')) {
		include('../gears/auth_index.php');
	} else {
		include('./gears/auth_index.php');
	}
	
	exit();
}

// если кука есть то проверяем есть ли учетка с таким хешем
if ($cookie) {
	
	// only hex allow
	if (!preg_match("/^[a-f0-9]{1,}$/is", $cookie)) {
		exit('Hack attempt!');
	}
	
	$value=$db->query("SELECT user.*, rank.rankname, rank.rankrights FROM `users` as user LEFT JOIN `rank` AS rank ON user.rank = rank.id WHERE user.sid = '".$cookie."'");
	// если хеша нет то показываем логин
	if (count($value)<=0) { 
		if (!file_exists('./gears/auth_index.php')) {
			include('../gears/auth_index.php');
		} else {
			include('./gears/auth_index.php');
		}
		exit(); 
	} else {
		// если все ОК то заносим данные о пользователе в конфиг
		foreach($value[0] as $k=>$v) {
			if ($k=='rankrights') continue;
			$user[$k]=$v;
		}
		unset($k);
		unset($v);
		$user['rights'] = unserialize($value[0]->rankrights); 
		if ($value[0]->status=='ban') {
			if (!file_exists('./gears/auth_ban.php')) {
				include('../gears/auth_ban.php');
			} else {
				include('./gears/auth_ban.php');
			}
			exit();
		}
		// обновляем last_time
		$db->query("UPDATE `users` SET `last_time` = '".date("Y-m-d H:i:s", time())."' WHERE `sid` = '".$cookie."'");
	}
}


?>