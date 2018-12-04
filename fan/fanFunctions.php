<?php

//Lineas 241 y 257 cambiar < por > para que se muestren conciertos futuros
require_once '../funciones.php';
if (isset($_POST['Concierto'])) {
    $conc = $_POST['Concierto'];
    $fan = $_POST['Fan'];
    $vote = votarConcierto($fan, $conc);
    if ($vote == "ok") {
        echo '{"idConcierto":"' . $conc . '","idFan":"' . $fan . '"}';
    }
}
if (isset($_POST['Musico'])) {
    $musico = $_POST['Musico'];
    $fan = $_POST['Fan'];
    $vote = votarMusico($fan, $musico);
    if ($vote == "ok") {
        echo '{"idMusico":"' . $musico . '","idFan":"' . $fan . '"}';
    }
}

if (isset($_POST['idConcierto'])) {
    $idConc = $_POST['idConcierto'];
    if (isset($_POST['Fan'])) { //Este if isset lo he puesto para probar porque me daba un error el buscador en esta linia
        $fan = $_POST['Fan'];
        $vote = quitarVotoConcierto($fan, $idConc);
        if ($vote == "ok") {
            echo '{"idConcierto":"' . $idConc . '","idFan":"' . $fan . '"}';
        }
    }
}

if (isset($_POST['idMusico'])) {
    $musico = $_POST['idMusico'];
    if (isset($_POST['Fan'])) { //Este if isset lo he puesto para probar porque me daba un error el buscador en esta linia
        $fan = $_POST['Fan'];
        $vote = quitarVoto($fan, $musico);
        if ($vote == "ok") {
            echo '{"idMusico":"' . $musico . '","idFan":"' . $fan . '"}';
        }
    }
}

function votarConcierto($fan, $concierto) {
    $conexion = conectar();
    $insert = "INSERT INTO fanvotaconcierto VALUES ($fan,$concierto)";
    if (mysqli_query($conexion, $insert)) {
        $resultado = "ok";
    } else {
        $resultado = mysqli_error($conexion);
    }
    desconectar($conexion);
    return $resultado;
}

function votarMusico($fan, $musico) {
    $conexion = conectar();
    $insert = "INSERT INTO fanvotamusico VALUES ($fan,$musico);";
    if (mysqli_query($conexion, $insert)) {
        $resultado = "ok";
    } else {
        $resultado = mysqli_error($conexion);
    }
    desconectar($conexion);
    return $resultado;
}

function quitarVotoConcierto($fan, $concierto) {
    $conexion = conectar();
    $delete = "DELETE FROM fanvotaconcierto WHERE fan = $fan AND idConcierto = $concierto";
    if (mysqli_query($conexion, $delete)) {
        $resultado = "ok";
    } else {
        $resultado = mysqli_error($conexion);
    }
    desconectar($conexion);
    return $resultado;
}

function quitarVoto($fan, $musico) {
    $conexion = conectar();
    $delete = "DELETE FROM fanvotamusico WHERE fan = $fan AND musico = $musico";
    if (mysqli_query($conexion, $delete)) {
        $resultado = "ok";
    } else {
        $resultado = mysqli_error($conexion);
    }
    desconectar($conexion);
    return $resultado;
}

if (isset($_POST['Genero'])) {
    $genero = $_POST['Genero'];
    $fan = $_POST['Usuario'];
    $total = totalMusicosByGenre($genero);
    $limite = $_POST['Limite'];
    $musicos = selectMusicosByGenero($genero, $limite);
    if ($musicos != NULL) {
        if ($limite > 0) {
            $lim = $limite - 5;
            $prev = "<aside onclick='show5Music(" . $lim.",".$genero .",".$fan. ")'>&laquo;</aside>";
        }
        if ($limite < $total - 5) {
            $lim = $limite + 5;
            $next = "<aside onclick='show5Music(" . $lim.",".$genero .",".$fan.")'>&raquo;</aside>";
        }
        echo "[";
        while ($fila = mysqli_fetch_assoc($musicos)) {
            $estado = isVote($fan, $fila['usuMusico']);
            if ($estado) {
                echo '{"idMusico":"' . $fila["usuMusico"] . '","idFan":"' . $fan . '","nombreArtistico":"' . $fila["nombreArtistico"] . '","Estado":"Si"},';
            } else {
                echo '{"idMusico":"' . $fila["usuMusico"] . '","idFan":"' . $fan . '","nombreArtistico":"' . $fila["nombreArtistico"] . '","Estado":"No"},';
            }
        }
        if (isset($prev) & isset($next)) {
            echo '{"Prev":"' . $prev . '","Next":"' . $next . '"}]';
        } else if (isset($prev)) {
            echo '{"Prev":"' . $prev . '"}]';
        }else if(isset($next)){
            echo '{"Next":"' . $next . '"}]';
        }else{
            echo "{}]";
        }
    }
}

function totalMusicosByGenre($genero) {
    $c = conectar();
    $select = "select count(*) as total from musico WHERE genero = $genero;";
    $result = mysqli_query($c, $select);
    $fila = mysqli_fetch_assoc($result);
    desconectar($c);
    return $fila["total"];
}
if (isset($_POST['idGenero'])) {
    $genero = $_POST['idGenero'];
    $nombreGenero = selectNombreGenero($genero);
    echo '{"nombreGenero":"' . utf8_encode($nombreGenero) . '"}';
}

function selectConciertos($inicio, $cantidad) {
    $conexion = conectar();
    $select = "SELECT idConcierto,usuario,nombreConcierto,nombreGenero,nombreArtistico,tarifa,fecha,hora FROM concierto 
            INNER JOIN usuario ON usuario.idUsuario = concierto.local 
            INNER JOIN musico ON musico.usuMusico = concierto.musico
            INNER JOIN genero ON genero.idGenero = concierto.genero WHERE fecha < CURDATE() and estado = 'Aceptado' limit $inicio, $cantidad";
    $result = mysqli_query($conexion, $select);
    if (mysqli_num_rows($result) == 0) {
        $result = NULL;
    }
    desconectar($conexion);
    return $result;
}

function totalConciertosPasados() {
    $c = conectar();
    $select = "select count(*) as cantidad from concierto where concierto.estado = 'Aceptado' and concierto.fecha < curdate()";
    $result = mysqli_query($c, $select);
    $fila = mysqli_fetch_assoc($result);
    desconectar($c);
    return $fila["cantidad"];
}

function selectNombreGenero($genero) {
    $conexion = conectar();
    $select = "SELECT nombreGenero as nombre FROM genero WHERE idGenero = $genero";
    $resultado = mysqli_query($conexion, $select);
    $fila = mysqli_fetch_assoc($resultado);
    desconectar($conexion);
    return $fila["nombre"];
}

function selectMusicos1() {
    $conexion = conectar();
    $select = "SELECT * FROM musico WHERE genero = 1";
    $result = mysqli_query($conexion, $select);
    if (mysqli_num_rows($result) == 0) {
        $result = NULL;
    }
    desconectar($conexion);
    return $result;
}

function isVote($fan, $musico) {
    $conexion = conectar();
    $select = "SELECT * FROM fanvotamusico WHERE fan = $fan AND musico = $musico";
    $result = mysqli_query($conexion, $select);
    if (mysqli_num_rows($result) == 1) {
        $result = TRUE;
    } else {
        $result = FALSE;
    }
    desconectar($conexion);
    return $result;
}

function isVoteConc($fan, $concierto) {
    $conexion = conectar();
    $select = "SELECT * FROM fanvotaconcierto WHERE fan = $fan AND idConcierto = $concierto";
    $result = mysqli_query($conexion, $select);
    if (mysqli_num_rows($result) == 1) {
        $result = TRUE;
    } else {
        $result = FALSE;
    }
    desconectar($conexion);
    return $result;
}

function selectMusicosByGenero($genero, $limit) {
    $conexion = conectar();
    $select = "SELECT * FROM musico WHERE genero = $genero LIMIT $limit,5";
    $result = mysqli_query($conexion, $select);
    if (mysqli_num_rows($result) == 0) {
        $result = NULL;
    }
    desconectar($conexion);
    return $result;
}

//Buscador en página fan
if (isset($_POST["buscadormusicos"])) {
    buscadormusico($_POST["buscadormusicos"]);
} else if (isset($_POST["buscadorlocales"])) {
    buscadorlocal($_POST["buscadorlocales"]);
}

//Buscar Músicos
function buscadormusico($nombremusico) {
    $conexion = conectar();
    $musicos = [];
    if ($nombremusico !== "") {
        $select = "select nombreArtistico, usuMusico from musico where nombreArtistico like '%$nombremusico%'";
        $resultado = mysqli_query($conexion, $select);
        while ($fila = mysqli_fetch_array($resultado)) {
            $musicos[] = $fila;
        }
    }
    desconectar($conexion);
    echo json_encode($musicos);
}

//Buscar Local
function buscadorlocal($nombrelocal) {
    $conexion = conectar();
    $locales = [];
    if ($nombrelocal !== "") {
        $select = "select nombreLocal, usuLocal from local where nombreLocal like '%$nombrelocal%'";
        $resultado = mysqli_query($conexion, $select);
        while ($fila = mysqli_fetch_array($resultado)) {
            $locales[] = $fila;
        }
    }
    desconectar($conexion);
    echo json_encode($locales);
}

function selectNombre($user) {
    $conexion = conectar();
    $select = "SELECT nombreLocal FROM local where usuLocal='$user'";
    $resultado = mysqli_query($conexion, $select);
    $fila = mysqli_fetch_assoc($resultado);
    desconectar($conexion);
    return $fila["idUsuario"];
}

////////////////////////////////////////////////////////////////////////////////
function conciertosMusico($idMusico) {
    $conexion = conectar();
    $select = "select concierto.nombreConcierto, nombreGenero, 
               concierto.fecha, nombreLocal, concierto.estado, 
               provincia, ciudad.nombreCiudad, concierto.img from concierto
               inner join usuario on usuario.idUsuario = concierto.local 
               inner join ciudad on ciudad.idCiudad = usuario.ciudad
               inner join local on usuario.idUsuario = local.usuLocal 
               inner join genero on genero.idGenero=concierto.genero
               where estado='aceptado' and fecha>=curdate() 
               and concierto.musico = '$idMusico' order by fecha asc";
    $resultado = mysqli_query($conexion, $select);
    if (mysqli_num_rows($resultado) == 0) {
        $resultado = 0;
    }
    desconectar($conexion);
    return $resultado;
}

function conciertosLocal($idUsuario) {
    $conexion = conectar();
    $select = "select concierto.nombreConcierto, concierto.estado, 
               concierto.fecha,genero.nombreGenero, musico.nombreArtistico, 
               ciudad.provincia, ciudad.nombreCiudad, concierto.img from concierto
               inner join genero on genero.idGenero = concierto.genero
               inner join musico on musico.usuMusico = concierto.musico
               inner join usuario on concierto.local = usuario.idUsuario
               inner join ciudad on usuario.ciudad = ciudad.idCiudad
               where estado='Aceptado' and fecha>=curdate() 
               and usuario.idUsuario = '$idUsuario' order by fecha asc";
    $resultado = mysqli_query($conexion, $select);
    if (mysqli_num_rows($resultado) == 0) {
        $resultado = 0;
    }
    desconectar($conexion);
    return $resultado;
}

//Mostrar datos músico
function datosMusico($idMusico) {
    $conexion = conectar();
    $select = "select usuario.imagen, musico.web, genero.nombreGenero,
               musico.numComponentes from musico
               inner join genero on genero.idGenero=musico.genero
               inner join usuario on usuMusico=usuario.idUsuario
               where usuMusico='$idMusico'";
    $resultado2 = mysqli_query($conexion, $select);
    desconectar($conexion);
    return $resultado2;
}

function datosLocal($idUsuario) {
    $conexion = conectar();
    $select = "select local.aforo, ciudad.nombreCiudad, ciudad.provincia, 
               usuario.direccion, usuario.telefono, 
               usuario.imagen from local
               inner join usuario on usuario.idUsuario=local.usuLocal
               inner join ciudad on usuario.ciudad = ciudad.idCiudad
               where usuario.idUsuario='$idUsuario'";
    $resultado2 = mysqli_query($conexion, $select);
    desconectar($conexion);
    return $resultado2;
}
