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
			<object width="50%" height="75%" type="application/pdf" id="result">
			</object>
		</div>
	</body>
</html>
<script src="/documents/frontend/assets/js/reports.js"></script>
<script>

var viewButton = document.getElementById("view");
var id = document.getElementById("report");
var error = document.getElementById("error");
var result = document.getElementById("result");
viewButton.addEventListener("click",view);

var params = getSearchParameters();
if(params.id != undefined){
	id.value = params.id;
	view();
}

function getCookie(cname) {
	let name = cname + "=";
	let decodedCookie = decodeURIComponent(document.cookie);
	let ca = decodedCookie.split(';');
	for(let i = 0; i <ca.length; i++) {
		let c = ca[i];
		while (c.charAt(0) == ' ') {
			c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
			return c.substring(name.length, c.length);
		}
	}
	return "";
}

function view(){
	result.data = "";
	var report = get_report(id.value);
	if(!report.success){
		console.log("Failed to retrieve data!");
		error.innerHTML = "An error occurred while processing your request. Error: "+report.reason;
		return;
	}
	result.data = "/documents/api/public/get_document_raw.php?document_id="+id.value+"&username="+getCookie('username')+"&token="+getCookie('token');
}

</script>

