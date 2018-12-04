$(document).ready(inicio);
function inicio() {    
    $(".panel").hide();
    $(".info .editar").click(mostrar);   
}

function mostrar() {   
        $(this).parent().parent().parent().next(".panel").toggle();  
}