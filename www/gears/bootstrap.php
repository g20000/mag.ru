<?php

date_default_timezone_set('Europe/Moscow');


// create log file
if(!file_exists($cfg['realpath'].'/logs/logs.txt')) {
	touch($cfg['realpath'].'/logs/logs.txt');
	chmod($cfg['realpath'].'/logs/logs.txt', 0777);
}

//error_reporting(0);

function getLogsDir() {
	global $cfg;
	if (isset($cfg['realpath'])) {
		// если в конфиге есть
		return $cfg['realpath'].'/logs'; 
	} else {
		// если в корне есть
		if (file_exists('./logs') && is_dir('./logs')) {
			return './logs'; 
		} else {
			// если на уровень выше есть
			if (file_exists('../logs') && is_dir('../logs')) { 
				return '../logs'; 
			}
		}
	}
	
	return false;
	
}


function logError($msg) {
	ob_start();
	var_dump($msg);
	$msg = ob_get_contents();
	ob_end_clean();
	$logDir = getLogsDir();
	if ($logDir!==false && is_writable($logDir)) {
		file_put_contents($logDir.'/logs.txt', date("Y/m/d H:i.s",time())."\n-------------------\n".$msg."\n\n",FILE_APPEND);
	} elseif($logDir!==false) {
		debug($logDir.' is not writable!', false, false, false);
	} else {
		debug('Log directory is not exist!', false, false, false);
	}
	
}



function debug($error,$backtrace = false, $vardump = false, $logToFile = true) {
	global $cfg;
	if (isset($_SERVER['HTTP_USER_AGENT']) && (isset($cfg['debug']) && $cfg['debug']==true)) {
		echo "<pre style='border: 1px dashed gray;padding:10px;border-radius:10px;'>";
		
		if(is_array($error) || is_object($error)) { 
			if ($vardump==false) { print_r($error); } else { var_dump($error); }
		} else { 
			echo str_replace("\n", "<br>", $error);
		}
		
		echo "</pre>";
		
		if ($backtrace===true) {
			echo "<pre>";
			debug_print_backtrace();
			echo "</pre>";
		}
	} elseif (!isset($_SERVER['HTTP_USER_AGENT']) || (isset($cfg['debug']) && $cfg['debug']==true)) {
		if(is_array($error) || is_object($error)) { 
			if ($vardump===false) { print_r($error); } else { var_dump($error); }
		} else { 
			echo $error."\n";
		}
		if ($backtrace===true) { debug_print_backtrace(); }
	}
	if ($logToFile!==false) { logError($error); }
}

function myErrorHandler($errno, $errstr, $errfile, $errline) {
    $errfile=str_replace('\\','/',$errfile);
    $url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'Local file';
    switch ($errno) {
        case E_USER_ERROR:
            debug("ERROR: [$errno]\nMessage: $errstr\nFile: $errfile\nLine: $errline\nPHP_VERSION: ".PHP_VERSION."(".PHP_OS.")"."\nURL: $url");
            exit(1);
            break;

        case 8:
            debug("Замечение: [$errno]\nMessage: $errstr\nFile: $errfile\nLine: $errline\nURL: $url");
            break;

       case 2:
            debug("Функция: [$errno]\nMessage: $errstr\nFile: $errfile\nLine: $errline\nURL: $url");
            break;

        case E_USER_NOTICE:
            debug("Сообщение: [$errno]\nMessage: $errstr\nFile: $errfile\nLine: $errline\nURL: $url");
            break;

        default:
            debug("Совет: [$errno]\nMessage: $errstr\nFile: $errfile\nLine: $errline\nURL: $url");
            break;

    }
    return true;
}


function myFatalErrorShutdownHandler() {
	$last_error = error_get_last();
	if ($last_error['type']===E_ERROR) {
		myErrorHandler(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
	}
}


set_error_handler('myErrorHandler');
register_shutdown_function('myFatalErrorShutdownHandler');

?>