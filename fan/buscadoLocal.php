<?php
session_start();
require_once '../funciones.php';
require_once 'fanFunctions.php';
?>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="../css/comun.css" rel="stylesheet" type="text/css"/>
        <link href="../css/buscador.css" rel="stylesheet" type="text/css"/>
        <title></title>
    </head>
    <body>
        <header id="encabezado">
            <div id="titulo" onclick="home()">
                <h1>OhhMusic</h1>
            </div>                               
        </header>
        <div class="contenido">
            <?php
            if (isset($_SESSION["user"])) {
                if (isset($_POST['nombreLocal'])) {
                    $nombre = $_POST['nombreLocal'];
                    $idLocal = $_POST['usuLocal'];
                    $user = $_SESSION["user"];
                }
            }
            ?>
            <div class="datos">                        
                <?php
                $resultado2 = datosLocal($idLocal);
                while ($fila = mysqli_fetch_array($resultado2)) {
                    extract($fila);
                    ?>
                    <div id="foto">  
                        <?php
                        if ($imagen != "") {
                            ?>
                            <img src='../imagesUser/<?php echo $imagen ?>' width="200px" height="200px"/> 
                        <?php } else { ?>
                            <td><img src='../img/icon-user.png' width="200px" height="200px"/></td>
                        <?php } ?>                                                 
                    </div>
                    <div id="table">
                        <h1><?php echo $nombre ?></h1>
                        <table>
                            <tr>                                
                                <td><b>Aforo</b></td>
                                <td><?php echo $aforo ?></td>
                            </tr>
                            <tr>
                                <td><b>Provincia</b></td>
                                <td><?php echo $provincia ?></td>
                            </tr>
                            <tr>
                                <td><b>Ciudad</b></td>
                                <td><?php echo $nombreCiudad ?></td>
                            </tr>
                            <tr>
                                <td><b>Dirección</b></td>
                                <td><?php echo $direccion ?></td>
                            </tr>
                            <tr>
                                <td><b>Télefono</b></td>
                                <td><?php echo $telefono ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
            <div id="datosConcierto">
                <div id="concierto">
                    <h2>Próximos conciertos</h2>  
                    <div class="hr"></div>                     
                    <?php
                    $resultado = conciertosLocal($idLocal);
                    if ($resultado !== 0) {
                        ?>
                        <table class="table_conciertos">
                            <tr>
                                <td></td>
                                <td><b>Concierto</b></td>
                                <td><b>Género</b></td>
                                <td><b>Fecha</b></td>
                                <td><b>Local</b></td>
                                <td><b>Provincia</b></td>
                                <td><b>Ciudad</b></td>
                            </tr>
                            <tr>
                                <?php
                                while ($fila = mysqli_fetch_array($resultado)) {
                                    extract($fila);
                                    ?>                            
                                    <?php
                                    if ($img != "") {
                                        ?>
                                        <td><img src='../imagesUser/<?php echo $img ?>' width="50px" height="50px"/></td>
                                    <?php } else { ?>
                                        <td><img src='../img/Sin_imagen.png' width="50px" height="50px"/></td>
        <?php } ?>                             
                                    <td><?php echo $nombreConcierto ?></td>
                                    <td><?php echo $nombreGenero ?></td>
                                    <td><?php echo $fecha ?></td>
                                    <td><?php echo $nombreArtistico ?></td>
                                    <td><?php echo $provincia ?></td>
                                    <td><?php echo $nombreCiudad ?></td>
                                </tr>

                        <?php }
                        ?>
                        </table>
                    <?php } else {
                        ?>
                        <h3>No hay conciertos confirmados</h3>                               
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <input id="atras" type="button" value="Volver atrás" onclick="history.back()">
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
        <script src="../archivosJSyJQUERY/arriba.js" type="text/javascript"></script>
    </body>
</html>
