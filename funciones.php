<?php

//CAMBIAR LA CONTRASEÑA!!!
// Función que se conecta a una base de datos
function conectar() {
    $conexion = mysqli_connect("172.16.2.93", "grupo4", "grupo4", "daw1mgrupo4");
    if (!$conexion) {
        // Acabamos el programa dando msg de error
        die("No se ha podido establecer la conexión con el servidor");
    }
    return $conexion;
}

//function conectar() {
//    $connect = mysqli_connect("localhost", "root", "", "daw1mgrupo4");
//    if (!$connect) {
//        die("No se ha podido establecer la conexion con el servidor");
//    }
//    return $connect;
//}

//Validar nombre usuario
function validarNombre($user) {
    $conexion = conectar();
    $select = "select usuario from usuario where usuario='$user'";
    $resultado = mysqli_query($conexion, $select);
    desconectar($conexion);
    return mysqli_num_rows($resultado) == 1;
}

//Validar el nombre y contraseña
function validarUsuario($user, $pasw) {
    $conexion = conectar();
    $select = "select psw from usuario where usuario='$user'";
    $resultado = mysqli_query($conexion, $select);
    if (mysqli_num_rows($resultado) == 1) {
        $fila = mysqli_fetch_assoc($resultado);
        extract($fila);
        desconectar($conexion);
        return password_verify($pasw, $psw);
    } else {
        return false;
    }
}

//Insertar los datos del usuario
function insertarAllDatesUser($user, $password, $name, $surname, $email, $tlf, $city, $dir, $img, $tipo, $fan1, $fan2, $musico1, $musico2, $musico3, $musico4, $local1, $local2) {
    $conexion = conectar();
    mysqli_autocommit($conexion, FALSE);
    mysqli_begin_transaction($conexion);
    $pas = password_hash($password, PASSWORD_DEFAULT);
    $insert = "INSERT INTO usuario (usuario, psw, nombre, apellidos, email, telefono, ciudad, direccion, imagen, tipo) "
            . "VALUES ('$user','$pas', '$name', '$surname', '$email', $tlf, $city, '$dir', '$img', '$tipo')";
    $resultado = mysqli_query($conexion, $insert);
    $id = mysqli_insert_id($conexion);
    if ($tipo == "Fan") {
        $insert = "INSERT INTO fan VALUES ($id, '$fan1', '$fan2')";
        $resultado2 = mysqli_query($conexion, $insert);
    } else if ($tipo == "Musico") {
        $insert = "INSERT INTO musico VALUES ($id, '$musico1', '$musico2', $musico3, '$musico4')";
        $resultado2 = mysqli_query($conexion, $insert);
    } else {
        $insert = "INSERT INTO local VALUES ($id, '$local1', $local2)";
        $resultado2 = mysqli_query($conexion, $insert);
    }
    if ($resultado && $resultado2) {
        mysqli_commit($conexion);
    } else {
        mysqli_rollback($conexion);
    }
    mysqli_autocommit($conexion, TRUE);
    desconectar($conexion);
    return $resultado2;
}

function imagen($nombre_img, $tipo, $tamano, $user, $tmp) {
    $resultado = array();
    $nombre_nuevo = $user . "_" . time() . "_" . date("Y-m-d", time());
//Si existe imagen y tiene un tamaño correcto
    if (($nombre_img == !NULL) && ($tamano <= 300000)) {
        //indicamos los formatos que permitimos subir a nuestro servidor
        if (($tipo == "image/gif") || ($tipo == "image/jpeg") || ($tipo == "image/jpg") || ($tipo == "image/png")) {
            // Ruta donde se guardarán las imágenes que subamos
            $directorio = './imagesUser/';
            $ruta = $directorio . $nombre_nuevo;
            // Muevo la imagen desde el directorio temporal a nuestra ruta indicada anteriormente
            if (move_uploaded_file($tmp, $ruta)) {
                array_push($resultado, 1, $nombre_nuevo);
                return $resultado;
            } else {
                array_push($resultado, 0, "Error al registrar usuario");
                return $resultado;
            }
        } else {
            //si no cumple con el formato
            array_push($resultado, 0, "Error al registrar usuario");
                return $resultado;
        }
    } else {
        //si existe la variable pero se pasa del tamaño permitido
        if ($nombre_img == !NULL) {            
             array_push($resultado, 0, "Error al registrar usuario");
                return $resultado;
        }
    }
}

//Seleccionar ID de usuario
function selectIdByUser($user) {
    $conexion = conectar();
    $select = "SELECT idUsuario FROM usuario where usuario='$user'";
    $resultado = mysqli_query($conexion, $select);
    $fila = mysqli_fetch_assoc($resultado);
    desconectar($conexion);
    return $fila["idUsuario"];
}

//Seleccionar tipo de usuario
function recojerCategoria($nombre) {
    $conexion = conectar();
    $select = "select tipo from usuario where usuario='$nombre'";
    $resultado = mysqli_query($conexion, $select);
    $fila = mysqli_fetch_assoc($resultado);
    extract($fila);
    desconectar($conexion);
    return $tipo;
}

//Funcion que nos devuelve listado de generos
function selectGeneros() {
    $connect = conectar();
    $select = "SELECT * from genero";
    $result = mysqli_query($connect, $select);
    desconectar($connect);
    return $result;
}

// Función que nos devuelve cantidad determinada de los conciertos
function selectConcierto($inicio, $cantidad) {
    $c = conectar();
    $select = "SELECT img,nombreConcierto,fecha,hora,nombreArtistico,nombreLocal, nombreCiudad FROM concierto "
            . "INNER JOIN usuario ON usuario.idUsuario = concierto.musico "
            . "INNER JOIN musico ON musico.usuMusico = usuario.idUsuario "
            . "INNER JOIN local ON local.usuLocal = concierto.local "
            . "INNER JOIN ciudad ON ciudad.idCiudad = usuario.ciudad where concierto.estado = 'Aceptado' "
            . "and concierto.fecha>=curdate() order by concierto.fecha asc limit $inicio, $cantidad";
    $result = mysqli_query($c, $select);
    desconectar($c);
    return $result;
}

// Función que devuelve cuántos conciertos hay en la bbdd
function totalConciertos() {
    $c = conectar();
    $select = "select count(*) as cantidad from concierto where concierto.estado = 'Aceptado' and concierto.fecha>=curdate()";
    $result = mysqli_query($c, $select);
    $fila = mysqli_fetch_assoc($result);
    desconectar($c);
    return $fila["cantidad"];
}

//Listado de locales ordenador por ciudad
function listarLocales() {
    $conexion = conectar();
    $select = "select local.nombreLocal, ciudad.nombreCiudad, local.aforo from ((usuario inner join local on usuario.idUsuario = local.usuLocal) 
inner join ciudad on usuario.ciudad = ciudad.idCiudad) order by ciudad.nombreCiudad limit 10";
    $resultado = mysqli_query($conexion, $select);
    desconectar($conexion);
    return $resultado;
}

//Listado de musicos ordenados por votos
function listarMusicos() {
    $conexion = conectar();
    $select = "select musico.nombreArtistico, count(musico.nombreArtistico) as total_votos from 
((fanvotamusico inner join musico on fanvotamusico.musico = musico.usuMusico) 
inner join fan on fanvotamusico.fan = fan.usuFan) group by musico.nombreArtistico order by total_votos desc limit 10";
    $resultado = mysqli_query($conexion, $select);
    desconectar($conexion);
    return $resultado;
}

//Conciertos creados
function conciertosCreados() {
    $conexion = conectar();
    $select = "select concierto.nombreConcierto, genero.nombreGenero, concierto.tarifa, concierto.fecha, concierto.hora, 
        musico.nombreArtistico, local.nombreLocal, concierto.estado from (((concierto inner join musico 
on concierto.musico = musico.usuMusico) inner join local on concierto.local = local.usuLocal) inner join genero 
on concierto.genero = genero.idGenero);";
    $resultado = mysqli_query($conexion, $select);
    desconectar($conexion);
    return $resultado;
}

//Mostar imagen de perfil
function mostrarImg($user) {
    $conexion = conectar();
    $select = "select imagen from usuario where usuario= '$user'";
    $resultado = mysqli_query($conexion, $select);
    if (mysqli_num_rows($resultado) == 1) {
        $fila = mysqli_fetch_assoc($resultado);
        extract($fila);
        desconectar($conexion);
        return $imagen;
    } else {
        return NULL;
    }
}

// Función que cierra una conexión
function desconectar($conexion) {
    mysqli_close($conexion);
}
