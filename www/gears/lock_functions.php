<?php

/* === Местка времени === */

function microtime_float() {
	list($usec, $sec) = explode(" ", microtime());
	return ((float) $usec + (float) $sec);
}

/* === смотрим язык пользователя === */

function getClientLang() {
	$lang = filter_input(INPUT_SERVER, 'HTTP_ACCEPT_LANGUAGE', FILTER_SANITIZE_SPECIAL_CHARS);
	$lang_ex = explode(",", $lang);
	$out = isset($lang_ex[0]) ? mb_strtolower($lang_ex[0], 'UTF-8') : 'en-en';

	switch ($out) {
		case 'ru-ru':
			$out = "ru";
			break;
		case 'en-en':
			$out = "en";
			break;
		default:
			$out = "en";
	}

	return $out;
}

/* === Выбираем фразу из словаря || аргумент массива (например month, 1 ) === */

function ln($txt, $arrayItem = NULL) {
	global $cfg, $l18n;
	if ($arrayItem !== NULL) {
		echo isset($l18n[$cfg['ln']][$txt][$arrayItem]) ? $l18n[$cfg['ln']][$txt][$arrayItem] : $txt;
	} else {
		echo isset($l18n[$cfg['ln']][$txt]) ? $l18n[$cfg['ln']][$txt] : $txt;
	}
}

/* === Выбираем фразу из словаря || аргумент массива (например month, 1 ) === */

function getln($txt, $arrayItem = NULL) {
	global $cfg, $l18n;
	if ($arrayItem !== NULL) {
		return isset($l18n[$cfg['ln']][$txt][$arrayItem]) ? $l18n[$cfg['ln']][$txt][$arrayItem] : $txt;
	} else {
		return isset($l18n[$cfg['ln']][$txt]) ? $l18n[$cfg['ln']][$txt] : $txt;
	}
}



/* === обработка пути и выдача контента === */

function getTitle() {
	global $page, $thread;
	if (isset($page->title))
		return $page->title;
	if (isset($thread->title))
		return $thread->title;
}

/* === имя пользователя по ID === */

function getUserNameById($id) {
	global $db, $user;

	$q = "
		-- смотрим пользователя [getUserNameById]
		SELECT name FROM `users` WHERE `id` = " . $id . "
	";
	$res = $db->query($q);
	if (!isset($res[0])) {
		$out = false;
	} else {
		$out = $res[0]->name;
	}
	return $out;
}

function getFullUserNameById($id) {
	global $db, $user;
	
	if (empty($id)) return false;
	
	$q = "
		-- смотрим пользователя [getFullUserNameById]
		SELECT first_name, middle_name, last_name FROM `users` WHERE `id` = " . $id . "
	";
	$res = $db->query($q);
	if (!isset($res[0])) {
		$out = false;
	} else {
		$out = $res[0]->first_name.' '.$res[0]->middle_name.' '.$res[0]->last_name;
	}
	return $out;
}


/* === Смотрим профиль === */

function getUserProfile($id) {
	global $db;

	$q = "
		-- смотрим профиль пользователя [getUserProfile]
		SELECT * FROM `users` WHERE `id` = " . $id . "
	";

	$res = $db->query($q);
	if (isset($res[0])) {
		$ret = $res[0];
	} else {
		$ret = false;
	}

	return $ret;
}

/* === инфа о пользователе по ID === */

function getUserInfoById($id) {
	global $db;
	if (!is_numeric($id)) return false;
	$q = "
		-- смотрим ID пользователя [getUserInfoById]
		SELECT u.*, r.rankname FROM `users` as u LEFT JOIN `rank` AS r ON u.rank = r.id 
		WHERE u.id = ".$id."
		ORDER BY u.id
	";
	$ret = $db->query($q);
	if (isset($ret[0])) {
		$out = $ret[0];
	} else {
		$out = false;
	}
	return $out;
}

/* === иконка пользователя по ID === */

function getUserIconById($id) {
	$info = getUserInfoById($id);
	if (isset($info)) {
		switch ($info->rankname) {
			case 'admin':
				$out = '<i class="fa fa-user text-info"></i>';
				break;
			case 'support':
				$out = '<i class="fa fa-male fa-lg text-info"></i>';
				break;
			case 'shipper':
				$out = '<i class="fa fa-truck text-info"></i>';
				break;
			case 'drop':
				$out = '<i class="fa fa-male fa-lg text-info"></i>';
				break;
			case 'buyer':
				$out = '<i class="fa fa-usd fa-lg text-info"></i>';
				break;
			default :
				$out = '';
				break;
		}
	} else {
		$out = $res[0]->name;
	}
	return $out;
}


/* === ID пользователя по имени === */

function getUserIdByName($name) {
	global $db;
	$q = "
		-- смотрим ID пользователя [getUserIdByName]
		SELECT id FROM `users` WHERE `name` = '" . $name . "'
	";
	$ret = $db->query($q);
	if (isset($ret[0])) {
		$out = $ret[0]->id;
	} else {
		$out = false;
	}
	return $out;
}


/* === ранг по ее id === */

function getRankById($id) {
	global $db, $cacheMemory;

	$q = "
		-- ранг по ее id [getRankById]
		SELECT * FROM `rank` WHERE `id` = " . $id . "
	";

	$res = $db->query($q);
	$ret = $res[0];

	return $ret;
}

function isOnPage($val) {
	global $route;
	if ($route->type=='page' && $route->value==$val) {
		return 'active';
	}
}


/* === преобразуем время типа "Tue, 18 Feb 2014 12:16:57 +0400" в таймстемп и отдаем массив с двумя видами === */
function mailDateToTime($str) {
	$time = strtotime(trim($str));
	$engTime = date("r", $time)."\n";
	return array('timestamp'=>$time,'engTime'=>$engTime);

}


/* === берем всех пользователей для списка пользователей === */
function getUsersList() {
	global $db, $user;
	// проверить права на доступ
	$users = $db->query("SELECT u.*, r.rankname FROM `users` as u LEFT JOIN `rank` AS r ON u.rank = r.id ORDER BY u.id");
	if (isset($users[0])) {
		$out = $users;
	} else {
		$out = false;
	}
	return $out;
}

/* === берем всех пользователей определенного ранга === */
function getUsersListRank($rankid) {
	global $db;
	// проверить права на доступ
	$users = $db->query("SELECT u.*, r.rankname FROM `users` as u LEFT JOIN `rank` AS r ON u.rank = r.id WHERE u.rank = ".$rankid." ORDER BY u.id");
	if (isset($users[0])) {
		$out = $users;
	} else {
		$out = false;
	}
	return $out;
}


function sortByKey($array1, $key){
	debug($array1);
	function swap(&$arr1,$key1,$key2) {
		$_t = $arr1[$key1];
		$arr1[$key1] = $arr1[$key2];
		$arr1[$key2] = $_t;
	}
	
	foreach($array1 as $k=>$v) {
		foreach($array1 as $mk=>$mv) {

			if (isset($array1[$mk+1])) {
				if (gettype($mv)=='object') { $keyval1=$mv->$key; } else { $keyval1=$mv[$key]; }
				if (gettype($array1[$mk+1])=='object') { $keyval2=$array1[$mk+1]->$key; } else { $keyval2=$array1[$mk+1][$key]; }
				if ($keyval1<$keyval2) { swap($array1, $mk+1, $mk); }
			}

		}
		
	}
	
	return $array1;
}


/* смотрим кол-во новых паков для админа и саппорта */
function getNewPkgs() {
	global $user, $db;
	$count = 0;
	$q = "
		SELECT p.id, p.drop_id, p.*, p.action, pd.currency, pd.item, pd.price
		FROM `pkg_admin_approve` AS pa
		LEFT JOIN `packages` AS p ON p.id = pa.pkg_id
		LEFT JOIN `pkg_description` AS pd ON pd.pkg_id = p.id
	";
	$pkg = $db->query($q);
	if (isset($pkg[0])) {
		$count = count($pkg);
	} else {
		$count=0;
	}
	if ($count>0) {
		return '<span class="pull-right badge text-warning">'.$count.'</span>';
	} else {
		return '';
	}
}


function getNavMenu() {
	global $user, $cfg;
	if ($user['rankname']=='drop') {
		echo '
		<li class="'.isOnPage('dropinbox').'"><a href="'.$cfg['options']['siteurl'].'/dropinbox">Входящие '.getDropNewPackagesCount($user['id']).'</a></li>		
		<li class="'.isOnPage('dropoutbox').'"><a href="'.$cfg['options']['siteurl'].'/dropoutbox">Исходящие</a></li>
		<li class="'.isOnPage('chat').'"><a href="'.$cfg['options']['siteurl'].'/chat">Чат &nbsp;&nbsp;<span class="badge text-warning">'.getNewChatMsg().'</span></a></li>
		<li><a href="'.$cfg['options']['siteurl'].'/?exit=1">Выход <small>('.$user['name'].')</small></a></li>
		';
	} elseif($user['rankname']=='admin') {
		echo '
		<li class="'.isOnPage('packages').'"><a href="'.$cfg['options']['siteurl'].'/packages">Товары &nbsp;&nbsp;'.getNewPkgs().'</a></li>
		<li class="'.isOnPage('lablers').'"><a href="'.$cfg['options']['siteurl'].'/lablers">Сортировщики</a></li>
		<li class="'.isOnPage('drops').'"><a href="'.$cfg['options']['siteurl'].'/drops">Сотрудники</a></li>
		<li class="'.isOnPage('shippers').'"><a href="'.$cfg['options']['siteurl'].'/shippers">Отправители</a></li>
		<li class="'.isOnPage('buyers').'"><a href="'.$cfg['options']['siteurl'].'/buyers">Покупатели</a></li>
		<li class="'.isOnPage('chat').'"><a href="'.$cfg['options']['siteurl'].'/chat">Чат &nbsp;&nbsp;<span class="badge text-warning">'.getNewChatMsg().'</span></a></li>
		<li><a href="'.$cfg['options']['siteurl'].'/?exit=1">Выход <small>('.$user['name'].')</small></a></li>
		';
	} elseif($user['rankname']=='shipper') {
		echo '
		<li class="'.isOnPage('packages').'"><a href="'.$cfg['options']['siteurl'].'/packages">Товары</a></li>
		<li class="'.isOnPage('chat').'"><a href="'.$cfg['options']['siteurl'].'/chat">Чат &nbsp;&nbsp;<span class="badge text-warning">'.getNewChatMsg().'</span></a></li>
		<li><a href="'.$cfg['options']['siteurl'].'/?exit=1">Выход <small>('.$user['name'].')</small></a></li>
		';
	} elseif($user['rankname']=='buyer') {
		echo '
		<li class="'.isOnPage('buyerinbox').'"><a href="'.$cfg['options']['siteurl'].'/buyerinbox">Входящие</a></li>
		<li class="'.isOnPage('buyercompleated').'"><a href="'.$cfg['options']['siteurl'].'/buyercompleated">Завершенные</a></li>
		<li><a href="'.$cfg['options']['siteurl'].'/?exit=1">Выход <small>('.$user['name'].')</small></a></li>
		';		
	} elseif($user['rankname']=='labler') {
		echo '
		<li class="'.isOnPage('lablerinbox').'"><a href="'.$cfg['options']['siteurl'].'/lablerinbox">Входящие</a></li>
		<li><a href="'.$cfg['options']['siteurl'].'/?exit=1">Выход <small>('.$user['name'].')</small></a></li>
		';		
	} elseif($user['rankname']=='support') {
		echo '
		<li class="'.isOnPage('packages').'"><a href="'.$cfg['options']['siteurl'].'/packages">Товары &nbsp;&nbsp;'.getNewPkgs().'</a></li>
		<li class="'.isOnPage('drops').'"><a href="'.$cfg['options']['siteurl'].'/drops">Сотрудники</a></li>
		<li class="'.isOnPage('shippers').'"><a href="'.$cfg['options']['siteurl'].'/shippers">Отправители</a></li>
		<li class="'.isOnPage('buyers').'"><a href="'.$cfg['options']['siteurl'].'/buyers">Покупатели</a></li>
		<li class="'.isOnPage('chat').'"><a href="'.$cfg['options']['siteurl'].'/chat">Чат &nbsp;&nbsp;<span class="badge text-warning">'.getNewChatMsg().'</span></a></li>
		<li><a href="'.$cfg['options']['siteurl'].'/?exit=1">Выход <small>('.$user['name'].')</small></a></li>
		';		
	} else {
		exit('oops!');
	}
}


function sideMenu_labler() {
	global $cfg;
	echo '
	<ul class="nav nav-sidebar">
		<li class="'.isOnPage('news').'"><a href="'.$cfg['options']['siteurl'].'/news"><i class="fa fa-list text-primary"></i> Новости</a></li>
	</ul>
	<ul class="nav nav-sidebar">
		<li class="'.isOnPage('lablerinbox').'"><a href="'.$cfg['options']['siteurl'].'/lablerinbox"><i class="fa fa-paste text-success"></i> Товары</a></li>
	</ul>
	<ul class="nav nav-sidebar">
		<li class="'.isOnPage('chat').'"><a href="'.$cfg['options']['siteurl'].'/chat"><i class="fa fa-comments text-warning"></i> Чат</a></li>
	</ul>	
	<ul class="nav nav-sidebar">
		
		<li class="'.isOnPage('profile').'"><a href="'.$cfg['options']['siteurl'].'/profile"><i class="fa fa-user"></i> Профиль</a></li>
		<li><a href="'.$cfg['options']['siteurl'].'/?exit=1"><i class="fa fa-power-off"></i> Выход</a></li>
	</ul>
	';
}


function sideMenu_buyer() {
	global $cfg;
	echo '
	<ul class="nav nav-sidebar">
		<li class="'.isOnPage('news').'"><a href="'.$cfg['options']['siteurl'].'/news"><i class="fa fa-list text-primary"></i> Новости</a></li>
	</ul>
	<ul class="nav nav-sidebar">
		<li class="'.isOnPage('buyerinbox').'"><a href="'.$cfg['options']['siteurl'].'/buyerinbox"><i class="fa fa-level-down fa-lg text-info"></i> Входящие</a></li>
		<li class="'.isOnPage('buyercompleated').'"><a href="'.$cfg['options']['siteurl'].'/buyercompleated"><i class="fa fa-level-up fa-lg text-info"></i> Завершенные</a></li>
	</ul>
	<ul class="nav nav-sidebar">
		<li class="'.isOnPage('chat').'"><a href="'.$cfg['options']['siteurl'].'/chat"><i class="fa fa-comments text-warning"></i> Чат</a></li>
	</ul>	
	<ul class="nav nav-sidebar">
		
		<li class="'.isOnPage('profile').'"><a href="'.$cfg['options']['siteurl'].'/profile"><i class="fa fa-user"></i> Профиль</a></li>
		<li><a href="'.$cfg['options']['siteurl'].'/?exit=1"><i class="fa fa-power-off"></i> Выход</a></li>
	</ul>
	';
}

function sideMenu_shipper() {
	global $cfg;
	echo '
	<ul class="nav nav-sidebar">
		<li class="'.isOnPage('news').'"><a href="'.$cfg['options']['siteurl'].'/news"><i class="fa fa-list text-primary"></i> Новости</a></li>
	</ul>
	<ul class="nav nav-sidebar">
		<li class="'.isOnPage('packages').'"><a href="'.$cfg['options']['siteurl'].'/packages"><i class="fa fa-paste text-success"></i> Товары</a></li>
	</ul>
	<ul class="nav nav-sidebar">
		<li class="'.isOnPage('dropslist').'"><a href="'.$cfg['options']['siteurl'].'/dropslist"><i class="fa fa-user-secret  text-info"></i> Сотрудники</a></li>
	</ul>
	<ul class="nav nav-sidebar">
		<li class="'.isOnPage('chat').'"><a href="'.$cfg['options']['siteurl'].'/chat"><i class="fa fa-comments text-warning"></i> Чат</a></li>
	</ul>	
	<ul class="nav nav-sidebar">
		
		<li class="'.isOnPage('profile').'"><a href="'.$cfg['options']['siteurl'].'/profile"><i class="fa fa-user"></i> Профиль</a></li>
		<li><a href="'.$cfg['options']['siteurl'].'/?exit=1"><i class="fa fa-power-off"></i> Выход</a></li>
	</ul>
	';
}

function sideMenu_drop() {
	global $cfg,$user;
	echo '
	<ul class="nav nav-sidebar">
		<li class="'.isOnPage('news').'"><a href="'.$cfg['options']['siteurl'].'/news"><i class="fa fa-list text-primary"></i> Новости</a></li>
	</ul>
	<ul class="nav nav-sidebar">
		<li class="'.isOnPage('dropinbox').'"><a href="'.$cfg['options']['siteurl'].'/dropinbox"><i class="fa fa-level-down fa-lg text-info"></i> Входящие '.getDropNewPackagesCount($user['id']).'</a></li>
		<li class="'.isOnPage('dropoutbox').'"><a href="'.$cfg['options']['siteurl'].'/dropoutbox"><i class="fa fa-level-up fa-lg text-info"></i> Исходящие</a></li>
	</ul>
	<ul class="nav nav-sidebar">
		<li class="'.isOnPage('chat').'"><a href="'.$cfg['options']['siteurl'].'/chat"><i class="fa fa-comments text-warning"></i> Чат</a></li>
	</ul>	
	<ul class="nav nav-sidebar">
		
		<li class="'.isOnPage('profile').'"><a href="'.$cfg['options']['siteurl'].'/profile"><i class="fa fa-user"></i> Профиль</a></li>
		<li><a href="'.$cfg['options']['siteurl'].'/?exit=1"><i class="fa fa-power-off"></i> Выход</a></li>
	</ul>
	';
}

function sideMenu_support() {
	global $cfg;
	echo '
	<ul class="nav nav-sidebar">
		<li class="'.isOnPage('news').'"><a href="'.$cfg['options']['siteurl'].'/news"><i class="fa fa-list text-primary"></i> Новости</a></li>
	</ul>
	<ul class="nav nav-sidebar">
		<li class="'.isOnPage('packages').'"><a href="'.$cfg['options']['siteurl'].'/packages"><i class="fa fa-paste text-success"></i> Товары '.getNewPkgs().'</a></li>
		<li class="'.isOnPage('dropslist').'"><a href="'.$cfg['options']['siteurl'].'/dropslist"><i class="fa fa-user-secret  text-info"></i> Сотрудники</a></li>			
	</ul>
	<ul class="nav nav-sidebar">
		<li class="'.isOnPage('drops').'"><a href="'.$cfg['options']['siteurl'].'/drops"><i class="fa fa-male fa-lg text-info"></i> Сотрудники</a></li>
		<li class="'.isOnPage('shippers').'"><a href="'.$cfg['options']['siteurl'].'/shippers"><i class="fa fa-truck text-info"></i> Отправители</a></li>
		<li class="'.isOnPage('buyers').'"><a href="'.$cfg['options']['siteurl'].'/buyers"><i class="fa fa-usd fa-lg text-info"></i> Покупатели</a></li>
	</ul>
	<ul class="nav nav-sidebar">
		<li class="'.isOnPage('shops').'"><a href="'.$cfg['options']['siteurl'].'/shops"><i class="fa fa-shopping-cart fa-lg text-success"></i> Магазины</a></li>
	</ul>	
	<ul class="nav nav-sidebar">
		<li class="'.isOnPage('chat').'"><a href="'.$cfg['options']['siteurl'].'/chat"><i class="fa fa-comments text-warning"></i> Чат</a></li>
	</ul>	
	<ul class="nav nav-sidebar">
		
		<li class="'.isOnPage('profile').'"><a href="'.$cfg['options']['siteurl'].'/profile"><i class="fa fa-user"></i> Профиль</a></li>
		<li><a href="'.$cfg['options']['siteurl'].'/?exit=1"><i class="fa fa-power-off"></i> Выход</a></li>
	</ul>
	';
}



function sideMenu_admin() {
	global $cfg;
	echo '
	<ul class="nav nav-sidebar">
		<li class="'.isOnPage('statsAdmin').'"><a href="'.$cfg['options']['siteurl'].'/statsAdmin"><i class="fa fa-signal text-primary"></i> Статистика</a></li>
		<li class="'.isOnPage('news').'"><a href="'.$cfg['options']['siteurl'].'/news"><i class="fa fa-list text-primary"></i> Новости</a></li>
	</ul>
	<ul class="nav nav-sidebar">
		<li class="'.isOnPage('packages').'"><a href="'.$cfg['options']['siteurl'].'/packages"><i class="fa fa-paste text-success"></i> Товары '.getNewPkgs().'</a></li>
		<li class="'.isOnPage('dropslist').'"><a href="'.$cfg['options']['siteurl'].'/dropslist"><i class="fa fa-user-secret  text-info"></i> Сотрудники</a></li>
	</ul>
	<ul class="nav nav-sidebar">
		<li class="'.isOnPage('lablers').'"><a href="'.$cfg['options']['siteurl'].'/lablers"><i class="fa fa-barcode fa-sm text-info"></i> Сортировщики</a></li>
		<li class="'.isOnPage('drops').'"><a href="'.$cfg['options']['siteurl'].'/drops"><i class="fa fa-male fa-lg text-info"></i> Сотрудники</a></li>
		<li class="'.isOnPage('shippers').'"><a href="'.$cfg['options']['siteurl'].'/shippers"><i class="fa fa-truck text-info"></i> Отправители</a></li>
		<li class="'.isOnPage('buyers').'"><a href="'.$cfg['options']['siteurl'].'/buyers"><i class="fa fa-usd fa-lg text-info"></i> Покупатели</a></li>
	</ul>
	<ul class="nav nav-sidebar">
		<li class="'.isOnPage('shops').'"><a href="'.$cfg['options']['siteurl'].'/shops"><i class="fa fa-shopping-cart fa-lg text-success"></i> Магазины</a></li>
	</ul>
	<ul class="nav nav-sidebar">
		<li class="'.isOnPage('chat').'"><a href="'.$cfg['options']['siteurl'].'/chat"><i class="fa fa-comments text-warning"></i> Чат</a></li>
	</ul>	
	<ul class="nav nav-sidebar">
		<li class="'.isOnPage('users').'"><a href="'.$cfg['options']['siteurl'].'/users"><i class="fa fa-group text-danger"></i> Аккаунты</a></li>
		<li class="'.isOnPage('options').'"><a href="'.$cfg['options']['siteurl'].'/options"><i class="fa fa-cogs text-danger"></i> Опции</a></li>
		<li class="'.isOnPage('blackshoplist').'"><a href="'.$cfg['options']['siteurl'].'/blackshoplist"><i class="fa fa-list text-danger"></i> Черный лист магазинов</a></li>
		<li class="'.isOnPage('receptlist').'"><a href="'.$cfg['options']['siteurl'].'/receptlist"><i class="fa fa-list text-danger"></i> Лист приемки</a></li>
	</ul>
	<ul class="nav nav-sidebar">
		
		<li class="'.isOnPage('profile').'"><a href="'.$cfg['options']['siteurl'].'/profile"><i class="fa fa-user"></i> Профиль</a></li>
		<li><a href="'.$cfg['options']['siteurl'].'/?exit=1"><i class="fa fa-power-off"></i> Выход</a></li>
	</ul>
	';		
}

function getSideMenu() {
	global $user;
	call_user_func('sideMenu_'.$user['rankname']);
}


function getPackageTrackById($id) {
	global $db;
	$q = "SELECT * FROM `trackers` WHERE `pkg_id` = ".$id;
	$trk = $db->query($q);
	if (isset($trk[0])) {
		$track_type = $trk[0]->track_type;
		$track_num = $trk[0]->track_num;
	} else {
		$track_type = 'no tracks';
		$track_num = '';
	}
	return array('track_num'=>$track_num,'track_type'=>$track_type);
}

function getPackageStatus($id) {
	global $db;
	$q = "SELECT * FROM `pkg_statuses` WHERE `pkg_id` = ".$id." ORDER BY `time` DESC";
	$status = $db->query($q);
	if (isset($status[0])) {
		$out = $status[0];
	}
	return $out;
}

function getLablerPackages($status) {
	global $db, $user;
	if ($user['rankname']!='labler') { return false; }
	
	$groups = array();
		
	$q = "
		SELECT p.*, pd.item, pd.price, pd.currency
		FROM `packages` AS p 
		LEFT JOIN `pkg_description` AS pd ON pd.pkg_id = p.id
		ORDER BY `id` DESC
	";
	
	$pkg = $db->query($q);
	if (isset($pkg[0])) {
		$out = array();
		foreach ($pkg as $k=>$v) {
			$currPkgStatus = getPackageStatus($v->id);
			
			
			//if ($currPkgStatus->status_text!='accepted' && $currPkgStatus->status_text!='ondrop') {
			//	continue;
			//} 

			
			$pkg[$k]->status_text = $currPkgStatus->status_text;
			$track = getPackageTrackById($v->id);
			$pkg[$k]->track_type = $track['track_type'];
			$pkg[$k]->track_num = $track['track_num'];
			if (!empty($v->group_hash)) {
				$groups[$v->group_hash][] = $pkg[$k];
			} else {
				$groups[$k] = $pkg[$k];
			}
		}
		$out = $groups;
	} else {
		$out = false;
	}
	return $out;	
}

function getBuyerPackages($status) {
	global $db, $user;
	if ($user['rankname']!='buyer') { return false; }
	
	$groups = array();
		
	
	$q = "
		SELECT p.*, pd.item, pd.price, pd.currency
		FROM `packages` AS p 
		LEFT JOIN `pkg_description` AS pd ON pd.pkg_id = p.id 
		WHERE p.buyer_id = ".$user['id']."
	";
	
	$pkg = $db->query($q);
	if (isset($pkg[0])) {
		$out = array();
		foreach ($pkg as $k=>$v) {
			$currPkgStatus = getPackageStatus($v->id);
			
			
				if ($status=='inbox') {
					if ($currPkgStatus->status_text!='tobuyer') {
						continue;
					}
				} else {
					if ($currPkgStatus->status_text!='compleate') {
						continue;
					}				
				}
			
			$pkg[$k]->status_text = $currPkgStatus->status_text;
			$track = getPackageTrackById($v->id);
			$pkg[$k]->track_type = $track['track_type'];
			$pkg[$k]->track_num = $track['track_num'];
			if (!empty($v->group_hash)) {
				$groups[$v->group_hash][] = $pkg[$k];
			} else {
				$groups[$k] = $pkg[$k];
			}
		}
		$out = $groups;
	} else {
		$out = false;
	}
	return $out;
}

// кол-во новых заданий для дропа
function getDropNewPackagesCount($id) {
	global $db, $user;
	if ($user['rankname']!='drop') { return false; }
	
	$groups = array();
		
	
	$q = "
		SELECT p.id, p.drop_id
		FROM `packages` AS p 
		LEFT JOIN `pkg_description` AS pd ON pd.pkg_id = p.id 
		WHERE p.drop_id = ".$id."
	";
	$pkg = $db->query($q);
	$count=0;
	if (isset($pkg[0])) {
		foreach ($pkg as $k=>$v) {
			$currPkgStatus = getPackageStatus($v->id);
			
			if ($currPkgStatus->status_text!='new') {
				continue;
			} else {
				$count++;
			}

		}
	}
	return $count==0 ? '' : '<span class="badge text-danger">'.$count.'</span>';
}


function getDropPackages($status) {
	global $db, $user;
	if ($user['rankname']!='drop') { return false; }
	
	$groups = array();
		
	
	$q = "
		SELECT p.*, pd.item, pd.price, pd.currency
		FROM `packages` AS p 
		LEFT JOIN `pkg_description` AS pd ON pd.pkg_id = p.id 
		WHERE p.drop_id = ".$user['id']."
		ORDER BY `id` DESC
	";
	$pkg = $db->query($q);
	if (isset($pkg[0])) {
		$out = array();
		foreach ($pkg as $k=>$v) {
			$currPkgStatus = getPackageStatus($v->id);
			
			
			if ($status=='inbox') {
				if ($currPkgStatus->status_text!='todrop' && $currPkgStatus->status_text!='new' && $currPkgStatus->status_text!='accepted') {
					continue;
				}
			} else {
				if ($currPkgStatus->status_text!='labled' && $currPkgStatus->status_text!='ondrop') {
					continue;
				}				
			}
			
			$pkg[$k]->status_text = $currPkgStatus->status_text;
			$track = getPackageTrackById($v->id);
			$pkg[$k]->track_type = $track['track_type'];
			$pkg[$k]->track_num = $track['track_num'];
			if (!empty($v->group_hash)) {
				$groups[$v->group_hash][] = $pkg[$k];
			} else {
				$groups[$k] = $pkg[$k];
			}
		}
		$out = $groups;
	} else {
		$out = false;
	}
	return $out;	
}

// листинг паков
function getPackages($id = false) {
	global $db, $user;
	
	$groups = array();
	$where = '';
	if ($user['rankname']!='admin' && $user['rankname']!='support') {
		$where = "WHERE p.".$user['rankname']."_id = ".$user['id'];
		if ($id!=false) { $where .= ' AND p.id = '.$id; }
	} else {
		if ($id!=false) { $where .= 'WHERE p.id = '.$id; }
	}
	
	$q = "
		SELECT p.*, pd.item, pd.price, pd.currency
		FROM `packages` AS p 
		LEFT JOIN `pkg_description` AS pd ON pd.pkg_id = p.id 
		".$where."
	";
	
	$pkg = $db->query($q);
	if (isset($pkg[0])) {
		
		$out = array();
		foreach ($pkg as $k=>$v) {
			$track = getPackageTrackById($v->id);
			$pkg[$k]->track_type = $track['track_type'];
			$pkg[$k]->track_num = $track['track_num'];
			$currPkgStatus = getPackageStatus($v->id);
			$pkg[$k]->status_text = $currPkgStatus->status_text;
			if (!empty($v->group_hash)) {
				$groups[$v->group_hash][] = $pkg[$k];
			} else {
				$groups[$k] = $pkg[$k];
			}
		}
		$out = $groups;
		//sortByKey($array1, $key); // TODO: надо как-то сортировать по статусу.
	} else {
		$out = false;
	}
	return $out;	
}


// листинг паков
function getUserPackages($id) {
	global $db;
	
	$groups = array();
	
	// смотрим что за пользователь
	$q = "SELECT u.*, r.rankname FROM users AS u LEFT JOIN `rank` AS r ON r.id = u.rank WHERE u.id = ".$id;
	$usr = $db->query($q);
	if (isset($usr[0])) {
		$user_rank = $usr[0]->rankname;
	}
	
	
	$q = '
		SELECT p.*, pd.item, pd.price, pd.currency
		FROM `packages` AS p 
		LEFT JOIN `pkg_description` AS pd ON pd.pkg_id = p.id 
		WHERE p.'.$user_rank.'_id = '.$id.'
	';
	$pkg = $db->query($q);
	
	if (isset($pkg[0])) {
		$out = array();
		foreach ($pkg as $k=>$v) {
			$track = getPackageTrackById($v->id);
			$pkg[$k]->track_type = $track['track_type'];
			$pkg[$k]->track_num = $track['track_num'];
			if (!empty($v->group_hash)) {
				$groups[$v->group_hash][] = $pkg[$k];
			} else {
				$groups[$k] = $pkg[$k];
			}
		}
		$out = $groups;
	} else {
		$out = false;
	}
	return $out;	
}

function getPackageStatuses($id) {
	global $db;
	// get statuses
	$q = "SELECT * FROM `pkg_statuses` WHERE `pkg_id` = ".$id." ORDER BY `time` DESC";
	$stat = $db->query($q);	
	if (isset($stat[0])) {
		$out = $stat;
	} else {
		$out = false;
	}
	return $out;
}


// на странице просмотра пака
function getPackageInfo($id) {
	global $db, $user;
	
	// если шипер, то смотрим его ли пак
	if ($user['rankname']=='shipper') {
		$q = "SELECT shipper_id FROM `packages` WHERE id = ".$id;
		$isOwner = $db->query($q);
		if (!isset($isOwner[0]) || $isOwner[0]->shipper_id != $user['id']) {
			return false;
		}
	}
	
	// if group
	$q = "SELECT group_hash FROM `packages` WHERE id = ".$id;
	$hash = $db->query($q);
	if (isset($hash[0]) && $hash[0]->group_hash !='') {
		$where = "p.group_hash = '".$hash[0]->group_hash."'";
	} else {
		$where = 'p.id = '.$id;
	}

	$q = '
		SELECT p.*, pd.item, pd.price, pd.currency
		FROM `packages` AS p 
		LEFT JOIN `pkg_description` AS pd ON pd.pkg_id = p.id 
		WHERE '.$where.'
	';
	$pkg = $db->query($q);
	if (isset($pkg[0])) {
		$out = array();
		foreach ($pkg as $k=>$v) {
			$q = "SELECT * FROM `trackers` WHERE `pkg_id` = ".$v->id;
			$trk = $db->query($q);
			if (isset($trk[0])) {
				$pkg[$k]->track_type = $trk[0]->track_type;
				$pkg[$k]->track_num = $trk[0]->track_num;
			} else {
				$pkg[$k]->track_type = 'no tracks';
				$pkg[$k]->track_num = '';
			}
		}
		$out = $pkg;
	} else {
		$out = false;
	}
	return $out;	
}


// количество непрочитанных чатов
function getNewChatMsg() {
	global $user, $db;
	$q = "SELECT COUNT(*) as cnt FROM `chat` WHERE `to_id` = ".$user['id']." AND `is_read` = 0";
	$ret = $db->query($q);
	if (isset($ret[0])) {
		$out = $ret[0]->cnt;
	} else {
		$out = false;
	}
	return $out;
}

// берем конкретный диалог чата
function getChatDialog($hash) {
	global $db;
	$q = "SELECT * FROM `chat` WHERE `hash` = '".$hash."' ORDER BY `time` DESC;";
	$ret = $db->query($q);
	if (isset($ret[0])) {
		$out = $ret;
	} else {
		$out = false;
	}
	return $out;
}

// берем список чатов
function getAllChats() {
	global $user, $db;
	$q = "SELECT * FROM `chat_hashes` WHERE `from_id` = ".$user['id']." OR `to_id` = ".$user['id']."";
	$ret = $db->query($q);
	//debug($ret);
	if (isset($ret[0])) {
		$chats = array();
		foreach($ret as $v) {
			$chats[] = getChatDialog($v->hash);
		}
		$out = $chats;
	} else {
		$out = false;
	}
	return $out;
}

// при открытии делаем диалог прочитанным после вывода на страницу у данного пользователя
function setDialogReaded($hash) {
	global $user, $db;
	$q = "UPDATE `chat` SET `is_read` = 1 WHERE `hash` = '".$hash."' AND `to_id` = ".$user['id'];
	$db->query($q);
}

function getPackageNotes($id) {
	global $db;
	$q = "SELECT * FROM `pkg_notes` WHERE `pkg_id` = ".$id;
	$res = $db->query($q);
	if (isset($res[0])) {
		foreach($res as $v) {
			$out[$v->user_type][$v->type] = $v->note;
		}
	} else {
		$out = array();
	}
	return $out;
}

function getShopPacksCount($id) {
	global $db;
	$q = "SELECT COUNT(shop_id) as cnt FROM `packages` WHERE `shop_id` = ".$id;
	$res = $db->query($q);
	if (isset($res[0])) {
		$out = $res[0]->cnt;
	} else {
		$out = 0;
	}
	return $out;
}

function getAllShops() {
	global $db;
	$out = 'No precreated shops';
	$q = "SELECT * FROM `shops` ORDER BY `shop_name`";
	$res = $db->query($q);
	if (isset($res[0])) {
		$out = "<ul class='list-unstyled'>";
		foreach($res as $k=>$v) {
			$out .= '<li onclick="pasteShopData(this);" data-shop-id="'.$v->id.'" data-shop-name="'.$v->shop_name.'" data-shop-url="'.$v->shop_url.'" style="cursor:pointer;text-transform:capitalize;">'.$v->shop_name.' <span class="badge">'.getShopPacksCount($v->id).'</span></li>';
		}
		$out .= "</ul>";
	}
	return $out;
}

function getShopLinkById($id) {
	global $db, $cfg;
	$q = "SELECT * FROM `shops` WHERE `id` = ".$id;
	$res = $db->query($q);
	if (isset($res[0])) {
		$out = '<a href="'.$res[0]->shop_url.'" target="_blank">'.$res[0]->shop_name.'</a>';
	}
	return $out;
}

function getShopNameById($id) {
	global $db, $cfg;
	$q = "SELECT * FROM `shops` WHERE `id` = ".$id;
	$res = $db->query($q);
	if (isset($res[0])) {
		$out = $res[0]->shop_name;
	}
	return $out;
}


function getDropShops($id) {
	global $db;
	$q = "SELECT s.* FROM `packages` AS p LEFT JOIN `shops` AS s ON s.id = p.shop_id WHERE p.drop_id = ".$id." GROUP BY s.shop_name";
	$shops = $db->query($q);
	if (isset($shops)) {
		foreach($shops as $sv){
			echo '<span class="badge" style="text-transform:capitalize;">'.$sv->shop_name.'</span>&nbsp;';
		}
	}	
}

function getShipperShops($id) {
	global $db;
	$q = "SELECT s.* FROM `packages` AS p LEFT JOIN `shops` AS s ON s.id = p.shop_id WHERE p.shipper_id = ".$id." GROUP BY s.shop_name";
	$shops = $db->query($q);
	if (isset($shops)) {
		foreach($shops as $sv){
			echo '<span class="badge" style="text-transform:capitalize;">'.$sv->shop_name.'</span>&nbsp;';
		}
	}	
}

function getDropCompleatePkgs($id) {
	global $db;
	$q = "SELECT * FROM `packages` WHERE drop_id = ".$id.";";
	$pkgs = $db->query($q);
	
	$cnt=0;
	if (isset($pkgs[0])) {
		foreach($pkgs as $pk=>$pv) {
			$status = getPackageStatus($pv->id);
			if ($status->status_text=='tobuyer' || $status->status_text=='compleate') { $cnt++; }
		}
	}
	return $cnt;
}

function getDropInworkPkgs($id) {
	global $db;
	$q = "SELECT * FROM `packages` WHERE drop_id = ".$id.";";
	$pkgs = $db->query($q);
	$cnt=0;
	if (isset($pkgs[0])) {
		foreach($pkgs as $pk=>$pv) {
			$status = getPackageStatus($pv->id);
			if ($status->status_text!='onbuyer' && $status->status_text!='compleate') { $cnt++; }
		}
	}
	return $cnt;
}


function getShipperCompleatePkgs($id) {
	global $db;
	$q = "SELECT * FROM `packages` WHERE shipper_id = ".$id.";";
	$pkgs = $db->query($q);
	
	$cnt=0;
	if (isset($pkgs[0])) {
		foreach($pkgs as $pk=>$pv) {
			$status = getPackageStatus($pv->id);
			if ($status->status_text!='new' && $status->status_text!='todrop') { $cnt++; }
		}
	}
	return $cnt;
}

function getShipperInworkPkgs($id) {
	global $db;
	$q = "SELECT * FROM `packages` WHERE shipper_id = ".$id.";";
	$pkgs = $db->query($q);
	
	$cnt=0;
	if (isset($pkgs[0])) {
		foreach($pkgs as $pk=>$pv) {
			$status = getPackageStatus($pv->id);
			if ($status->status_text=='new' || $status->status_text=='accepted' || $status->status_text=='todrop') { $cnt++; }
		}
	}
	return $cnt;
}

function getBuyerCompleatePkgs($id) {
	global $db;
	$q = "SELECT * FROM `packages` WHERE buyer_id = ".$id.";";
	$pkgs = $db->query($q);
	
	$cnt=0;
	if (isset($pkgs[0])) {
		foreach($pkgs as $pk=>$pv) {
			$status = getPackageStatus($pv->id);
			if ($status->status_text=='compleate') { $cnt++; }
		}
	}
	return $cnt;
}

function getBuyerInworkPkgs($id) {
	global $db;
	$q = "SELECT * FROM `packages` WHERE buyer_id = ".$id.";";
	$pkgs = $db->query($q);
	
	$cnt=0;
	if (isset($pkgs[0])) {
		foreach($pkgs as $pk=>$pv) {
			$status = getPackageStatus($pv->id);
			if ($status->status_text=='onbuyer') { $cnt++; }
		}
	}
	return $cnt;
}


function getInboxPackages() {
	global $db, $user;
	
	$groups = array();
	
	$q = "
		SELECT p.*, pd.item, pd.price, pd.currency, ps.status_text
		FROM `packages` AS p 
		LEFT JOIN `pkg_description` AS pd ON pd.pkg_id = p.id 
		LEFT JOIN `pkg_statuses` AS ps ON ps.pkg_id = p.id 
		WHERE p.".$user['rankname']."_id = ".$user['id']." AND ps.status_text = 'to".$user['rankname']."'
	";
	$pkg = $db->query($q);
	if (isset($pkg[0])) {
		$out = array();
		foreach ($pkg as $k=>$v) {
			$track = getPackageTrackById($v->id);
			$pkg[$k]->track_type = $track['track_type'];
			$pkg[$k]->track_num = $track['track_num'];
			if (!empty($v->group_hash)) {
				$groups[$v->group_hash][] = $pkg[$k];
			} else {
				$groups[$k] = $pkg[$k];
			}
		}
		$out = $groups;
	} else {
		$out = false;
	}
	return $out;		
}


function readablePkgStatuses($status) {
	$statuses = array(
		'approve'	=> 'Добавлен но не одобрен администратором',
		'new'		=> 'Добавлен но не отправлен',
		'accepted'	=> 'Сотрудник принял задание',
		'todrop'	=> 'Отправлено сотруднику',
		'ondrop'	=> 'Сотрудник получил товар',
		'labled'	=> 'Сортировка завершена',
		'tobuyer'	=> 'Товар отправлен получателю',
		'onbuyer'	=> 'Покупатель получил товар',
		'compleate'	=> 'Покупатель подтвердил получение'
	);
	if (isset($statuses[$status])) {
		$out = $statuses[$status];
	} else {
		$out = $status;
	}
	return $out;
}

function iconPkgStatuses($status) {
	$statuses = array(
		'approve'	=> '<i style="color:#FF9966;" class="fa fa-play" data-placement="top" data-toggle="tooltip" title="Добавлен но не одобрен администратором" alt="Добавлен но не одобрен администратором"></i>',
		'new'		=> '<i style="color:#FF9966;" class="fa fa-bookmark" data-placement="top" data-toggle="tooltip" title="Добавлен но не отправлен" alt="Добавлен но не отправлен"></i>',
		'accepted'	=> '<i style="color:#EF9966;" class="fa fa-check" data-placement="top" data-toggle="tooltip" title="Сотрудник принял задание" alt="Сотрудник принял задание"></i>',
		'todrop'	=> '<i style="color:#E09966;" class="fa fa-truck" data-placement="top" data-toggle="tooltip" title="Отправлено сотруднику" alt="Отправлено сотруднику"></i>',
		'ondrop'	=> '<i style="color:#C29966;" class="fa fa-user" data-placement="top" data-toggle="tooltip" title="Сотрудник получил товар" alt="Сотрудник получил товар"></i>',
		'labled'	=> '<i style="color:#B29966;" class="fa fa-barcode" data-placement="top" data-toggle="tooltip" title="Сортировка завершена" alt="Сортировка завершена"></i>',
		'tobuyer'	=> '<i style="color:#A39966;" class="fa fa-plane" data-placement="top" data-toggle="tooltip" title="Товар отправлен получателю" alt="Товар отправлен получателю"></i>',
		'onbuyer'	=> '<i style="color:#859966;" class="fa fa-usd" data-placement="top" data-toggle="tooltip" title="Покупатель получил товар" alt="Покупатель получил товар"></i>',
		'compleate'	=> '<i style="color:#669966;" class="fa fa-thumbs-up" data-placement="top" data-toggle="tooltip" title="Покупатель подтвердил получение" alt="Покупатель подтвердил получение"></i>'
	);
	if (isset($statuses[$status])) {
		$out = $statuses[$status];
	} else {
		$out = $status;
	}
	return $out;
}


function getAdminId() {
	global $db;
	$admin = $db->query("SELECT r.*, u.id as adminId FROM `rank` AS r LEFT JOIN `users` AS u ON u.rank = r.id WHERE r.rankname = 'admin';");
	if (isset($admin[0])) {
		return $admin[0]->adminId;
	}
}

function getPkgTracks($pkg_id) {
	global $db;
	$tracks = $db->query("SELECT * FROM `trackers` WHERE `pkg_id` = ".$pkg_id." ORDER BY `id` DESC;");
	if (isset($tracks[0])) {
		return $tracks;
	} else {
		return false;
	}
}

function readableUserStatuses($status) {
	$statuses = array(
		'admin'		=> 'Администратор',
		'support'	=> 'Помощник',
		'drop'		=> 'Сотрудник',
		'shipper'	=> 'Отправитель',
		'buyer'		=> 'Покупатель',
	);
	if (isset($statuses[$status])) {
		$out = $statuses[$status];
	} else {
		$out = $status;
	}
	return $out;
}


function getItemPhotos($pkg_id, $status_text) {
	global $db, $cfg, $user;
	if ($user['rankname']=='drop') {
		if ($status_text=='inbox') {
			$where = "AND (`status_text` = 'ondrop' OR `status_text` = 'todrop' OR `status_text` = 'new')";
		} else {
			$where = "AND (`status_text` = 'tobuyer' OR `status_text` = 'onbuyer' OR `status_text` = 'compleate')";
		}
	} elseif ($user['rankname']=='buyer') {
		if ($status_text=='inbox') {
			$where = "AND (`status_text` = 'tobuyer' OR `status_text` = 'compleate')";
		} else {
			$where = "AND (`status_text` = 'compleate')";
		}		
	}
	
	$q = "SELECT * FROM `uploads` WHERE `pkg_id` = ".$pkg_id." ".$where." AND `user_id` = ".$user['id'].";";
	
	$fotos = $db->query($q);
	
	$html = '
		<div class="pull-left" style="margin: 5px; display: block;">
			<div class="well" data-img_id=%img_id% data-rmv_url="%rmv_url%">
				<img src="%filename%" style="width: 80px; height: 80px; margin: 0px 0px 10px;">
				<div>
					<div class="progress" style="margin: 0px; height: 2em;">
						<div class="progress-bar" style="width: 0%;"></div>
					</div>
				</div>
				<div class="text-center"><a href="javascript:" onclick="deletePhoto(%img_id%);"><span class="fa fa-times"></span></a></div>
			</div>
		</div>
	';
	
	$out = '';
	if (isset($fotos[0])) {
		foreach($fotos as $v) {
			$out .= str_replace('%filename%', $cfg['options']['siteurl'].'/upload/'.$v->filename, $html);
			$out = str_replace('%img_id%', $v->id, $out); 
			$out = str_replace('%rmv_url%', $cfg['options']['siteurl'].'/gears/photoRemove.php', $out);
			
		}
	}
	return $out;
}


function getLabelPDF($pkg_id) {
	global $db, $cfg, $user;
	
	$q = "SELECT * FROM `uploads` WHERE `pkg_id` = ".$pkg_id." AND `status_text` = 'labled';";
	
	$labels = $db->query($q);
	
	$html = '
		<div class="pull-left" style="margin: 5px; display: block;">
			<div class="well" data-img_id=%img_id% data-rmv_url="%rmv_url%">
				<a href="%filename%" target="_blank">Get file</a>
				<div>
					<div class="progress" style="margin: 0px; height: 2em;">
						<div class="progress-bar" style="width: 0%;"></div>
					</div>
				</div>
				<div class="text-center"><a href="javascript:" onclick="deletePhoto(%img_id%);"><span class="fa fa-times"></span></a></div>
			</div>
		</div>
	';
	
	$out = '';
	if (isset($labels[0])) {
		foreach($labels as $v) {
			$out .= str_replace('%filename%', $cfg['options']['siteurl'].'/upload/'.$v->filename, $html);
			$out = str_replace('%img_id%', $v->id, $out); 
			$out = str_replace('%rmv_url%', $cfg['options']['siteurl'].'/gears/photoRemove.php', $out);
			
		}
	}
	return $out;
}


function getLabelPDFForDrop($pkg_id) {
	global $db, $cfg, $user;
	
	$q = "SELECT * FROM `uploads` WHERE `pkg_id` = ".$pkg_id." AND `status_text` = 'labled';";
	
	$labels = $db->query($q);
	
	$html = '
		<div class="pull-left" style="margin: 5px; display: block;">
			<div class="well" data-img_id=%img_id% data-rmv_url="%rmv_url%">
				<a href="%filename%" target="_blank"><i class="fa fa-download"></i> Get Label</a>
			</div>
		</div>
	';
	
	$out = '';
	if (isset($labels[0])) {
		foreach($labels as $v) {
			$out .= str_replace('%filename%', $cfg['options']['siteurl'].'/upload/'.$v->filename, $html);
			$out = str_replace('%img_id%', $v->id, $out); 
			$out = str_replace('%rmv_url%', $cfg['options']['siteurl'].'/gears/photoRemove.php', $out);
			
		}
	}
	return $out;
}


function getTrackCheckLink($deliveryType,$trackNum) {
	$deliveryType = strtolower($deliveryType);
	$dp['hermes'] = 'https://www.myhermes.de/wps/portal/paket/Home/privatkunden/sendungsverfolgung/!ut/p/b1/04_Sj9Q1MTA2tzQ0MDfWj9CPykssy0xPLMnMz0vMAfGjzOJDjQxCHZ0MHQ0sfEydDTwDXEIMPIKCjP1dDfUjgQrMcSoINdAP14_EryAKqCYxGWSffm6UR2W5o6IiAGDkwiw!/?action=trace&receiptID=&shipmentID=';
	$dp['omest'] = 'http://wwww.sda.it/SITO_SDA-WEB/dispatcherHol?execute2=ActionTracking.doGetSpedizioneSelfTrck&id_cliente=000000021156&id_ldv=';
	$dp['dhl'] = 'http://nolp.dhl.de/nextt-online-public/set_identcodes.do?lang=de&rfn=&extendedSearch=true&idc=';
	$dp['dhlexplress'] = 'http://anonym.to/?http://www.dhl.com/en/express/tracking.html?AWB=';
	$dp['dpd'] = 'https://tracking.dpd.de/parcelstatus?locale=de_DE&query=';
	$dp['usps'] = 'http://anonym.to/?https://tools.usps.com/go/TrackConfirmAction.action?tRef=fullpage&tLc=1&tLabels=';
	$dp['fedex'] = 'http://www.fedex.com/Tracking?action=track&language=english&ascend_header=1&cntry_code=us&initial=x&mps=y&tracknumbers=';
	if (isset($dp[$deliveryType])) {
		$out = '<a href="'.$dp[$deliveryType].$trackNum.'" target="_blank">'.$trackNum.'</a>';
	} else {
		$out = $trackNum;
	}
	return $out;
}


function colorText($text,$color) {
	global $user;
	if ($user['rankname']!='admin') return $text;
	if (empty($color)) { $color='inherit'; }
	return '<span style="color:'.$color.' !important;">'.$text.'</span>';
}

function getUserColor($id) {
	global $db;
	if (!is_numeric($id)) {
		$out = 'inherit';
	} else {
		$q = "SELECT color FROM `users` WHERE id = ".$id;
		$res = $db->query($q);
		if (isset($res[0]) && $res != false && !empty($res[0]->color)) {
			$out = $res[0]->color;
		} else {
			$out = 'inherit';
		}
	}
	return $out;
}


function getPkgColor($id){
	global $db, $user;
	if ($user['rankname']!='admin' && $user['rankname']!='support') return 'inherit';
	$q = "SELECT * FROM `pkg_color` WHERE `id` = ".$id;
	$color = $db->query($q);
	if (isset($color[0])) {
		$out = $color[0]->color;
	} else {
		$out = 'inherit';
	}
	return $out;
}


function getUserNote($id) {
	global $db, $user;
	if ($user['rankname']!='admin' && $user['rankname']!='support') return 'inherit';
	if (!empty($id)) {
		$q = "SELECT * FROM `users` WHERE `id` = ".$id;
		$res = $db->query($q);
		if (isset($res[0]->about)) {
			$out = $res[0]->about;
		} else {
			$out = '';
		}
	} else {
		$out = '';
	}
	return $out;
}

function getLinkToUserProfile($id) {
	global $user,$cfg;
	if ($user['rankname']!='admin' && $user['rankname']!='support' && $user['rankname']!='labler') {
		$out = getFullUserNameById($id);
	} else {
		$note = getUserNote($id);
		$out = '<a href="'.$cfg['options']['siteurl'].'/userInfo/'.$id.'"  data-title="'.$note.'" data-toggle="tooltip" data-placement="top">'.colorText(getFullUserNameById($id),getUserColor($id)).'</a>';
	}
	return $out;
}


?>