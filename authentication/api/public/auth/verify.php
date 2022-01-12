<?php

header('Content-Type: application/json');

require_once "../../private/authentication.php";

if(!isset($_REQUEST['min_perms'])){
	die(json_encode(array('success' => false, 'reason' => 'invalid_request')));
}
$perms = $_REQUEST['min_perms'];
$result = authenticate_request($perms);
if($result)
	die(json_encode(array('success' => true)));
die(json_encode(array('success' => false)));

?>