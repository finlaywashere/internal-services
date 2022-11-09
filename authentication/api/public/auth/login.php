<?php

header('Content-Type: application/json');

require_once "../../private/authentication.php";

if(!req_param('username') || !req_param('password')){
	http_response_code(400);
	die(json_encode(array('success' => false, 'reason' => 'invalid_request')));
}
$username = req_get('username');
$password = req_get('password');

$token = login($username,$password);
if(!$token){
	http_response_code(401);
	die(json_encode(array('success' => false, 'reason' => 'authorization')));
}
die(json_encode(array('success' => true, 'token' => $token)));

?>
