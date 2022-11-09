<?php

header('Content-Type: application/json');

require_once "../../private/authentication.php";
if(req_param('override')){
	$auth = authenticate_request("authentication");
	if(!$auth){
		http_response_code(401);
		die(json_encode(array('success' => false, 'reason' => 'authorization')));
	}
	$key = req_get('override');
	$auth = verify_key($key, "","","");
	if($auth){
		http_response_code(401);
		die(json_encode(array('success' => false, 'reason' => 'authorization')));
	}
	$key_data = get_key($key);
	if($key_data['type'] != 1 || $key_data['subtype'] != 0){
		http_response_code(401);
		die(json_encode(array('success' => false, 'reason' => 'authorization')));
	}
	$user = get_user($key_data['user']);
	if(!authenticate_request_other($user['perms'], "authentication/admin")){
		http_response_code(401);
		die(json_encode(array('success' => false, 'reason' => 'authorization')));
	}
}else{
	$auth = authenticate_request("authentication/admin");
	if(!$auth){
		http_response_code(401);
		die(json_encode(array('success' => false, 'reason' => 'authorization')));
	}
}

if(!req_param('reg_username') || !req_param('reg_password') || !req_param('reg_email') || !req_param_i('reg_perms')){
	http_response_code(400);
	die(json_encode(array('success' => false, 'reason' => 'invalid_request')));
}
$username = req_get('reg_username');
$password = req_get('reg_password');
$email = req_get('reg_email');
$perms = req_get('reg_perms');

$auth = authenticate_request($perms);
if(!$auth){
	http_response_code(401);
    die(json_encode(array('success' => false, 'reason' => 'authorization')));
}
$result = register($username,$password,$email,$perms);
if(!$result){
	http_response_code(500);
	die(json_encode(array('success' => false, 'reason' => 'internal_error')));
}
die(json_encode(array('success' => true)));

?>
