/*global tinyMCE*/
var loadMCE = function () {
	'use strict';
	var locarray, path;
    /*
var ajax = new XMLHttpRequest();
var processAjax = function(){
	if(ajax.readyState===4){
var locale=JSON.parse(ajax.responseText);
*/
//L'idéal serait de traduire tinyMCE mais leur système de localisation est foireux donc on verra plus tard.	
	tinyMCE.init({  mode : 'textareas', remove_script_host : false, relative_urls : false, theme : 'advanced', theme_advanced_toolbar_location : 'top', entity_encoding : "raw", plugins : 'table, advlist, autosave, contextmenu, media, nonbreaking, preview, visualchars', theme_advanced_buttons4: 'tablecontrols', theme_advanced_buttons2_add: "media", theme_advanced_buttons3_add: "nonbreaking, visualchars, preview"});
/*
	}
};
ajax.onreadystatechange = processAjax;
ajax.open("GET","../ajax/getlocale.php",true);
ajax.send();
*/
};
window.addEventListener("load", loadMCE, false);
