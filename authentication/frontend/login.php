<?php
	if($_SERVER['REQUEST_METHOD'] === 'POST'){
		require_once "../api/private/authentication.php";
		if(!isset($_REQUEST['username']) || !isset($_REQUEST['password'])){
			$failure = 1;
		}else{
			$username = $_REQUEST['username'];
			$password = $_REQUEST['password'];
			
			$result = login($username,$password);
			if($result){
				$timestamp = time() + 60*60*24*30; // Set the cookies to expire in 30 days
				setcookie("username",$username,$timestamp,"/");
				setcookie("token",$result,$timestamp,"/");
				if(isset($_REQUEST['referrer'])){
					$referrer = $_REQUEST['referrer'];
				}else{
					$referrer = "index.php";
				}

				header("Location: ".$referrer);
				die("Successfully logged in!");
			}else{
				$failure = 2;
			}
		}
	}
?>

<html>
<head>
<title>Please Log In</title>
<link rel="stylesheet" type="text/css" href="assets/css/main.css">
</head>
<body>
<div id="login">
	<h1>Log In</h1>
	<form action="login.php" method="post">
		Username:<input type="text" name="username"><br>
		Password:<input type="password" name="password"><br>
		<input type="submit" value="Log In">
		<?php
		if(isset($_REQUEST['referrer'])){
			echo "<input type=\"hidden\" name=\"referrer\" value=\"".$_REQUEST['referrer']."\">";
		}
		?>
	</form>
	<?php
		GLOBAL $failure;
		if($failure == 1){
			echo "<div class=\"error\">Invalid Request!</div><br>";
		}else if($failure == 2){
			echo "<div class=\"error\">Invalid Credentials!</div><br>";
		}
	?>
</div>
</body>
</html>
