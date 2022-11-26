<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/authentication/api/private/permission_config.php";

function authenticate_request_other($uperms, $permission){
	return authenticate_request_other_remote($uperms, $permission, false);
}
function authenticate_request_other_remote($uperms, $permission, $force_local){
	global $remote_auth;
	if($remote_auth){
		if(!$force_local){
			global $auth_url;
			$c = curl_init();
			$params = array('min_perms' => $permission);
			$username = get_username();
			if($username != NULL){
				$params['username'] = $username;
			}
			$token = NULL;
			if(isset($_REQUEST['token'])){
				$token = $_REQUEST['token'];
			}else if(isset($_COOKIE['token'])){
				$token = $_COOKIE['token'];
			}
			if($token != NULL){
				$params['token'] = $token;
			}
			$key = NULL;
			if(isset($_REQUEST['key'])){
				$key = $_REQUEST['key'];
			}else if(isset($_COOKIE['key'])){
				$key = $_COOKIE['key'];
			}
			if($key != NULL){
				$params['key'] = $key;
			}
			$key_a = NULL;
			if(isset($_REQUEST['key_auth'])){
				$key_a = $_REQUEST['key_auth'];
			}else if(isset($_COOKIE['key_auth'])){
				$key_a = $_COOKIE['key_auth'];
			}
			if($key_a != NULL){
				$params['key_auth'] = $key_a;
			}
			$url = $auth_url."?".http_build_query($params);
			curl_setopt($c, CURLOPT_URL, $url);
			curl_setopt($c, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
			$result = curl_exec($c);
			if(!$result){
				return false;
			}
			$json = json_decode($result);
			return $json['success'];
		}else{
			error_log("Authentication Error: No authentication method is configured and local auth is forced", 3, "/var/log/php.log");
			return false;
		}
	}
	if(is_numeric($permission)){
		return $uperms >= $permission;
	}
	global $permission_map;
	
	if(!array_key_exists($permission,$permission_map)){
		return $uperms >= 100; // Default in case permissions not found
	}
	return $uperms >= $permission_map[$permission];
}

function authenticate_request($permission){
	return authenticate_request_other_remote(get_permissions(), $permission, false);
}

?>
