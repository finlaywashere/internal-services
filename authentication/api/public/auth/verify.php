<?php

header('Content-Type: application/json');

require_once "../private/authentication.php";

if(!isset($_REQUEST['username']) || !isset($_REQUEST['token'])){
    die(json_encode(array('success' => false)));
}
$username = $_REQUEST['username'];
$token = $_REQUEST['token'];

$success = login_verify($username,$token);
if(!$success){
    die(json_encode(array('success' => false, 'reason' => '1')));
}
die(json_encode(array('success' => true)));

?>
