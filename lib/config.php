<?php
		
	global $_APP;	
	session_start();
	define('SITEID', '1');
	define('APP_DIR', '/var/www/vhosts/mediaserveit.com/httpdocs/ecc/');
	define('VIEW_DIR', APP_DIR."app/views" );
	define('MOD_DIR', APP_DIR."app/modules" );
	define('SAMP_DIR', APP_DIR."sample/");
	define('MAIL_DIR', APP_DIR."app/views/_mail" );
	define('ASSETS_DIR', "/eccc/app/assets" );
	define('DS', '/');
	define('ROOT', "eccc");
	define('DOMAIN', "http://192.168.1.158:8080");
	define('UPLOADS',DOMAIN.'/eccc/Uploads/');
	define('LIB_URL', DOMAIN."/eccc/lib/");
	$_APP["DB"]		=	 array();
	$_APP["DB"]["HOST"]		=	"69.64.95.121";
	$_APP["DB"]["USER"]		=	"eccc";
	$_APP["DB"]["PASSWD"]	=	"ecccgroup";
	$_APP["DB"]["DB_NAME"]	=	"eccc";
	require("lib/core.php");
?>