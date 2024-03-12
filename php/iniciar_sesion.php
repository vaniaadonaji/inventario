<?php

    # ALMACENANDO DATOS DEL FORMULARIO AQUI:  #

    //Aqui guardo los datos que envio el usuario desde el formulario(login) para agregar iniciar sesion en variables
    //y los limpio de inyecciones con la funcion limpiar_cadena() que tengo en main.php pero la puedo usar por el require_once
    $usuario=limpiar_cadena($_POST['login_usuario']);
    $clave=limpiar_cadena($_POST['login_clave']); 


    # Verificacion de campos OBLIGATORIOS #

    //Si alguno de estos campos obligatorios viene vacio entonces
    if($usuario=="" || $clave==""){
        //Mostramos una notificacion de alerta en codigo HTML sacada de bulma y detenemos la ejecucion del codigo con exit()
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatorios
            </div>
        ';
        exit();
    }


    # Ahora vamos a comprobar que NO haya caracteres en el formulario que NO esten permitidos #

    // Para eso utilizando la funcion verificar_datos() de mi archivo main.php con cada uno de los input mandando los 2 parametros que pide esta funcion
    // Le doy el filtro de cada input y la cadena que quiero que filtre
    if(verificar_datos("[a-zA-Z0-9]{4,20}",$usuario)){
        //Mostramos una notificacion de alerta en codigo HTML sacada de bulma y detenemos la ejecucion del codigo con exit()
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El USUARIO no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    if(verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave)){
        //Mostramos una notificacion de alerta en codigo HTML sacada de bulma y detenemos la ejecucion del codigo con exit()
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La CLAVE no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    # Verificando que el Usuario y la contraseña que vienen del login coincidan con alguno con los de la base de datos #

    // Hacemos conexion a la BD con la funcion conexion() del archivo main.php
    $check_user=conexion();
    $check_user=$check_user->query("SELECT * FROM usuario WHERE usuario_usuario='$usuario'");

    // Si se ha seleccionado algun registro en la consulta a la base de datos 
    // Es decir, si el usuario SI existe en la BD entonces
    if($check_user->rowCount()==1){
        // Realizamos un array de datos con la funcion fetch de todo lo que hemos seleccionado con el SELECT anterior
        $check_user=$check_user->fetch();

        // Si el usuario que seleccionamos de la Base de datos es igual al usuario que se mando desde el login AND
        // Si la clave ingresada del login es la misma que la que esta registrada y encriptada en la Base de datos entonces:
        if($check_user['usuario_usuario']==$usuario && password_verify($clave, $check_user['usuario_clave'])){
            # YA SE INICIO SESION EN ESTE PUNTO DEL CODIGO #
            // Creamos las variables de sesion, de la sesion que iniciamos en el index.php (Esa sesion tambien se incluye en este archivo)
            $_SESSION['id'] = $check_user['usuario_id'];
            $_SESSION['nombre'] = $check_user['usuario_nombre'];
            $_SESSION['apellido'] = $check_user['usuario_apellido'];
            $_SESSION['usuario'] = $check_user['usuario_usuario'];

            // Si se han enviado encabezados HTTP al cliente entonces:
            if(headers_sent()){
                // Redirecciona al usuario a la vista home usando JS ya que no se puede ocupar php puro, porque nos mandaria un error
                echo "<script> window.location.href='index.php?vista=home'; </script>";
            }else{
                // Redirecciona al usuario a la vista home utilizando php
                header("Location: index.php?vista=home");
            }

        }else{
        //Mostramos una notificacion de alerta en codigo HTML sacada de bulma 
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                Usuario o clave incorrectos
            </div>
        ';
        }
    }else{
        //Mostramos una notificacion de alerta en codigo HTML sacada de bulma 
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                Usuario o clave incorrectos
            </div>
        ';
    }
    $check_user=null;

?>