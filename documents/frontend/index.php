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
			<div class="container">
				<div class="row justify-content-center text-center">
					<div class="col">
						<h3><a class="btn btn-secondary" href="search_documents.php">Search Documents</a></h3>
						<h3><a class="btn btn-secondary" href="upload_document.php">Upload Documents</a></h3>
					</div>
				</div>
			</div>
		</body>
</html>

