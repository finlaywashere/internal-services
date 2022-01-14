<?php

header('Content-Type: application/json');

require_once "../../private/authentication.php";

if(!req_param('username') || !req_param('password')){
	die(json_encode(array('success' => false)));
}
$username = req_get('username');
$password = req_get('password');

$token = login($username,$password);
if(!$token){
	die(json_encode(array('success' => false, 'reason' => '1')));
}
die(json_encode(array('success' => true, 'token' => $token)));

?>
