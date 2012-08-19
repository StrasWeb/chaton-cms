/*global Gettext*/
var myGettext = new Gettext({"domain" : "Chaton"});
var gettext = function (msgid) {
    "use strict";
    return myGettext.gettext(msgid);
};



