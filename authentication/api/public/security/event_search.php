<?php

header('Content-Type: application/json');

require_once "../../private/authentication.php";

$auth = authenticate_request(2);
if(!$auth){
    die(json_encode(array('success' => false, 'reason' => 'authorization')));
}
if(!req_param("type") || !req_param_i("param")){
	die(json_encode(array('success' => false, 'reason' => 'invalid_request')));
}
$type = req_get("type");
$param = req_get("param");

$result = search_security_events($type,$param);
if($result == NULL){
	die(json_encode(array('success' => false, 'reason' => 'internal_error')));
}
die(json_encode(array('success' => true, 'events' => $result)));

?>
