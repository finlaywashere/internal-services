<?php
header('Content-Type: application/pdf');

require_once $_SERVER['DOCUMENT_ROOT']."/documents/api/private/reports.php";

$auth = authenticate_request(1);
if(!$auth){
    die(json_encode(array('success' => false, 'reason' => 'authorization')));
}
if(!req_param('document_id')){
    die(json_encode(array('success' => false, 'reason' => 'invalid_id')));
}
$id = req_get('document_id');

$perms = get_permissions();

$report = get_document($id, $perms);
if(!$report){
    die(json_encode(array('success' => false, 'reason' => 'invalid_id')));
}
die(base64_decode($report['document']));

?>
