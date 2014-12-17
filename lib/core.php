<?php
	
	$_APP["ARG"]	= 	array();
	$_APP["ARG"]["MODULE"] 	= !isset($_REQUEST['_app_mod']) || empty($_REQUEST['_app_mod']) ? 'default' : trim($_REQUEST['_app_mod']);
	$_APP["ARG"]["VIEW"] 	= !isset($_REQUEST['_app_view']) || empty($_REQUEST['_app_view']) ? 'index' : substr(trim($_REQUEST['_app_view']),1);
	$_APP["ARG"]["ID"] 		= !isset($_REQUEST['_app_id']) || empty($_REQUEST['_app_id']) ? '0' : trim($_REQUEST['_app_id']);
	$_APP["ARG"]["REDIR"] = 0;

	class app{

		public static function link_to($controller="", $view="", $id=""){
			$link 	= 	empty($controller) ? '' : trim($controller);
			$link 	=	empty($view) ? $link  : $link.DS.trim($view);
			$link 	=	empty($id) ? $link : $link.DS.trim($id);
			$link_str = "";
			$root_folder = ROOT;
			if(empty($root_folder)){
				return DOMAIN.DS.$link;
			}else{
				return DOMAIN.DS.ROOT.DS.$link;
			}
			
		}

		public static function nav_link_to($text, $controller="", $view="", $id="", $inline="", $class=""){
			global $_APP;
			$cls ="";
			$cont1 = empty($controller) ? 'default' : trim($controller);
			$view1 = empty($view) ? 'index' : trim($view);
			$class= empty($class) ? '' : "class='$class'";

			$cont2 = !isset($_REQUEST['_app_mod']) || empty($_REQUEST['_app_mod']) ? 'default' : trim($_REQUEST['_app_mod']);
			$view2 = !isset($_REQUEST['_app_view']) || empty($_REQUEST['_app_view']) ? 'index' : trim($_REQUEST['_app_view']);
			if($_APP["ARG"]["MODULE"]==$cont1  &&  $_APP["ARG"]["VIEW"]==$view1 && $_APP["ARG"]["REDIR"]==0 ){
				$cls ="class=\"active\"";
			}else if($cont2==$cont1  && $view1==$view2){
				$cls ="class=\"active\"";
			}
			echo "<li $cls><a href=\"".self::link_to($controller, $view, $id)."\"  $inline $class>".$text."</a></li>";	
		}

		public static function load_lib($str=""){	
			$lib = explode(";", $str);
			for($i=0;$i<count($lib);$i++){
				require($lib[$i].".php");
			}
		}

		public static function seo_redirect($routes=null){
			global $_APP;
			foreach ($routes as $soruce => $destination) {
				//echo $_APP["ARG"]["MODULE"];
				if($_APP["ARG"]["MODULE"] == $soruce){
					header("Location:".$destination);
					exit;
				}
			}
		}


		public static function re_route($a="",$b=""){
			global $_APP;
			$a_val = explode("#", $a);
			$b_val = explode("#", $b);
			if(!isset($a_val[1]) ||  empty($a_val[1]) )
				$a_val[1] = "index";
			if(!isset($b_val[1]) || empty($b_val[1]) )
				$b_val[1] = "index";
			if($_APP["ARG"]["MODULE"] == $a_val[0] && $_APP["ARG"]["VIEW"] == $a_val[1]){
				$_APP["ARG"]["MODULE"] = $b_val[0];
				$_APP["ARG"]["VIEW"] = $b_val[1];
				$_APP["ARG"]["REDIR"] = 1;
			}
		}



		public static function re_route_group($a=NULL,$b=""){
			global $_APP;
			foreach($a as $val){
				if($_APP["ARG"]["MODULE"] == $val){
					$_APP["ARG"]["MODULE"] = $b;
					$_APP["ARG"]["VIEW"] = $val;
					$_APP["ARG"]["REDIR"] = 1;
					return;
				}
			}
			
		}




		public static function get_post($post,$field){
			$data = array();
			foreach ($field as $value) {
				$data[$value] = empty($post["$value"]) ? "" : $post["$value"];
			}
			return $data;
		}

		public static function sys_date($fromat="",$strDate=""){
			if(empty($strDate))
				return empty($format) ? date("Y-M-d H:i:s") : date($fromat);
			else
				return empty($format)  ? date("Y-M-d H:i:s",strtotime($strDate)) : date($fromat, strtotime($strDate));
		}

		public static function redirect($location){
			header("Location:".$location);
			exit;
		}


		public static function getErrors($validation_result){
        $arr = array();
        foreach($validation_result->errors as $key=>$error){
            foreach($error as $key2=>$error2){
                $msg = implode("::", $error2['params']);
                $arr[$key][$key2] = $msg;           
            }
       	 }
       	 return $arr;
    	}

		public static function validate($data,$rules){
			$validation_result = Validator::validate($data, $rules);
			if ($validation_result->isSuccess() == true) {
			    return 1;
			} else {
			    return self::getErrors($validation_result); 
			}
		}

		public static function fileExists($fname){
			if(file_exists($fname)){
    			return true;
    		}
		else {
    		throw new Exception("Could not find file {$fname}");
			}
		}


		public static function geoIP(){
			$client  = @$_SERVER['HTTP_CLIENT_IP'];
		    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		    $remote  = $_SERVER['REMOTE_ADDR'];
		    $result  = "Unknown";
		    if(filter_var($client, FILTER_VALIDATE_IP))
		    {
		        $ip = $client;
		    }
		    elseif(filter_var($forward, FILTER_VALIDATE_IP))
		    {
		        $ip = $forward;
		    }
		    else
		    {
		        $ip = $remote;
		    }
			
		    return $ip;
		}



	}

	

	function getBrowser() 
		{ 
		    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
		    $bname = 'Unknown';
		    $platform = 'Unknown';
		    $version= "";

		    //First get the platform?
		    if (preg_match('/linux/i', $u_agent)) {
		        $platform = 'linux';
		    }
		    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
		        $platform = 'mac';
		    }
		    elseif (preg_match('/windows|win32/i', $u_agent)) {
		        $platform = 'windows';
		    }
		    
		    // Next get the name of the useragent yes seperately and for good reason
		    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
		    { 
		        $bname = 'Internet Explorer'; 
		        $ub = "MSIE"; 
		    } 
		    elseif(preg_match('/Firefox/i',$u_agent)) 
		    { 
		        $bname = 'Mozilla Firefox'; 
		        $ub = "Firefox"; 
		    } 
		    elseif(preg_match('/Chrome/i',$u_agent)) 
		    { 
		        $bname = 'Google Chrome'; 
		        $ub = "Chrome"; 
		    } 
		    elseif(preg_match('/Safari/i',$u_agent)) 
		    { 
		        $bname = 'Apple Safari'; 
		        $ub = "Safari"; 
		    } 
		    elseif(preg_match('/Opera/i',$u_agent)) 
		    { 
		        $bname = 'Opera'; 
		        $ub = "Opera"; 
		    } 
		    elseif(preg_match('/Netscape/i',$u_agent)) 
		    { 
		        $bname = 'Netscape'; 
		        $ub = "Netscape"; 
		    }

		   
		    
		    // finally get the correct version number
		    $known = array('Version', $ub, 'other');
		    $pattern = '#(?<browser>' . join('|', $known) .
		    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		    if (!preg_match_all($pattern, $u_agent, $matches)) {
		        // we have no matching number just continue
		    }
		    
		    // see how many we have
		    $i = count($matches['browser']);
		    if ($i != 1) {
		        //we will have two since we are not using 'other' argument yet
		        //see if version is before or after the name
		        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
		            $version= $matches['version'][0];
		        }
		        else {
		            $version= $matches['version'][1];
		        }
		    }
		    else {
		        $version= $matches['version'][0];
		    }
		    
		    

		    // check if we have a number
		    if ($version==null || $version=="") {$version="?";}
		    
		    return array(
		        'userAgent' => $u_agent,
		        'name'      => $bname,
		        'version'   => $version,
		        'platform'  => $platform,
		        'pattern'    => $pattern
		    );
		} 

?>