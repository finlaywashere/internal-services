<?php

header('Content-Type: application/json');

require_once "../private/authentication.php";

if(!isset($_REQUEST['username']) || !isset($_REQUEST['password'])){
	die(json_encode(array('success' => false)));
}
$username = $_REQUEST['username'];
$password = $_REQUEST['password'];

$token = login($username,$password);
if(!$token){
	die(json_encode(array('success' => false, 'reason' => '1')));
}
die(json_encode(array('success' => true, 'token' => $token)));

?>
