/*global gettext*/
var i = 0;
var dragndrop = {
    drags: [],
    drag: function (event) {
        "use strict";
        event.dataTransfer.setData('text/html', this.innerHTML);
        event.dataTransfer.setData('text/plain', this.id);
        event.dataTransfer.effectAllowed = "move";
    },
    dragover: function (event) {
        "use strict";
        event.preventDefault();
    },
    drop: function (event) {
        "use strict";
        var data, num;
        data = event.dataTransfer.getData('text/html');
        num = event.dataTransfer.getData('text/plain');
        dragndrop.drags[num].innerHTML = event.target.innerHTML;
        event.target.innerHTML = data;
        for (i = 0; i < dragndrop.drags.length; i += 1) {
            dragndrop.drags[i].getElementsByTagName("input")[0].setAttribute("value", i + 1);
        }
    },
    load: function () {
        "use strict";
        dragndrop.drags = document.getElementsByClassName('draggable');
        if (dragndrop.drags[0] && dragndrop.drags[0].hasOwnProperty("draggable")) {
            document.getElementById('drag_text').textContent = gettext("Drag the links to change their position.");
            for (i = 0; i < dragndrop.drags.length; i += 1) {
                dragndrop.drags[i].getElementsByClassName('pos')[0].style.display = "none";
                dragndrop.drags[i].addEventListener("dragstart", dragndrop.drag, false);
                dragndrop.drags[i].addEventListener("drop", dragndrop.drop, true);
                dragndrop.drags[i].addEventListener("dragover", dragndrop.dragover, false);
            }
        }
    }
};
window.addEventListener("load", dragndrop.load, false);
