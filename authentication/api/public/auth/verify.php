<?php

header('Content-Type: application/json');

require_once "../../private/authentication.php";

if(!req_param_i("min_perms")){
	die(json_encode(array('success' => false, 'reason' => 'invalid_request')));
}
$perms = req_get('min_perms');
$result = authenticate_request($perms);
if($result)
	die(json_encode(array('success' => true)));
die(json_encode(array('success' => false)));

?>
