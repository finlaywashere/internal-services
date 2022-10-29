<?php

require_once "config.php";

function db_connect($dbname=""){
    GLOBAL $dbhost;
    GLOBAL $dbuser;
    GLOBAL $dbpass;
	GLOBAL $default_db;
	if($dbname == "")
		$dbname = $default_db;

    $conn = new mysqli($dbhost,$dbuser,$dbpass,$dbname);
    if($conn->connect_error){
        return 0;
    }
    return $conn;
}

?>
