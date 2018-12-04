<?php

require_once '../funciones.php';

//MOSTRAR AL USUARIO LA INFO QUE TIENE
if (isset($_POST["User"])) {
    $user = $_POST["User"];
    $infoUser = getInfoUser($user);

    echo "[";
    while ($fila = mysqli_fetch_assoc($infoUser)) {
        echo '{"Password":"' . $fila["psw"] . '","Nombre":"' . $fila["nombre"] . '","Apellidos":"' . $fila["apellidos"] . '","Email":"' . $fila["email"] . '","Telefono":"' . $fila["telefono"] . '","Direccion":"' . $fila["direccion"] . '","Imagen":"' . $fila["imagen"] . '","Tipo":"' . $fila["tipo"] . '"},';
        $ciudadUser = getProvincia($fila["ciudad"]);
        while ($fila2 = mysqli_fetch_assoc($ciudadUser)) {
            echo '{"Provincia":"' . $fila2["provincia"] . '","Ciudad":"' . $fila2["nombreCiudad"] . '","idCiudad":"' . $fila2["idCiudad"] . '"},';
        }
        $infoUserByTipo = getInfoUserByTipo($fila["tipo"], $fila["idUsuario"]);
        while ($fila3 = mysqli_fetch_assoc($infoUserByTipo)) {
            if ($fila["tipo"] == "Local") {
                echo '{"NombreLocal":"' . $fila3["nombreLocal"] . '","Aforo":"' . $fila3["aforo"] . '"}';
            } else if ($fila["tipo"] == "Musico") {
                echo '{"NombreArtistico":"' . $fila3["nombreArtistico"] . '","Web":"' . $fila3["web"] . '","Genero":"' . $fila3["genero"] . '","NumComponentes":"' . $fila3["numComponentes"] . '"}';
            } else {
                echo '{"Sexo":"' . $fila3["sexo"] . '","Nacimiento":"' . $fila3["nacimiento"] . '"}';
            }
        }
    }
    echo "]";
}
//RECOGER NUMERO DEL GÉNERO PARA PASARLE EL NOMBRE Y MOSTRARLE EL NOMBRE EN VEZ DEL NUM(ID)
if (isset($_POST["Genero"])) {
    $genero = $_POST["Genero"];
    $nombreGen = selectGeneroByID($genero);
    while ($fila = mysqli_fetch_assoc($nombreGen)) {
        echo '{"idGenero":"' . $fila["idGenero"] . '","NombreGenero":"' . $fila["nombreGenero"] . '"}';
    }
}
//RECOGER INFORMACION DE LAS CIUDADES DE LA PROVINCIA CORRESPONDIENTE
if (isset($_POST["Provincia"])) {
    $provincia = $_POST["Provincia"];
    $ciudad = selectCiudad($provincia);
    echo "[";
    while ($fila = mysqli_fetch_assoc($ciudad)) {
        echo '{"idCiudad":"' . $fila["idCiudad"] . '","ciudad":"' . $fila["nombreCiudad"] . '"},';
    }
    echo "{}]";
}
//MODIFICACIONES
if (isset($_POST["OldPass"])) {
    $oldpsw = $_POST["OldPass"];
    $newpsw = $_POST["NewPass"];
    $vnewpsw = $_POST["VNewPass"];
    $usuario = $_POST["Usuario"];
    $pass = validarUsuario($usuario, $oldpsw);
    if ($pass) {
        if ($oldpsw != $newpsw) {
            if ($newpsw == $vnewpsw) {
                $changePass = changePassByUser($usuario, $newpsw);
                if ($changePass == "ok") {
                    echo '{"Respuesta":"Password actualizada"}';
                }else{
                    echo $changePass;
                }
            } else {
                echo '{"Respuesta":"Password nueva y de verificacion no son iguales"}';
            }
        } else {
            echo '{"Respuesta":"Password actual y nueva iguales"}';
        }
    } else {
        echo '{"Respuesta":"Password actual introducida incorrecta"}';
    }
}

if (isset($_POST["Nombre"])) {
    $newName = $_POST["Nombre"];
    $usuario = $_POST["Usuario"];
    $change = updateDatosUsuario($newName, $usuario, 'nombre');
    if ($change == "ok") {
        echo '{"Respuesta":"' . $newName . '", "Exito":"Nombre Modificado"}';
    }
}
if (isset($_POST["Apellidos"])) {
    $newSurname = $_POST["Apellidos"];
    $usuario = $_POST["Usuario"];
    $change = updateDatosUsuario($newSurname, $usuario, 'apellidos');
    if ($change == "ok") {
        echo '{"Respuesta":"' . $newSurname . '","Exito":"Apellidos Modificado"}';
    }
}
if (isset($_POST["Mail"])) {
    $newMail = $_POST["Mail"];
    $usuario = $_POST["Usuario"];
    $change = updateDatosUsuario($newMail, $usuario, 'email');
    if ($change == "ok") {
        echo '{"Respuesta":"' . $newMail . '","Exito":"Correo Electronico Modificado"}';
    }
}
if (isset($_POST["Telefono"])) {
    $telf = $_POST["Telefono"];
    $usuario = $_POST["Usuario"];
    $change = updateDatosUsuario($telf, $usuario, 'telefono');
    if ($change == "ok") {
        echo '{"Respuesta":"' . $telf . '","Exito":"Telefono Modificado"}';
    }
}
if (isset($_POST["Ciudad"])) {
    $newCity = $_POST["Ciudad"];
    $usuario = $_POST["Usuario"];
    $change = updateCity($newCity, $usuario);
    if ($change == "ok") {
        $ciudadUser = getProvincia($newCity);
        while ($fila2 = mysqli_fetch_assoc($ciudadUser)) {
            echo '{"Provincia":"' . $fila2["provincia"] . '","Ciudad":"' . $fila2["nombreCiudad"] . '","idCiudad":"' . $fila2["idCiudad"] . '","Exito":"Ciudad y/o Provincia Modificado"}';
        }
    }
}
if (isset($_POST["Direccion"])) {
    $newDir = $_POST["Direccion"];
    $usuario = $_POST["Usuario"];
    $change = updateDatosUsuario($newDir, $usuario, 'direccion');
    if ($change == "ok") {
        echo '{"Respuesta":"' . $newDir . '","Exito":"Direccion Modificada"}';
    }
}
if (isset($_POST["LocalName"])) {
    $newLocalName = $_POST["LocalName"];
    $usuario = $_POST["Usuario"];
    $infoUser = getInfoUser($usuario);
    while ($fila = mysqli_fetch_assoc($infoUser)) {
        $change = updateDatosByTipo('local', 'nombreLocal', $newLocalName, 'usuLocal', $fila["idUsuario"]);
        if ($change == "ok") {
            echo '{"Respuesta":"' . $newLocalName . '","Exito":"Nombre del Local Modificado"}';
        }
    }
}
if (isset($_POST["Aforo"])) {
    $newAforo = $_POST["Aforo"];
    $usuario = $_POST["Usuario"];
    $infoUser = getInfoUser($usuario);
    while ($fila = mysqli_fetch_assoc($infoUser)) {
        $change = updateDatosByTipo('local', 'aforo', $newAforo, 'usuLocal', $fila["idUsuario"]);
        if ($change == "ok") {
            echo '{"Respuesta":"' . $newAforo . '","Exito":"Aforo Modificado"}';
        }
    }
}
if (isset($_POST["Sexo"])) {
    $genero = $_POST["Sexo"];
    $usuario = $_POST["Usuario"];
    $infoUser = getInfoUser($usuario);
    while ($fila = mysqli_fetch_assoc($infoUser)) {
        $change = updateDatosByTipo('fan', 'sexo', $genero, 'usuFan', $fila["idUsuario"]);
        if ($change == "ok") {
            echo '{"Respuesta":"' . $genero . '","Exito":"Sexo Modificado"}';
        }
    }
}
if (isset($_POST["Born"])) {
    $born = $_POST["Born"];
    $usuario = $_POST["Usuario"];
    $infoUser = getInfoUser($usuario);
    while ($fila = mysqli_fetch_assoc($infoUser)) {
        $change = updateDatosByTipo('fan', 'nacimiento', $born, 'usuFan', $fila["idUsuario"]);
        if ($change == "ok") {
            echo '{"Respuesta":"' . $born . '","Exito":"Fecha de Nacimiento Modificado"}';
        }
    }
}
if (isset($_POST["NombreArtistico"])) {
    $nombreArt = $_POST["NombreArtistico"];
    $usuario = $_POST["Usuario"];
    $infoUser = getInfoUser($usuario);
    while ($fila = mysqli_fetch_assoc($infoUser)) {
        $change = updateDatosByTipo('musico', 'nombreArtistico', $nombreArt, 'usuMusico', $fila["idUsuario"]);
        if ($change == "ok") {
            echo '{"Respuesta":"' . $nombreArt . '","Exito":"Nombre Artistico Modificado"}';
        }
    }
}
if (isset($_POST["Web"])) {
    $web = $_POST["Web"];
    $usuario = $_POST["Usuario"];
    $infoUser = getInfoUser($usuario);
    while ($fila = mysqli_fetch_assoc($infoUser)) {
        $change = updateDatosByTipo('musico', 'web', $web, 'usuMusico', $fila["idUsuario"]);
        if ($change == "ok") {
            echo '{"Respuesta":"' . $web . '","Exito":"Web Modificado"}';
        }
    }
}
if (isset($_POST["NewGenero"])) {
    $newGenero = $_POST["NewGenero"];
    $usuario = $_POST["Usuario"];
    $infoUser = getInfoUser($usuario);

    $nombreGen = selectGeneroByID($newGenero);
    while ($fila = mysqli_fetch_assoc($infoUser)) {
        $change = updateDatosByTipo('musico', 'genero', $newGenero, 'usuMusico', $fila["idUsuario"]);
        if ($change == "ok") {
            while ($fila = mysqli_fetch_assoc($nombreGen)) {
                echo '{"idGenero":"' . $fila["idGenero"] . '","NombreGenero":"' . $fila["nombreGenero"] . '","Exito":"Genero de Musica Modificado"}';
            }
        }
    }
}
if (isset($_POST["Componentes"])) {
    $comp = $_POST["Componentes"];
    $usuario = $_POST["Usuario"];
    $infoUser = getInfoUser($usuario);
    while ($fila = mysqli_fetch_assoc($infoUser)) {
        $change = updateDatosByTipo('musico', 'numComponentes', $comp, 'usuMusico', $fila["idUsuario"]);
        if ($change == "ok") {
            echo '{"Respuesta":"' . $comp . '","Exito":"Núm. de Componentes Modificado"}';
        }
    }
}

//FUNCIONES UTILIZADAS
function getInfoUser($usuario) {
    $c = conectar();
    $select = "SELECT * FROM usuario where usuario = '$usuario'";
    $result = mysqli_query($c, $select);
    desconectar($c);
    return $result;
}


function getInfoUserByTipo($tipo, $usuario) {
    $c = conectar();
    if ($tipo == "Local") {
        $select = "SELECT * FROM local where usuLocal = $usuario";
    } else if ($tipo == "Musico") {
        $select = "SELECT * FROM musico where usuMusico = $usuario";
    } else {
        $select = "SELECT * FROM fan where usuFan = $usuario";
    }
    $result = mysqli_query($c, $select);
    desconectar($c);
    return $result;
}

function getProvincia($ciudad) {
    $c = conectar();
    $select = "SELECT * FROM ciudad where idCiudad = $ciudad";
    $result = mysqli_query($c, $select);
    desconectar($c);
    return $result;
}


function changePassByUser($usuario,$pass){
    $c = conectar();
    $psw = password_hash($pass, PASSWORD_DEFAULT);
    $change = "UPDATE usuario SET psw='$psw' WHERE usuario='$usuario'";
    if (mysqli_query($c, $change)) {
        $result = "ok";
    } else {
        $result = mysqli_error($c);
    }
    desconectar($c);
    return $result;
}

function selectProvincias() {
    $connect = conectar();
    $select = "SELECT DISTINCT provincia from ciudad";
    $result = mysqli_query($connect, $select);
    desconectar($connect);
    return $result;
}

function selectCiudad($provincia) {
    $connect = conectar();
    $select = "SELECT * FROM ciudad WHERE provincia='$provincia'";
    $result = mysqli_query($connect, $select);
    desconectar($connect);
    return $result;
}

function selectCiudadByUser($usuario) {
    $connect = conectar();
    $select = "SELECT idCiudad,provincia,nombreCiudad FROM ciudad INNER JOIN usuario ON usuario.ciudad = ciudad.idCiudad where usuario = '$usuario';";
    $result = mysqli_query($connect, $select);
    desconectar($connect);
    return $result;
}

function selectGeneroByID($genero) {
    $connect = conectar();
    $select = "SELECT * from genero where idGenero = $genero";
    $result = mysqli_query($connect, $select);
    desconectar($connect);
    return $result;
}

function updateDatosUsuario($nombre, $usuario, $tabla) {
    $c = conectar();
    $change = "UPDATE usuario SET $tabla='$nombre' WHERE usuario='$usuario';";
    if (mysqli_query($c, $change)) {
        $result = "ok";
    } else {
        $result = mysqli_error($c);
    }
    desconectar($c);
    return $result;
}

function updateDatosByTipo($tabla, $col, $datoString, $usuTipo, $usuario) {
    $c = conectar();
    if (is_numeric($datoString)) {
        $change = "UPDATE $tabla SET $col=$datoString WHERE $usuTipo=$usuario";
    } else {
        $change = "UPDATE $tabla SET $col='$datoString' WHERE $usuTipo=$usuario";
    }

    if (mysqli_query($c, $change)) {
        $result = "ok";
    } else {
        $result = mysqli_error($c);
    }
    desconectar($c);
    return $result;
}

function updateCity($ciudad, $usuario) {
    $c = conectar();
    $change = "UPDATE usuario SET ciudad=$ciudad WHERE usuario='$usuario';";
    if (mysqli_query($c, $change)) {
        $result = "ok";
    } else {
        $result = mysqli_error($c);
    }
    desconectar($c);
    return $result;
}
