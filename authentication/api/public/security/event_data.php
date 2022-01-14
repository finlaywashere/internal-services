<?php

header('Content-Type: application/json');

require_once "../../private/authentication.php";

$auth = authenticate_request(2);
if(!$auth){
    die(json_encode(array('success' => false, 'reason' => 'authorization')));
}
if(!req_param_i("event_id")){
	die(json_encode(array('success' => false, 'reason' => 'invalid_request')));
}
$id = req_get("event_id");

$result = get_security_event($id);
if($result == NULL){
	die(json_encode(array('success' => false, 'reason' => 'internal_error')));
}
die(json_encode(array('success' => true, 'event' => $result)));

?>
