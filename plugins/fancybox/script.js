/*global $*/
$(document).ready(function () {
	"use strict";
	$("#page #pageText img").each(function () {
		$(this).wrap("<a class='gallery' rel='prefetch' href='" + $(this).attr("src") + "'/>");
	});
	$("#main .gallery").fancybox();
});
