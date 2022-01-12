// From https://www.geeksforgeeks.org/how-to-include-a-javascript-file-in-another-javascript-file/
function include(file) {
    var script  = document.createElement('script');
    script.src  = file;
    script.type = 'text/javascript';
    script.defer = true;
    document.getElementsByTagName('head').item(0).appendChild(script);
}
include('/assets/js/master.js');

function change_password(currPass,newPass){
	return json_request("/authentication/api/public/auth/change_password.php", "password="+encode(currPass)+"&new_password="+encode(newPass));
}
