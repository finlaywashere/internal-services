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
	<div class="container">
		<div class="row justify-content-center text-center">
			<div class="col">
				<h1>Internal Services</h1>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<div class="p-3 col-sm-4 border bg-light">
					<h2>Message of the day</h2>
					<p><?php require("../data/motd.txt");?></p>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
