var modalLink = document.getElementById('myModalLink');
var modalBtn = document.getElementById('myModalButton');

var link = document.getElementById("myLink");
var btn = document.getElementById("myBtn");

var spanLink = document.getElementsByClassName("closeLink")[0];
var spanBtn = document.getElementsByClassName("closeBtn")[0];

btn.onclick = function () {
    modalBtn.style.display = "block";
};

link.onclick = function () {
    modalLink.style.display = "block";
};

spanLink.onclick = function () {
    modalLink.style.display = "none";
};
spanBtn.onclick = function () {
    modalBtn.style.display = "none";
};

window.onclick = function (event) {
    if (event.target === modalBtn) {
        modalBtn.style.display = "none";
    };
    
    window.onclick = function (event) {
        if (event.target === modalLink) {
            modalLink.style.display = "none";
        };
    };
};