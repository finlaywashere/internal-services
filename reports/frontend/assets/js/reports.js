// From https://www.geeksforgeeks.org/how-to-include-a-javascript-file-in-another-javascript-file/
function include(file) {
	var script  = document.createElement('script');
	script.src  = file;
	script.type = 'text/javascript';
	script.defer = true;
	document.getElementsByTagName('head').item(0).appendChild(script);
}
include('/assets/js/master.js');

function report_type_to_string(type){
	if(type === 0){
		return "UNK";
	}
}


function get_reports(type,param){
	return json_request("/reports/api/public/get_reports.php","search_type="+type+"&search_param="+encode(param));
}
function get_report(id){
	return json_request("/reports/api/public/get_report.php","report_id="+id);
}
