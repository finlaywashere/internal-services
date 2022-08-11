<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/private/db.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/private/verify.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/private/authentication.php";

function create_report($user, $title, $body, $type = 0, $security = 0){
	$conn = db_connect();
	if(!$conn){
		return 0;
	}
	$stmt = $conn->prepare("INSERT INTO reports (user_id,report_title,report_body,report_type,report_security) VALUES (?,?,?,?,?);");
	$stmt->bind_param("issii",$user,$title,$body,$type,$security);
	$stmt->execute();
	$id = $conn->insert_id;
	$conn->close();
	return $id;
}

function reports_search($stype, $value, $offset, $limit){
	$conn = db_connect();
	if(!$conn){
		return NULL;
	}
	$stmt = NULL;
	if($stype == 1){
		// Search by user
		$stmt = $conn->prepare("SELECT report_id FROM reports WHERE user_id = ? AND user_id > ? LIMIT ?;");
		$stmt->bind_param("iii",$value,$offset,$limit);
	}else if($stype == 2){
		// Search by date
		$stmt = $conn->prepare("SELECT report_id FROM reports WHERE DATE(report_date) = ? AND user_id > ? LIMIT ?;");
		$stmt->bind_param("sii",$value,$offset,$limit);
	}else if($stype == 3){
		// Search by body
		$value = "%".$value."%";
		$stmt = $conn->prepare("SELECT report_id FROM reports WHERE report_body LIKE ? AND user_id > ? LIMIT ?;");
		$stmt->bind_param("sii",$value,$offset,$limit);
	}else if($stype == 4){
		// Search by title
		$value = "%".$value."%";
		$stmt = $conn->prepare("SELECT report_id FROM reports WHERE report_title LIKE ? AND user_id > ? LIMIT ?;");
		$stmt->bind_param("sii",$value,$offset,$limit);
	}else if($stype == 5){
		// Search by type
		$stmt = $conn->prepare("SELECT report_id FROM reports WHERE report_type = ? AND user_id > ? LIMIT ?;");
		$stmt->bind_param("sii",$value,$offset,$limit);
	}else if($stype == 6){
		// Search by id
		$stmt = $conn->prepare("SELECT report_id FROM reports WHERE report_id=? AND user_id > ? LIMIT ?;");
		$stmt->bind_param("iii",$value,$offset,$limit);
	}else{
		$conn->close();
		return NULL;
	}
	$stmt->execute();
	$result = $stmt->get_result();
	if(!mysqli_num_rows($result)){
		return array();
	}
	$return = array();
	while($row = $result->fetch_assoc()){
		array_push($return,$row['report_id']);
	}
	$conn->close();
	return $return;
}

function get_report($report_id, $perms){
	$conn = db_connect();
	if(!$conn){
		return 0;
	}
	$stmt = $conn->prepare("SELECT * FROM `reports` WHERE `report_id`=?;");
	$stmt->bind_param("i",$report_id);
	$stmt->execute();

	$result = $stmt->get_result();
	if(!mysqli_num_rows($result)){
		return 0;
	}
	$row = $result->fetch_assoc();
	
	$security = $row['report_security'];
	
	// Validate that user has access to report
	if($security > $perms){
		return 0;
	}

	$return = array("user" => get_user($row['user_id']),"title" => $row['report_title'],"body" => $row['report_body'],"date" => $row['report_date'],"type" => $row['report_type'],"security" => $security);

	$conn->close();
	return $return;
}


?>
