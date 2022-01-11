<?php

/**

Event types:
0 - User security (logins, access attempts, etc)
1 - Key security (key creation, key usage, etc)
2 - Physical security (alarm activation/deactivation, etc)
3 - Misc security

Others are allowed (for use in other applications integrating with the auth framework) and are treated like type 3

*/
function security_event($type, $actor, $source, $info, $extra){
	$conn = db_connect();
	if(!$conn){
		return 0;
	}
	$stmt = $conn->prepare("INSERT INTO security_events (actor_id,event_type,event_source,event_info,event_extra) VALUES (?,?,?,?,?)");
	$stmt->bind_param("iisss",$actor,$type,$source,$info,$extra);
	$stmt->execute();
	$conn->close();
	return 1;
}

/**

Key types:
0 - User access key (login without password, 2fa, etc)
1 - User override key (allow another account temporary access to something, etc)
2 - Physical security code (alarm code, etc)

Key subtypes:
Type 0:
0 - Login key
1 - 2fa key

Type 1:
0 - Override key

Type 2:
0 - Arming key
1 - Disarming key
2 - Admin key
3 - Silent alarm key
4 - Device key

Key security levels:
0 - None
1 - Password/PIN (stored in key_auth)
2 - Login (must be logged into account in user_id)
3 - Device (must be used on specific device eg alarm)

*/

function create_key($user, $type, $subtype, $security, $auth, $contents){
	$conn = db_connect();
	if(!$conn){
		return 0;
	}
	$auth_value = $auth;
	if($security != 3){
		$auth_value = password_hash($auth,PASSWORD_DEFAULT);
	}
	$stmt = $conn->prepare("INSERT INTO security_keys (user_id, key_type, key_subtype, key_security, key_auth, key_contents) VALUES (?,?,?,?,?,?);");
	$stmt->bind_param("iiiiss",$user,$type,$subtype,$security,$auth_value,$contents);
	$stmt->execute();
	$conn->close();
	return 1;
}

/**

Returns:
0 - Success
1 - Error
2 - Invalid key
3 - Authentication (password/PIN)
4 - Authentication (user)
5 - Authentication (device)


*/
function verify_key($key, $user, $auth, $device){
	$conn = db_connect();
	if(!$conn){
		return 1;
	}
	$stmt = $conn->prepare("SELECT user_id,key_security,key_auth FROM security_keys WHERE key_contents=?;");
	$stmt->bind("s",$key);
	$stmt->execute();
	$result = $stmt->get_result();
	if(!mysqli_num_rows($result)){
		$conn->close();
		return 2;
	}
	$row = $result->fetch_assoc();
	$security = $row['key_security'];
	if($security == 0) return 0;
	if($security == 1){
		$hash = $row['key_auth'];
		if(password_verify($auth,$hash)){
			return 0;
		}
		return 3;
	}
	if($security == 2){
		if($user == $row['user_id']){
			return 0;
		}
		return 4;
	}
	if($security == 3){
		if($device == $row['key_auth']){
			return 0;
		}
		return 5;
	}
	return 1;
}
function get_key($key){
	$conn = db_connect();
    if(!$conn){
        return -1;
    }
    $stmt = $conn->prepare("SELECT * FROM security_keys WHERE key_contents=?;");
    $stmt->bind("s",$key);
    $stmt->execute();
    $result = $stmt->get_result();
    if(!mysqli_num_rows($result)){
        $conn->close();
        return -1;
    }
    $row = $result->fetch_assoc();
	$array = array('type' => $row['key_type'], 'subtype' => $row['key_subtype'], 'user_id' => $row['user_id']);
	$conn->close();
	return $array;
}

?>
