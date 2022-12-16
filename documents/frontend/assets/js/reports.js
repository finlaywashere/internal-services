// From https://stackoverflow.com/a/5448635
function getSearchParameters() {
    var prmstr = window.location.search.substr(1);
    return prmstr != null && prmstr != "" ? transformToAssocArray(prmstr) : {};
}
function transformToAssocArray( prmstr ) {
    var params = {};
    var prmarr = prmstr.split("&");
    for ( var i = 0; i < prmarr.length; i++) {
        var tmparr = prmarr[i].split("=");
        params[tmparr[0]] = tmparr[1];
    }
    return params;
}

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
	return "UNK";
}

function json_request(url,args){
    var result = null;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open("POST", url, false);
    xmlhttp.addEventListener("load",function() {
        if (xmlhttp.readyState != 4) return;
        if (xmlhttp.status==200) {
            var json = JSON.parse(this.responseText);
            result = json;
            return null;
        }else{
            return null;
        }
    });
    xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    xmlhttp.send(args);
    return result;
}


function get_reports(type,param){
	return json_request("/documents/api/public/get_documents.php","search_type="+type+"&search_param="+encode(param));
}
function get_report(id){
	return json_request("/documents/api/public/get_document.php","document_id="+id);
}
