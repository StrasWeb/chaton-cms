var printThis = function () {
	"use strict";
	window.print();
	window.close();
};

window.addEventListener("load", printThis, true);