<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/authentication/api/private/permission_config.php";

function authenticate_request_other($uperms, $permission){
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
	return authenticate_request_other(get_permissions(), $permission);
}

?>
