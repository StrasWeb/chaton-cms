/*global gettext*/
var deleteConf = function () {
    "use strict";
    var deleteBtns, sureDel, deleteHidden, i, validDel;
    validDel = function () {
        sureDel = window.confirm(gettext("Are you sure you want to delete this page/article?"));
        if (!sureDel) {
            return false;
        }
    };
    deleteBtns = document.getElementsByClassName("deleteBtn");
    if (deleteBtns) {
        deleteHidden = document.getElementById("deleteHidden");
        for (i = 0; i < deleteBtns.length; i += 1) {
            deleteBtns[i].onclick = validDel;
        }
    }
};

window.addEventListener("load", deleteConf, false);
