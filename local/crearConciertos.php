<?php
session_start();
require_once 'funcionesLocal.php';
ob_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="../archivosJSyJQUERY/jquery-3.2.1.js" type="text/javascript"></script>        
        <link href="../css/comun.css" rel="stylesheet" type="text/css"/>
        <link href="../css/right-nav-style.css" rel="stylesheet" type="text/css"/>
        <link href="../css/perfiles.css" rel="stylesheet" type="text/css"/>  
        <link href="../css/crearConcierto.css" rel="stylesheet" type="text/css"/>
        <title>Crear Conciertos</title>
    </head>
    <body>
        <?php
        if (isset($_SESSION["type"])) {
            if (($_SESSION["type"]) == 'Local') {
                $user = $_SESSION["user"];
                $type = $_SESSION["type"];
                $fecha = selectFecha();
                ?>
                <script>
                    function home() {
                        window.location.replace("../index.php");
                    }
                </script>
                <header id="encabezado">
                    <div id="titulo" onclick="home()">
                        <h1>OhhMusic</h1>
                    </div>                    
                                                 
                </header>
                <input type="checkbox" id="nav-toggle" hidden>
                <nav class="nav">
                    <label for="nav-toggle" class="nav-toggle" onclick></label>
                    <h2 class="logo"> 
                        <?php
                        $img = mostrarImg($user);
                        if ($img != NULL) {
                            ?>
                            <img src="../imagesUser/<?php echo $img ?>"  id="imgPefil" alt=""/>
                        <?php } else { ?>
                            <img src="../img/icon-user.png" width="150" height="150" alt=""/>  
                        <?php } ?>   
                    </h2>
                    <ul>
                        <li>
                            <i class="fa fa-home"></i>
                            <a href="../index.php">Pagina principal</a>  
                        <li>
                            <i class="fa fa-address-card"></i>
                            <a href="../local/local.php"> Volver al perfil</a>
                        <li>
                            <i class="fa fa-cogs"></i>
                            <a href="../editar/editarPerfil.php"> Modificar perfil</a>                    
                        <li>
                            <i class="fa fa-plus-square"></i>
                            <a href="../local/crearConciertos.php">Crear conciertos</a>                                           
                            <form method="POST">                             
                                <input id="input" type="submit" name="close" value="Cerrar sesion">
                            </form>
                    </ul>
                </nav>
                <section>
                    <div id="contenido">
                        <h1>CREACIÓN DE CONCIERTOS</h1>
                        <div class="hr"></div>
                        <form method="POST" action="" enctype="multipart/form-data">
                            <table>
                                <tr><td><label>Nombre</label></td>
                                    <td><input type="text" name="nombre" required=""/></td></tr> 
                                <tr><td><label>Género</label></td>
                                    <td><select name="genero" required="">
                                            <?php
                                            $generos = selectGeneros();
                                            while ($fila = mysqli_fetch_assoc($generos)) {
                                                echo "<option value='" . $fila["idGenero"] . "'>";
                                                echo utf8_encode($fila["nombreGenero"]);
                                                echo "</option>";
                                            }
                                            ?>
                                        </select></td>   </tr>                        
                                <tr><td><label>Tarifa </label></td>
                                    <td><input type="number" name="precio" step="any" min="0" required=""/></td></tr>
                                <tr><td><label>Fecha</label></td>
                                    <td><input type="date" name="fecha" min="<?php echo $fecha ?>" required=""/></td></tr>
                                <tr><td><label>Hora</label></td>
                                    <td><input type="time" name="hora" required=""/></td></tr>
                                <tr><td><label>Cartel del concierto</label></td>
                                    <td><input type="file" name="img" size="150" value=""/></td></tr>
                                <tr><td colspan="2"><input type="submit" name="crear" id="input"/></td></tr>
                            </table>
                        </form>
                        <?php
                        if (isset($_POST["crear"])) {
                            $nombre = $_POST["nombre"];
                            $genero = $_POST["genero"];
                            $precio = $_POST["precio"];
                            $fecha = $_POST["fecha"];
                            $hora = $_POST["hora"];
                            $nombre_img = $_FILES['img']['name'];
                            $tipoImg = $_FILES['img']['type'];
                            $tamano = $_FILES['img']['size'];
                            $tmp = $_FILES['img']['tmp_name'];
                            $img = imagenConcierto($nombre_img, $tipoImg, $tamano, $user, $tmp);
                            if ($img[0] === 0) {
                                echo"<script type=\"text/javascript\">alert('$img[1]'); </script>";
                            } else {
                                $idLocal = selectIdByUser($user);
                                $resultado = createConcierto($nombre, $genero, $precio, $fecha, $hora, $idLocal, $img);
                                if ($resultado == "ok") {
                                    echo "Concierto creado";
                                } else {
                                    echo "ERROR";
                                }
                            }
                        }
                        ?>
                    </div>
                </section>
                <?php
                if (isset($_POST["close"])) {
                    session_destroy();
                    header("Location: ../index.php");
                    ob_end_flush();
                }
            } else {
                echo "No tienes los permisos para ver su contenido";
                header("Refresh:2; url=../index.php");
                ob_end_flush();
            }
        } else {
            echo "No hay ningún usuario logueado";
            header("Refresh:2; url=../index.php");
            ob_end_flush();
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
        </div>        
    </body>
</html>
