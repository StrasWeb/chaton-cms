/*global flowplayer*/
if (!document.getElementsByClassName) {
	document.getElementsByClassName = function (searchClass, node, tag) {
		"use strict";
		var classElements, els, elsLen, pattern, i, j;
		classElements = [];
		if (node === null) {
			node = document;
		}
		if (tag === null) {
			tag = '*';
		}
		els = node.getElementsByTagName(tag);
		elsLen = els.length;
		pattern = new RegExp("(^|\\s)" + searchClass + "(\\s|$)");
		for (i = 0, j = 0; i < elsLen; i += 1) {
			if (pattern.test(els[i].className)) {
				classElements[j] = els[i];
				j += 1;
			}
		}
		return classElements;
	};
}

var covers = document.getElementsByClassName("cover");
var gallery = {
	switchCover: function (e) {
		"use strict";
		if (e.target) {
			e.target.src = "plugins/gallery/covers/" + e.target.id + "_over.jpg";
		} else if (e.srcElement) {
			e.srcElement.src = "plugins/gallery/covers/" + e.srcElement.id + "_over.jpg";
		}
	},
	switchCover2: function (e) {
		"use strict";
		if (e.target) {
			e.target.src = "plugins/gallery/covers/" + e.target.id + ".jpg";
		} else if (e.srcElement) {
			e.srcElement.src = "plugins/gallery/covers/" + e.srcElement.id + ".jpg";
		}
	},
	loadListeners: function (e) {
		"use strict";
		if (window.addEventListener) {
			covers[e.target.num].addEventListener("mouseover", gallery.switchCover, false);
			covers[e.target.num].addEventListener("mouseout", gallery.switchCover2, false);
		} else if (window.attachEvent) {
			covers[e.srcElement.num].attachEvent("onmouseover", gallery.switchCover);
			covers[e.srcElement.num].attachEvent("onmouseout", gallery.switchCover2);
		}
	},
	init: function () {
		"use strict";
		var i;
		for (i = 0; i < covers.length; i += 1) {
			covers[i].loader = new Image();
			covers[i].loader.src = "plugins/gallery/covers/" + covers[i].id + "_over.jpg";
			covers[i].loader.num = i;
			if (window.addEventListener) {
				covers[i].loader.addEventListener("load", gallery.loadListeners, false);
			} else if (window.attachEvent) {
				covers[i].loader.attachEvent("onload", gallery.loadListeners);
			}
		}
	}
};
if (window.addEventListener) {
	window.addEventListener("load", gallery.init, true);
} else if (window.attachEvent) {
	window.attachEvent("onload", gallery.init);
}
