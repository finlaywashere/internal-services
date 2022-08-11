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
function create_key(password,key,type,subtype,security,auth){
	return json_request("/authentication/api/public/security/create_key.php", "curr_password="+encode(password)+"&key="+encode(key)+"&key_type="+type+"&key_subtype="+subtype+"&key_security="+security+"&key_auth="+encode(auth));
}
function create_key_target(password,key,type,subtype,security,target){
	return json_request("/authentication/api/public/security/create_key.php", "curr_password="+encode(password)+"&key="+encode(key)+"&key_type="+type+"&key_subtype="+subtype+"&key_security="+security+"&key_auth="+encode(auth)+"&target_user="+target);
}
function search_security_events(type,param){
	return json_request("/authentication/api/public/security/event_search.php", "type="+type+"&param="+encode(param));
}
function get_security_event(id){
	return json_request("/authentication/api/public/security/event_data.php", "event_id="+id);
}
function register_user(user,pass,perms,email){
	return json_request("/authentication/api/public/auth/register.php","reg_username="+encode(user)+"&reg_password="+encode(pass)+"&reg_perms="+perms+"&reg_email="+encode(email));
}
