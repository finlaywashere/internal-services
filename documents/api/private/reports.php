<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/private/db.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/private/verify.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/private/authentication.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/documents/api/private/conversion.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/documents/api/private/scan.php";

function create_report($user, $title, $body, $type = 0, $security = 0){
	$report = generate_report($title, $security, get_user($user)['username'], $body);
	return create_document($user,$title,$type,$security,$report);
}
function create_document($user,$title,$type,$security,$report){
	$conn = db_connect();
	if(!$conn){
		return 0;
	}
	$stmt = $conn->prepare("INSERT INTO documents (user_id,document_title,document_type,document_security, document_data) VALUES (?,?,?,?,?);");
	if(!$stmt){ sql_error($conn); }
	$stmt->bind_param("isiib",$user,$title,$type,$security,$report);
	$stmt->send_long_data(4, $report);
	if(!$stmt->execute()){ sql_error($conn); }
	$id = $conn->insert_id;
	$conn->close();
	return $id;
}

function document_search($stype, $value, $offset, $limit){
	$conn = db_connect();
	if(!$conn){
		return NULL;
	}
	$stmt = NULL;
	if($stype == 1){
		// Search by user
		$stmt = $conn->prepare("SELECT document_id FROM documents WHERE user_id = ? AND user_id > ? LIMIT ?;");
		if(!$stmt){ sql_error($conn); }
		$stmt->bind_param("iii",$value,$offset,$limit);
	}else if($stype == 2){
		// Search by date
		$stmt = $conn->prepare("SELECT document_id FROM documents WHERE DATE(document_date) = ? AND user_id > ? LIMIT ?;");
		if(!$stmt){ sql_error($conn); }
		$stmt->bind_param("sii",$value,$offset,$limit);
	}else if($stype == 3){
		// Search by description
		$value = "%".$value."%";
		$stmt = $conn->prepare("SELECT document_id FROM documents WHERE document_desc LIKE ? AND user_id > ? LIMIT ?;");
		if(!$stmt){ sql_error($conn); }
		$stmt->bind_param("sii",$value,$offset,$limit);
	}else if($stype == 4){
		// Search by title
		$value = "%".$value."%";
		$stmt = $conn->prepare("SELECT document_id FROM documents WHERE document_title LIKE ? AND user_id > ? LIMIT ?;");
		if(!$stmt){ sql_error($conn); }
		$stmt->bind_param("sii",$value,$offset,$limit);
	}else if($stype == 5){
		// Search by type
		$stmt = $conn->prepare("SELECT document_id FROM documents WHERE document_type = ? AND user_id > ? LIMIT ?;");
		if(!$stmt){ sql_error($conn); }
		$stmt->bind_param("sii",$value,$offset,$limit);
	}else if($stype == 6){
		// Search by id
		$stmt = $conn->prepare("SELECT document_id FROM documents WHERE document_id=? AND user_id > ? LIMIT ?;");
		if(!$stmt){ sql_error($conn); }
		$stmt->bind_param("iii",$value,$offset,$limit);
	}else{
		$conn->close();
		return NULL;
	}
	if(!$stmt->execute()){ sql_error($conn); }
	$result = $stmt->get_result();
	if(!mysqli_num_rows($result)){
		return array();
	}
	$return = array();
	while($row = $result->fetch_assoc()){
		array_push($return,$row['document_id']);
	}
	$conn->close();
	return $return;
}

function get_document($document_id){
	$conn = db_connect();
	if(!$conn){
		return 0;
	}
	$stmt = $conn->prepare("SELECT * FROM `documents` WHERE `document_id`=?;");
	if(!$stmt){ sql_error($conn); }
	$stmt->bind_param("i",$document_id);
	if(!$stmt->execute()){ sql_error($conn); }

	$result = $stmt->get_result();
	if(!mysqli_num_rows($result)){
		return 0;
	}
	$row = $result->fetch_assoc();
	
	$security = $row['document_security'];
	
	// Validate that user has access to report
	if(!authenticate_request($security)){
		return 0;
	}
	$return = array("user" => get_user($row['user_id']),"title" => $row['document_title'],"desc" => $row['document_desc'],"date" => $row['document_date'],"type" => $row['document_type'],"security" => $security, "document" => $row['document_data']);
	echo "4";
	$conn->close();
	return $return;
}


?>
