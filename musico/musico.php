<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
session_start();
require_once '../funciones.php';
require_once '../local/funcionesLocal.php';
ob_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="../archivosJSyJQUERY/jquery-3.2.1.js" type="text/javascript"></script>
        <script src="musico.js" type="text/javascript"></script>
        <link href="../css/comun.css" rel="stylesheet" type="text/css"/>        
        <link href="../css/right-nav-style.css" rel="stylesheet" type="text/css"/>
        <link href="../css/perfiles.css" rel="stylesheet" type="text/css"/>
        <link href="../css/conciertosMusicos.css" rel="stylesheet" type="text/css"/>
        <title></title>
    </head>
    <body>
        <?php
        anularConcierto();
        if (isset($_SESSION["user"])) {
            if (isset($_SESSION["type"])) {
                if (($_SESSION["type"]) == 'Musico') {
                    $user = $_SESSION["user"];
                    $type = $_SESSION["type"];
                    $idUser = selectIdByUser($user);
                    $concAcep = selectConciertosAceptados($idUser);
                    $concPend = selectConcPend($idUser);
                    $concRech = selectConciertosRechazados($idUser);
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
                        <div class="usuario">                    
                            <p>
                                Bienvenido, <?php echo $user ?>! 
                            </p>                   
                        </div>
                    </header> 
                    <input type="checkbox" id="nav-toggle" hidden>
                    <nav class="nav">
                        <label for="nav-toggle" class="nav-toggle" onclick></label>
                        <h2 class="logo"> 
                            <?php
                            $img = mostrarImg($user);
                            if ($img != NULL || $img != "") {
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
                            </li>
                            <li>
                                <i class="fa fa-cogs"></i>
                                <a href="../editar/editarPerfil.php"> Modificar perfil</a>
                            </li>
                            <li>                        
                                <form method="POST">                            
                                    <input id="input" type="submit" name="close" value="Cerrar sesión">
                                </form>
                            </li>
                        </ul>
                    </nav>
                    <section>
                        <div class="contenido">
                            <div class="botones">
                                <button class="tablinks" onclick="openOption(event, 'nextConcert')" id="defaultOpen">Próximos conciertos</button>
                                <button class="tablinks" onclick="openOption(event, 'inscription')">Inscripción a conciertos</button>
                                <button class="tablinks" onclick="openOption(event, 'denied')">Inscripciones rechazadas y anuladas</button>
                            </div>
                            <div id="nextConcert" class="tabcontent">
                                <h1>TUS PRÓXIMOS CONCIERTOS</h1>
                                <div class="hr"></div>
                                <?php
                                if ($concAcep == NULL) {
                                    ?>
                                    <h3>Todavía no has sido aceptado en ningún concierto</h3>
                                    <?php
                                } else {
                                    ?>                                    
                                    <table>
                                        <tr>
                                            <th>Nombre</th><th>Fecha</th><th>Hora</th><th>Nombre Local</th><th>Dirección</th>
                                        </tr>
                                        <?php
                                        while ($fila = mysqli_fetch_assoc($concAcep)) {
                                            ?>
                                            <tr>
                                                <td><?php echo $fila['nombreConcierto'] ?></td>
                                                <td><?php echo $fila['fecha'] ?></td>
                                                <td><?php echo $fila['hora'] ?></td>
                                                <td><?php echo $fila["nombreLocal"] ?></td> 
                                                <td><?php echo $fila["direccion"] ?></td> 
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </table>
                            </div>
                            <div id="inscription" class="tabcontent">
                                <h1>CONCIERTOS EN CURSO</h1>
                                <div class="hr"></div>
                                <?php
                                if ($concPend == NULL) {
                                    ?>
                                    <h3>Todavía no hay conciertos de tu género</h3>
                                    <?php
                                } else {
                                    ?>                                    
                                    <table>
                                        <tr>
                                            <th>Nombre</th><th>Fecha</th><th>Hora</th><th>Lugar</th><th>Dirección</th>
                                        </tr>
                                        <?php
                                        while ($pend = mysqli_fetch_assoc($concPend)) {
                                            $estado = selectEstadoByConcPend($idUser, $pend["idConcierto"]);
                                            if ($estado == NULL) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $pend["nombreConcierto"] ?></td>
                                                    <td><?php echo $pend["fecha"] ?></td>
                                                    <td><?php echo $pend["hora"] ?></td> 
                                                    <td><?php echo $pend["nombreLocal"] ?></td> 
                                                    <td><?php echo $pend["direccion"] ?></td> 
                                                    <td><button onclick="darAltaConcierto(<?php echo $pend["idConcierto"] ?>,<?php echo $idUser ?>)"><i class="fa fa-share-square"></i>Enviar petición</button></td>
                                                </tr>
                                                <?php
                                            } else {
                                                while ($res = mysqli_fetch_assoc($estado)) {
                                                    if ($res["estado"] == "Pendiente") {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $pend["nombreConcierto"] ?></td>
                                                            <td><?php echo $pend["fecha"] ?></td>
                                                            <td><?php echo $pend["hora"] ?></td> 
                                                            <td><?php echo $pend["nombreLocal"] ?></td> 
                                                            <td><?php echo $pend["direccion"] ?></td> 
                                                            <td><button onclick="darBajaConcierto(<?php echo $pend['idConcierto'] ?>,<?php echo $idUser ?>)"><i class="fa fa-times-circle"></i>DAR DE BAJA</button></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </table>
                            </div>
                            <div id="denied" class="tabcontent">
                                <h1>CONCIERTOS RECHAZADOS O ANULADOS</h1>
                                <div class="hr"></div>
                                <?php
                                if ($concRech == NULL) {
                                    ?>
                                    <h3>Todavía no tienes ningún solicitud rechazada o anulada</h3>
                                    <?php
                                } else {
                                    ?>                                    
                                    <table>
                                        <tr>
                                            <th>Nombre</th><th>Fecha</th><th>Hora</th><th>Estado</th>
                                        </tr>
                                        <?php
                                        while ($fila3 = mysqli_fetch_assoc($concRech)) {
                                            ?>
                                            <tr>
                                                <td><?php echo $fila3['nombreConcierto'] ?></td>
                                                <td><?php echo $fila3['fecha'] ?></td>
                                                <td><?php echo $fila3['hora'] ?></td>
                                                <?php if ($fila3['estado'] == "Anulado") { ?>
                                                    <td>Anulado</td>
                                                <?php } else { ?>
                                                    <td>Rechazado</td>
                                                    <?php
                                                }
                                                ?>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </table>
                            </div>                        
                        </div>
                    </section>
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
            }
        } else {
            echo "No hay ningún usuario logueado";
            header("Refresh:2; url=../index.php");
        }
        ?>
        <script src="../archivosJSyJQUERY/arriba.js" type="text/javascript"></script>
        <script src="../archivosJSyJQUERY/conciertosMusicos.js" type="text/javascript"></script>
    </body>
</html>
