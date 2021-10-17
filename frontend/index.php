<?php
	require_once "private/authentication.php";
	require_once "private/config.php";
	
	$result = authenticate_request(0);
	if($result == 0){
		GLOBAL $login_page;
		header("Location: ".$login_page."?referrer=/frontend/index.php");
		die("Please log in!");
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
