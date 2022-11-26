<?php

function adf_scan(){
	$id = rand();
	$path = "/tmp/".$id;
	shell_exec($_SERVER['DOCUMENT_ROOT']."/documents/adf.sh ".$path);
	$file = fopen($path."/document.pdf", "r");
	$result = fread($file, filesize($path."/document.pdf"));
	fclose($file);
	shell_exec("rm -rf ".$path);
	return base64_encode($result);
}

?>
