<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/documents/api/private/reports.php";

	$result = authenticate_request("documents");
	if($result == 0){
		force_login();
	}
?>

<html>
	<head>
		<title>Internal Reports Services</title>
		<link rel="stylesheet" type="text/css" href="/frontend/assets/css/main.css">
		<link rel="stylesheet" type="text/css" href="assets/css/main.css">
	</head>
		<body>
			<?php require("../../frontend/header.php");?>
			<div class="content">
				<ul>
					<li><h3><a href="search_documents.php">Search Documents</a></h3></li>
				</ul>
			</div>
		</body>
</html>

