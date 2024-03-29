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

function clearTable(t){
	var children = t.querySelectorAll('tr')
	for(let i = 0; i < children.length; i++){
		let found = false;
		if(children[i].childNodes != undefined){
			for(let i1 = 0; i1 < children[i].childNodes.length; i1++){
				var child = children[i].childNodes[i1];
				if(child.nodeName == "TH")
					found = true;
			}
		}
		if(found)
			continue;
		var child = children[i];
		var parent = children[i].parentNode;
		parent.removeChild(child);
	}
}
function createElement(text, parent){
	var tmp = document.createElement("td");
	tmp.innerHTML = text;
	parent.appendChild(tmp);
	return tmp;
}
function createEditableElement(text,parent){
	var tmp = document.createElement("td");
	tmp.innerHTML = text;
	tmp.setAttribute("contenteditable","true");
	parent.appendChild(tmp);
	return tmp;
}
/*
This is from https://stackoverflow.com/a/23642134
*/
function getCookie(cookieName) {
	var name = cookieName + "=";
	var ca = document.cookie.split(';');
	for (var i = 0; i < ca.length; i++) {
		var c = ca[i].trim();
		if ((c.indexOf(name)) == 0) {
			console.log("found");
			return c.substr(name.length);
		}

	}
	console.log("not found");
	return null;
}

function json_request(url,args){
	var result = null;
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.open("POST", url, false);
	xmlhttp.addEventListener("load",function() {
		if (xmlhttp.readyState != 4) return;
		try{
			var json = JSON.parse(this.responseText);
			result = json;
			return null;
		}catch(err){
			result = null;
			return null;
		}
	});
	xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	xmlhttp.send("username="+getCookie('username')+"&token="+getCookie('token')+"&"+args);
	return result;
}
function isWhole(num){
	return !isNaN(parseInt(num)) && isFinite(num) && (num % 1 == 0);
}
function strip(str){
	return str.replace(/(<([^>]+)>)/gi, "");
}
function encode(str){
	return encodeURIComponent(strip(str));
}

