function cogeProvincia() {
    var provincia = document.getElementById("provincia").value;
//    var provincia = $("#provincia").val();
    $.ajax({
        type: "POST",
        url: "provincias.php",
        dataType: "json",
        data: {Provincia: provincia},
        success: function (respJSON) {
            $("#ciudad").html("");
            for (i = 0; i < (respJSON.length - 1); i++) {
                var idCiudad = respJSON[i].idCiudad;
                var ciudad = respJSON[i].ciudad;
                $("#ciudad").append('<option value="' + idCiudad + '">' + ciudad + '</option>');

            }
        }
    });
}