<?php

require_once "authentication.php";

echo "Enter username: ";
$username = readline();
echo "Enter password: ";
$password = readline();
echo "Enter permission level: ";
$perms = (int) readline();
echo "Enter email: ";
$email = readline();

$result = register($username,$password,$email,$perms);

if($result){
	die("\nSuccessfully registered user!");
}else{
	die("\nFailed to register user, error code ".$result);
}

?>
