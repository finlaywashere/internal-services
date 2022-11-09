<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/authentication/api/private/permission_config.php";

function authenticate_request_other($uperms, $permission){
	if(is_numeric($permission)){
		return get_permissions() >= $permission;
	}
	global $permission_map;

	return $uperms >= $permission_map[$permission];
}

function authenticate_request($permission){
	return authenticate_request_other(get_permissions(), $permission);
}

?>
