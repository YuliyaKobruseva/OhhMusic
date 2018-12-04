<?php
session_start();
require_once 'editarPerfiles.php';
ob_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="../archivosJSyJQUERY/jquery-3.2.1.js" type="text/javascript"></script>
        <script src="editarPerfiles.js" type="text/javascript"></script>
        <script src="../archivosJSyJQUERY/editPerf.js" type="text/javascript"></script>
        <link href="../css/comun.css" rel="stylesheet" type="text/css"/>
        <link href="../css/perfiles.css" rel="stylesheet" type="text/css"/>
        <link href="../css/right-nav-style.css" rel="stylesheet" type="text/css"/>        
        <link href="../css/editarPerfil.css" rel="stylesheet" type="text/css"/>
        <title></title>
    </head>
    <body>
        <?php
        if (isset($_SESSION["type"])) {
            $tipo = $_SESSION["type"];
            $usuario = $_SESSION["user"];
            ?>
            <script>
                function home() {
                    window.location.replace("../index.php");
                }
            </script>
            <header id="encabezado" onclick="home()">
                <div id="titulo">
                    <h1>OhhMusic</h1>
                </div>                     
                <span id="usuario" hidden=""><?php echo $usuario ?></span>
            </header>
            <input type="checkbox" id="nav-toggle" hidden>
            <nav class="nav">
                <label for="nav-toggle" class="nav-toggle" onclick></label>
                <h2 class="logo"> 
                    <?php
                    $img = mostrarImg($usuario);
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
                        <?php if ($tipo == "Musico") { ?>
                            <a href="../musico/musico.php">Volver al perfil</a>
                        <?php } else if ($tipo == "Fan") { ?>
                            <a href="../fan/seguidor.php">Volver al perfil</a>
                        <?php } else if ($tipo == "Local") { ?>
                            <a href="../local/local.php">Volvel al perfil</a>
                        <?php } ?>
                    <li>
                        <i class="fa fa-cogs"></i>
                        <a href="../editar/editarPerfil.php"> Modificar perfil</a>                        
                        <?php
                        if ($tipo == "Local") {
                            ?>
                        <li>
                            <i class="fa fa-plus-square"></i>
                            <a href="../local/crearConciertos.php">Crear conciertos</a> 
                        <?php } ?>
                        <form method="POST">                             
                            <input id="input" type="submit" name="close" value="Cerrar sesion">
                        </form>
                </ul>
            </nav>     
            <div class="contenido">    
                <h1>Configuración de la cuenta</h1> 
                <div class="hr"></div>
                <div class="info">
                    <ul class="list">
                        <li><i class="fa fa-lock"></i>Contraseña</li> 
                        <li></li>
                        <li><button class="editar"><i class="fa fa-pencil"></i>Editar</button> </li>                            
                    </ul>                       
                </div>
                <div class="panel">   
                    <table>
                        <tbody>
                            <tr>
                                <td>Contraseña Actual</td>
                                <td><input type="password" name="oldPass" /><br><br></td>
                            </tr>
                            <tr>
                                <td>Contraseña Nueva</td>
                                <td><input type="password" name="newPass" /><br><br></td>
                            </tr>
                            <tr>
                                <td>Confirmación Contraseña Nueva</td>
                                <td><input type="password" name="vNewPass"/></td>
                            </tr>
                            <tr>
                                <td colspan="2"><button id="modPass"><i class="fa fa-save"></i>Guardar cambios</button></td>
                            </tr>
                            <tr>
                                <td colspan="2"><div id="exitoPass"></div></td>                                           
                            </tr>
                        </tbody>
                    </table>
                </div> 
                <div class="info">
                    <ul class="list">
                        <li>Nombre</li>    
                        <li id="nombre"></li>
                        <li><button class="editar"><i class="fa fa-pencil"></i>Editar</button> </li>
                    </ul>                        
                </div>
                <div class="panel">       
                    Nombre<input type="text" id="nom" maxlength="20"/>
                    <button id="modNombre"><i class="fa fa-save"></i>Guardar cambios</button>
                    <div id="exitoNombre"></div>
                </div> 
                <div class="info">
                    <ul class="list">
                        <li>Apellidos</li>    
                        <li id="apellidos"></li>
                        <li><button class="editar"><i class="fa fa-pencil"></i>Editar</button> </li>
                    </ul>                        
                </div>
                <div class="panel">       
                    Apellidos <input type="text" id="cognoms" maxlength="50"/>
                    <button id="modApellidos"><i class="fa fa-save"></i>Guardar cambios</button>
                    <div id="exitoApellidos"></div>
                </div> 
                <?php if ($tipo == "Fan") { ?>
                    <div class="info">
                        <ul class="list">
                            <li><i class="fa fa-mars"></i><i class="fa fa-venus"></i>Sexo</li>    
                            <li id="sexo"></li>
                            <li><button class="editar"><i class="fa fa-pencil"></i>Editar</button> </li>
                        </ul>                             
                    </div>
                    <div class="panel">
                        Sexo:<input type="radio" name="sex" value="H"  id="hombre"/>Hombre
                        <input type="radio" name="sex" value="M" id="mujer"/>Mujer
                        <button id="modSexo"><i class="fa fa-save"></i>Guardar cambios</button>
                        <div id="exitoSexo"></div>
                    </div>  
                    <div class="info">
                        <ul class="list">
                            <li><i class="fa fa-calendar-o" aria-hidden="true"></i>Fecha Nacimiento</li>    
                            <li id="born"></li>
                            <li><button class="editar"><i class="fa fa-pencil"></i>Editar</button> </li>
                        </ul>                            
                    </div>
                    <div class="panel">       
                        Fecha de Nacimiento:<input type="date" name="born" />
                        <button id="modBorn"><i class="fa fa-save"></i>Guardar cambios</button>
                        <div id="exitoBorn"></div>
                    </div>
                <?php } ?>
                <div class="info">
                    <ul class="list">
                        <li><i class="fa fa-envelope-square"></i>Correo electrónico</li>    
                        <li id="mail"></li>
                        <li><button class="editar"><i class="fa fa-pencil"></i>Editar</button> </li>
                    </ul>                       
                </div>
                <div class="panel">
                    Correo electrónico nuevo:<input type="email" id="mairu" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"/>
                    <button id="modMail"><i class="fa fa-save"></i>Guardar cambios</button>
                    <div id="exitoMail"></div>
                </div>
                <div class="info">
                    <ul class="list">
                        <li><i class="fa fa-phone-square"></i></i>Teléfono</li>    
                        <li id="telf"></li>
                        <li><button class="editar"><i class="fa fa-pencil"></i>Editar</button> </li>
                    </ul>                        
                </div>
                <div class="panel">
                    Teléfono <input type="text" id="telf2" maxlength="9" minlength="9"/>
                    <button id="modTelf"><i class="fa fa-save"></i>Guardar cambios</button>
                    <div id="exitoTelf"></div>
                </div>
                <div class="info">
                    <ul class="list">
                        <li><i class="fa fa-building"></i>Provincia</li>    
                        <li id="provincia"></li> 
                        <li></li> 
                    </ul>                    
                </div>
                <div class="info">
                    <ul class="list">                          
                        <li><i class="fa fa-building"></i>Ciudad</li>    
                        <li id="city"></li>
                        <li><button class="editar"><i class="fa fa-pencil"></i>Editar</button> </li>
                    </ul>                        
                </div>
                <div class="panel">
                    <table>
                        <tbody>
                            <tr>
                                <td>Provincia</td>
                                <td><select id="selProvincia" onchange="cogeProvincia()">
                                        <?php
                                        $provincias = selectProvincias();
                                        $ciudades = selectCiudadByUser($usuario);
                                        while ($fila = mysqli_fetch_assoc($provincias)) {
                                            echo "<option>";
                                            echo $fila["provincia"];
                                            echo "</option>";
                                        }
                                        ?>    
                                    </select></td>
                            </tr>
                            <tr>
                                <td>Ciudad</td>
                                <td><select id="ciudad">
                                        <?php
                                        while ($fila2 = mysqli_fetch_assoc($ciudades)) {
                                            $ciudadesUser = selectCiudad($fila2["provincia"]);
                                            while ($fila3 = mysqli_fetch_assoc($ciudadesUser)) {
                                                if ($fila3["nombreCiudad"] == $fila2["nombreCiudad"]) {
                                                    echo "<option value='" . $fila3["idCiudad"] . "' selected>";
                                                } else {
                                                    echo "<option value='" . $fila3["idCiudad"] . "'>";
                                                }
                                                echo $fila3["nombreCiudad"];
                                                echo "</option>";
                                            }
                                        }
                                        ?>  
                                    </select></td>
                            </tr>                           
                            <tr>
                                <td colspan="2"><button id="modCiudad"><i class="fa fa-save"></i>Guardar cambios</button></td>
                            </tr>
                            <tr>
                                <td colspan="2"><div id="exitoCiudad"></div></td>                                           
                            </tr>
                        </tbody>
                    </table>                  
                </div>
                <div class="info">
                    <ul class="list">
                        <li><i class="fa fa-home"></i>Dirección</li>    
                        <li id="direccion"></li>
                        <li><button class="editar"><i class="fa fa-pencil"></i>Editar</button> </li>
                    </ul>                        
                </div> 
                <div class="panel">
                    Dirección:<input type="text" id="newDir" />
                    <button id="modDireccion"><i class="fa fa-save"></i>Guardar cambios</button>
                    <div id="exitoDireccion"></div>
                </div>
                <?php if ($tipo == "Musico") { ?>
                    <div class="info">
                        <ul class="list">
                            <li>Nombre Artístico</li> 
                            <li id="nombreArtistico"></li>                            
                            <li><button class="editar"><i class="fa fa-pencil"></i>Editar</button> </li>
                        </ul>                            
                    </div> 
                    <div class="panel">
                        Nombre Artístico:<input type="text" id="nombreArt"/>
                        <button id="modNombreArt"><i class="fa fa-save"></i>Guardar cambios</button>
                        <div id="exitoNombreArtistico"></div>
                    </div> 
                    <div class="info">
                        <ul class="list">
                            <li><i class="fa fa-globe"></i>Página Web</li>    
                            <li id="web"></li>
                            <li><button class="editar"><i class="fa fa-pencil"></i>Editar</button> </li>
                        </ul>                            
                    </div> 
                    <div class="panel">
                        Página Web:<input type="url" name="web"/>
                        <button id="modWeb"><i class="fa fa-save"></i>Guardar cambios </button>
                        <div id="exitoWeb"></div>
                    </div> 
                    <div class="info">
                        <ul class="list">
                            <li><i class="fa fa-headphones"></i>Género</li>    
                            <li id="genero"></li>
                            <li><button class="editar"><i class="fa fa-pencil"></i>Editar</button> </li>
                        </ul>                            
                    </div> 
                    <div class="panel">
                        Género:<select id="selGenero"> 
                            <?php
                            $generos = selectGeneros();
                            while ($fila = mysqli_fetch_assoc($generos)) {
                                echo "<option value='" . $fila["idGenero"] . "'>";
                                echo utf8_encode($fila["nombreGenero"]);
                                echo "</option>";
                            }
                            ?>    
                        </select>
                        <button id="modGenero"><i class="fa fa-save"></i>Guardar cambios</button>
                        <div id="exitoGenero"></div>
                    </div>  
                    <div class="info">
                        <ul class="list">
                            <li><i class="fa fa-users"></i>Número de Componentes</li>    
                            <li id="numComp"></li>
                            <li><button class="editar"><i class="fa fa-pencil"></i>Editar</button> </li>
                        </ul>                            
                    </div> 
                    <div class="panel">
                        Número de Compomponentes:<input type="number" min="1" name="numComp" />
                        <button id="modNumComp"><i class="fa fa-save"></i>Guardar cambios</button> 
                        <div id="exitoNumComp"></div>
                    </div>
                <?php } else if ($tipo == "Local") { ?>
                    <div class="info">
                        <ul class="list">
                            <li><i class="fa fa-fort-awesome"></i>Nombre Local</li>    
                            <li id="localName"></li>
                            <li><button class="editar"><i class="fa fa-pencil"></i>Editar</button> </li>
                        </ul>                            
                    </div> 
                    <div class="panel">
                        Nombre Local:<input type="text" name="local" maxlength="30" />
                        <button id="modNameLocal"><i class="fa fa-save"></i>Guardar cambios</button>
                        <div id="exitoLocalName"></div>
                    </div>
                    <div class="info">
                        <ul class="list">
                            <li><i class="fa fa-users"></i>Aforo</li>    
                            <li id="aforo"></li>
                            <li><button class="editar"><i class="fa fa-pencil"></i>Editar</button> </li>
                        </ul>                            
                    </div> 
                    <div class="panel">
                        Aforo:<input type="number" min="0" name="aforo"/>
                        <button id="modAforo"><i class="fa fa-save"></i>Guardar cambios</button>
                        <div id="exitoAforo"></div>
                    </div>
                <?php } ?>                
            </div>                       
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
            }
        } else {
            echo "No hay ningún usuario logueado";
            header("Refresh:1; url=../index.php");
        }
        ?>
        <script src="../archivosJSyJQUERY/arriba.js" type="text/javascript"></script>        
    </body>
</html>
