<?php

header('Content-Type: application/json');

require_once "../../private/authentication.php";

$auth = authenticate_request(100);
if(!$auth){
    die(json_encode(array('success' => false, 'reason' => 'authorization')));
}

if(!req_param('reg_username') || !req_param('reg_password') || !req_param('reg_email') || !req_param_i('reg_perms')){
	die(json_encode(array('success' => false, 'reason' => 'invalid_request')));
}
$username = req_get('reg_username');
$password = req_get('reg_password');
$email = req_get('reg_email');
$perms = req_get('reg_perms');

$auth = authenticate_request($perms);
if(!$auth){
    die(json_encode(array('success' => false, 'reason' => 'authorization')));
}
$result = register($username,$password,$email,$perms);
if(!$result){
	die(json_encode(array('success' => false, 'reason' => 'internal_error')));
}
die(json_encode(array('success' => true)));

?>
