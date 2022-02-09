<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/reports/api/private/reports.php";

	$result = authenticate_request(1);
	if($result == 0){
		force_login();
	}
?>
<html>
	<head>
		<title>Internal Reports Services</title>
		<link rel="stylesheet" type="text/css" href="/inventory/frontend/assets/css/main.css">
		<link rel="stylesheet" type="text/css" href="/frontend/assets/css/main.css">
	</head>
	<body>
		<?php require($_SERVER['DOCUMENT_ROOT']."/frontend/header.php");?>
		<div class="subheader" style="display: inline-block;">
			<label>Report ID: </label><input id="report" type="number" min="1">
			<button id="view">View Report</button>
			<p style="color: red;" id="error"></p>
		</div>
		<div class="content">
			<h2 id="resultHeader"></h2>
			<h2 id="resultTitle"></h2>
			<p id="resultBody"></p>
		</div>
	</body>
</html>
<script src="/reports/frontend/assets/js/reports.js"></script>
<script>

var viewButton = document.getElementById("view");
var id = document.getElementById("report");
var error = document.getElementById("error");
var resultHeader = document.getElementById("resultHeader");
var resultTitle = document.getElementById("resultTitle");
var resultBody = document.getElementById("resultBody");
viewButton.addEventListener("click",view);

var params = getSearchParameters();
if(params.id != undefined){
    id.value = params.id;
    view();
}

function view(){
	var report = get_report(id.value);
	resultHeader.innerHTML = "";
	resultTitle.innerHTML = "";
	resultBody.innerHTML = "";
	if(!report.success){
		console.log("Failed to retrieve data!");
		error.innerHTML = "An error occurred while processing your request. Error: "+report.reason;
		return;
	}
	resultHeader.innerHTML = "Report from user "+report.report.user+" on "+report.report.date;
	resultTitle.innerHTML = report.report.title;
	resultBody.innerHTML = report.report.body;
}

</script>

