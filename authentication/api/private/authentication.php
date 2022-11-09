<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/private/db.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/private/verify.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/authentication/api/private/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/authentication/api/private/credentials.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/authentication/api/private/security.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/authentication/api/private/alarm.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/authentication/api/private/request.php";

function force_login(){
	GLOBAL $_SERVER;
	$referrer = $_SERVER['SCRIPT_NAME'];
	header("Location: /authentication/frontend/login.php?referrer=".$referrer);
	die("Please log in!");
}

?>
