$(document).ready(inici);
function inici() {
    getInfoUsu();
    $("#modPass").click(modPass);
    $("#modNombre").click(modNombre);
    $("#modApellidos").click(modApellidos);
    $("#modMail").click(modMail);
    $("#modTelf").click(modTelf);
    $("#modCiudad").click(modCiudad);
    $("#modDireccion").click(modDireccion);

    $("#modNameLocal").click(modLocalName);
    $("#modAforo").click(modAforo);

    $("#modSexo").click(modSexo);
    $("#modBorn").click(modBorn);

    $("#modNombreArt").click(modNombreArt);
    $("#modWeb").click(modWeb);
    $("#modGenero").click(modGenero);
    $("#modNumComp").click(modNumComp);
}
/*MUESTRA LA INFORMACION Y LOS PONE TAMBIEN EN LOS INPUTS DE EDICION DE MODO PREDETERMINADO */{
    function getInfoUsu() {
        var user = document.getElementById("usuario").innerHTML;
        $.ajax({
            type: "POST",
            url: "editarPerfiles.php",
            dataType: "json",
            data: {User: user},
            success: function (respJSON) {
                var psw = respJSON[0].Password;
                var nombre = respJSON[0].Nombre;
                var apellidos = respJSON[0].Apellidos;
                var email = respJSON[0].Email;
                var telefono = respJSON[0].Telefono;
                var direccion = respJSON[0].Direccion;
                var tipo = respJSON[0].Tipo;
                var provincia = respJSON[1].Provincia;
                var ciudad = respJSON[1].Ciudad;
                $("#pass").html(psw);
                $("#pass").hide();
                $("#nombre").html(nombre);
                $("#nom").val(nombre);
                $("#apellidos").html(apellidos);
                $("#cognoms").val(apellidos);
                $("#mail").html(email);
                $("#mairu").val(email);
                $("#telf").html(telefono);
                $("#telf2").val(telefono);
                $("#provincia").html(provincia);
                $("#selProvincia").val(provincia);
                $("#city").html(ciudad);
                $("#direccion").html(direccion);
                $("#newDir").val(direccion);
                if (tipo == "Local") {
                    var nombreLocal = respJSON[2].NombreLocal;
                    var aforo = respJSON[2].Aforo;
                    $("#localName").html(nombreLocal);
                    $("[name=local]").val(nombreLocal);
                    $("#aforo").html(aforo);
                    $("[name=aforo]").val(aforo);
                } else if (tipo == "Musico") {
                    var nombreArtistico = respJSON[2].NombreArtistico;
                    var web = respJSON[2].Web;
                    var genero = respJSON[2].Genero;
                    var numComp = respJSON[2].NumComponentes;
                    $("#nombreArtistico").html(nombreArtistico);
                    $("#nombreArt").val(nombreArtistico);
                    $("#web").html(web);
                    $("[name=web]").val(web);
                    valueGenero(genero);
                    $("#numComp").html(numComp);
                } else {
                    var sexo = respJSON[2].Sexo;
                    var born = respJSON[2].Nacimiento;
                    if (sexo == "H") {
                        $("#sexo").html("Hombre");
                        $("#hombre").prop("checked", true);
                    } else {
                        $("#sexo").html("Mujer");
                        $("#mujer").prop("checked", true);
                    }
                    $("#born").html(born);
                }
            }
        });
    }
}
/*MODIFICACIONES DE USUARIO GENERAL */ {
    function modPass() {
        var user = document.getElementById("usuario").innerHTML;
        var oldPass = $("[name=oldPass]").val();
        var newPass = $("[name=newPass]").val();
        var vNewPass = $("[name=vNewPass]").val();
        if (oldPass === "" || newPass === "" || vNewPass === "") {
            $("#exitoPass").html("Rellene los campos vacios.");
        } else {
            $.ajax({
                type: "POST",
                url: "editarPerfiles.php",
                dataType: "json",
                data: {OldPass: oldPass, NewPass: newPass, VNewPass: vNewPass, Usuario: user},
                success: function (respJSON) {
                    $("#exitoPass").html(respJSON.Respuesta);
                }

            });
        }



    }

    function modNombre() {
        var newNombre = $("#nom").val();
        var usuario = document.getElementById("usuario").innerHTML;
        if (/^[ñA-Za-z _]*[ñA-Za-z][ñA-Za-z _]*$/.test(newNombre)) {
            $.ajax({
                type: "POST",
                url: "editarPerfiles.php",
                dataType: "json",
                data: {Nombre: newNombre, Usuario: usuario},
                success: function (respJSON) {
                    $("#nombre").html(respJSON.Respuesta);
                    $("#exitoNombre").html(respJSON.Exito);
                }
            });
        } else {
            $("#exitoNombre").html("No se aceptan números ni carácteres especiales");
        }
    }
    function modApellidos() {
        var newApellidos = $("#cognoms").val();
        var usuario = document.getElementById("usuario").innerHTML;
        if (/^[ñA-Za-z _]*[ñA-Za-z][ñA-Za-z _]*$/.test(newApellidos)) {
            $.ajax({
                type: "POST",
                url: "editarPerfiles.php",
                dataType: "json",
                data: {Apellidos: newApellidos, Usuario: usuario},
                success: function (respJSON) {
                    $("#apellidos").html(respJSON.Respuesta);
                    $("#exitoApellidos").html(respJSON.Exito);
                }
            });
        } else {
            $("#exitoApellidos").html("No se aceptan números ni carácteres especiales");
        }
    }
    function modMail() {
        var newMail = $("#mairu").val();
        var usuario = document.getElementById("usuario").innerHTML;
        if (/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(newMail)) {
            $.ajax({
                type: "POST",
                url: "editarPerfiles.php",
                dataType: "json",
                data: {Mail: newMail, Usuario: usuario},
                success: function (respJSON) {
                    $("#mail").html(respJSON.Respuesta);
                    $("#exitoMail").html(respJSON.Exito);
                }
            });
        } else {
            $("#exitoMail").html("Correo Electronico Incorrecto. Intentelo de nuevo");
        }
    }
    function modTelf() {
        var telf = $("#telf2").val();
        var usuario = document.getElementById("usuario").innerHTML;
        telf = telf.toString().replace(/\s/g, '');
        if (telf.length === 9 && /^[679]{1}[0-9]{8}$/.test(telf)) {
            $.ajax({
                type: "POST",
                url: "editarPerfiles.php",
                dataType: "json",
                data: {Telefono: telf, Usuario: usuario},
                success: function (respJSON) {
                    $("#telf").html(respJSON.Respuesta);
                    $("#exitoTelf").html(respJSON.Exito);
                }
            });
        } else {
            $("#exitoTelf").html("Teléfono Incorrecto. Intentelo denuevo");
        }

    }
    function modCiudad() {
        var newCity = $("#ciudad option:selected").val();
        var usuario = document.getElementById("usuario").innerHTML;
        $.ajax({
            type: "POST",
            url: "editarPerfiles.php",
            dataType: "json",
            data: {Ciudad: newCity, Usuario: usuario},
            success: function (respJSON) {
                $("#provincia").html(respJSON.Provincia);
                $("#city").html(respJSON.Ciudad);
                $("#exitoCiudad").html(respJSON.Exito);
            }
        });
    }
    function modDireccion() {
        var newDir = $("#newDir").val();
        var usuario = document.getElementById("usuario").innerHTML;
        $.ajax({
            type: "POST",
            url: "editarPerfiles.php",
            dataType: "json",
            data: {Direccion: newDir, Usuario: usuario},
            success: function (respJSON) {
                $("#direccion").html(respJSON.Respuesta);
                $("#exitoDireccion").html(respJSON.Exito);
            }
        });
    }
}
/*MODIFICACIONES DE USUARIO LOCAL*/{
    function modLocalName() {
        var newLocalName = $("[name=local]").val();
        var usuario = document.getElementById("usuario").innerHTML;
        $.ajax({
            type: "POST",
            url: "editarPerfiles.php",
            dataType: "json",
            data: {LocalName: newLocalName, Usuario: usuario},
            success: function (respJSON) {
                $("#localName").html(respJSON.Respuesta);
                $("#exitoLocalName").html(respJSON.Exito);
            }
        });
    }
    function modAforo() {
        var aforo = $("[name=aforo]").val();
        var usuario = document.getElementById("usuario").innerHTML;
        $.ajax({
            type: "POST",
            url: "editarPerfiles.php",
            dataType: "json",
            data: {Aforo: aforo, Usuario: usuario},
            success: function (respJSON) {
                $("#aforo").html(respJSON.Respuesta);
                $("#exitoAforo").html(respJSON.Exito);
            }
        });
    }
}
/*MODIFICACIONES DE USUARIO FAN*/{
    function modSexo() {
        var sexo = $("[name=sex]:checked").val();
        var usuario = document.getElementById("usuario").innerHTML;
        $.ajax({
            type: "POST",
            url: "editarPerfiles.php",
            dataType: "json",
            data: {Sexo: sexo, Usuario: usuario},
            success: function (respJSON) {
                if (respJSON.Respuesta == "H") {
                    $("#sexo").html("Hombre");
                } else {
                    $("#sexo").html("Mujer");
                }
                $("#exitoSexo").html(respJSON.Exito);
            }
        });
    }
    function modBorn() {
        var born = $("[name=born]").val();
        var usuario = document.getElementById("usuario").innerHTML;
        $.ajax({
            type: "POST",
            url: "editarPerfiles.php",
            dataType: "json",
            data: {Born: born, Usuario: usuario},
            success: function (respJSON) {
                var born = respJSON.Respuesta;
                $("#born").html(born);
                $("#exitoBorn").html(respJSON.Exito);
            }
        });
    }
}
/*MODIFICACIONES DE USUARIO MUSICO */{
    function modNombreArt() {
        var nombreArt = $("#nombreArt").val();
        var usuario = document.getElementById("usuario").innerHTML;
        $.ajax({
            type: "POST",
            url: "editarPerfiles.php",
            dataType: "json",
            data: {NombreArtistico: nombreArt, Usuario: usuario},
            success: function (respJSON) {
                var newArt = respJSON.Respuesta;
                $("#nombreArtistico").html(newArt);
                $("#exitoNombreArtistico").html(respJSON.Exito);
            }
        });
    }

    function modWeb() {
        var web = $("[name=web]").val();
        var usuario = document.getElementById("usuario").innerHTML;
        if (/[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/gi.test(web)) {
            $.ajax({
                type: "POST",
                url: "editarPerfiles.php",
                dataType: "json",
                data: {Web: web, Usuario: usuario},
                success: function (respJSON) {
                    var web = respJSON.Respuesta;
                    $("#web").html(web);
                    $("#exitoWeb").html(respJSON.Exito);
                }
            });
        } else {
            $("#exitoWeb").html("Web no válido. Inténtelo de nuevo");
        }
    }
    function modGenero() {
        var newGenero = $("#selGenero").val();
        var usuario = document.getElementById("usuario").innerHTML;
        $.ajax({
            type: "POST",
            url: "editarPerfiles.php",
            dataType: "json",
            data: {NewGenero: newGenero, Usuario: usuario},
            success: function (respJSON) {
                var genero = respJSON.NombreGenero;
                $("#genero").html(genero);
                $("#exitoGenero").html(respJSON.Exito);
            }
        });
    }
    function modNumComp() {
        var newNumComp = $("[name=numComp]").val();
        var usuario = document.getElementById("usuario").innerHTML;
        $.ajax({
            type: "POST",
            url: "editarPerfiles.php",
            dataType: "json",
            data: {Componentes: newNumComp, Usuario: usuario},
            success: function (respJSON) {
                var comp = respJSON.Respuesta;
                $("#numComp").html(comp);
                $("#exitoNumComp").html(respJSON.Exito);
            }
        });
    }
}
//PARA QUE SALGA EL NOMBRE DEL GENERO EN VEZ DEL NUMERO Y DARLE EL VALUE PREDETERMINADO AL SELECT DE EDITAR
function valueGenero(genero) {
    $("#selGenero").val(genero);
    $.ajax({
        type: "POST",
        url: "editarPerfiles.php",
        dataType: "json",
        data: {Genero: genero},
        success: function (respJSON) {
            var nombreGen = respJSON.NombreGenero;
            $("#genero").html(nombreGen);
        }
    });
}
//PARA QUE VAYA CAMBIANDO Y MOSTRANDO LAS CIUDAD DE LA PROVINCIA CORRESPONDIENTE
function cogeProvincia() {
    var provincia = document.getElementById("selProvincia").value;
    $.ajax({
        type: "POST",
        url: "editarPerfiles.php",
        dataType: "json",
        data: {Provincia: provincia},
        success: function (respJSON) {
            $("#ciudad").html("");
            for (i = 0; i < (respJSON.length - 1); i++) {
                var idCiudad = respJSON[i].idCiudad;
                var ciudad = respJSON[i].ciudad;
                $("#ciudad").append('<option value="' + idCiudad + '" >' + ciudad + '</option>');
            }
        }
    });
}

