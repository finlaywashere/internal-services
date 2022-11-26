<?php
header('Content-Type: application/json');

require_once $_SERVER['DOCUMENT_ROOT']."/documents/api/private/reports.php";

$auth = authenticate_request("documents/create");
if(!$auth){
	http_response_code(401);
    die(json_encode(array('success' => false, 'reason' => 'authorization')));
}
if(!req_param('title') || !req_param('security')){
	http_response_code(400);
    die(json_encode(array('success' => false, 'reason' => 'invalid_request')));
}
$title = req_get('title');
$security = req_get('security');

$report = create_document(get_username(),$title,0,$security,adf_scan());
if(!$report){
	http_response_code(500);
    die(json_encode(array('success' => false, 'reason' => 'internal_error')));
}
die(json_encode(array('success' => true,'document' => $report)));

?>
