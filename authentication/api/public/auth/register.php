<?php

header('Content-Type: application/json');

require_once "../private/authentication.php";

$auth = authenticate_request(100);
if(!$auth){
    die(json_encode(array('success' => false, 'reason' => 'authorization')));
}

if(!isset($_REQUEST['reg_username']) || !isset($_REQUEST['reg_password']) || !isset($_REQUEST['reg_email']) || !isset($_REQUEST['reg_perms'])){
	die(json_encode(array('success' => false, 'reason' => 'invalid_request')));
}
$username = $_REQUEST['reg_username'];
$password = $_REQUEST['reg_password'];
$email = $_REQUEST['reg_email'];
$perms = $_REQUEST['reg_perms'];

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
