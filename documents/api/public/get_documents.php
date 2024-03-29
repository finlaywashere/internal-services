<?php

header('Content-Type: application/json');

require_once $_SERVER['DOCUMENT_ROOT']."/documents/api/private/reports.php";

$auth = authenticate_request("documents");
if(!$auth){
	http_response_code(401);
    die(json_encode(array('success' => false, 'reason' => 'authorization')));
}
if(!req_param_i('search_type') || !req_param('search_param')){
	http_response_code(400);
    die(json_encode(array('success' => false, 'reason' => 'invalid_request')));
}
$offset = 0;
$limit = 20;
if(req_param_i('search_offset')){
    $offset = req_get('search_offset');
}
if(req_param_i('search_limit')){
    $limit = req_get('search_limit');
}
$stype = req_get('search_type');
$param = req_get('search_param');

$reports = document_search($stype, $param,$offset,$limit);
if($reports === NULL){
	http_response_code(400);
    die(json_encode(array('success' => false, 'reason' => 'invalid_type')));
}

die(json_encode(array('success' => true, 'reports' => $reports)));

?>

