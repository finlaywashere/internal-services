<?php
	require_once "../api/private/authentication.php";
	if(isset($_COOKIE['username']) && isset($_COOKIE['token'])){
		logout($_COOKIE['username'],$_COOKIE['token']);
		setcookie("username","",time()-3600,"/");
		setcookie("token","",time()-3600,"/");
	}
	if(isset($_COOKIE['key'])){
		destroy_key($_COOKIE['key']);
		setcookie("key","",time()-3600,"/");
	}
	if(isset($_REQUEST['referrer'])){
		$referrer = $_REQUEST['referrer'];
	}else{
		$referrer = "login.php";
	}
	header("Location: ".$referrer);
	die();
?>
