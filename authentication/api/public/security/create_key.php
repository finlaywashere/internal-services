<?php

header('Content-Type: application/json');

require_once "../../private/authentication.php";

$auth = authenticate_request("authentication");
if(!$auth){
	http_response_code(401);
    die(json_encode(array('success' => false, 'reason' => 'authorization')));
}
if(!req_param("key") || !req_param("curr_password") || !req_param_i("key_type") || !req_param_i("key_subtype") || !req_param_i("key_security")){
	http_response_code(400);
	die(json_encode(array('success' => false, 'reason' => 'invalid_request')));
}
$security = (int) req_get("key_security");
if($security && !req_param("key_auth")){
	http_response_code(400);
	die(json_encode(array('success' => false, 'reason' => 'invalid_request')));
}

$password = req_get("curr_password");
$user = verify_creds(get_username(), $password);
if($user == 0){
	http_response_code(401);
	die(json_encode(array('success' => false, 'reason' => 'authorization')));
}

if(req_param("target_user")){
	$auth = authenticate_request("authentication/admin");
	if(!$auth){
		http_response_code(401);
		die(json_encode(array('success' => false, 'reason' => 'authorization')));
	}
	$user = get_user_id(req_get("target_user"));
	$tgt_perms = get_user($user)['perms'];
	$perms = get_user(get_user_id(get_username()))['perms'];
	if($tgt_perms >= $perms){
		http_response_code(401);
		die(json_encode(array('success' => false, 'reason' => 'authorization')));
	}
}
$auth = "";
if(req_param("key_auth")){
	$auth = req_get("key_auth");
}
$key = req_get("key");
$type = req_get("key_type");
$subtype = req_get("key_subtype");

$result = create_key($username,$type,$subtype,$security,$auth,$key);
if(!$result){
	http_response_code(500);
	die(json_encode(array('success' => false, 'reason' => 'internal_error')));
}
die(json_encode(array('success' => true)));

?>
