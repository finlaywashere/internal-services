<?php
header('Content-Type: application/json');

require_once $_SERVER['DOCUMENT_ROOT']."/documents/api/private/reports.php";

$auth = authenticate_request("documents");
if(!$auth){
	http_response_code(401);
    die(json_encode(array('success' => false, 'reason' => 'authorization')));
}
if(!req_param('document_id')){
	http_response_code(400);
    die(json_encode(array('success' => false, 'reason' => 'invalid_id')));
}
$id = req_get('document_id');

$perms = get_permissions();

$report = get_document($id, $perms);
if(!$report){
	http_response_code(400);
    die(json_encode(array('success' => false, 'reason' => 'invalid_id')));
}
die(json_encode(array('success' => true,'report' => $report)));

?>
