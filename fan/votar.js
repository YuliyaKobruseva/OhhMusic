function votarConcierto(idUsuario,idConcierto){
    $.ajax({
        type: "POST",
        url: "fanFunctions.php",
        dataType: "json",
        data: {Concierto: idConcierto, Fan: idUsuario},
        success: function (respJSON) {
            var idConcierto = respJSON.idConcierto;
            var idFan = respJSON.idFan;
            var id = 'conc'+idConcierto;
            $('#'+id+'').html('<button title="Quitar voto" onclick="quitarVotoConc(' + idFan + ',' + idConcierto + ')"><i class="fa fa-heart" style="color:red"></i></button>');
        }
    });
}
function quitarVotoConc(idUsuario, idConcierto) {
    $.ajax({
        type: "POST",
        url: "fanFunctions.php",
        dataType: "json",
        data: {idConcierto: idConcierto, Fan: idUsuario},
        success: function (respJSON) {
            var idConcierto = respJSON.idConcierto;
            var idFan = respJSON.idFan;
            var id = 'conc'+idConcierto;
            $('#'+id+'').html('<button title="Votar" onclick="votarConcierto(' + idFan + ',' + idConcierto + ')"><i class="fa fa-heart"></button>');
        }
    });
}
function votarMusico(idUsuario, idMusico) {
    $.ajax({
        type: "POST",
        url: "fanFunctions.php",
        dataType: "json",
        data: {Musico: idMusico, Fan: idUsuario},
        success: function (respJSON) {
            var idMusico = respJSON.idMusico;
            var idFan = respJSON.idFan;
            var id = 'boton'+idMusico;
            $('#'+id+'').html('<button title="Quitar voto" onclick="quitarVoto(' + idFan + ',' + idMusico + ')"><i class="fa fa-heart" style="color:red"></i></button>');
        }
    });
}

function quitarVoto(idUsuario, idMusico) {
    $.ajax({
        type: "POST",
        url: "fanFunctions.php",
        dataType: "json",
        data: {idMusico: idMusico, Fan: idUsuario},
        success: function (respJSON) {
            var idMusico = respJSON.idMusico;
            var idFan = respJSON.idFan;
            var id = 'boton'+idMusico;
            $('#'+id+'').html('<button title="Votar" onclick="votarMusico(' + idFan + ',' + idMusico + ')"><i class="fa fa-heart"></button>');
        }
    });
}

function selectMusicos(idUsuario) {
    $("#musicos").html("");
    var genero = document.getElementById("genero").value;
    $.ajax({
        type: "POST",
        url: "fanFunctions.php",
        dataType: "json",
        data: {Genero: genero, Usuario: idUsuario, Limite: 0},
        success: function (respJSON) {            
            var datos = '<table>';
            for (i = 0; i < (respJSON.length - 1); i++) {
                var idMusico = respJSON[i].idMusico;
                var idFan = respJSON[i].idFan;
                var estado = respJSON[i].Estado;
                var nombre = respJSON[i].nombreArtistico;
                var id = 'boton'+idMusico;
                datos += '<tr><td>'; 
                datos += nombre+'</td>'; 
                if (estado == "Si") {
                    datos+='<td id='+id+'><button title="Quitar voto" onclick="quitarVoto(' + idFan + ',' + idMusico + ')"><i class="fa fa-heart" style="color:red"></i></button></td>';
                } else {
                    datos+='<td id='+id+'><button title="Votar" onclick="votarMusico(' + idFan + ',' + idMusico + ')"><i class="fa fa-heart"></button></td>';
                }
                datos +='</tr>';
            }
            datos += '</table>';
            $("#musicos").append(datos);
            if (typeof respJSON[[respJSON.length-1]] != 'undefined') {
                if (typeof respJSON[respJSON.length-1].Prev != 'undefined') {
                    $("#musicos").append(respJSON[respJSON.length-1].Prev);
                }
                if (typeof respJSON[respJSON.length-1].Next != 'undefined') {
                    $("#musicos").append(respJSON[respJSON.length-1].Next);
                }
            }
        },
        error: function () {
            selectNombreGenero(genero);
            
        }
    });
}

function show5Music(limite, gen, idFan) {
    $("#musicos").html("");
    var genero = document.getElementById("genero").value;
    $.ajax({
        type: "POST",
        url: "fanFunctions.php",
        dataType: "json",
        data: {Genero: gen, Usuario: idFan, Limite: limite},
        success: function (respJSON) {
            var datos = '<table>';
            for (i = 0; i < (respJSON.length - 1); i++) {
                var idMusico = respJSON[i].idMusico;
                var idFan = respJSON[i].idFan;
                var estado = respJSON[i].Estado;
                var nombre = respJSON[i].nombreArtistico;
                var id = 'boton' + idMusico;
                datos += '<tr><td>';
                datos += nombre + '</td>';
                if (estado == "Si") {
                    datos+='<td id='+id+'><button title="Quitar voto" onclick="quitarVoto(' + idFan + ',' + idMusico + ')"><i class="fa fa-heart" style="color:red"></i></button></td>';
                } else {
                    datos+='<td id='+id+'><button title="Votar" onclick="votarMusico(' + idFan + ',' + idMusico + ')"><i class="fa fa-heart"></button></td>';
                }
                datos += '</tr>';
            }
            datos += '</table>';
            $("#musicos").append(datos);
            if (typeof respJSON[[respJSON.length-1]] != 'undefined') {
                if (typeof respJSON[respJSON.length-1].Prev != 'undefined') {
                    $("#musicos").append(respJSON[respJSON.length-1].Prev);
                }
                if (typeof respJSON[respJSON.length-1].Next != 'undefined') {
                    $("#musicos").append(respJSON[respJSON.length-1].Next);
                }
            }

        },
        error: function () {
            selectNombreGenero(genero);

        }
    });
}

function selectNombreGenero(genero) {
    $.ajax({
        type: "POST",
        url: "fanFunctions.php",
        dataType: "json",
        data: {idGenero: genero},
        success: function (respJSON) {
            var nombreGenero = respJSON.nombreGenero;
            $("#musicos").append("<div>No hay músicos del género " + nombreGenero+"</div>");
        }
    });
}