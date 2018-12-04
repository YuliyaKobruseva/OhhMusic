function darBajaConcierto(idConcierto, idMusico) {
    $.ajax({
        type: "POST",
        url: "funcionesMusico.php",
        dataType: "json",
        data: {bajaConc: idConcierto, idMusico: idMusico},
        success: function (respJSON) {
            alert(respJSON.Res);
            refreshPage();
        }
    });
}

function darAltaConcierto(idConcierto, idMusico) {
    $.ajax({
        type: "POST",
        url: "funcionesMusico.php",
        dataType: "json",
        data: {altaConc: idConcierto, idMusico: idMusico},
        success: function (respJSON) {
            alert(respJSON.Res);
            refreshPage();
        }
    });
}

function refreshPage() {
    window.location.reload();
}
