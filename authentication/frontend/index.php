<?php
	require_once "../api/private/authentication.php";
	
	$result = authenticate_request("authentication");
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
	<div class="container">
		<div class="row justify-content-center text-center">
			<div class="col"><h3><a class="btn btn-secondary" href="auth/change_password.php">Change Password</a></h3></div>
			<?php
				$result = authenticate_request("authentication/admin");
				if($result){
					echo "<div class=\"col\"><h3><a class=\"btn btn-secondary\" href=\"auth/register.php\">Register</a></h3></div>";
				}
			?>
		</div>
	</div>
	<script src="assets/js/auth.js"></script>
</body>
</html>
