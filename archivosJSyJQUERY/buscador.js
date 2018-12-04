$(buscar());
$(buscar2());

//Músicos
function buscar(buscador) {
    $.ajax({
        url: '../fan/fanFunctions.php',
        type: 'POST',
        dataType: 'json',
        data: {buscadormusicos: buscador},
        success: function (respPHP) {
            $("#resultadomusico").html("");
            if (respPHP.length === 0) {
                $("#resultadomusico").html("Sin coincidencias </br>");
            } else {
                for (var x = 0; x < respPHP.length; x++) {
                    var form = '<form method=POST action=buscadoMusico.php>';
                    form += '<input type="submit" name="nombreArtistico" value="' + respPHP[x].nombreArtistico + '">';
                    form += '<input type="hidden" name="idMusico" value="' + respPHP[x].idMusico + '">';
                    form += '</form>';
                    $("#resultadomusico").append(form);
                }
            }
        }
    });
}
$(document).on('keyup', '#buscadormusico', function (letra) {
    var valorBusqueda = $(this).val();
    if (letra.which <= 90 && letra.which >= 65 || letra.which === 8) {
        if (valorBusqueda !== "") {
            buscar(valorBusqueda);
        } else {
            buscar("");
        }
    }
});

//Local
function buscar2(buscador) {
    $.ajax({
        url: '../fan/fanFunctions.php',
        type: 'POST',
        dataType: 'json',
        data: {buscadorlocales: buscador},
        success: function (respPHP) {
            $("#resultadolocal").html("");
            if (respPHP.length === 0) {
                $("#resultadolocal").html("Sin coincidencias </br>");
            } else {
                for (var x = 0; x < respPHP.length; x++) {
                    var form = '<form method=POST action=buscadoLocal.php>';
                    form += '<input type="submit" name="nombreLocal" value="' + respPHP[x].nombreLocal + '">';
                    form += '<input type="hidden" name="usuLocal" value="' + respPHP[x].usuLocal + '">';
                    form += '</form>';
                    $("#resultadolocal").append(form);
                }
            }
        }
    });
}

$(document).on('keyup', '#buscadorlocal', function (letra) {
    var valorBusqueda = $(this).val();
    if (letra.which <= 90 && letra.which >= 65 || letra.which === 8) {
        if (valorBusqueda !== "") {
            buscar2(valorBusqueda);
        } else {
            buscar2("");
        }
    }
});