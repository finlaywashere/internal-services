<?php

header('Content-Type: application/json');

require_once "../private/authentication.php";

if(isset($_REQUEST['username']) && isset($_REQUEST['token'])){
	$user = $_REQUEST['username'];
	$token = $_REQUEST['token'];
}else if(isset($_COOKIE['username']) && isset($_COOKIE['token'])){
	$user = $_COOKIE['username'];
	$token = $_COOKIE['token'];
}else{
	die(json_encode(array('success' => false)));
}
if(!logout($user,$token)){
	die(json_encode(array('success' => false)));
}else{
	if(isset($_COOKIE['username'])){
		setcookie("username","",time()-3600,"/");
		setcookie("token","",time()-3600,"/");
	}
	die(json_encode(array('success' => true)));
}
?>
