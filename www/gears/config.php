<?php

$cfg = array (
					'debug' 		=> true,
					'realpath'		=> 'Z:/home/mag.ru/www/panel',
					'cookiename'	=> '93cd49b8b38f505313731b07d6765a85',
					'db'	 		=> array (
												'dbdriver' 		=> 'mysql',
												'dbuser' 		=> 'root',
												'dbpassword' 	=> '',
												'dsn' 			=>  array (
																			'dbhost' => 'localhost',
																			'dbport' => '3306', /* 3306 */
																			'dbname' => 'database2',
																			'charset' => 'utf8',
																	),
												'dboptions' 	=> array ( 'PDO::MYSQL_ATTR_INIT_COMMAND' => 'set names utf8' ),
												'dbattributes' 	=> array ( 'ATTR_ERRMODE' => 'ERRMODE_EXCEPTION' ),
										),
					'ava_dim'		=> array(250,250),
					'cacheFTTL'		=> 60,
);

$cfg['options']['siteurl']='http://'.$_SERVER['HTTP_HOST'];

if (!file_exists($cfg['realpath'].'/index.php')) { exit('[realpath] check is failed!'); }



?>