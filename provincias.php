<?php
require_once 'funciones.php';
if (isset($_POST["Provincia"])) {
    $provincia = $_POST["Provincia"];
    $ciudad = selectCiudad($provincia);
    echo "[";
    while ($fila = mysqli_fetch_assoc($ciudad)) {
        echo '{"idCiudad":"' . $fila["idCiudad"] . '","ciudad":"' . $fila["nombreCiudad"] . '"},';
    }
    echo "{}]";
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


