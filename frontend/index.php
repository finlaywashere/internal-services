<?php
	require_once "private/authentication.php";
	require_once "private/config.php";
	
	$result = authenticate_request("main");
	if($result == 0){
		force_login();
	}
?>
<html>
<head>
<title>Internal Services</title>
<link rel="stylesheet" type="text/css" href="assets/css/main.css">
</head>
<body>
<?php require("header.php")?>
<div class="content">
	<ul>
		<li>
			<h2>Message of the day</h2>
			<p><?php require("../data/motd.txt");?></p>
		</li>
	</ul>
</div>
</body>
</html>
