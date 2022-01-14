<?php

header('Content-Type: application/json');

require_once "../../private/authentication.php";

if(req_param('username') && req_param('token')){
	$user = req_get('username');
	$token = req_get('token');
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
