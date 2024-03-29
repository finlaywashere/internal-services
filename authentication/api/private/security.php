<?php

/**

Event types:
0 - User security (logins, access attempts, etc)
1 - Key security (key creation, key usage, etc)
2 - Physical security (alarm activation/deactivation, etc)
3 - Misc security

Others are allowed (for use in other applications integrating with the auth framework) and are treated like type 3

*/
function security_event($type, $actor, $source, $info, $extra, $ip){
	$conn = db_connect();
	if(!$conn){
		return 0;
	}
	$stmt = $conn->prepare("INSERT INTO security_events (actor_id,event_type,event_source,event_info,event_extra,event_ip) VALUES (?,?,?,?,?,?)");
	if(!$stmt){ sql_error($conn); }
	$stmt->bind_param("iissss",$actor,$type,$source,$info,$extra,$ip);
	if(!$stmt->execute()){ sql_error($conn); }
	$conn->close();
	return 1;
}
/**

Search for security events in the log by a search type and value

*/
function search_security_events($stype,$value){
	$conn = db_connect();
	if(!$conn){
		return NULL;
	}
	$stmt = NULL;
	if($stype == 1){
		// Search by source
		$value = "%".$value."%";
		$stmt = $conn->prepare("SELECT event_id FROM security_events WHERE event_source LIKE ?;");
		if(!$stmt){ sql_error($conn); }
		$stmt->bind_param("s",$value);
	}else if($stype == 2){
		// Search by date
		$stmt = $conn->prepare("SELECT event_id FROM security_events WHERE DATE(event_time) = ?;");
		if(!$stmt){ sql_error($conn); }
		$stmt->bind_param("s",$value);
	}else if($stype == 3){
		// Search by contents
		$value = "%".$value."%";
		$stmt = $conn->prepare("SELECT event_id FROM security_events WHERE journal_info LIKE ?;");
		if(!$stmt){ sql_error($conn); }
		$stmt->bind_param("s",$value);
	}else if($stype == 4){
		// Search by event type
		$stmt = $conn->prepare("SELECT event_id FROM security_events WHERE event_type = ?;");
		if(!$stmt){ sql_error($conn); }
		$stmt->bind_param("i",$value);
	}else if($stype == 5){
		// Search by actor
		$stmt = $conn->prepare("SELECT event_id FROM security_events WHERE actor_id = ?;");
		if(!$stmt){ sql_error($conn); }
		$stmt->bind_param("i",$value);
	}else if($stype == 6){
		// Search by extra
		$value = "%".$value."%";
		$stmt = $conn->prepare("SELECT event_id FROM security_events WHERE event_extra LIKE ?;");
		if(!$stmt){ sql_error($conn); }
		$stmt->bind_param("s",$value);
	}else if($stype == 7){
		// Search by event id
		$stmt = $conn->prepare("SELECT event_id FROM security_events WHERE event_id = ?;");
		if(!$stmt){ sql_error($conn); }
		$stmt->bind_param("i",$value);
	}else if($stype == 8){
		// Search by ip
		$value = "%".$value."%";
		$stmt = $conn->prepare("SELECT event_id FROM security_events WHERE event_ip LIKE ?;");
		if(!$stmt){ sql_error($conn); }
		$stmt->bind_param("s",$value);
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
		array_push($return,$row['event_id']);
	}
	$conn->close();
	return $return;
}
/**

Gets a security event by its event id

*/
function get_security_event($id){
	$conn = db_connect();
	if(!$conn){
		return NULL;
	}
	$stmt = $conn->prepare("SELECT * FROM security_events WHERE event_id = ?;");
	if(!$stmt){ sql_error($conn); }
	$stmt->bind_param("i",$id);
	if(!$stmt->execute()){ sql_error($conn); }
	$result = $stmt->get_result();
	if(!mysqli_num_rows($result)){
		return NULL;
	}
	$row = $result->fetch_assoc();
	$array = array('actor' => $row['actor_id'], 'type' => $row['event_type'], 'source' => $row['event_source'], 'info' => $row['event_info'], 'extra' => $row['event_extra'], 'time' => $row['event_time'], 'ip' => $row['event_ip']);
	$conn->close();
	return $array;
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

Note: Key contents are not hashed to make finding them easier during login, DO NOT store passwords or any other PII in them

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
	if(!$stmt){ sql_error($conn); }
	$stmt->bind_param("iiiiss",$user,$type,$subtype,$security,$auth_value,$contents);
	if(!$stmt->execute()){ sql_error($conn); }
	$conn->close();
	return 1;
}

function destroy_key($key){
	$conn = db_connect();
	if(!$conn){
		return 0;
	}
	$stmt = $conn->prepare("DELETE FROM security_keys WHERE key_contents=?;");
	if(!$stmt){ sql_error($conn); }
	$stmt->bind_param("s",$key);
	if(!$stmt->execute()){ sql_error($conn); }
	$conn->close();
	return 1;
}
function revoke_keys($user){
	$conn = db_connect();
	if(!$conn){
		return 0;
	}
	$stmt = $conn->prepare("DELETE FROM security_keys WHERE user_id=?;");
	if(!$stmt){ sql_error($conn); }
	$stmt->bind_param("i",$user);
	if(!$stmt->execute()){ sql_error($conn); }
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
	if(!$stmt){ sql_error($conn); }
	$stmt->bind_param("s",$key);
	if(!$stmt->execute()){ sql_error($conn); }
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
	if(!$stmt){ sql_error($conn); }
	$stmt->bind_param("s",$key);
	if(!$stmt->execute()){ sql_error($conn); }
	$result = $stmt->get_result();
	if(!mysqli_num_rows($result)){
		$conn->close();
		return -1;
	}
	$row = $result->fetch_assoc();
	$array = array('type' => $row['key_type'], 'subtype' => $row['key_subtype'], 'user' => $row['user_id'], 'key' => $row['key_id']);
	$conn->close();
	return $array;
}
/**

Creates an override for a transaction and validates the key used

*/
function create_override($key, $transaction, $source, $field, $data, $reason){
	$authorized = verify_key($key);
	if($authorized){
		return 2;
	}
	$key_data = get_key($key);
	if($key_data['type'] != 1 || $key_data['subtype'] != 0){
		return 3;
	}
	$id = $key_data['key'];
	$conn = db_connect();
	if(!$conn){
		return 1;
	}
	$stmt = $conn->prepare("INSERT INTO `security_overrides` (key_id,override_reason,transaction_id,transaction_source,transaction_field,transaction_data) VALUES (?,?,?,?,?,?);");
	if(!$stmt){ sql_error($conn); }
	$stmt->bind_param("isisss",$id,$reason,$transaction,$source,$field,$date);
	if(!$stmt->execute()){ sql_error($conn); }

	$conn->close();
	return 0;
}

/**

Retrieves all of the overrides for a given transaction and does basic validation
NOTE: The application must validate that the source, field, and data are all correct to make sure that that data is locked in

NOTE: This invalidates every override from the current transaction when called to prevent replay attacks

*/

function retrieve_overrides($transaction){
	$conn = db_connect();
	if(!$conn){
		return NULL;
	}
	$stmt = $conn->prepare("SELECT * FROM security_overrides WHERE transaction_id=? AND override_expiry > NOW();");
	if(!$stmt){ sql_error($conn); }
	$stmt->bind_param("i",$transaction);
	if(!$stmt->execute()){ sql_error($conn); }

	$result = $stmt->get_result();
	if(!mysqli_num_rows($result)){
		return array();
	}
	$return = array();
	while($row = $result->fetch_assoc()){
		array_push($return,array('key_id' => $row['key_id'], 'reason' => $row['override_reason'], 'source' => $row['transaction_source'], 'field' => $row['transaction_field'], 'data' => $row['transaction_data']));
	}
	$stmt = $conn->prepare("DELETE FROM security_overrides WHERE transaction_id=?;");
	if(!$stmt){ sql_error($conn); }
	$stmt->bind_param("i",$transaction);
	if(!$stmt->execute()){ sql_error($conn); }
	$conn->close();
	return $return;
}
function create_transaction(){
	return random_int(0,1000000000);
}

?>
