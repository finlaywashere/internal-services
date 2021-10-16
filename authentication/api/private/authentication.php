<?php

require_once "db.php";
require_once "config.php";

function generate_token(
	int $length = 64,
	string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
): string {
	if ($length < 1) {
		throw new \RangeException("Length must be a positive integer");
	}
	$pieces = [];
	$max = mb_strlen($keyspace, '8bit') - 1;
	for ($i = 0; $i < $length; ++$i) {
		$pieces []= $keyspace[random_int(0, $max)];
	}
	return implode('', $pieces);
}

function login($user,$password){
	$conn = db_connect();
	if(!$conn){
		return 0;
	}
	$stmt = $conn->prepare("SELECT `user_password`, `user_id` FROM `users` WHERE `user_username`=?;");
	$stmt->bind_param("s",$user);
	$stmt->execute();

	$result = $stmt->get_result();
	if(!mysqli_num_rows($result)){
		$conn->close();
		return 0;
	}
	$row = $result->fetch_assoc();
	if(!password_verify($password,$row['user_password'])){
		$conn->close();
		return 0;
	}
	$token = generate_token();
	$stmt = $conn->prepare("INSERT INTO `tokens` (user_id,token_data,token_expiry) VALUES (?,?,FROM_UNIXTIME(?));");
	GLOBAL $token_expiry;
	$expiry = time() + $token_expiry;

	$stmt->bind_param("ssi",$row['user_id'],$token,$expiry);
	$stmt->execute();

	$conn->close();
	return $token;
}
/**
	This function is designed to be used internally by authenticated users and not open to anyone
*/
function register($user,$password,$email,$perms){
	$conn = db_connect();
	if(!$conn){
		return 0;
	}
	$stmt = $conn->prepare("SELECT * FROM `users` WHERE `user_username`=?;");
	$stmt->bind_param("s",$user);
	$stmt->execute();

	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	if($row){
		// User exists
		$conn->close();
		return -1;
	}
	$hash = password_hash($password,PASSWORD_DEFAULT);
	$stmt = $conn->prepare("INSERT INTO `users` (user_username,user_password,user_perms,user_email) VALUES (?,?,?,?);");
	$stmt->bind_param("ssis",$user,$hash,$perms,$email);
	$stmt->execute();

	$conn->close();
	return 1;
}
function change_password($user,$password){
	$conn = db_connect();
	if(!$conn){
		return 0;
	}
	$hash = password_hash($password,PASSWORD_DEFAULT);
	$stmt = $conn->prepare("UPDATE `users` SET `user_password`=? WHERE `user_username`=?;");
	$stmt->bind_param("ss",$hash,$user);
	$stmt->execute();
	
	$conn->close();
	return 1;
}
function login_verify($user_name,$token){
	$conn = db_connect();
	if(!$conn){
		return -1;
	}
	$stmt = $conn->prepare("SELECT `user_perms`,`user_id` FROM `users` WHERE `user_username`=?;");
	$stmt->bind_param("s",$user_name);
    $stmt->execute();
    $result = $stmt->get_result();
	if(!mysqli_num_rows($result)){
        $conn->close();
        return -1;
    }
    $row = $result->fetch_assoc();

    $perms = $row['user_perms'];
	$user = $row['user_id'];

	$stmt = $conn->prepare("SELECT `token_data` FROM `tokens` WHERE `user_id`=? AND `token_expiry` > now() AND `token_type` = 0;");
	$stmt->bind_param("i",$user);
	$stmt->execute();

	$result = $stmt->get_result();

	if(!mysqli_num_rows($result)){
		$conn->close();
		return -1;
	}
	$found = false;
	while($row = $result->fetch_assoc()){
		if($token == $row['token_data']){
			$found = true;
			break;
		}
	}
	if(!$found){
		$conn->close();
		return -1;
	}

	$conn->close();
	return $perms;
}
/**
	Helper function to easily authenticate user and check permission levels
*/
function authenticate_request(int $min_perms){
	GLOBAL $_REQUEST;
	GLOBAL $_COOKIE;
	if((!isset($_REQUEST['username']) || !isset($_REQUEST['token'])) && (!isset($_COOKIE['username']) || !isset($_COOKIE['token']))){
		return 0;
	}
	if(isset($_REQUEST['username'])){
		$username = $_REQUEST['username'];
		$token = $_REQUEST['token'];
	}else{
		$username = $_COOKIE['username'];
		$token = $_COOKIE['token'];
	}
	$perms = login_verify($username,$token);
	if($perms < 0){
		return 0;
	}
	return $perms >= $min_perms;
}

?>
