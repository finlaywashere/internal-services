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
function sql_error($conn){
	error_log("SQL Error: ".mysqli_error($conn), 3, "/var/log/php.log");
}

?>
