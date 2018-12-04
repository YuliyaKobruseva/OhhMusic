<!DOCTYPE html>
<?php
session_start();
ob_start();
require_once 'funciones.php';
require_once 'provincias.php';
?>
<html>
    <head>
        <meta charset="UTF-8">
        <link href="css/comun.css" rel="stylesheet" type="text/css"/>
        <link href="css/datosUser.css" rel="stylesheet" type="text/css"/>
        <link href="css/estiloContenidoHome.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="archivosJSyJQUERY/jquery-3.2.1.js" type="text/javascript"></script>
        <script src="archivosJSyJQUERY/provinciasCiudad.js" type="text/javascript"></script>
        <title>OhhMusic</title>
    </head>
    <body>
        <header id="encabezado">
            <div id="titulo">
                <h1>OhhMusic</h1>
            </div>                     
            <div class="login">  
                <div class="popup_box">
                    <a href="#" id="myLink">Iniciar sesión</a>
                    <div id="myModalLink" class="modal">            
                        <div class="modal-content">
                            <span class="closeLink">&times;</span>
                            <form method="POST">
                                <h3>Iniciar sesion</h3>  
                                <div class="hr"></div>
                                <input type="text" name="user" required placeholder="User"></p>                                
                                <input type="password" name="psw"  placeholder="Contraseña"required></p>
                                <input type="submit" name="login" value="Entrar">
                            </form>
                            <?php
                            if (isset($_POST["login"])) {
                                $user = $_POST["user"];
                                $psw = $_POST["psw"];
                                if (validarUsuario($user, $psw)) {
                                    $_SESSION["user"] = $user;
                                    $type = recojerCategoria($user);
                                    $_SESSION["type"] = $type;
                                    if ($type == "Local") {
                                        header("Location: local/local.php");
                                    } else if ($type == "Musico") {
                                        header("Location: musico/musico.php");
                                    } else if ($type == "Fan") {
                                        header("Location: fan/seguidor.php");
                                    }
                                } else {
                                    echo "Usuario o contraseña incorrecta";
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <a href="#" id="myBtn">Crear cuenta</a>       
                    <div id="myModalButton" class="modal">            
                        <div class="modal-content">
                            <span class="closeBtn">&times;</span>
                            <form method="POST" >
                                <h3>DARME DE ALTA</h3>
                                <div class="hr"></div>
                                <div>
                                    Tu perfil:                         
                                    <input type="radio" name="type" value="Fan">Fan
                                    <input type="radio" name="type" value="Musico">Musico
                                    <input type="radio" name="type" value="Local">Local            
                                    <input type="email" name="email" value="" required="" placeholder="Correo electrónico" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">                                                      
                                    <input pattern=".{3,}" title="Tu contraseña debere tener al menos 6 letras" type="password" name="password" value="" required="" placeholder="Contraseña">
                                    <input type="submit" value="Darme de alta" name="alta" class="button">                                
                                </div>
                            </form>
                        </div>
                    </div>
                </div> 
            </div>             
        </header>
        <?php
        if (isset($_POST["alta"])) {
            $email = $_POST["email"];
            $password = $_POST["password"];
            $tipo = $_POST["type"];
            $_SESSION['type'] = $tipo;
            if (isset($_SESSION['type'])) {
                if ($tipo == "Fan" || $tipo == "Musico" || $tipo == "Local") {
                    ?>
                    <form method="POST" id="form" enctype="multipart/form-data">
                        <h2>Informacion personal del usuario</h2>
                        <div class="hr"></div>
                        <div>
                            <label>Usuario: </label><input type="text" name="user" value="" required="" placeholder="Usuario...">                        
                            <label>Nombre: </label><input type="text" name="name" value="" required="" placeholder="Nombre...">                        
                            <label>Apellido: </label><input type="text" name="surname" value="" required="" placeholder="Apellido...">
                            <br>
                            <?php if ($tipo == "Fan") { ?>
                                <label>Fecha de nacimiento: </label><input type="date" name="date" id="date" required="">
                                <label>Sexo: </label><input type="radio"  name="sex" id="sex" value="H" required=""> Hombre
                                <input type="radio"  name="sex" value="M" id="sex" required=""> Mujer<br>
                            <?php }
                            ?>                        
                            <label>Teléfono: </label><input type="text" name="tlf" value="" required="" placeholder="Telefono..." pattern="[o-9]" maxlength="9" minlength="9">
                            <br>
                            <label>Provincia: </label><select id="provincia" name="provin" onchange="cogeProvincia()"> 
                                <?php
                                $provincias = selectProvincias();
                                while ($fila = mysqli_fetch_assoc($provincias)) {
                                    echo "<option>";
                                    echo $fila["provincia"];
                                    echo "</option>";
                                }
                                ?>    
                            </select> 
                            <div id="divCiudad">                            
                                Ciudad: <select id="ciudad" name="city" >
                                    <?php
                                    $Alava = selectCiudad("Alava");
                                    while ($fila2 = mysqli_fetch_assoc($Alava)) {
                                        ?>
                                        <option value="<?php echo $fila2["idCiudad"]; ?>">
                                            <?php echo $fila2["nombreCiudad"] ?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>                        
                            <label>Dirección: </label><input type="text" name="dir" value="" <?php if ($tipo == "Local") { ?> required="" <?php } ?> placeholder="Direccion...">
                            <br>
                            <label>Foto de perfil: </label><input type="file" id="imagen" name="imagen" size="30"/>
                            <br>
                        </div>
                        <?php if ($tipo == "Local") { ?>
                            <h2>Informacion profesional de local</h2>
                            <div class="hr"></div>
                            <div>
                                <label>Nombre local: </label><input type="text" name="localname" value="" required="" placeholder="Nombre local...">
                                <label>Aforo: </label><input type="number" name="aforo" value="" required="" placeholder="Aforo..."><br><br></div>
                            <?php
                        } else if ($tipo == "Musico") {
                            ?>
                            <h2>Informacion profesional del grupo</h2>
                            <div class="hr"></div>
                            <div>
                                <label>Nombre artístico: </label><input type = "text" name = "artname" value = "" required = "" placeholder = "Nombre artistico...">
                                <label>Numero de componentes: </label><input type = "number" name = "numcomp" value = "" required = "" placeholder = "Numero de componentes..." min="1"><br><br>
                                <label>Género de musica: </label><select name="gender" id="gender">
                                    <?php
                                    $generos = selectGeneros();
                                    while ($fila = mysqli_fetch_assoc($generos)) {
                                        echo "<option value='" . $fila["idGenero"] . "'>";
                                        echo utf8_encode($fila["nombreGenero"]);
                                        echo "</option>";
                                    }
                                    ?>    
                                </select>
                                <label>Web-pagina: </label><input type = "url" name = "web" value = "" placeholder = "Web..."><br><br>
                                <?php
                            }
                            ?>
                            <input type="hidden" name="email" value="<?php echo $email ?>">
                            <input type="hidden" name="password" value="<?php echo $password ?>">
                            <input type="hidden" name="type" value="<?php echo $tipo ?>">
                            <input type="submit" value="Crear perfil" name="crear" class="button" id="btnCrearPerf">
                        </div>
                    </form>
                    <?php
                }
            }
        }
        if (isset($_POST['crear'])) {
            $mail = $_POST["email"];
            $psw = $_POST["password"];
            $type = $_POST["type"];
            $_SESSION['type'] = $type;
            $user = $_POST['user'];
            $name = $_POST['name'];
            $surname = $_POST['surname'];
            $email = $_POST['email'];
            $tlf = $_POST['tlf'];
            $city = $_POST['city'];
            $dir = $_POST['dir'];
            $tipo = $_SESSION['type'];
            $nombre_img = $_FILES['imagen']['name'];
            $tipoImg = $_FILES['imagen']['type'];
            $tamano = $_FILES['imagen']['size'];
            $tmp = $_FILES['imagen']['tmp_name'];
            $img = imagen($nombre_img, $tipoImg, $tamano, $user, $tmp);
            if ($img === 0) {
                echo "Ha habido un error al subir el fichero";
                $img = "";
            } else {
                if (validarNombre($user)) {
                    echo "Ya existe usuario con este nombre";
                } else {
                    if ($tipo == "Fan") {
                        $date = $_POST["date"];
                        $sex = $_POST["sex"];
                        $resultado = insertarAllDatesUser($user, $psw, $name, $surname, $mail, $tlf, $city, $dir, $img, $type, $sex, $date, "", "", "", "", "", "");
                        if ($resultado == "ok") {
                            if (validarUsuario($user, $psw)) {
                                $_SESSION["user"] = $user;
                                header("Location: fan/seguidor.php");
                                ob_end_flush();
                            } else {
                                echo "Usuario o contraseña incorrecta";
                            }
                        } else {
                            echo "Error al registrar usuario";
                        }
                    } else if ($tipo == "Musico") {
                        $artname = $_POST["artname"];
                        $web = $_POST["web"];
                        $numcomp = $_POST["numcomp"];
                        $gender = $_POST["gender"];
                        $resultado = insertarAllDatesUser($user, $psw, $name, $surname, $mail, $tlf, $city, $dir, $img, $type, "", "", $artname, $web, $gender, $numcomp, "", "");
                        if ($resultado == "ok") {
                            if (validarUsuario($user, $psw)) {
                                $_SESSION["user"] = $user;
                                header("Location: musico/musico.php");
                                ob_end_flush();
                            } else {
                                echo "Usuario o contraseña incorrecta";
                            }
                        } else {
                            echo "Error al registrar usuario";
                        }
                    } else {
                        $localname = $_POST["localname"];
                        $aforo = $_POST["aforo"];
                        $resultado = insertarAllDatesUser($user, $psw, $name, $surname, $mail, $tlf, $city, $dir, $img, $type, "", "", "", "", "", "", $localname, $aforo);
                        if ($resultado == "ok") {
                            if (validarUsuario($user, $psw)) {
                                $_SESSION["user"] = $user;
                                header("Location: local/local.php");
                                ob_end_flush();
                            } else {
                                echo "Usuario o contraseña incorrecta";
                            }
                        } else {
                            echo "Error al registrar usuario";
                        }
                    }
                }
            }
        }
        ?>
        <button onclick="topFunction()" id="arriba"><i class="fa fa-angle-double-up"></i></button>
        <div id="pie"> 
            <footer>
                <h6><b>© 2018 STUCOM, DAW1M Grupo4</b></h6>
                <ul class="social">
                    <li><a href="https://www.instagram.com/stucom/" title="Síguenos en Instagram" target="_blank" rel="nofollow"><i class="fa fa-instagram"></i></a></li>
                    <li><a href="https://twitter.com/stucom" title="Síguenos en Twitter" target="_blank" rel="nofollow"><i class="fa fa-twitter"></i></a></li>
                    <li><a href="https://www.facebook.com/stucombarcelona" title="Síguenos en Facebook" target="_blank" rel="nofollow"><i class="fa fa-facebook-square"></i></a></li>
                </ul>
            </footer>
            <script src="archivosJSyJQUERY/popup_box.js" type="text/javascript"></script>
            <script src="archivosJSyJQUERY/arriba.js" type="text/javascript"></script>
        </div>

    </body>
</html>
