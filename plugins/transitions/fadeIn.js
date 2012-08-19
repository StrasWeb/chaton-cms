/*global $*/
var fadeIn = function () {
	'use strict';
	$("body, #wrapper").hide();
	$("body, #wrapper").fadeIn();
};
$(window).ready(fadeIn);
