<?php

header('Content-Type: application/json');

require_once "../../private/authentication.php";

if((!req_param("key") && !req_param("username")) || !req_param('password') || !req_param('new_password')){
	die(json_encode(array('success' => false, 'reason' => 'invalid_request')));
}
if(req_param("key")){
	// Don't bother verifying the key as it is only being used to get a username
	// Note: if 2fa for password change gets added then this will need to be checked if using a 2fa key to find username
	$k = get_key(req_get("key"));
	if($k == -1){
		die(json_encode(array('success' => false, 'reason' => 'authorization')));
	}
	$username = get_user($k['user'])['username'];
}else{
	$username = req_get("username");
}
$password = req_get('password');
$new_password = req_get('new_password');

$id = verify_creds($username,$password);
if(!$id){
	die(json_encode(array('success' => false, 'reason' => 'authorization')));
}
$success = change_password($username,$new_password);
if(!$success){
	die(json_encode(array('success' => false, 'reason' => 'internal_error')));
}
die(json_encode(array('success' => true)));

?>
