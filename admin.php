<?php
	header('Content-type:text/html;charset = utf-8');
	date_default_timezone_set('Asia/Shanghai');
	session_start();
	error_reporting(E_ALL^E_NOTICE^E_WARNING^E_DEPRECATED);
	require_once('config.php');
	require_once('frame/pc.php');
	PC::run($config);
	
?>
