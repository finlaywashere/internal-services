<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/documents/api/private/reports.php";

	$result = authenticate_request("documents/create");
	if($result == 0){
		force_login();
	}
?>
<html>
	<head>
		<title>Internal Reports Services</title>
		<link rel="stylesheet" type="text/css" href="/inventory/frontend/assets/css/main.css">
		<link rel="stylesheet" type="text/css" href="/frontend/assets/css/main.css">
	</head>
	<body>
		<?php require($_SERVER['DOCUMENT_ROOT']."/frontend/header.php");?>
		<div class="subheader" style="display: inline-block;">
			<form action="/documents/api/public/upload_document.php" method="POST" enctype="multipart/form-data">
				<label for="title">Title:</label><input id="title" name="title" type="text"><br>
				<label for="type">Type:</label><input id="type" name="type" type="number"><br>
				<label for="security">Security:</label><input id="security" name="security" type="number"><br>
				<input type="hidden" id="username" name="username">
				<input type="hidden" id="token" name="token">
				<input type="file" id="document" name="document" accept="application/pdf"><br>
				<input type="submit" value="Upload">
			</form>
		</div>
	</body>
</html>

<script>

function getCookie(cname) {
	let name = cname + "=";
	let decodedCookie = decodeURIComponent(document.cookie);
	let ca = decodedCookie.split(';');
	for(let i = 0; i <ca.length; i++) {
		let c = ca[i];
		while (c.charAt(0) == ' ') {
			c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
			return c.substring(name.length, c.length);
		}
	}
	return "";
}

window.onload = function(){

document.getElementById("username").value = getCookie("username");
document.getElementById("token").value = getCookie("token");

};

</script>
