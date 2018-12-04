<!DOCTYPE html>
<?php
session_start();
require_once 'funciones.php';
?>
<html>
    <head>
        <meta charset="UTF-8">
        <link href="css/comun.css" rel="stylesheet" type="text/css"/>
        <link href="css/estiloContenidoHome.css" rel="stylesheet" type="text/css"/>
        <link href="css/tablaConciertos.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="archivosJSyJQUERY/jquery-3.2.1.js" type="text/javascript"></script>
        <script src="slick/slick.min.js" type="text/javascript"></script>
        <link href="slick/slick.css" rel="stylesheet" type="text/css"/>
        <link href="slick/slick-theme.css" rel="stylesheet" type="text/css"/>
        <script src="archivosJSyJQUERY/slick.js" type="text/javascript"></script>
        <link href="css/mediaQUERY.css" rel="stylesheet" type="text/css"/>
        <title>OhhMusic</title>
    </head>
    <body>        
        <header id="encabezado">
            <div id="titulo">
                <h1>OhhMusic</h1>
            </div>                    
              
            <?php
            if (isset($_SESSION["user"])) {
                ?>
                <div id="perfil">  
                    <?php
                    $user = $_SESSION["user"];
                    $type = recojerCategoria($user);
                    $_SESSION["type"] = $type;
                    if ($type == "Local") {
                        ?>
                        <a href="local/local.php">
                            <i class="fa fa-user fa-2x"></i>
                        </a>
                    <?php } else if ($type == "Musico") {
                        ?>
                        <a href="musico/musico.php">
                            <i class="fa fa-user fa-2x"></i>
                            
                        </a>
                    <?php } else if ($type == "Fan") {
                        ?>
                        <a href="fan/seguidor.php">
                            <i class="fa fa-user fa-2x"></i>                            
                        </a>
                        <?php
                    }
                    ?>
                </div>
            <?php } ?>
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
                            <form method="POST" action="datos_user.php">
                                <h3>DARME DE ALTA</h3>
                                <div class="hr"></div>
                                <div>Tu perfil:                         
                                <input type="radio" name="type" value="Fan">Fan
                                <input type="radio" name="type" value="Musico">Musico
                                <input type="radio" name="type" value="Local">Local      
                                <input type="email" name="email" value="" required="" placeholder="Correo electrónico" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">                                                      
                                <input pattern=".{1,}" title="Tu contraseña debe tener al menos 1 digitos" type="password" name="password" value="" required="" placeholder="Contraseña">
                                <input type="submit" value="Darme de alta" name="alta" class="button">
                                </div>
                            </form>
                        </div>
                    </div>
                </div> 
            </div>             
        </header>
        <div id="slider">
            <div class="responsive">
                <div><img src="fotosAnuncio/anuncia_aqui-3.png" alt=""/></div>
                <div><img src="fotosAnuncio/cartel_narwan.jpg" alt=""/></div>
                <div><img src="fotosAnuncio/anuncia_aqui-3.png" alt=""/></div>
                <div><img src="fotosAnuncio/13606505_10208137630652884_4424247226395015955_n.jpg" alt=""/></div>
                <div><img src="fotosAnuncio/Tu-anuncio-aqui.jpg" alt=""/></div>
            </div></div>
        <section>            
            <article class="contenido">                
                <h1>PROXIMOS CONCIERTOS</h1>
                <div class="hr"></div>
                <table class="table_conciertos">
                    <tr>
                        <th></th><th><b>Concierto</b></th><th><b>Fecha</b></th><th><b>Musico</b></th><th><b>Lugar</b></th>
                    </tr>
                    <?php
                    $filasPorPagina = 3;
                    if (isset($_GET["contador"])) {
                        $contador = $_GET["contador"];
                    } else {
                        $contador = 0;
                    }
                    $total = totalConciertos();
                    $concierto = selectConcierto($contador, $filasPorPagina);
                    while ($fila = mysqli_fetch_assoc($concierto)) {
                        extract($fila);
                        ?>
                        <tr>
                            <td><img src="./imagesUser/<?php echo $img?>" width="80" height="100" alt=""/></td>
                            <td><?php echo $nombreConcierto ?></td>
                            <td><?php echo $fecha ?></td>
                            <td><?php echo $nombreArtistico ?></td>
                            <td><?php echo $nombreLocal ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
                <?php
                if ($contador > 0) {
                    echo "<a href='index.php?contador=" . ($contador - $filasPorPagina) . "'>&laquo; </a>";
                }
                if (($contador + $filasPorPagina) <= $total) {
                    echo "Mostrando de " . ($contador + 1) . " a " . ($contador + $filasPorPagina) . " de $total";
                } else {
                    echo "Mostrando de " . ($contador + 1) . " a $total de $total";
                }
                if (($contador + $filasPorPagina) < $total) {
                    echo "<a href='index.php?contador=" . ($contador + $filasPorPagina) . "'> &raquo;</a>";
                }
                ?>                
            </article> 
            <div id="topList">               
                <div class="ranking">
                    <h3>MUSICOS MAS VOTADOS</h3> 
                    <div class="hr"></div>
                    <table>
                        <tr>
                            <th>Musico</th><th>VOTOS</th>
                        </tr>
                        <?php
                        $lista = listarMusicos();
                        while ($fila = mysqli_fetch_assoc($lista)) {
                            extract($fila);
                            ?>  
                            <tr>
                                <td><?php echo $nombreArtistico ?></td>   
                                <td><?php echo $total_votos ?></td>                                
                            </tr>
                            <?php
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
        <script src="archivosJSyJQUERY/popup_box.js" type="text/javascript"></script>
        <script src="archivosJSyJQUERY/arriba.js" type="text/javascript"></script>
    </body>
</html>
