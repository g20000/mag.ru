<?php
/*
 * Handler for ajax requests (modern browsers with HTML5 file API) will post here
 */

header('Content-Type: application/json; charset=utf-8');

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

// For error handling tests :)
/*
if(rand(1, 4) == 4) {
    $status = '500 Internal Server Error';
    header("HTTP/1.1 {$status}");
    header("Status: {$status}");
    die();
}
*/

function file_extension($original_filename) { $arr = explode('.',$original_filename); return $arr[count($arr)-1]; };

if(!empty($_FILES) && !empty($_FILES['my-file'])) {

    $fileMeta = $_FILES['my-file']; // Key was defined in 'fieldName' option
    if ($fileMeta['type'] == 'image/png' || $fileMeta['type'] == 'image/jpeg') {		
        $pkg_id = filter_input(INPUT_POST, 'pkg_id', FILTER_VALIDATE_INT);
		$upload_type = addslashes(strip_tags(filter_input(INPUT_POST, 'upload_type', FILTER_UNSAFE_RAW)));
		$original_filename = addslashes(strip_tags(filter_input(INPUT_POST, 'original-filename', FILTER_UNSAFE_RAW)));
		// если запрос пришел из инбокса, то ставим статус (у баера неважно)
		$status_text = 'compleate';

		// смотрим есть ли уже такой статус
		$isstatusexist = $db->query("SELECT * FROM `pkg_statuses` WHERE pkg_id = ".$pkg_id." AND `status_text` = '".$status_text."';");
		// если нет, то ставим
		if (!isset($isstatusexist[0])) {
			$db->query("INSERT INTO `pkg_statuses` VALUES (NULL, ".$pkg_id.", '".date("Y-m-d H:i:s", time())."', '".$status_text."');");
		}
		$new_file_name = $pkg_id.'_'.$status_text.'_'.time().'.'.file_extension($original_filename);
		$db->query("INSERT INTO `uploads` VALUES (NULL, ".$pkg_id.", ".$user['id'].", '".$new_file_name."', '".$status_text."', '".date("Y-m-d H:i:s", time())."');");
		move_uploaded_file($fileMeta['tmp_name'], $cfg['realpath'].'/upload/'.$new_file_name);	

		// Sending JSON-encoded response
		echo json_encode(array(
			'file' => $fileMeta,
			'post' => $_POST
		));		
		
		
	} else {
		echo json_encode(array('error' => 'Неправильный тип файла!'));
	}


} else {
    echo json_encode(array('error' => 'Ошибка!'));
}
?>
