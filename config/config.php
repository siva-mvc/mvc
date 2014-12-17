<?php
	global $_APP;	
	session_start();
	define('APP_DIR', 'C:/xampp/htdocs/new/');
	define('VIEW_DIR', APP_DIR."app/views" );
	define('MOD_DIR', APP_DIR."app/modules" );
	define('SAMP_DIR', APP_DIR."sample/");
	define('MAIL_DIR', APP_DIR."app/views/_mail" );
	define('ASSETS_DIR', "/new/app/assets" );
	define('DS', '/');
	define('ROOT', "new");
	define('DOMAIN', "http://localhost");
	define('UPLOADS',DOMAIN.'/new/Uploads/');
	define('LIB_URL', DOMAIN."/new/lib/");
	$_APP["DB"]		=	 array();
	$_APP["DB"]["HOST"]		=	"localhost";
	$_APP["DB"]["USER"]		=	"root";
	$_APP["DB"]["PASSWD"]	=	"";
	$_APP["DB"]["DB_NAME"]	=	"";
	require("lib/core.php");
?>