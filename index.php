<?php
    require "./inc/session_start.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    include "./inc/head.php";
    ?>
</head>
<body class="fondo">
    <?php

    // Si mi variable tipo GET llamada vista no viene definida o esta vacia
    if(!isset($_GET['vista']) || $_GET['vista'] == ""){
        // entonces le ponemos el valor el nombre de mi archivo login en las vistas
        $_GET['vista']="login";
    }

    /*   Si en la carpeta vistas existe el archivo que viene almacenado en mi variable vista por ejemplo home
     y es diferente a login 
     y es diferente a 404    */
    if(is_file("./vistas/".$_GET['vista'].".php") && $_GET['vista'] != "login" && $_GET['vista'] != "404"){

        # Cerrar la sesion #
        /* Aqui comprobamos si el usuario ingreso datos en el login al entrar al home o solo intento ingresar mediante la URL brincandose el login*/

        // Si la variable de sesion llamada id NO esta definida o NO tiene valor
        // O
        // Si la variable de sesion llamada nombre NO esta definida o NO tiene valor entonces:
        if((!isset($_SESSION['id']) || $_SESSION['id'] == "") || (!isset($_SESSION['nombre']) || $_SESSION['nombre'] == "")){
            // Cerramos la sesion destruyendola y reedireccionamos al usuario al login incluyendo el script del archivo logout.php de la carpeta vistas
            include "./vistas/logout.php";
            //Detenemos el script
            exit();
        }

        // Entonces se va a cargar mi navbar
        include "./inc/navbar.php";
        // La vista que contenga la variable vista desde mi carpeta vistas: por ejemplo la variable vista puede traer home
        include "./vistas/".$_GET['vista'].".php";
        // Y el script de funcionamiento para el navbar
        include "./inc/script.php";
    }else{
        //SINO que abra el login o la pagina 404
        if($_GET['vista'] == "login"){
            include "./vistas/login.php";
        }else{
            include "./vistas/404.php";
        }
    }
    ?>
</body>
</html>