<?php
	require_once "../../api/private/authentication.php";

	$result = authenticate_request(100);
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
			<label>Username: </label><input id="user" type="text"><br>
			<label>Perms: </label><input id="perms" type="number" min="0" value="0"><br>
			<label>Email: </label><input id="email" type="email"><br>
			<label>Password: </label><input id="pass" type="password"><br>
			<button id="register">Register</button>
			<p style="color: green;" id="success"></p>
			<p style="color: red;" id="error"></p>

		</div>
	</body>
</html>
<script src="/authentication/frontend/assets/js/auth.js"></script>
<script>

var user = document.getElementById("user");
var perms = document.getElementById("perms");
var email = document.getElementById("email");
var pass = document.getElementById("pass");

var register = document.getElementById("register");
register.addEventListener("click",reg);

var success = document.getElementById("success");
var error = document.getElementById("error");

function reg(){
	success.innerHTML = "";
	error.innerHTML = "";
	if(user.value == '' || email.value == '' || pass.value == ''){
		error.innerHTML = "All fields must be completed!";
		return;
	}
	var response = register_user(user.value,pass.value,perms.value,email.value);
	if(response.success){
		success.innerHTML = "Successfully created user!";
	}else{
		error.innerHTML = "Failed to create user. Reason: "+response.reason;
	}
}

</script>
