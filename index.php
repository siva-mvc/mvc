<?php
	ini_set("display_errors", "on");
	require("config/config.php");
	app::load_lib("mydb;form;validator;mymail");
	require("config/route.php");
?>
<!-- <IfModule mod_rewrite.c>
RewriteEngine on
RewriteCond %{HTTP_HOST} ^ecccgroup.com.au$ [NC]
RewriteRule ^(.*)$ http://www.ecccgroup.com.au/$1 [L,R=301]
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^([^/]*)(.*)$ index.php?_app_mod=$1&_app_view=$2 [L,QSA]
</IfModule> -->