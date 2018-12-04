//Cuando el usuario despliega 20px desde la parte superior del documento, muestre el botón
window.onscroll = function() {scrollFunction()};
function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        document.getElementById("arriba").style.display = "block";
    } else {
        document.getElementById("arriba").style.display = "none";
    }
}
// Cuando usuario clica sobre boton, scroll sube arriba del documento
function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

