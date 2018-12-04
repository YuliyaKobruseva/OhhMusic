<?php
require_once '../funciones.php';
if (isset($_POST["bajaConc"])) {
    $idMus = $_POST["idMusico"];
    $idConc = $_POST["bajaConc"];
    $resultado = darBajaByIDConc($idConc, $idMus);
    if ($resultado == "ok") {
        echo '{"Res":"Concierto Dado de Baja"}';
    } else {
        echo $resultado;
    }
}

if (isset($_POST["altaConc"])) {
    $idMus = $_POST["idMusico"];
    $idConc = $_POST["altaConc"];
    $resultado = darAltaByIDConc($idConc, $idMus);
    if ($resultado == "ok") {
        echo '{"Res":"Concierto Dado de Alta"}';
    } else {
        echo $resultado;
    }
}

function darBajaByIDConc($idConcierto, $idMusico) {
    $conexion = conectar();
    $delete = "DELETE FROM inscripcion WHERE musico = $idMusico AND idConcierto = $idConcierto";
    if (mysqli_query($conexion, $delete)) {
        $resultado = "ok";
    } else {
        $resultado = mysqli_error($conexion);
    }
    desconectar($conexion);
    return $resultado;
}

function darAltaByIDConc($idConcierto, $idMusico) {
    $conexion = conectar();
    $insert = "INSERT INTO inscripcion VALUES ($idMusico,$idConcierto,'Pendiente')";
    if (mysqli_query($conexion, $insert)) {
        $resultado = "ok";
    } else {
        $resultado = mysqli_error($conexion);
    }
    desconectar($conexion);
    return $resultado;
}