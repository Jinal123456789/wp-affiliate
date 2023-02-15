<?php
/**
 * Funtions file
 *
 * @package All Common Functions
 * @author Todd Lahman LLC
 * @copyright   Copyright (c) Todd Lahman LLC
 * @since 1.3
 *
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// if (!function_exists('is_pro_license')) {
// 	function is_pro_license() {
// 		if(defined('WPLM_LICENSE_VALID')) {
// 			// define('WPLM_LICENSE_VALID', true);
// 			return WPLM_LICENSE_VALID;
// 		}
// 	}
// }

// if (!function_exists('wpam_is_activate')) {
// 	function wpam_is_activate() {
// 		if(defined('WPLM_LICENSE_ACTIVE')) {
// 			// define('WPLM_LICENSE_ACTIVE', true);
// 			return WPLM_LICENSE_ACTIVE;
// 		}
// 	}
// }

function wpam_getBrowser() {
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
	$operating_system = 'Unknown';
    $version= "";
	$ub = 'unknown';

	$os_array =   array(
		'/windows nt 10/i'      =>  'Windows 10',
		'/windows nt 6.3/i'     =>  'Windows 8.1',
		'/windows nt 6.2/i'     =>  'Windows 8',
		'/windows nt 6.1/i'     =>  'Windows 7',
		'/windows nt 6.0/i'     =>  'Windows Vista',
		'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
		'/windows nt 5.1/i'     =>  'Windows XP',
		'/windows xp/i'         =>  'Windows XP',
		'/windows nt 5.0/i'     =>  'Windows 2000',
		'/windows me/i'         =>  'Windows ME',
		'/win98/i'              =>  'Windows 98',
		'/win95/i'              =>  'Windows 95',
		'/win16/i'              =>  'Windows 3.11',
		'/macintosh|mac os x/i' =>  'Mac OS X',
		'/mac_powerpc/i'        =>  'Mac OS 9',
		'/linux/i'              =>  'Linux',
		'/ubuntu/i'             =>  'Ubuntu',
		'/iphone/i'             =>  'iPhone',
		'/ipod/i'               =>  'iPod',
		'/ipad/i'               =>  'iPad',
		'/android/i'            =>  'Android',
		'/blackberry/i'         =>  'BlackBerry',
		'/webos/i'              =>  'Mobile'
	);
	foreach ( $os_array as $regex => $value ) { 
		if ( preg_match($regex, $u_agent ) ) {
			$operating_system = $value;
		}
	}

	$browser_array  = array(
		'MSIE'       =>  'Internet Explorer',
		'Firefox'    =>  'Mozilla Firefox',
		'Safari'     =>  'Apple Safari',
		'Chrome'     =>  'Google Chrome',
		'Edge'       =>  'Edge',
		'Opera'      =>  'Opera',
		'Netscape'   =>  'Netscape',
		'Maxthon'    =>  'Maxthon',
		'Konqueror'  =>  'Konqueror',
		'Mobile'     =>  'Handheld Browser'
	);
	foreach ( $browser_array as $regex => $value ) { 
		if ( preg_match( '/'.$regex.'/i', $u_agent ) ) {
			$bname = $value;
			$ub = $regex;
		}
	}
   
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)) {
            $version= $matches['version'][0];
        } else {
            $version= $matches['version'][1];
        }
    } else {
        $version= $matches['version'][0];
    }
   
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
   
    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'operating_system'  => $operating_system,
        'pattern'    => $pattern
    );
}


function wpam_url_exist($url) {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch , CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch , CURLOPT_CUSTOMREQUEST, 'HEAD');

    $data = curl_exec($ch);
    $headers = curl_getinfo($ch);
    curl_close($ch);
	
	if(in_array($headers['http_code'],array(200,301))){
		//echo $headers['http_code']; echo $url;
			$return = true;
	}else{
		//echo "adasd";
			$return = false;
	}
    return $return;
}

function wpam_url_exist_old($url) {
	$http_response_header = @get_headers($url);
	print_r($http_response_header); die;
	$return = false;
	if ( isset($http_response_header) && !empty($http_response_header) && isset($http_response_header[0]) ) {
		if ( strpos($http_response_header[0], '200') ) {
			$return = true;
		} elseif (strpos($http_response_header[0], '301')) {
			foreach ($http_response_header as $val) {
				if ( strpos($val, 'Location:') !==false ) {
					$return = true;
				}
			}
		}
	}
	return $return;
}
	
// wpam_url_exist('http://fa3cuieboi23.com');