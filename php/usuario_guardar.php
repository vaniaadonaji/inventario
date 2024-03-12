<?php
    /* Incluyo solo una vez el archivo main.php y si tiene errores ya NO continua con su ejecucion
    ya que ahi tengo cosas como la conexion a la BD, verificacion de datos y limpieza de cadenas para evitar
    inyeccion SQL y HTML*/
    require_once "main.php";

    # ALMACENANDO DATOS DEL FORMULARIO AQUI:  #

    //Aqui guardo los datos que envio el usuario desde el formulario para agregar nuevo usuario (user_new.php) en variables
    //y los limpio de inyecciones con la funcion limpiar_cadena() que tengo en main.php pero la puedo usar por el require_once
    $nombre=limpiar_cadena($_POST['usuario_nombre']);  
    $apellido=limpiar_cadena($_POST['usuario_apellido']); 
    $usuario=limpiar_cadena($_POST['usuario_usuario']);  
    $email=limpiar_cadena($_POST['usuario_email']);
    $clave_1=limpiar_cadena($_POST['usuario_clave_1']);  
    $clave_2=limpiar_cadena($_POST['usuario_clave_2']);


    # Verificacion de campos OBLIGATORIOS #

    //Si alguno de estos campos obligatorios viene vacio entonces
    if($nombre=="" || $apellido=="" || $usuario=="" || $clave_1=="" || $clave_2==""){
        //Mostramos una notificacion de alerta en codigo HTML sacada de bulma y detenemos la ejecucion del codigo con exit()
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No has llenado todos los campos 
            </div>
        ';
        exit();
    }

    # Ahora vamos a comprobar que NO haya caracteres en el formulario que NO esten permitidos #

    // Para eso utilizando la funcion verificar_datos() de mi archivo main.php con cada uno de los input mandando los 2 parametros que pide esta funcion
    // Le doy el filtro de cada input y la cadena que quiero que filtre
    if(verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$nombre)){
        //Mostramos una notificacion de alerta en codigo HTML sacada de bulma y detenemos la ejecucion del codigo con exit()
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El NOMBRE no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    // FILTRA LA CADENA DEL APELLIDO
    if(verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$apellido)){
        //Mostramos una notificacion de alerta en codigo HTML sacada de bulma y detenemos la ejecucion del codigo con exit()
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El APELLIDO no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    # FILTRA LA CADENA DEL USUARIO #
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

    # FILTRA LAS CADENAS DE LAS CLAVES #
    if(verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_1) || verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_2)){
        //Mostramos una notificacion de alerta en codigo HTML sacada de bulma y detenemos la ejecucion del codigo con exit()
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                Las CLAVES no coinciden con el formato solicitado, la
                clave debe tener al menos 7 caracteres y puede contener
                (a-z)(A-Z)(0-9) y ($, @,  .  , -)
            </div>
        ';
        exit();
    }

    # FILTRA LA CADENA DEL EMAIL: (de una forma diferente a los demas input) #

    // Si el correo no esta vacio entonces:
    if($email != ""){
        // Si la variable email contiene un correo valido entonces:
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){

            # Entonces verificamos que el correo que se va a registrar no se vaya a REPETIR en la base de datos: #

            // Hacemos conexion a la BD con la funcion conexion() del archivo main.php
            $check_email=conexion();
            $check_email=$check_email->query("SELECT usuario_email FROM usuario WHERE usuario_email='$email'");
            // Si existe un registro con ese correo en la consulta a la base de datos entonces:
            if($check_email->rowCount() > 0){
                //Mostramos una notificacion de alerta en codigo HTML sacada de bulma y detenemos la ejecucion del codigo con exit()
                echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    El correo ingresado ya se encuentra registrado, por
                    favor elija otro
                </div>
            ';
            exit();
            }
            // Cierro la conexion a la Base de datos (es necesario cerrarla para liberar espacio de memoria)
            $check_email=null;
        }else{
            //Mostramos una notificacion de alerta en codigo HTML sacada de bulma y detenemos la ejecucion del codigo con exit()
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    El CORREO ELECTRONICO ingresado no es valido
                </div>
            ';
            exit();
        }
    }

    # Verificando que el Usuario que se va a registrar no se vaya a REPETIR en la base de datos #

    // Hacemos conexion a la BD con la funcion conexion() del archivo main.php
    $check_usuario=conexion();
    $check_usuario=$check_usuario->query("SELECT usuario_usuario FROM usuario WHERE usuario_usuario = '$usuario'");
    // Si existe un registro con ese usuario en la consulta a la base de datos entonces:
    if($check_usuario->rowCount() > 0){
        //Mostramos una notificacion de alerta en codigo HTML sacada de bulma y detenemos la ejecucion del codigo con exit()
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El USUARIO ingresado ya se encuentra registrado, por
            favor elija otro
        </div>
    ';
    exit();
    }
    // Cierro la conexion a la Base de datos (es necesario cerrarla para liberar espacio de memoria)
    $check_usuario=null;


    # Verificar que las contraseñas o claves sean iguales #

    // Si las claves NO coinden entonces:
    if($clave_1 != $clave_2){
        //Mostramos una notificacion de alerta en codigo HTML sacada de bulma y detenemos la ejecucion del codigo con exit()
        echo '
            <div class="notification is-danger is-light">
              <strong>¡Ocurrio un error inesperado!</strong><br>
              Las CLAVES que ha ingresado no coinciden, por favor
              reviselas
            </div>
        ';
        exit();
    }else{
        // Encriptamos la clave que se ingreso y lo almacenamos en la variable $clave
        $clave = password_hash($clave_1, PASSWORD_BCRYPT,["cost"=>10]);
    }


    # Comenzar a guardar los datos en la base de datos: #

    // Hacemos conexion a la base de datos
    $guardar_usuario = conexion();
    // Preparamos la insercion de los datos a la BD con marcadores ej. (:nombre)
    $guardar_usuario = $guardar_usuario->prepare("INSERT into usuario
    (usuario_nombre, usuario_apellido, usuario_usuario, usuario_clave, usuario_email) 
    VALUES(:nombre,:apellido,:usuario,:clave,:email)");

    // Creamos el arreglo asociativo (clave-valor) 
    //que tendra en la clave el marcador de cada dato y en el valor los datos reales
    $marcadores=[
        ":nombre"=>$nombre,
        ":apellido"=>$apellido,
        ":usuario"=>$usuario,
        ":clave"=>$clave,
        ":email"=>$email
    ];

    // Luego pasamos los valores reales a la insercion SQL a traves del array (marcadores)
    $guardar_usuario->execute($marcadores);

    // Si en la consulta se logro registrar un dato entonces:
    if($guardar_usuario->rowCount()==1){
        echo '
            <div class="notification is-info is-light">
                <strong>¡USUARIO REGISTRADO!</strong><br>
                El usuario se registro con exito
            </div>
        ';
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No se pudo registrar el usuario, por favor intente nuevamente
            </div>
        ';
    }
    //Cerramos la base de datos:
    $guardar_usuario=null;

    ?>


