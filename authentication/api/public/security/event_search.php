<?php

header('Content-Type: application/json');

require_once "../../private/authentication.php";

$auth = authenticate_request("authentication/events");
if(!$auth){
	http_response_code(401);
    die(json_encode(array('success' => false, 'reason' => 'authorization')));
}
if(!req_param("type") || !req_param_i("param")){
	http_response_code(400);
	die(json_encode(array('success' => false, 'reason' => 'invalid_request')));
}
$type = req_get("type");
$param = req_get("param");

$result = search_security_events($type,$param);
if($result == NULL){
	http_response_code(500);
	die(json_encode(array('success' => false, 'reason' => 'internal_error')));
}
die(json_encode(array('success' => true, 'events' => $result)));

?>
