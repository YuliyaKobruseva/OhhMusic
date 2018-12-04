function verMusicos(id) {
    $.ajax({
        type: "POST",
        url: "../local/funcionesLocal.php",
        dataType: "json",
        data: {idConcierto: id},
        success: function (respJSON) {
            $("#inscripciones").empty();
            if (respJSON.error == 1) {
                $("#inscripciones").append("Ningún músico se ha inscrito todavia");
            } else {
//                $("#inscripciones").append("<tr> <th>Nombre Musico</th> </tr>");
                for (i = 0; i < respJSON.length - 1; i++) {
                    var datos = '';
                    datos += '<tr>';
                    datos += '<td>' + respJSON[i].Artistico + '</td>';
                    datos += '<td><button onclick="aceptarMusico(' + respJSON[i].idConcierto + ',' + respJSON[i].Musico + ')"><i class="fa fa-check-square fa-1x"></i>Aceptar</button> <button onclick="rechazarMusico(' + respJSON[i].idConcierto + ',' + respJSON[i].Musico + ')"><i class="fa fa-minus-square fa-1x"></i>Rechazar</button></td>';
                    datos += '</tr>';
                    $("#inscripciones").append(datos);
                }
            }
            modal.style.display = "block"; //abrir el modal
        },
        error: function () {
            $("#inscripciones").empty();
            $("#inscripciones").append("Ningún músico se ha inscrito todavia");
        }
    });
}

function delConcierto(idConcierto) {
    $.ajax({
        type: "POST",
        url: "../local/funcionesLocal.php",
        dataType: "json",
        data: {concDel: idConcierto},
        success: function (respJSON) {
            alert(respJSON.Res);
            refreshPage();
        }
    });
}


function aceptarMusico(idConcierto, idMusico) {
    $.ajax({
        type: "POST",
        url: "../local/funcionesLocal.php",
        dataType: "json",
        data: {idConc: idConcierto, acepMus: idMusico},
        success: function (respJSON) {
            alert(respJSON.Res);
            refreshPage();
        }
    });
}

function rechazarMusico(idConcierto, idMusico) {
    $.ajax({
        type: "POST",
        url: "../local/funcionesLocal.php",
        dataType: "json",
        data: {idConc: idConcierto, rechMus: idMusico},
        success: function (respJSON) {
            alert(respJSON.Res);
            refreshPage();
        }
    });
}
function refreshPage() {
    window.location.reload();
}