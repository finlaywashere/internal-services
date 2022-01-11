<?php

require_once "authentication.php";

echo "Enter password: ";
$password = readline();

$hash = password_hash($password,PASSWORD_DEFAULT);

echo "Password hash is \"".$hash."\". You must manually insert it into the authentication database!";

?>
