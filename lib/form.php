<?php
class form{
	private $frmName;
	private $data;
	public function __construct($data=array()){
		$this->data = $data;
	}

	public function open($name="",$method="",$url="", $id="", $class="", $inline=""){
		$name = empty($name) ? "myform" : $name;
		$method = empty($method) ? "get" : $method;
		$url = empty($url) ? app::link_to() : $url;
		$str = $this->inline_str( $id, $class, $inline);
		echo "\n<form name='$name' method='$method'  action='$url' $str >\n";
		$this->frmName = $name;
	}
	
	public function hidden_tag($name, $id="", $class="", $inline="", $val=""){
		$name = empty($name) ? "textbox" : $name;
		$value =  (!empty($val)) ? $val : $this->get_data($name) ;
		$str = $this->inline_str( $id, $class, $inline);
		echo "\n<input type=\"hidden\"  name=\"".$this->frmName."[$name]\" $str  value=\"$value\" />\n";	
	}

	public function text_tag($name, $id="", $class="", $inline=""){
		$name = empty($name) ? "textbox" : $name;
		$value =  $this->get_data($name) ;
		$str = $this->inline_str( $id, $class, $inline);
		echo "\n<input type=\"text\"  name=\"".$this->frmName."[$name]\" $str  value=\"$value\" />\n";	
	}

	public function textarea_tag($name, $id="", $class="", $inline=""){
		$name = empty($name) ? "textarea" : $name;
		$value =  $this->get_data($name) ;
		$str = $this->inline_str( $id, $class, $inline);
		echo "\n<textarea  name=\"".$this->frmName."[$name]\" $str >$value</textarea>\n";	
	}

	public function password_tag($name, $id="", $class="", $inline=""){
		$name = empty($name) ? "password" : $name;
		$value =  $this->get_data($name);
		$str = $this->inline_str( $id, $class, $inline);
		echo "\n<input type=\"password\"  name=\"".$this->frmName."[$name]\" $str value=\"$value\" />\n";	
	}


	public function checkbox_tag($name="", $inline=""){
		$name = empty($name) ? "checkbox" : $name;
		$value = $this->get_data($name);
		$checked = empty($value) ?  "" : "checked='checked'";
		$str = $this->inline_str( "", "" , " value=\"$value\" $checked ".$inline);
		echo "\n<label><input type=\"checkbox\"  name=\"".$this->frmName."[$name]\" $str /> $value</label>\n";	
	}
	

	public function checkbox($name="", $value="", $label="", $checked="", $inline=""){
		$name = empty($name) ? "checkbox" : $name;
		$checked = empty($checked) ?  "" : "checked='checked'";
		$str = $this->inline_str( "", "" , " value=\"$value\" $checked ".$inline);

		echo empty($label) ?  	"\n<input type=\"checkbox\"  name=\"".$this->frmName."[$name]\" $str />\n" : 
								"\n<label><input type=\"checkbox\"  name=\"".$this->frmName."[$name]\" $str /> $label</label>\n";	
	}


	public function radio_tag($name="", $inline=""){
		$name = empty($name) ? "radio" : $name;
		$value = $this->get_data($name);
		$checked = empty($value) ?  "" : "checked='checked'";
		$str = $this->inline_str( "", "" , " value=\"$value\" $checked ".$inline);
		echo "\n<label><input type=\"radio\"  name=\"".$this->frmName."[$name]\" $str /> $value</label>\n";	
	}
	

	public function radio($name="", $value="", $label="", $checked="", $inline=""){
		$name = empty($name) ? "radio" : $name;
		$checked = empty($checked) ?  "" : "checked='checked'";
		$str = $this->inline_str( "", "" , " value=\"$value\" $checked ".$inline);
		
		echo empty($label) ?  	"\n<input type=\"radio\"  name=\"".$this->frmName."[$name]\" $str />\n" : 
								"\n<label><input type=\"radio\"  name=\"".$this->frmName."[$name]\" $str /> $label</label>\n";	
	}


	public function checkbox_group($name="",$val=array(), $label="", $inline=""){
		$name = empty($name) ? "checkbox" : $name;
		$value = $this->get_data($name);
		$val2 = json_decode($value) ? json_decode($value) : array($value) ;
		foreach($val as $key=>$val3){
			$checked = in_array($val3, $val2) ?  "checked='checked'" : "";
			$str = $this->inline_str( "", "" , " value=\"$val3\" $checked ".$inline);
			echo empty($label) ?  	"\n<input type=\"checkbox\"  name=\"".$this->frmName."[$name][]\" $str />\n" : 
								"\n<label><input type=\"checkbox\"  name=\"".$this->frmName."[$name][]\" $str /> $val3</label>\n";
		}

	}



	public function select_tag($name="",$val=array(), $mulitple="", $inline="", $keyVal=""){
		$name = empty($name) ? "select" : $name;
		$mulitple = empty($mulitple) ? "" : " multiple='multiple'";
		$value = $this->get_data($name);
		$val2 = json_decode($value) ? json_decode($value) : array($value) ;
		echo "\n<select  name=\"".$this->frmName."[$name]\"  $mulitple $inline>\n";
		foreach($val as $key=>$val3){
			$selected = empty($keyVal) ?  ( in_array($val3, $val2) ?  "selected='selected'" : "" ) :   ( in_array($key, $val2) ?  "selected='selected'" : "" );			 
			echo empty($keyVal) ? "\n<option value=\"$val3\" $selected >$val3</option>" : "\n<option value=\"$key\" $selected >$val3</option>";
		}
		echo "\n</select>\n";
	}


	public function select_tag_default($name="",$val=array(), $mulitple="", $inline="", $keyVal=""){
		$name = empty($name) ? "select" : $name;
		$mulitple = empty($mulitple) ? "" : " multiple='multiple'";
		$value = $this->get_data($name);
		$val2 = json_decode($value) ? json_decode($value) : array($value) ;
		echo "\n<select  name=\"".$this->frmName."[$name]\"  $mulitple $inline>\n";
		echo "<option disabled='disabled' selected='selected'>Select</option>";
		foreach($val as $key=>$val3){
			$selected = empty($keyVal) ?  ( in_array($val3, $val2) ?  "selected='selected'" : "" ) :   ( in_array($key, $val2) ?  "selected='selected'" : "" );			 
			echo empty($keyVal) ? "\n<option value=\"$val3\" $selected >$val3</option>" : "\n<option value=\"$key\" $selected >$val3</option>";
		}
		echo "\n</select>\n";
	}


	public function radio_group($name="",$val=array(), $label="", $inline=""){
		$name = empty($name) ? "checkbox" : $name;
		$value = $this->get_data($name);
		foreach($val as $key=>$val3){
			$checked = ($val3 == $value) ?  "checked='checked'" : "";
			$str = $this->inline_str( "", "" , " value=\"$val3\" $checked ".$inline);
			echo empty($label) ?  	"\n<input type=\"radio\"  name=\"".$this->frmName."[$name][]\" $str />\n" : 
								"\n<label><input type=\"radio\"  name=\"".$this->frmName."[$name][]\" $str /> $val3</label>\n";
		}

	}


	public function file_tag($name="",$id="",$class="",$inline=""){
		$name = empty($name) ? "file" : $name;
		$str = $this->inline_str( $id, $class, $inline);
		echo "\n<input type=\"file\"  name=\"".$this->frmName."[$name]\" $str />\n";
	}

	public function submit_tag($name="", $id="", $class="", $inline=""){
		$name = empty($name) ? "submit" : $name;
		$str = $this->inline_str( $id, $class, $inline);
		echo "\n<input type=\"submit\"  name=\"".$this->frmName."[$name]\" $str value=\"$name\" />\n";	
	}


	public function reset_tag($name="", $id="", $class="", $inline=""){
		$name = empty($name) ? "reset" : $name;
		$str = $this->inline_str( $id, $class, $inline);
		echo "\n<input type=\"reset\"  name=\"".$this->frmName."[$name]\" $str value=\"$name\" />\n";	
	}


	public function button_tag($name="", $id="", $class="", $inline=""){
		$name = empty($name) ? "button" : $name;
		$str = $this->inline_str( $id, $class, $inline);
		echo "\n<button  $str >$name</button>\n";	
	}

	private function inline_str($id="", $class="", $inline=""){
		$str = "";
		$str .= empty($id) ? "" : " id='$id'";
		$str .= empty($class) ? "" : " class='$class'";
		$str .= empty($inline) ? "" : " $inline";
		return $str;
	}

	private function get_data($name){
		return isset($this->data[$name]) ?  $this->data[$name] :  "";
	}

	public function close(){
		echo "\n</form>\n";
	}


	public function country_tag($name="", $mulitple="", $inline=""){
		$name = empty($name) ? "country" : $name;
		$multiple = !empty($multiple) ? $multiple : '';
		//$value = array("Aust", "Briton", "Eng", "Ice", "India", "Zim");
		$_countries = array(
							  "AU" => "Australia",
							  "GB" => "United Kingdom",
							  "US" => "United States",
							  "AF" => "Afghanistan",
							  "AL" => "Albania",
							  "DZ" => "Algeria",
							  "AS" => "American Samoa",
							  "AD" => "Andorra",
							  "AO" => "Angola",
							  "AI" => "Anguilla",
							  "AQ" => "Antarctica",
							  "AG" => "Antigua And Barbuda",
							  "AR" => "Argentina",
							  "AM" => "Armenia",
							  "AW" => "Aruba",
							  "AT" => "Austria",
							  "AZ" => "Azerbaijan",
							  "BS" => "Bahamas",
							  "BH" => "Bahrain",
							  "BD" => "Bangladesh",
							  "BB" => "Barbados",
							  "BY" => "Belarus",
							  "BE" => "Belgium",
							  "BZ" => "Belize",
							  "BJ" => "Benin",
							  "BM" => "Bermuda",
							  "BT" => "Bhutan",
							  "BO" => "Bolivia",
							  "BA" => "Bosnia And Herzegowina",
							  "BW" => "Botswana",
							  "BV" => "Bouvet Island",
							  "BR" => "Brazil",
							  "IO" => "British Indian Ocean Territory",
							  "BN" => "Brunei Darussalam",
							  "BG" => "Bulgaria",
							  "BF" => "Burkina Faso",
							  "BI" => "Burundi",
							  "KH" => "Cambodia",
							  "CM" => "Cameroon",
							  "CA" => "Canada",
							  "CV" => "Cape Verde",
							  "KY" => "Cayman Islands",
							  "CF" => "Central African Republic",
							  "TD" => "Chad",
							  "CL" => "Chile",
							  "CN" => "China",
							  "CX" => "Christmas Island",
							  "CC" => "Cocos (Keeling) Islands",
							  "CO" => "Colombia",
							  "KM" => "Comoros",
							  "CG" => "Congo",
							  "CD" => "Congo, The Democratic Republic Of The",
							  "CK" => "Cook Islands",
							  "CR" => "Costa Rica",
							  "CI" => "Cote D'Ivoire",
							  "HR" => "Croatia (Local Name: Hrvatska)",
							  "CU" => "Cuba",
							  "CY" => "Cyprus",
							  "CZ" => "Czech Republic",
							  "DK" => "Denmark",
							  "DJ" => "Djibouti",
							  "DM" => "Dominica",
							  "DO" => "Dominican Republic",
							  "TP" => "East Timor",
							  "EC" => "Ecuador",
							  "EG" => "Egypt",
							  "SV" => "El Salvador",
							  "GQ" => "Equatorial Guinea",
							  "ER" => "Eritrea",
							  "EE" => "Estonia",
							  "ET" => "Ethiopia",
							  "FK" => "Falkland Islands (Malvinas)",
							  "FO" => "Faroe Islands",
							  "FJ" => "Fiji",
							  "FI" => "Finland",
							  "FR" => "France",
							  "FX" => "France, Metropolitan",
							  "GF" => "French Guiana",
							  "PF" => "French Polynesia",
							  "TF" => "French Southern Territories",
							  "GA" => "Gabon",
							  "GM" => "Gambia",
							  "GE" => "Georgia",
							  "DE" => "Germany",
							  "GH" => "Ghana",
							  "GI" => "Gibraltar",
							  "GR" => "Greece",
							  "GL" => "Greenland",
							  "GD" => "Grenada",
							  "GP" => "Guadeloupe",
							  "GU" => "Guam",
							  "GT" => "Guatemala",
							  "GN" => "Guinea",
							  "GW" => "Guinea-Bissau",
							  "GY" => "Guyana",
							  "HT" => "Haiti",
							  "HM" => "Heard And Mc Donald Islands",
							  "VA" => "Holy See (Vatican City State)",
							  "HN" => "Honduras",
							  "HK" => "Hong Kong",
							  "HU" => "Hungary",
							  "IS" => "Iceland",
							  "IN" => "India",
							  "ID" => "Indonesia",
							  "IR" => "Iran (Islamic Republic Of)",
							  "IQ" => "Iraq",
							  "IE" => "Ireland",
							  "IL" => "Israel",
							  "IT" => "Italy",
							  "JM" => "Jamaica",
							  "JP" => "Japan",
							  "JO" => "Jordan",
							  "KZ" => "Kazakhstan",
							  "KE" => "Kenya",
							  "KI" => "Kiribati",
							  "KP" => "Korea, Democratic People's Republic Of",
							  "KR" => "Korea, Republic Of",
							  "KW" => "Kuwait",
							  "KG" => "Kyrgyzstan",
							  "LA" => "Lao People's Democratic Republic",
							  "LV" => "Latvia",
							  "LB" => "Lebanon",
							  "LS" => "Lesotho",
							  "LR" => "Liberia",
							  "LY" => "Libyan Arab Jamahiriya",
							  "LI" => "Liechtenstein",
							  "LT" => "Lithuania",
							  "LU" => "Luxembourg",
							  "MO" => "Macau",
							  "MK" => "Macedonia, Former Yugoslav Republic Of",
							  "MG" => "Madagascar",
							  "MW" => "Malawi",
							  "MY" => "Malaysia",
							  "MV" => "Maldives",
							  "ML" => "Mali",
							  "MT" => "Malta",
							  "MH" => "Marshall Islands",
							  "MQ" => "Martinique",
							  "MR" => "Mauritania",
							  "MU" => "Mauritius",
							  "YT" => "Mayotte",
							  "MX" => "Mexico",
							  "FM" => "Micronesia, Federated States Of",
							  "MD" => "Moldova, Republic Of",
							  "MC" => "Monaco",
							  "MN" => "Mongolia",
							  "MS" => "Montserrat",
							  "MA" => "Morocco",
							  "MZ" => "Mozambique",
							  "MM" => "Myanmar",
							  "NA" => "Namibia",
							  "NR" => "Nauru",
							  "NP" => "Nepal",
							  "NL" => "Netherlands",
							  "AN" => "Netherlands Antilles",
							  "NC" => "New Caledonia",
							  "NZ" => "New Zealand",
							  "NI" => "Nicaragua",
							  "NE" => "Niger",
							  "NG" => "Nigeria",
							  "NU" => "Niue",
							  "NF" => "Norfolk Island",
							  "MP" => "Northern Mariana Islands",
							  "NO" => "Norway",
							  "OM" => "Oman",
							  "PK" => "Pakistan",
							  "PW" => "Palau",
							  "PA" => "Panama",
							  "PG" => "Papua New Guinea",
							  "PY" => "Paraguay",
							  "PE" => "Peru",
							  "PH" => "Philippines",
							  "PN" => "Pitcairn",
							  "PL" => "Poland",
							  "PT" => "Portugal",
							  "PR" => "Puerto Rico",
							  "QA" => "Qatar",
							  "RE" => "Reunion",
							  "RO" => "Romania",
							  "RU" => "Russian Federation",
							  "RW" => "Rwanda",
							  "KN" => "Saint Kitts And Nevis",
							  "LC" => "Saint Lucia",
							  "VC" => "Saint Vincent And The Grenadines",
							  "WS" => "Samoa",
							  "SM" => "San Marino",
							  "ST" => "Sao Tome And Principe",
							  "SA" => "Saudi Arabia",
							  "SN" => "Senegal",
							  "SC" => "Seychelles",
							  "SL" => "Sierra Leone",
							  "SG" => "Singapore",
							  "SK" => "Slovakia (Slovak Republic)",
							  "SI" => "Slovenia",
							  "SB" => "Solomon Islands",
							  "SO" => "Somalia",
							  "ZA" => "South Africa",
							  "GS" => "South Georgia, South Sandwich Islands",
							  "ES" => "Spain",
							  "LK" => "Sri Lanka",
							  "SH" => "St. Helena",
							  "PM" => "St. Pierre And Miquelon",
							  "SD" => "Sudan",
							  "SR" => "Suriname",
							  "SJ" => "Svalbard And Jan Mayen Islands",
							  "SZ" => "Swaziland",
							  "SE" => "Sweden",
							  "CH" => "Switzerland",
							  "SY" => "Syrian Arab Republic",
							  "TW" => "Taiwan",
							  "TJ" => "Tajikistan",
							  "TZ" => "Tanzania, United Republic Of",
							  "TH" => "Thailand",
							  "TG" => "Togo",
							  "TK" => "Tokelau",
							  "TO" => "Tonga",
							  "TT" => "Trinidad And Tobago",
							  "TN" => "Tunisia",
							  "TR" => "Turkey",
							  "TM" => "Turkmenistan",
							  "TC" => "Turks And Caicos Islands",
							  "TV" => "Tuvalu",
							  "UG" => "Uganda",
							  "UA" => "Ukraine",
							  "AE" => "United Arab Emirates",
							  "UM" => "United States Minor Outlying Islands",
							  "UY" => "Uruguay",
							  "UZ" => "Uzbekistan",
							  "VU" => "Vanuatu",
							  "VE" => "Venezuela",
							  "VN" => "Viet Nam",
							  "VG" => "Virgin Islands (British)",
							  "VI" => "Virgin Islands (U.S.)",
							  "WF" => "Wallis And Futuna Islands",
							  "EH" => "Western Sahara",
							  "YE" => "Yemen",
							  "YU" => "Yugoslavia",
							  "ZM" => "Zambia",
							  "ZW" => "Zimbabwe"
		);
		$this->select_tag($name,$_countries,$multiple,$inline,"YES");
	}	


	public function timezone_tag($name="", $mulitple="", $inline=""){
		$name = empty($name) ? "timezone" : $name;
		$_timezones = array(
				    'Pacific/Midway'       => "(GMT-11:00) Midway Island",
				    'US/Samoa'             => "(GMT-11:00) Samoa",
				    'US/Hawaii'            => "(GMT-10:00) Hawaii",
				    'US/Alaska'            => "(GMT-09:00) Alaska",
				    'US/Pacific'           => "(GMT-08:00) Pacific Time (US &amp; Canada)",
				    'America/Tijuana'      => "(GMT-08:00) Tijuana",
				    'US/Arizona'           => "(GMT-07:00) Arizona",
				    'US/Mountain'          => "(GMT-07:00) Mountain Time (US &amp; Canada)",
				    'America/Chihuahua'    => "(GMT-07:00) Chihuahua",
				    'America/Mazatlan'     => "(GMT-07:00) Mazatlan",
				    'America/Mexico_City'  => "(GMT-06:00) Mexico City",
				    'America/Monterrey'    => "(GMT-06:00) Monterrey",
				    'Canada/Saskatchewan'  => "(GMT-06:00) Saskatchewan",
				    'US/Central'           => "(GMT-06:00) Central Time (US &amp; Canada)",
				    'US/Eastern'           => "(GMT-05:00) Eastern Time (US &amp; Canada)",
				    'US/East-Indiana'      => "(GMT-05:00) Indiana (East)",
				    'America/Bogota'       => "(GMT-05:00) Bogota",
				    'America/Lima'         => "(GMT-05:00) Lima",
				    'America/Caracas'      => "(GMT-04:30) Caracas",
				    'Canada/Atlantic'      => "(GMT-04:00) Atlantic Time (Canada)",
				    'America/La_Paz'       => "(GMT-04:00) La Paz",
				    'America/Santiago'     => "(GMT-04:00) Santiago",
				    'Canada/Newfoundland'  => "(GMT-03:30) Newfoundland",
				    'America/Buenos_Aires' => "(GMT-03:00) Buenos Aires",
				    'Greenland'            => "(GMT-03:00) Greenland",
				    'Atlantic/Stanley'     => "(GMT-02:00) Stanley",
				    'Atlantic/Azores'      => "(GMT-01:00) Azores",
				    'Atlantic/Cape_Verde'  => "(GMT-01:00) Cape Verde Is.",
				    'Africa/Casablanca'    => "(GMT) Casablanca",
				    'Europe/Dublin'        => "(GMT) Dublin",
				    'Europe/Lisbon'        => "(GMT) Lisbon",
				    'Europe/London'        => "(GMT) London",
				    'Africa/Monrovia'      => "(GMT) Monrovia",
				    'Europe/Amsterdam'     => "(GMT+01:00) Amsterdam",
				    'Europe/Belgrade'      => "(GMT+01:00) Belgrade",
				    'Europe/Berlin'        => "(GMT+01:00) Berlin",
				    'Europe/Bratislava'    => "(GMT+01:00) Bratislava",
				    'Europe/Brussels'      => "(GMT+01:00) Brussels",
				    'Europe/Budapest'      => "(GMT+01:00) Budapest",
				    'Europe/Copenhagen'    => "(GMT+01:00) Copenhagen",
				    'Europe/Ljubljana'     => "(GMT+01:00) Ljubljana",
				    'Europe/Madrid'        => "(GMT+01:00) Madrid",
				    'Europe/Paris'         => "(GMT+01:00) Paris",
				    'Europe/Prague'        => "(GMT+01:00) Prague",
				    'Europe/Rome'          => "(GMT+01:00) Rome",
				    'Europe/Sarajevo'      => "(GMT+01:00) Sarajevo",
				    'Europe/Skopje'        => "(GMT+01:00) Skopje",
				    'Europe/Stockholm'     => "(GMT+01:00) Stockholm",
				    'Europe/Vienna'        => "(GMT+01:00) Vienna",
				    'Europe/Warsaw'        => "(GMT+01:00) Warsaw",
				    'Europe/Zagreb'        => "(GMT+01:00) Zagreb",
				    'Europe/Athens'        => "(GMT+02:00) Athens",
				    'Europe/Bucharest'     => "(GMT+02:00) Bucharest",
				    'Africa/Cairo'         => "(GMT+02:00) Cairo",
				    'Africa/Harare'        => "(GMT+02:00) Harare",
				    'Europe/Helsinki'      => "(GMT+02:00) Helsinki",
				    'Europe/Istanbul'      => "(GMT+02:00) Istanbul",
				    'Asia/Jerusalem'       => "(GMT+02:00) Jerusalem",
				    'Europe/Kiev'          => "(GMT+02:00) Kyiv",
				    'Europe/Minsk'         => "(GMT+02:00) Minsk",
				    'Europe/Riga'          => "(GMT+02:00) Riga",
				    'Europe/Sofia'         => "(GMT+02:00) Sofia",
				    'Europe/Tallinn'       => "(GMT+02:00) Tallinn",
				    'Europe/Vilnius'       => "(GMT+02:00) Vilnius",
				    'Asia/Baghdad'         => "(GMT+03:00) Baghdad",
				    'Asia/Kuwait'          => "(GMT+03:00) Kuwait",
				    'Africa/Nairobi'       => "(GMT+03:00) Nairobi",
				    'Asia/Riyadh'          => "(GMT+03:00) Riyadh",
				    'Asia/Tehran'          => "(GMT+03:30) Tehran",
				    'Europe/Moscow'        => "(GMT+04:00) Moscow",
				    'Asia/Baku'            => "(GMT+04:00) Baku",
				    'Europe/Volgograd'     => "(GMT+04:00) Volgograd",
				    'Asia/Muscat'          => "(GMT+04:00) Muscat",
				    'Asia/Tbilisi'         => "(GMT+04:00) Tbilisi",
				    'Asia/Yerevan'         => "(GMT+04:00) Yerevan",
				    'Asia/Kabul'           => "(GMT+04:30) Kabul",
				    'Asia/Karachi'         => "(GMT+05:00) Karachi",
				    'Asia/Tashkent'        => "(GMT+05:00) Tashkent",
				    'Asia/Kolkata'         => "(GMT+05:30) Kolkata",
				    'Asia/Kathmandu'       => "(GMT+05:45) Kathmandu",
				    'Asia/Yekaterinburg'   => "(GMT+06:00) Ekaterinburg",
				    'Asia/Almaty'          => "(GMT+06:00) Almaty",
				    'Asia/Dhaka'           => "(GMT+06:00) Dhaka",
				    'Asia/Novosibirsk'     => "(GMT+07:00) Novosibirsk",
				    'Asia/Bangkok'         => "(GMT+07:00) Bangkok",
				    'Asia/Jakarta'         => "(GMT+07:00) Jakarta",
				    'Asia/Krasnoyarsk'     => "(GMT+08:00) Krasnoyarsk",
				    'Asia/Chongqing'       => "(GMT+08:00) Chongqing",
				    'Asia/Hong_Kong'       => "(GMT+08:00) Hong Kong",
				    'Asia/Kuala_Lumpur'    => "(GMT+08:00) Kuala Lumpur",
				    'Australia/Perth'      => "(GMT+08:00) Perth",
				    'Asia/Singapore'       => "(GMT+08:00) Singapore",
				    'Asia/Taipei'          => "(GMT+08:00) Taipei",
				    'Asia/Ulaanbaatar'     => "(GMT+08:00) Ulaan Bataar",
				    'Asia/Urumqi'          => "(GMT+08:00) Urumqi",
				    'Asia/Irkutsk'         => "(GMT+09:00) Irkutsk",
				    'Asia/Seoul'           => "(GMT+09:00) Seoul",
				    'Asia/Tokyo'           => "(GMT+09:00) Tokyo",
				    'Australia/Adelaide'   => "(GMT+09:30) Adelaide",
				    'Australia/Darwin'     => "(GMT+09:30) Darwin",
				    'Asia/Yakutsk'         => "(GMT+10:00) Yakutsk",
				    'Australia/Brisbane'   => "(GMT+10:00) Brisbane",
				    'Australia/Canberra'   => "(GMT+10:00) Canberra",
				    'Pacific/Guam'         => "(GMT+10:00) Guam",
				    'Australia/Hobart'     => "(GMT+10:00) Hobart",
				    'Australia/Melbourne'  => "(GMT+10:00) Melbourne",
				    'Pacific/Port_Moresby' => "(GMT+10:00) Port Moresby",
				    'Australia/Sydney'     => "(GMT+10:00) Sydney",
				    'Asia/Vladivostok'     => "(GMT+11:00) Vladivostok",
				    'Asia/Magadan'         => "(GMT+12:00) Magadan",
				    'Pacific/Auckland'     => "(GMT+12:00) Auckland",
				    'Pacific/Fiji'         => "(GMT+12:00) Fiji",
				);
		$this->select_tag($name,$_timezones,$multiple,$inline,"YES");
	}	



}

//EXAMPLE
/*
$data = array("fname"=>"Jerald","lname"=>"pragash","password"=>"123456","email"=>"jforjerald@gmail.com", "username"=>"jforjerald", "sex"=>"male", 
		"city"=>json_encode(array("brisbane","vic","melbourne")), "zone"=>"vic", "country" => "India", "billingcountry" =>json_encode(array("IN")), 
		"myzone" =>json_encode(array("Australia/Sydney"))  ); 
	$form1 = new form($data);

	//$form1 = new form();
	$form1->open("customer","post",app::link_to("auth","create"), "new_customer");
	$form1->text_tag("fname","","","placeholder= 'First name' title='Enter Your First name'");
	$form1->text_tag("lname","","","placeholder= 'Last name' title='Enter Your Last name'");
	$form1->text_tag("email","","","placeholder= 'Email address' title='Enter Your Email address'");
	$form1->text_tag("username","","","placeholder= 'Username' title='Enter Your Username'");
	$form1->password_tag("password","","","placeholder= 'Password' title='Enter Your Password'");
	$form1->textarea_tag("username","","","placeholder= 'Username' rows='25' cols='30' title='Enter Your Username'");
	$form1->checkbox_tag("sex","title='Choose Male'");
	$form1->checkbox("sex", "male", "female", "checked");
	$form1->radio_tag("sex","title='Choose Female'");
	$form1->radio("sex", "male", "female", "checked");
	$form1->checkbox_group("city",array("brisbane","qld","vic","sydny","melbourne"), "label");
	$form1->radio_group("zone",array("brisbane","qld","vic","sydny","melbourne"), "label");
	$form1->submit_tag();
	$form1->reset_tag();
	$form1->file_tag("logo");
	$form1->select_tag("country",array("Austaralia","India","Zimbabawe","UK","USA"), "multi");
	$form1->country_tag("billingcountry");
	$form1->timezone_tag("myzone");
*/
?>