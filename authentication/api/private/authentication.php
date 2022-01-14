<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/private/db.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/private/verify.php";
require_once "config.php";
require_once "credentials.php";
require_once "security.php";
require_once "alarm.php";

function force_login(){
	GLOBAL $_SERVER;
	$referrer = $_SERVER['SCRIPT_NAME'];
	header("Location: /authentication/frontend/login.php?referrer=".$referrer);
	die("Please log in!");
}

?>
