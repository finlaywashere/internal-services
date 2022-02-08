<?php
header('Content-Type: application/json');

require_once $_SERVER['DOCUMENT_ROOT']."/reports/api/private/reports.php";

$auth = authenticate_request(1);
if(!$auth){
    die(json_encode(array('success' => false, 'reason' => 'authorization')));
}
if(!req_param('report_id')){
    die(json_encode(array('success' => false, 'reason' => 'invalid_id')));
}
$id = req_get('report_id');

$perms = get_permissions();

$report = get_report($id, $perms);
if(!$report){
    die(json_encode(array('success' => false, 'reason' => 'invalid_id')));
}
die(json_encode(array('success' => true,'report' => $report)));

?>
