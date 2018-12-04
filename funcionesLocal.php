<?php
require_once '../funciones.php';
//NEW Funciones
if (isset($_POST["idConcierto"])) {
    $idConcierto = $_POST["idConcierto"];
    $musicos = selectMusicosByID($idConcierto);
    if ($musicos == NULL) {
      
        echo '{"error":"1"}';
   
    } else {
        echo "[";
        while ($fila = mysqli_fetch_assoc($musicos)) {
            echo '{"Musico":"' . $fila["musico"] . '","idConcierto":"' . $fila["idConcierto"] . '","Artistico":"' . $fila["nombreArtistico"] . '"},';
        }
        echo "{}]";
    }
}

if (isset($_POST["concDel"])) {
    $idConc = $_POST["concDel"];
    $resultado = eliminarConcierto($idConc);
    if ($resultado == "ok") {
        echo '{"Res":"Concierto Eliminado"}';
    } else {
        echo $resultado;
    }
}

if (isset($_POST["acepMus"])) {
    $idMus = $_POST["acepMus"];
    $idConc = $_POST["idConc"];
    $resultado = aceptarMusico($idConc, $idMus);
    if ($resultado == "ok") {
        echo '{"Res":"Musico Aceptado"}';
    } else {
        echo $resultado;
    }
}

if (isset($_POST["rechMus"])) {
    $idMus = $_POST["rechMus"];
    $idConc = $_POST["idConc"];
    $resultado = rechazarMusico($idConc, $idMus);
    if ($resultado == "ok") {
        echo '{"Res":"Musico Rechazado"}';
    } else {
        echo $resultado;
    }
}


function eliminarConcierto($idConc) {
    $conexion = conectar();
    $delete = "DELETE FROM concierto WHERE idConcierto = $idConc";
    if (mysqli_query($conexion, $delete)) {
        $resultado = "ok";
    } else {
        $resultado = mysqli_error($conexion);
    }
    desconectar($conexion);
    return $resultado;
}

function aceptarMusico($idCon, $idMus) {
    $conexion = conectar();
    mysqli_autocommit($conexion, FALSE);
    mysqli_begin_transaction($conexion);
    $update1 = "UPDATE inscripcion set estado='Aceptado' WHERE musico=$idMus AND idConcierto = $idCon";
    $resultado = mysqli_query($conexion, $update1);
    $update2 = "UPDATE inscripcion set estado='Rechazado' WHERE musico<>$idMus AND idConcierto = $idCon";
    $resultado2 = mysqli_query($conexion, $update2);
    $update3 = "UPDATE concierto set musico=$idMus,estado='Aceptado' WHERE idConcierto=$idCon";
    $resultado3 = mysqli_query($conexion, $update3);
    if ($resultado && $resultado2 && $resultado3) {
        mysqli_commit($conexion);
    } else {
        mysqli_rollback($conexion);
    }
    mysqli_autocommit($conexion, TRUE);
    desconectar($conexion);
    return $resultado3;
}

function rechazarMusico($idCon, $idMus) {
    $conexion = conectar();
    $update = "UPDATE inscripcion set estado='Rechazado' WHERE musico=$idMus AND idConcierto = $idCon";
    if (mysqli_query($conexion, $update)) {
        $resultado = "ok";
    } else {
        $resultado = mysqli_error($conexion);
    }
    desconectar($conexion);
    return $resultado;
}


function selectConcPend($idMusu) {
    $conexion = conectar();
    $select = "SELECT idConcierto,nombreConcierto,fecha,hora,nombreLocal,direccion from concierto
	INNER JOIN local ON local.usuLocal = concierto.local
	INNER JOIN usuario ON usuario.idUsuario = concierto.local
    WHERE concierto.idConcierto IN (SELECT * FROM (SELECT idConcierto FROM concierto WHERE genero = (SELECT genero FROM musico WHERE usuMusico = $idMusu))
						  as tab)
	AND concierto.estado = 'Pendiente'
    AND concierto.idConcierto NOT IN(SELECT idConcierto FROM inscripcion WHERE musico = $idMusu AND estado = 'Rechazado');";
    $result = mysqli_query($conexion, $select);
    if (mysqli_num_rows($result) == 0) {
        $result = NULL;
    }
    desconectar($conexion);
    return $result;
}

function selectEstadoByConcPend($idMusu, $idConc) {
    $conexion = conectar();
    $select = "SELECT estado FROM inscripcion WHERE musico = $idMusu and idConcierto = $idConc";
    $result = mysqli_query($conexion, $select);
    if (mysqli_num_rows($result) == 0) {
        $result = NULL;
    }
    desconectar($conexion);
    return $result;
}

function selectMusicosByID($idConcierto) {
    $conexion = conectar();
    $select = "SELECT musico,idConcierto,nombreArtistico FROM inscripcion
                INNER JOIN musico ON musico.usuMusico = inscripcion.musico 
                WHERE idConcierto = $idConcierto AND estado = 'Pendiente'";
    $result = mysqli_query($conexion, $select);
    if (mysqli_num_rows($result) == 0) {
        $result = NULL;
    }
    desconectar($conexion);
    return $result;
}

function imagenConcierto($nombre_img, $tipo, $tamano, $user, $tmp) {
    $nombre_nuevo = $user . "_" . time() . "_" . date("Y-m-d", time());
    if (($nombre_img == !NULL) && ($tamano <= 200000)) {        
        if (($tipo == "image/gif") || ($tipo == "image/jpeg") || ($tipo == "image/jpg") || ($tipo == "image/png")) {            
            $directorio = '../imagesUser/';
            $ruta = $directorio . $nombre_nuevo;            
            if (move_uploaded_file($tmp, $ruta)) {
                return $nombre_nuevo;
            } else {
                return 0;
            }
        } else {            
            return 0;
        }
    } else {        
        if ($nombre_img == !NULL) {
            echo "La imagen es demasiado grande ";
        }
    }
}


function createConcierto($nombre, $genero, $precio, $fecha, $hora, $idLocal, $img) {
    $conexion = conectar();
    $insert = "INSERT INTO concierto values(NULL,'$nombre',$genero,$precio,'$fecha','$hora',NULL,$idLocal,'Pendiente','$img')";
    if (mysqli_query($conexion, $insert)) {
        $resultado = "ok";
    } else {
        $resultado = mysqli_error($conexion);
    }
    desconectar($conexion);
    return $resultado;
}

function selectConciertosByLocal($user, $estado) {
    $conexion = conectar();
    if (empty($estado)) {
        $select = "SELECT idConcierto,nombreConcierto,nombreGenero,tarifa,fecha,hora from concierto 
                    INNER JOIN genero ON genero.idGenero = concierto.genero
                    where local = $user AND estado ='Pendiente' ORDER BY fecha";
    } else {
        $select = "SELECT nombreConcierto,nombreGenero,tarifa,fecha,hora,usuario FROM concierto 
                    INNER JOIN usuario ON concierto.musico = usuario.idUsuario
                    INNER JOIN genero ON genero.idGenero = concierto.genero
                    where local = $user and estado = '$estado' ORDER BY fecha";
    }
    $result = mysqli_query($conexion, $select);
    if (mysqli_num_rows($result) == 0) {
        $result = NULL;
    }
    desconectar($conexion);
    return $result;
}

function selectConciertosAceptados($idMus) {
    $conexion = conectar();
    $select = "SELECT nombreConcierto,fecha,hora,nombreLocal,direccion FROM concierto
                INNER JOIN usuario ON concierto.local = usuario.idUsuario
                INNER JOIN local ON concierto.local = local.usuLocal
                WHERE musico = $idMus ORDER BY fecha";
    $result = mysqli_query($conexion, $select);
    if (mysqli_num_rows($result) == 0) {
        $result = NULL;
    }
    desconectar($conexion);
    return $result;
}

function selectConciertosRechazados($idMus) {
    $conexion = conectar();
    $select = "SELECT nombreConcierto,fecha,hora,concierto.estado FROM inscripcion
	INNER JOIN concierto ON inscripcion.idConcierto = concierto.idConcierto
    WHERE inscripcion.musico = $idMus AND (inscripcion.estado = 'Anulado' OR inscripcion.estado = 'Rechazado') ORDER BY fecha";
    $result = mysqli_query($conexion, $select);
    if (mysqli_num_rows($result) == 0) {
        $result = NULL;
    }
    desconectar($conexion);
    return $result;
}

function anularConcierto() {
    $conexion = conectar();
    $update = "UPDATE concierto SET estado = 'Anulado' WHERE fecha < CURDATE() AND estado = 'Pendiente'";
    if (mysqli_query($conexion, $update)) {
        $result = "ok";
    } else {
        $result = mysqli_error($conexion);
    }
    desconectar($conexion);
    return $result;
}

function selectConcAnulados($idUser) {
    $conexion = conectar();
    $select = "SELECT nombreConcierto,nombreGenero,tarifa,fecha,hora FROM concierto
                INNER JOIN genero ON genero.idGenero = concierto.genero
                WHERE local = $idUser AND estado = 'Anulado' ORDER BY fecha";
    $result = mysqli_query($conexion, $select);
    if (mysqli_num_rows($result) == 0) {
        $result = NULL;
    }
    desconectar($conexion);
    return $result;
}

function selectFecha(){
    $conexion = conectar();
    $select = "SELECT CURDATE() AS FECHA;";
    $resultado = mysqli_query($conexion, $select);
    $fila = mysqli_fetch_assoc($resultado);
    desconectar($conexion);
    return $fila["FECHA"];
}
