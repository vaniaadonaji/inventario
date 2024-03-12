<?php
    /* En este archivo vamos a cerrar la sesion destruyendola */
    session_destroy();

    // Si se han enviado encabezados HTTP al cliente entonces:
    if(headers_sent()){
        // Redirecciona al usuario a la vista login usando JS ya que no se puede ocupar php puro en caso de que si porque nos mandaria un error
        echo "<script> window.location.href='index.php?vista=login'; </script>";
    }else{
        // Redirecciona al usuario a la vista login utilizando php 
        header("Location: index.php?vista=login");
    }

?>