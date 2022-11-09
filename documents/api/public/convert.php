<?php
header('Content-Type: application/json');

require_once $_SERVER['DOCUMENT_ROOT']."/reports/api/private/reports.php";

$auth = authenticate_request("documents/create");
if(!$auth){
	http_response_code(401);
    die(json_encode(array('success' => false, 'reason' => 'authorization')));
}
die(generate_report("Test", 0, 'finlay', "Test2\nTest3"));
?>
