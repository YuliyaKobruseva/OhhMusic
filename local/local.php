<?php
session_start();
require_once '../funciones.php';
require_once 'funcionesLocal.php';
ob_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>local</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="../archivosJSyJQUERY/jquery-3.2.1.js" type="text/javascript"></script>
        <script src="conciertos.js" type="text/javascript"></script>
        <link href="../css/comun.css" rel="stylesheet" type="text/css"/>
        <link href="../css/right-nav-style.css" rel="stylesheet" type="text/css"/>
        <link href="../css/perfiles.css" rel="stylesheet" type="text/css"/>
        <link href="../css/tablasConciertosLocal.css" rel="stylesheet" type="text/css"/>
        <link href="../css/musicosInscritos.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <?php
        anularConcierto();
        if (isset($_SESSION["user"])) {
            if (($_SESSION["type"]) == 'Local') {
                $user = $_SESSION["user"];
                $idUser = selectIdByUser($user);
                $concAcep = selectConciertosByLocal($idUser, "Aceptado");
                $concAnulado = selectConcAnulados($idUser);
                $conciertos = selectConciertosByLocal($idUser, "");
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
                        if (isset($img)) {
                            if ($img != NULL || $img != "") {
                                ?>
                                <img src="../imagesUser/<?php echo $img ?>"  id="imgPefil" alt=""/>
                            <?php } else { ?>
                                <img src="../img/icon-user.png" width="150" height="150" alt=""/>  
                            <?php } ?>
                            <?php } else {
                            ?>
                            <img src = "../img/icon-user.png" width = "150" height = "150" alt = ""/>
                            <?php
                        }
                        ?>
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
                            <i class="fa fa-plus-square"></i>
                            <a href="../local/crearConciertos.php">Crear conciertos</a>
                            <form method="POST">
                                <input id="input" type="submit" name="close" value="Cerrar sesión">
                            </form>
                        </li>
                    </ul>
                </nav>
                <section>
                    <div class="contenido">
                        <div id="tablasLocal">
                            <button class="tablink" onclick="openPage('acept', this, 'rgba(153, 203, 190, 0.5)')" id="defaultOpen">Conciertos aceptados</button><button class="tablink" onclick="openPage('cansel', this, 'rgba(249, 195, 186, 0.5)')">Conciertos anulados</button><button class="tablink" onclick="openPage('confirm', this, 'rgba(143, 92, 152, 0.5)')">Conciertos pendientes a confirmar</button>
                            <div id="acept" class="tabcontent">
                                <?php
                                if ($concAcep == NULL) {
                                    ?>
                                    <p>No hay conciertos confirmados</p>
                                    <?php
                                } else {
                                    ?>
                                    <h1>CONCiERTOS ACEPTADOS</h1>
                                    <table>
                                        <tr>
                                            <th>Nombre</th><th>Género</th><th>Tarifa</th><th>Fecha</th><th>Hora</th><th>Músico</th>
                                        </tr>
                                        <?php
                                        while ($fila = mysqli_fetch_assoc($concAcep)) {
                                            echo "<tr>";
                                            foreach ($fila as $dato) {
                                                echo "<td>$dato</td>";
                                            }
                                            echo "</tr>";
                                        }
                                        ?>
                                    </table>
                                <?php }
                                ?>
                            </div>
                            <div id="cansel" class="tabcontent">
                                <?php
                                if ($concAnulado == NULL) {
                                    ?>
                                    <p>No hay conciertos anulados</p>
                                    <?php
                                } else {
                                    ?>
                                    <h1>CONCiERTOS ANULADOS</h1>
                                    <table>
                                        <tr>
                                            <th>Nombre</th><th>Género</th><th>Tarifa</th><th>Fecha</th><th>Hora</th>
                                        </tr>
                                        <?php
                                        while ($fila = mysqli_fetch_assoc($concAnulado)) {
                                            echo "<tr>";
                                            foreach ($fila as $dato) {
                                                echo "<td>$dato</td>";
                                            }
                                            echo "</tr>";
                                        }
                                        ?>
                                    </table>
                                    <?php
                                }
                                ?>
                            </div>
                            <div id="confirm" class="tabcontent">
                                <?php
                                if ($conciertos == NULL) {
                                    ?>
                                    <p>Todavia no has creado ningun concierto</p>
                                    <?php
                                } else {
                                    ?>
                                    <h1>CONCiERTOS PENDiENTES A CONFiRMAR</h1>
                                    <table>
                                        <tr>
                                            <th>Nombre</th><th>Género</th><th>Tarifa</th><th>Fecha</th><th>Hora</th><th>Ver Músicos</th><th>Eliminar</th>
                                        </tr>
                                        <?php
                                        while ($fila = mysqli_fetch_assoc($conciertos)) {
                                            ?>
                                            <tr>
                                                <td><?php echo $fila['nombreConcierto'] ?></td>
                                                <td><?php echo $fila['nombreGenero'] ?></td>
                                                <td><?php echo $fila['tarifa'] ?></td>
                                                <td><?php echo $fila['fecha'] ?></td>
                                                <td><?php echo $fila['hora'] ?></td>
                                                <td><button onclick="verMusicos(<?php echo $fila['idConcierto'] ?>)"> <i class="fa fa-eye  fa-1x"></i></button></td>
                                                <td><button onclick="delConcierto(<?php echo $fila['idConcierto'] ?>)"><i class="fa fa-minus-circle fa-1x"></i></button></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </table>
                                <?php } ?>
                            </div>
                            <div id="myModal" class="modal">
                                <div id="showMusicos" class="modal-content">
                                    <span class="close">&times;</span>
                                    <h1>MÚSICOS INSCRITOS EN EL CONCIERTO</h1>
                                    <div class="hr"></div>
                                    <table id="inscripciones">
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
                            header("Location: ../index.php");
                            ob_end_flush();
                        }
                    } else {
                        echo "No hay ningún usuario logueado";
                        header("Refresh:2; url=../index.php");
                        ob_end_flush();
                    }
                    ?>
                    <script src="../archivosJSyJQUERY/arriba.js" type="text/javascript"></script>
                    <script src="../archivosJSyJQUERY/jsRanking.js" type="text/javascript"></script>
                    <script src="../archivosJSyJQUERY/modalConciertos.js" type="text/javascript"></script>
                    </body>
                    </html>
