<?php
    require_once "../../api/private/authentication.php";

    $result = authenticate_request(0);
    if($result == 0){
        header("Location: /authentication/frontend/login.php?referrer=/authentication/frontend/index.php");
        die("Please log in!");
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
            <label>Current Password: </label><input id="curr" type="password"><br>
            <label>New Password: </label><input id="new" type="password"><br>
            <label>Confirm Password: </label><input id="newConf" type="password"><br>
            <button id="change">Change Password</button>
            <p style="color: red;" id="error"></p>
        </div>
    </body>
</html>
<script src="/authentication/frontend/assets/js/auth.js"></script>
<script>

var changeButton = document.getElementById("change");
var error = document.getElementById("error");

var curr = document.getElementById("curr");
var newPass = document.getElementById("new");
var confPass = document.getElementById("newConf");

changeButton.addEventListener("click",change);

function change(){
	error.innerHTML = "";
	newPass.style.border = "1px solid grey";
	confPass.style.border = "1px solid grey";
	if(newPass.value === ""){
		error.innerHTML = "New password is required";
		newPass.style.border = "1px solid red";
		return;
	}
	if(newPass.value !== confPass.value){
		error.innerHTML = "Passwords must match!";
		newPass.style.border = "1px solid red";
		confPass.style.border = "1px solid red";
		return;
	}
	var result = change_password(curr.value,newPass.value);
	if(result.success){
		location.href = "/authentication/frontend/index.php";
	}else{
		error.innerHTML = "Failed to change password, reason: "+result.reason;
	}
}

</script>
