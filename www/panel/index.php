<?php

if (!file_exists('./gears/config.php')) {
	header('Location: ./install.php');
}

include_once('./gears/config.php');
include_once($cfg['realpath'].'/gears/headers.php');
include_once($cfg['realpath'].'/gears/bootstrap.php');
include_once($cfg['realpath'].'/gears/functions.php');

include_once($cfg['realpath'].'/gears/router.class.php');

include_once($cfg['realpath'].'/gears/l18n.php');
include_once($cfg['realpath'].'/gears/db.php');


include_once($cfg['realpath'].'/gears/exchange.class.php');

//$curr = new exchange();
//echo $curr->getRate('gbp','usd',100);


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


// создаем инстанс класса
$route = new routerClass($_SERVER['REQUEST_URI']); // filter_input(INPUT_SERVER, "REQUEST_URI") всегда возвращает null, это баг php

// добавляем возможные пути
$route->addPath(array('name'=>'userInfo','file'=>'userInfo','params'=>'/^\d+/'));

$route->addPath(array('name'=>'statsAdmin','file'=>'statsAdmin','params'=>false));
$route->addPath(array('name'=>'news','file'=>'news','params'=>false));


$route->addPath(array('name'=>'dropslist','file'=>'dropslist','params'=>false));
$route->addPath(array('name'=>'addPackageToDrop','file'=>'addPackageToDrop','params'=>'/^\d+/'));

$route->addPath(array('name'=>'shopDropSelector','file'=>'shopDropSelector','params'=>'/^\d+/'));
$route->addPath(array('name'=>'shops','file'=>'shops','params'=>false));

$route->addPath(array('name'=>'options','file'=>'options','params'=>false));

$route->addPath(array('name'=>'blackshoplist','file'=>'blackshoplist','params'=>false));
$route->addPath(array('name'=>'receptlist','file'=>'receptlist','params'=>false));


$route->addPath(array('name'=>'profile','file'=>'profile','params'=>false));
$route->addPath(array('name'=>'packages','file'=>'packages','params'=>false));
$route->addPath(array('name'=>'package','file'=>'package','params'=>'/^\d+/'));
$route->addPath(array('name'=>'addUser','file'=>'addUser','params'=>false));
$route->addPath(array('name'=>'users','file'=>'users','params'=>false));
$route->addPath(array('name'=>'user','file'=>'user','params'=>'/^\d+/'));
$route->addPath(array('name'=>'lablers','file'=>'lablers','params'=>false));
$route->addPath(array('name'=>'drops','file'=>'drops','params'=>false));

$route->addPath(array('name'=>'shippers','file'=>'shippers','params'=>false));
$route->addPath(array('name'=>'buyers','file'=>'buyers','params'=>false));
$route->addPath(array('name'=>'fullUserPackagesInfo','file'=>'fullUserPackagesInfo','params'=>'/^\d+/'));
//$route->addPath(array('name'=>'addPackage','file'=>'addPackage','params'=>false));
$route->addPath(array('name'=>'chat','file'=>'chat','params'=>false));
$route->addPath(array('name'=>'chatroom','file'=>'chatroom','params'=>'/^[0-9A-F]+$/i'));

$route->addPath(array('name'=>'newchat','file'=>'chatroom','params'=>'/^[0-9A-F]+$/i'));

$route->addPath(array('name'=>'dropinbox','file'=>'dropinbox','params'=>false));
$route->addPath(array('name'=>'dropoutbox','file'=>'dropoutbox','params'=>false));
$route->addPath(array('name'=>'dropPkgInfo','file'=>'dropPkgInfo','params'=>'/^[0-9A-F]+$/i'));

$route->addPath(array('name'=>'lablerinbox','file'=>'lablerinbox','params'=>false));
$route->addPath(array('name'=>'lablerPkgInfo','file'=>'lablerPkgInfo','params'=>'/^\d+/'));


$route->addPath(array('name'=>'buyerinbox','file'=>'buyerinbox','params'=>false));
$route->addPath(array('name'=>'buyercompleated','file'=>'buyercompleated','params'=>false));
$route->addPath(array('name'=>'buyerPkgInfo','file'=>'buyerPkgInfo','params'=>'/^[0-9A-F]+$/i'));

$route->addPath(array('name'=>'trackSearch','file'=>'trackSearch','params'=>false));

// берем путь
$routes = $route->getRoute();

//debug($route);exit();

ob_start();
	$pagePath = $cfg['realpath'].'/pages/'.$route->value.'.php';
	if ($route->isPageExist($pagePath)) include($pagePath);
	//debug($user);
	//debug($route);
	//debug($db->getVar('callsDebug'));
	$mainHTML = ob_get_contents();
ob_end_clean();

include($cfg['realpath'].'/pages/index.php');


?>