<?php

header('Content-Type: application/json');

require_once "../../private/authentication.php";

if(!req_param_i("min_perms")){
	http_response_code(400);
	die(json_encode(array('success' => false, 'reason' => 'invalid_request')));
}
$perms = req_get('min_perms');
$result = authenticate_request($perms);
if($result)
	die(json_encode(array('success' => true)));
http_response_code(401);
die(json_encode(array('success' => false)));

?>
