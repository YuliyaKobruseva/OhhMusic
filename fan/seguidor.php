<!DOCTYPE html>
<?php
session_start();
require_once '../funciones.php';
require_once './fanFunctions.php';
ob_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="../css/comun.css" rel="stylesheet" type="text/css"/>
        <link href="../css/right-nav-style.css" rel="stylesheet" type="text/css"/>
        <link href="../css/perfiles.css" rel="stylesheet" type="text/css"/>
        <link href="../css/buscador.css" rel="stylesheet" type="text/css"/>
        <script src="jquery-3.2.1.js" type="text/javascript"></script>
        <script src="votar.js" type="text/javascript"></script>
        <script src="buscador.js" type="text/javascript"></script>
        <link href="../css/fanDivsVotar.css" rel="stylesheet" type="text/css"/>
        <title>fan</title>
    </head>
    <body>
        <?php
        if (isset($_SESSION["user"])) {
            $user = $_SESSION["user"];
            $type = $_SESSION["type"];
            $fan = selectIdByUser($user);
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
                    </li>
                    <li>
                        <i class="fa fa-address-book"></i>                                       
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
                <div id="contenido">                    
                    <div id="buscarmusico">                        
                        <div id="buscarmusicos">
                            <label><b>Buscar musicos...</b></label><input type="text" id="buscadormusico" placeholder="Nombre musico...">
                        </div>

                        <div id="resultadomusico"></div>
                    </div>                   
                    <div id="buscarlocal">
                        <div id="buscarlocales">
                            <label><b>Buscar locales...</b></label><input type="text" id="buscadorlocal" placeholder="Nombre de local...">
                        </div>

                        <div id="resultadolocal"></div>
                    </div>
                    <div id="msc">
                        <h1>VALORAR MÚSICOS</h1>
                        <div class="hr"></div>
                        <div>
                            Género: <select id="genero" onchange="selectMusicos('<?php echo $fan ?>')">
                                <?php
                                $generos = selectGeneros();
                                while ($fila = mysqli_fetch_assoc($generos)) {
                                    echo "<option value='" . $fila["idGenero"] . "'>";
                                    echo utf8_encode($fila["nombreGenero"]);
                                    echo "</option>";
                                }
                                ?>    
                            </select>
                        </div>
                        <div id="musicos">
                            <?php
                            $gen1 = selectMusicos1();
                            if ($gen1 == NULL) {
                                echo "<div>No hay músicos registrados del género Blues</div>";
                            } else {
                                ?> 
                                <table> 
                                    <?php
                                    while ($fila1 = mysqli_fetch_assoc($gen1)) {
                                        $estado = isVote($fan, $fila1['usuMusico']);
                                        $id = "boton" . $fila1["usuMusico"];
                                        ?>
                                        <tr>
                                            <td><?php echo $fila1["nombreArtistico"]; ?></td>
                                            <td id="<?php echo $id ?>">
                                                <?php
                                                if ($estado) {
                                                    ?><button title="Quitar voto" onclick="quitarVoto(<?php echo $fan . "," . $fila1["usuMusico"] ?>)"><i class="fa fa-heart" style="color:red"></i></button> <?php
                                                } else {
                                                    ?><button title="Votar" onclick="votarMusico(<?php echo $fan . "," . $fila1["usuMusico"] ?>)"><i class="fa fa-heart"></i></button> <?php
                                                    }
                                                    ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </table> <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div id="conciertos">
                        <h1>VALORAR CONCIERTOS</h1>
                        <div class="hr"></div>
                        <?php
                        $filasPorPagina = 5;
                        if (isset($_GET["contador"])) {
                            $contador = $_GET["contador"];
                        } else {
                            $contador = 0;
                        }
                        $total = totalConciertosPasados();
                        $selectConciertos = selectConciertos($contador, $filasPorPagina);
                        if ($selectConciertos == NULL) {
                            echo "<div>No hay conciertos a votar en estos momentos</div>";
                        } else {
                            ?> <table>
                                <tr><td><b>Evento</b></td><td><b>Músico</b></td><td><b>Fecha</b></td></tr> <?php
                                while ($fila2 = mysqli_fetch_assoc($selectConciertos)) {
                                    $estado2 = isVoteConc($fan, $fila2['idConcierto']);
                                    $idConc = "conc" . $fila2["idConcierto"];
                                    ?>
                                    <tr>
                                        <td><?php echo $fila2["nombreConcierto"]; ?> </td><td><?php echo $fila2["nombreArtistico"]; ?></td><td><?php echo $fila2["fecha"]; ?></td>
                                        <td id="<?php echo $idConc ?>">
                                            <?php
                                            if ($estado2) {
                                                ?><button title="Quitar voto" onclick="quitarVotoConc(<?php echo $fan . "," . $fila2['idConcierto'] ?>)"><i class="fa fa-heart" style="color:red"></i></button> <?php
                                            } else {
                                                ?><button title="Votar" onclick="votarConcierto(<?php echo $fan . "," . $fila2['idConcierto'] ?>)"><i class="fa fa-heart"></i></button> 
                                                <?php } ?>
                                        </td>
                                    </tr>
                                <?php }
                                ?>
                            </table> <?php
                        }
                        // Mostrando el anterior (en caso de que lo haya)
                        if ($contador > 0) {
                            echo "<a href='seguidor.php?contador=" . ($contador - $filasPorPagina) . "'>&laquo;</a>";
                        }
                        // Mostrando mensaje de los resultados actuales
                        if (($contador + $filasPorPagina) <= $total) {
                            echo "Mostrando de " . ($contador + 1) . " a " . ($contador + $filasPorPagina) . " de $total";
                        } else {
                            echo "Mostrando de " . ($contador + 1) . " a $total de $total";
                        }
                        // Mostrar el siguiente (en cado de que lo haya)
                        if (($contador + $filasPorPagina) < $total) {
                            echo "<a href='seguidor.php?contador=" . ($contador + $filasPorPagina) . "'>&raquo;</a>";
                        }
                        ?>
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
            echo "No hay ningún usuario logueado";
            header("Refresh:2; url=../index.php");
            ob_end_flush();
        }
        ?>        
        <script src="../archivosJSyJQUERY/arriba.js" type="text/javascript"></script>
    </body>
</html>
