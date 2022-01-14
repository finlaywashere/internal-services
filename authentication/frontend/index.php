<?php
	require_once "../api/private/authentication.php";

	$result = authenticate_request(0);
	if($result == 0){
		force_login();
	}
?>

<html>
<head>
	<title>Internal Authentication Services</title>
	<link rel="stylesheet" type="text/css" href="assets/css/main.css">
	<link rel="stylesheet" type="text/css" href="/frontend/assets/css/main.css">
</head>
<body>
	<?php require("../../frontend/header.php");?>
	<div class="content">
		<ul>
			<li><h3><a href="auth/change_password.php">Change Password</a></h3></li>
			<?php
				$result = authenticate_request(100);
				if($result){
					echo "<li><h3><a href=\"auth/register.php\">Register</a></h3></li>";
				}
			?>
		</ul>
	</div>
	<script src="assets/js/auth.js"></script>
</body>
</html>
