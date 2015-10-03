<?php
/*
 * Fallback handler (archaic browsers with no File API will send requests here by default form submitting)
 */

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

if(!empty($_FILES) && !empty($_FILES['my-file'])) {

    $fileMeta = $_FILES['my-file']; // Key was defined in 'fieldName' option
    if ($fileMeta['type'] == 'image/png') {
        // Do something with received file
    }

    echo "File received:<hr/>";
    echo '<pre>';
    print_r($fileMeta);
    echo '</pre><hr/>';
    echo '<a href="./">Back to demo</a>'; // in real case redirect after processing is appropriate here
} else {
    echo "Wrong request";
}
?>
