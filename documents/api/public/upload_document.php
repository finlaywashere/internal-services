<?php
header('Content-Type: application/json');

require_once $_SERVER['DOCUMENT_ROOT']."/documents/api/private/reports.php";

$auth = authenticate_request("documents/create");
if(!$auth){
	http_response_code(401);
    die(json_encode(array('success' => false, 'reason' => 'authorization')));
}
if(!req_param('title')){
	http_response_code(400);
    die(json_encode(array('success' => false, 'reason' => 'invalid_title')));
}
$title = req_get('title');

if(!isset($_FILES['document']) || !is_uploaded_file($_FILES['document']['tmp_name'])){
	http_response_code(400);
	die(json_encode(array('success' => false, 'reason' => 'invalid_document')));
}

$type = 0;
if(req_param_i('type')){
	$type = req_get('type');
}
$security = 0;
if(req_param_i('security')){
	$security = req_get('security');
}

$mime_type = mime_content_type($_FILES['document']['tmp_name']);
if($mime_type != 'application/pdf'){
	http_response_code(400);
	die(json_encode(array('success' => false, 'reason' => 'invalid_mime')));
}

$file = fopen($_FILES['document']['tmp_name'], "rb");
$size = fstat($file)['size'];
$data = base64_encode(fread($file, $size));

fclose($file);

$doc = create_document(get_user_id(get_username()),$title,$type,$security,$data);

die(json_encode(array('success' => true,'document' => $doc)));

?>
