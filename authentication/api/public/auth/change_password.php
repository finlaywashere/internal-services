<?php

header('Content-Type: application/json');

require_once "../../private/authentication.php";

if((!(isset($_REQUEST['key']) || isset($_COOKIE['key'])) && !(isset($_REQUEST['username']) || isset($_COOKIE['username']))) || !isset($_REQUEST['password']) || !isset($_REQUEST['new_password'])){
	die(json_encode(array('success' => false, 'reason' => 'invalid_request')));
}
if(isset($_REQUEST['key']) || isset($_COOKIE['key'])){
	if(isset($_REQUEST['key'])){
		$key = $_REQUEST['key'];
	}else{
		$key = $_COOKIE['key'];
	}
	// Don't bother verifying the key as it is only being used to get a username
	// Note: if 2fa for password change gets added then this will need to be checked if using a 2fa key to find username
	$k = get_key($key);
	if($k == -1){
		die(json_encode(array('success' => false, 'reason' => 'authorization')));
	}
	$username = get_user($k['user'])['username'];
}else{
	if(isset($_REQUEST['username'])){
		$username = $_REQUEST['username'];
	}else{
		$username = $_COOKIE['username'];
	}
}
$password = $_REQUEST['password'];
$new_password = $_REQUEST['new_password'];

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
