<?php
header('Content-Type: application/json');

require_once $_SERVER['DOCUMENT_ROOT']."/reports/api/private/reports.php";

$auth = authenticate_request(2);
if(!$auth){
    die(json_encode(array('success' => false, 'reason' => 'authorization')));
}
if(!req_param('title') || !req_param('body') || !req_param_i('type') || !req_param_i('security')){
    die(json_encode(array('success' => false, 'reason' => 'invalid_product')));
}
$title = req_get('title');
$body = req_get('body');
$type = req_get('type');
$security = req_get('security');

$report = create_report(get_user_id(),$title,$body,$type,$security);

die(json_encode(array('success' => true,'report' => $report)));

?>

