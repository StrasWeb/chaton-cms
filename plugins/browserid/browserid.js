/*jslint browser: true*/
var initLogin = function () {
    "use strict";
    var login, connect;
    login = function (assertion) {
        var query;
        if (assertion) {
            document.location = "plugins/browserid/login.php?assertion=" + assertion;
        }
    };
    connect = function (e) {
        e.preventDefault();
        navigator.id.get(login);
    };
    document.getElementById("browserID").addEventListener("click", connect, true);
};
window.addEventListener("load", initLogin, false);
