<?php

function req_param($name){
	GLOBAL $_REQUEST;
	if(isset($_REQUEST[$name]))
		return 1;
	GLOBAL $_COOKIE;
	return isset($_COOKIE[$name]);
}

function req_param_i($name){
	GLOBAL $_REQUEST;
	if(isset($_REQUEST[$name]))
		return is_numeric($_REQUEST[$name]);
	GLOBAL $_COOKIE;
	if(isset($_COOKIE[$name]))
		return is_numeric($_COOKIE[$name]);
	return 0;
}

function json_cont($json,$name){
	return isset($json->{$name});
}
function json_cont_i($json,$name){
	return isset($json->{$name}) && is_numeric($json->{$name});
}

function req_get($name){
	GLOBAL $_REQUEST;
	if(isset($_REQUEST[$name]))
		return sanitize($_REQUEST[$name]);
	GLOBAL $_COOKIE;
	return sanitize($_COOKIE[$name]);
}
function sanitize($data){
	return htmlspecialchars($data);
}
function html_encode($data){
	return str_replace("\n","<br>",str_replace("\t","&emsp;",$data));
}

?>
