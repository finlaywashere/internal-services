<?php
    require_once "../../api/private/authentication.php";

    $result = authenticate_request("authentication/events");
    if($result == 0){
    	force_login();
	}
?>
<html>
    <head>
        <title>Internal Inventory Services</title>
        <link rel="stylesheet" type="text/css" href="/frontend/assets/css/main.css">
        <link rel="stylesheet" type="text/css" href="../assets/css/main.css">
    </head>
    <body>
        <?php require($_SERVER['DOCUMENT_ROOT']."/frontend/header.php");?>
        <div class="subheader" style="display: inline-block;">
            <label>Search Parameter: </label><input id="param" type="text"><br>
            <label>Search Type: </label>
            <select id="type">
				<option value="1">Source</option>
				<option value="2">Date</option>
				<option value="3">Contents</option>
				<option value="4">Type</option>
				<option value="5">Actor</option>
				<option value="6">Extra Data</option>
				<option value="7">Event ID</option>
				<option value="8">IP</option>
			</select><br>
			<button id="search">Search</button>
            <p style="color: red;" id="error"></p>
        </div>
    </body>
</html>
<script src="/authentication/frontend/assets/js/auth.js"></script>
<script>

var searchButton = document.getElementById("search");
var error = document.getElementById("error");

var param = document.getElementById("param");
var type = document.getElementById("type");

searchButton.addEventListener("click",search);

function search(){
	error.innerHTML = "";
}

</script>
