<?php

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

function verify_creds($user, $password){
	$conn = db_connect();
	if(!$conn){
		return 0;
	}
	$stmt = $conn->prepare("SELECT `user_password`, `user_id` FROM `users` WHERE `user_username`=?;");
	if(!$stmt){ sql_error($conn); }
	$stmt->bind_param("s",$user);
	if(!$stmt->execute()){ sql_error($conn); }

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
	$id = $row['user_id'];
	$conn->close();
	return $id;
}

function login($user,$password){
	global $remote_auth;
	if(!$remote_auth){
		$conn = db_connect();
		if(!$conn){
			return 0;
		}
		$id = verify_creds($user,$password);
		if(!$id){
			$conn->close();
			return 0;
		}
		$token = generate_token();
		$stmt = $conn->prepare("INSERT INTO `tokens` (user_id,token_data,token_expiry) VALUES (?,?,FROM_UNIXTIME(?));");
		if(!$stmt){ sql_error($conn); }
		GLOBAL $token_expiry;
		$expiry = time() + $token_expiry;

		$stmt->bind_param("ssi",$id,$token,$expiry);
		if(!$stmt->execute()){ sql_error($conn); }
	
		$conn->close();
		return $token;
	}else{
		global $login_url;
		$c = curl_init();
		$url = $login_url."?".http_build_query(array("username" => $user, "password" => $password));
		curl_setopt($c, CURLOPT_URL, $url);
		curl_setopt($c, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
		$result = curl_exec($c);
		if(!$result){
			return 0;
		}
		echo $result;
		$json = json_decode($result);
		if(!$json['success']){
			return 0;
		}
		return $json['token'];
	}
}
function revoke_tokens($user){
	$conn = db_connect();
	if(!$conn){
		return 0;
	}
	$stmt = $conn->prepare("SELECT `user_id` FROM `users` WHERE `user_username`=?;");
	if(!$stmt){ sql_error($conn); }
	$stmt->bind_param("s",$user);
	if(!$stmt->execute()){ sql_error($conn); }
	$row = $stmt->get_result()->fetch_assoc();
	$id = $row['user_id'];
	$stmt = $conn->prepare("DELETE FROM `tokens` WHERE `user_id`=?;");
	if(!$stmt){ sql_error($conn); }
	$stmt->bind_param("i",$id);
	if(!$stmt->execute()){ sql_error($conn); }
	$conn->close();
	return 1;
}
function revoke_token($user, $token){
	$conn = db_connect();
	if(!$conn){
		return 0;
	}
	$stmt = $conn->prepare("SELECT `user_id` FROM `users` WHERE `user_username`=?;");
	if(!$stmt){ sql_error($conn); }
	$stmt->bind_param("s",$user);
	if(!$stmt->execute()){ sql_error($conn); }
	$row = $stmt->get_result()->fetch_assoc();
	$id = $row['user_id'];
	$stmt = $conn->prepare("DELETE FROM `tokens` WHERE `user_id`=? AND `token_data`=?;");
	if(!$stmt){ sql_error($conn); }
	$stmt->bind_param("is",$id,$token);
	if(!$stmt->execute()){ sql_error($conn); }
	$conn->close();
	return 1;
}

function logout($user,$token){
	if(login_verify($user,$token) < 0){
		return 0;
	}
	return revoke_token($user,$token);
}
function change_password($user,$password){
	$conn = db_connect();
	if(!$conn){
		return 0;
	}
	$hash = password_hash($password,PASSWORD_DEFAULT);
	$stmt = $conn->prepare("UPDATE `users` SET `user_password`=? WHERE `user_username`=?;");
	if(!$stmt){ sql_error($conn); }
	$stmt->bind_param("ss",$hash,$user);
	if(!$stmt->execute()){ sql_error($conn); }
	$conn->close();
	if(!revoke_tokens($user)){
		return 0;
	}
	return revoke_keys(get_user_id($user));
}
function register($user,$password,$email,$perms){
	$conn = db_connect();
	if(!$conn){
		return 0;
	}
	$hash = password_hash($password,PASSWORD_DEFAULT);
	$stmt = $conn->prepare("INSERT INTO `users` (user_username,user_password,user_email,user_perms) VALUES (?,?,?,?);");
	if(!$stmt){ sql_error($conn); }
	$stmt->bind_param("sssi",$user,$hash,$email,$perms);
	if(!$stmt->execute()){ sql_error($conn); }
	$conn->close();
	return 1;
}
function login_verify($user_name,$token){
	$conn = db_connect();
	if(!$conn){
		return -1;
	}
	$stmt = $conn->prepare("SELECT `user_perms`,`user_id` FROM `users` WHERE `user_username`=?;");
	if(!$stmt){ sql_error($conn); }
	$stmt->bind_param("s",$user_name);
	if(!$stmt->execute()){ sql_error($conn); }
	$result = $stmt->get_result();
	if(!mysqli_num_rows($result)){
		$conn->close();
		return -1;
	}
	$row = $result->fetch_assoc();

	$perms = $row['user_perms'];
	$user = $row['user_id'];

	$stmt = $conn->prepare("SELECT `token_data` FROM `tokens` WHERE `user_id`=? AND `token_expiry` > now() AND `token_type` = 0;");
	if(!$stmt){ sql_error($conn); }
	$stmt->bind_param("i",$user);
	if(!$stmt->execute()){ sql_error($conn); }

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

Gets a user's information by their id

*/
function get_user($id){
	$conn = db_connect();
	if(!$conn){
		return NULL;
	}
	$stmt = $conn->prepare("SELECT * FROM users WHERE user_id=?;");
	if(!$stmt){ sql_error($conn); }
	$stmt->bind_param("i",$id);
	if(!$stmt->execute()){ sql_error($conn); }

	$result = $stmt->get_result();
	if(!mysqli_num_rows($result)){
		$conn->close();
		return NULL;
	}
	$row = $result->fetch_assoc();
	$array = array('username' => $row['user_username'], 'perms' => $row['user_perms'], 'email' => $row['user_email']);
	$conn->close();
	return $array;
}
/**

Gets a user's id by their username

*/
function get_user_id($user){
	$conn = db_connect();
	if(!$conn){
		return 0;
	}
	$stmt = $conn->prepare("SELECT user_id FROM users WHERE user_username=?;");
	if(!$stmt){ sql_error($conn); }
	$stmt->bind_param("s",$user);
	if(!$stmt->execute()){ sql_error($conn); }

	$result = $stmt->get_result();
	if(!mysqli_num_rows($result)){
		$conn->close();
		return 0;
	}
	$row = $result->fetch_assoc();
	$id = $row['user_id'];
	$conn->close();
	return $id;
}
function get_permissions(){
	GLOBAL $remote_auth;
	if($remote_auth){
		return -2;
	}
	GLOBAL $_REQUEST;
	GLOBAL $_COOKIE;
	$is_api = strpos(getcwd(), 'api/') != 0;
	if((!isset($_REQUEST['username']) || !isset($_REQUEST['token'])) && $is_api && (!isset($_COOKIE['username']) || !isset($_COOKIE['token']))){
		if(!isset($_REQUEST['key']) && !isset($_COOKIE['key'])){
			return -1;
		}
		if(isset($_REQUEST['key'])){
			$key = $_REQUEST['key'];
		}else if(!$is_api){
			$key = $_COOKIE['key'];
		}
		$auth = "";
		if(isset($_REQUEST['key_auth'])){
			$auth = $_REQUEST['key_auth'];
		}else if(!$is_api && isset($_COOKIE['key_auth'])){
			$auth = $_COOKIE['key_auth'];
		}
		// Try to authenticate using key
		$result = verify_key($key,"",$auth,"");
		if(!$result){
			$key = get_key($key);
			// Get key type and check to make sure its ok
			// Key must be type 0 and subtype 0 to be used for logging in
			if($key['type'] == 0 && $key['subtype'] == 0){
				// Now check user permissions and make sure everything is okie dokie
				$user = get_user($key['user']);
				return $user['perms'];
			}else{
				return -1;
			}
		}else{
			// Failed to validate key
			return -1;
		}
	}
	// Try to authenticate using username and token
	if(isset($_REQUEST['username']) && isset($_REQUEST['token'])){
		$username = $_REQUEST['username'];
		$token = $_REQUEST['token'];
	} else if(!$is_api && isset($_COOKIE['username']) && isset($_COOKIE['token'])){
		$username = $_COOKIE['username'];
		$token = $_COOKIE['token'];
	}else{
		return -1;
	}

	$perms = login_verify($username,$token);
	if($perms < 0){
		// Perms = -1 indicates failure
		return -1;
	}
	return $perms;
}
function get_username(){
	// Must be in the same order as the authenticate_request function or authentication bugs can occur
	if(isset($_REQUEST['username'])){
		return $_REQUEST['username'];
	}else if(isset($_COOKIE['username'])){
		return $_COOKIE['username'];
	}else if(isset($_REQUEST['key']) || isset($_COOKIE['key'])){
		if(isset($_REQUEST['key'])){
			$key = $_REQUEST['key'];
		}else{
			$key = $_COOKIE['key'];
		}
		// Warning: this code does not validate any information contained in the key as it was validated when authenticating
		// DO NOT use this to reliably get a user's username before authentication as it does not check the type of key in use
		// An attacker could provide any type of key to this and get valid information from it even if it is outside of its scope
		$result = get_key($key);
		$user = get_user($result['user']);
		return $user['username'];
	}else{
		return NULL;
	}
}

?>
