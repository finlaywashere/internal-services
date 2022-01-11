<?php

header('Content-Type: application/json');

require_once "../private/authentication.php";

if(!isset($_REQUEST['username']) || !isset($_REQUEST['password']) || !isset($_REQUEST['new_password'])){
	die(json_encode(array('success' => false)));
}
$username = $_REQUEST['username'];
$password = $_REQUEST['password'];
$new_password = $_REQUEST['new_password'];

$id = verify_creds($username,$password);
if(!$id){
	die(json_encode(array('success' => false, 'reason' => '1')));
}
$success = change_password($username,$new_password);
if(!$success){
	die(json_encode(array('success' => false, 'reason' => '1')));
}
die(json_encode(array('success' => true)));

?>
