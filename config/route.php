<?php

	$_APP["ARG"]["MODULE"] = str_replace(".php", "", $_APP["ARG"]["MODULE"]);
	$_route = array(
					"why-us",
					"what-we-do",
					"pricing",
					"how-it-work"					
				);

	app::re_route_group($_route,"default");
	try {
		require(MOD_DIR.DS."index.php");
		switch ($_APP["ARG"]["MODULE"]) {
			case '':
				break;			
			default:
			if(file_exists(MOD_DIR.DS.$_APP["ARG"]["MODULE"].".php")){
				require(MOD_DIR.DS.$_APP["ARG"]["MODULE"].".php");
			}
			app::fileExists(VIEW_DIR.DS.$_APP["ARG"]["MODULE"].DS.$_APP["ARG"]["VIEW"].".php");
			require(VIEW_DIR.DS.$_APP["ARG"]["MODULE"].DS.$_APP["ARG"]["VIEW"].".php");
			break;
		}
	}catch (Exception  $e) {
    	//echo 'Page not found: 404 ',  $e->getMessage(), "\n";
    	require(VIEW_DIR.DS."default".DS."notfound".".php");
	}
	
?>