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
			<label>Report Search: </label><input id="search_param" type="text">
			<label>Type: </label>
			<select id="search_type">
				<option value="1">User</option>
				<option value="2">Date</option>
				<option value="3">Contents</option>
				<option value="4">Title</option>
				<option value="5">Type</option>
				<option value="6">ID</option>
			</select>
			<button id="search">Search</button>
			<p style="color: red;" id="error"></p>
		</div>
		<div class="content">
			<table id="results">
				<tr id="table_header">
					<th>Date</th>
					<th>Type</th>
					<th>ID</th>
					<th>User ID</th>
					<th>Security</th>
					<th>Title</th>	
				</tr>
			</table>
		</div>
	</body>
</html>
<script src="/reports/frontend/assets/js/reports.js"></script>
<script>

var searchButton = document.getElementById("search");
var param = document.getElementById("search_param");
var search_type = document.getElementById("search_type");
var error = document.getElementById("error");
var table = document.getElementById("results");
searchButton.addEventListener("click",search);

function search(){
	var reports = get_reports(search_type.value,param.value);
	if(!reports.success){
		console.log("Failed to retrieve data!");
		error.innerHTML = "An error occurred while processing your request. Error: "+reports.reason;
		return;
	}
	clearTable(table);
	var reports2 = reports.reports;
	for(let i = 0; i < reports2.length; i++){
		var report = get_report(reports2[i]);
		if(!report.success){
			console.log("Failed to retrieve some data!");
			error.innerHTML = "An error occurred while processing your request. Error: "+report.reason;
			return;
		}
		var entry = document.createElement("tr");
		createElement(report.report['date'],entry);
		var type = report.report['type'];
		createElement(report_type_to_string(type),entry);
		createElement("<a href=\"/reports/frontend/get_report.php?id="+reports2[i]+"\">"+reports2[i]+"</a>",entry);
		createElement(reports2[i],entry);
		createElement(report.report['user'],entry);
		createElement(report.report['security'],entry);
		createElement(report.report['title'],entry);
		table.appendChild(entry);
	}
}

</script>

