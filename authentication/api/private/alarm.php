<?php

function trigger_alarm($alarm, $reason){
	$conn = db_connect();
	if(!$conn){
		return 0;
	}

	$stmt = $conn->prepare("UPDATE security_alarms SET alarm_triggered=1 WHERE alarm_id=?;");
	$stmt->bind_param("i",$alarm);
	$stmt->execute();
	return security_event(2,$alarm,$reason,"","");
}
function reset_alarm($alarm){
	$conn = db_connect();
	if(!$conn){
		return 0;
	}
	
	$stmt = $conn->prepare("UPDATE security_alarms SET alarm_triggered=0 WHERE alarm_id=?;");
	$stmt->bind_param("i",$alarm);
	$stmt->execute();
	return 1;
}
/**

State values:
0 - Disarmed
1 - Armed

*/
function set_alarm($alarm, $state){
	$conn = db_connect();
	if(!$conn){
		return 0;
	}
	
	$stmt = $conn->prepare("UPDATE security_alarms SET alarm_status=? WHERE alarm_id=?;");
	$stmt->bind_param("ii",$state,$alarm);
	$stmt->execute();
	return 1;
}
?>
