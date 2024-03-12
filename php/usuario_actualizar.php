<!-- Este archivo va a tener todo lo necesario para ACTUALIZAR un usuario -->
<?php
    // incluimos solo para poder utilizar una vez el archivo que crea la sesion llamada IV
	require_once "../inc/session_start.php";

    // Incluimos el archivo con las funciones importantes
	require_once "main.php";

    // Almacenamos el id mandado desde el formulario del archivo user_update.php en el input con name usuario_id
    $id=limpiar_cadena($_POST['usuario_id']);

    /*== Verificando usuario ==*/

    // Abrimos una conexion a la base de datos
	$check_usuario=conexion();
    // Ahora almacenamos el resultado de la consulta select a la base de datos para poder verificar
    // si el usuario realmente existe en la base de datos mediante su id
	$check_usuario=$check_usuario->query("SELECT * FROM usuario WHERE usuario_id='$id'");

    // Verificamos si el usuario realmente existe en la base de datos mediante su id, a ver si la consulta trajo algun registro
    if($check_usuario->rowCount()<=0){
        // Si el usuario NO existe entonces
    	echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El usuario no existe en el sistema
            </div>
        ';
        // Y detenemos la ejecucion del script
        exit();
    }else{
        //Si el usuario SI existe en la base de datos entonces:
        // Almacenamos los campos del registro que encontro dentro de un array con la funcion fetch en la variable datos
    	$datos=$check_usuario->fetch();
    }
    // Cerramos la conexion a la base de datos
    $check_usuario=null;


    /*== Almacenando datos del administrador su usuario y clave desde el form de user_update.php ==*/
    $admin_usuario=limpiar_cadena($_POST['administrador_usuario']);
    $admin_clave=limpiar_cadena($_POST['administrador_clave']);


    /*== Verificando si los campos obligatorios del administrador no vienen vacios ==*/
    if($admin_usuario=="" || $admin_clave==""){
        // Si los todos los campos obligatorios NO vienen completados entonces
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No ha llenado los campos que corresponden a su USUARIO o CLAVE
            </div>
        ';
        // Detenemos la ejecucion del script
        exit();
    }

    /*== Verificando integridad de los datos (admin) ==*/
    if(verificar_datos("[a-zA-Z0-9]{4,20}",$admin_usuario)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                Su USUARIO no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    if(verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$admin_clave)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                Su CLAVE no coincide con el formato solicitado
            </div>
        ';
        exit();
    }


    /*== Verificando el administrador en DB ==*/

    // Abrimos una conexion a la base de datos
    $check_admin=conexion();
    // Hacemos una consulta select del usuario y clave de la tabla usuario donde el usuario_usuario sea igual al usuario que tenemos guardado AND
    // que el usuario_id sea igual al que tenemos guardado, recordemos que ambos se estan mandando desde el formulario de user_update.php
    $check_admin=$check_admin->query("SELECT usuario_usuario,usuario_clave FROM usuario WHERE usuario_usuario='$admin_usuario' AND usuario_id='".$_SESSION['id']."'");

    // Si la consulta Si selecciono un dato entonces:
    if($check_admin->rowCount()==1){

        // Almacena los campos de esa consulta en un array con la funcion fetch dentro de la vriable $check_admin
    	$check_admin=$check_admin->fetch();

        // Verificamos que el usuario y clave sean las mismas a las que tenemos en la base de datos, que coindidan
    	if($check_admin['usuario_usuario']!=$admin_usuario || !password_verify($admin_clave, $check_admin['usuario_clave'])){
            // Si alguno NO coincide entonces:
    		echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrio un error inesperado!</strong><br>
	                USUARIO o CLAVE de administrador incorrectos
	            </div>
	        ';
            // Detenemos la ejecucion del script
	        exit();
    	}

    }else{
        // Si la contulsa select, NO selecciono ningun dato entonces:
    	echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                USUARIO o CLAVE de administrador incorrectos
            </div>
        ';
        exit();
    }
    // Cerramos la conexion a la base de datos
    $check_admin=null;


    /*== Almacenando datos del usuario ==*/
    $nombre=limpiar_cadena($_POST['usuario_nombre']);
    $apellido=limpiar_cadena($_POST['usuario_apellido']);

    $usuario=limpiar_cadena($_POST['usuario_usuario']);
    $email=limpiar_cadena($_POST['usuario_email']);

    $clave_1=limpiar_cadena($_POST['usuario_clave_1']);
    $clave_2=limpiar_cadena($_POST['usuario_clave_2']);


    /*== Verificando campos obligatorios del usuario ==*/
    if($nombre=="" || $apellido=="" || $usuario==""){
        // Si no se han llenado estos 3 campos obligatorios entonces:
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatorios
            </div>
        ';
        exit();
    }


    /*== Verificando integridad de los datos (usuario) ==*/
    if(verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$nombre)){
        // Si el nombre NO coincide con el formato solicitado entonces:
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El NOMBRE no coincide con el formato solicitado
            </div>
        ';
        // Detenemos la ejecucion del script
        exit();
    }

    if(verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$apellido)){
        // Si el apellido NO coincide con el formato solicitado entonces:
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El APELLIDO no coincide con el formato solicitado
            </div>
        ';
        // Detenemos la ejecucion del script
        exit();
    }

    if(verificar_datos("[a-zA-Z0-9]{4,20}",$usuario)){
        // Si el usuario NO coincide con el formato solicitado entonces:
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El USUARIO no coincide con el formato solicitado
            </div>
        ';
        // Detenemos la ejecucion del script
        exit();
    }


    /*== Verificando email ==*/

    // Si el correo NO esta vacio y si NO coincide con el que tenemos registrado en la base de datos entonces:
    if($email!="" && $email!=$datos['usuario_email']){
        // Verificamos que sea un correo valido
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){

            // Creamos una conexion a la base de datos
            $check_email=conexion();
            // Hacemos un select del de usuario_email de la tabla usuario donde usuario_email sea igual al email que tenemos guardado
            $check_email=$check_email->query("SELECT usuario_email FROM usuario WHERE usuario_email='$email'");
            if($check_email->rowCount()>0){
                echo '
                    <div class="notification is-danger is-light">
                        <strong>¡Ocurrio un error inesperado!</strong><br>
                        El correo electrónico ingresado ya se encuentra registrado, por favor elija otro
                    </div>
                ';
                exit();
            }
            $check_email=null;
        }else{
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    Ha ingresado un correo electrónico no valido
                </div>
            ';
            exit();
        } 
    }


    /*== Verificando usuario ==*/
    if($usuario!=$datos['usuario_usuario']){
	    $check_usuario=conexion();
	    $check_usuario=$check_usuario->query("SELECT usuario_usuario FROM usuario WHERE usuario_usuario='$usuario'");
	    if($check_usuario->rowCount()>0){
	        echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrio un error inesperado!</strong><br>
	                El USUARIO ingresado ya se encuentra registrado, por favor elija otro
	            </div>
	        ';
	        exit();
	    }
	    $check_usuario=null;
    }


    /*== Verificando claves ==*/
    if($clave_1!="" || $clave_2!=""){
    	if(verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_1) || verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_2)){
	        echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrio un error inesperado!</strong><br>
	                Las CLAVES no coinciden con el formato solicitado
	            </div>
	        ';
	        exit();
	    }else{
		    if($clave_1!=$clave_2){
		        echo '
		            <div class="notification is-danger is-light">
		                <strong>¡Ocurrio un error inesperado!</strong><br>
		                Las CLAVES que ha ingresado no coinciden
		            </div>
		        ';
		        exit();
		    }else{
		        $clave=password_hash($clave_1,PASSWORD_BCRYPT,["cost"=>10]);
		    }
	    }
    }else{
    	$clave=$datos['usuario_clave'];
    }


    /*== Actualizar datos ==*/
    $actualizar_usuario=conexion();
    $actualizar_usuario=$actualizar_usuario->prepare("UPDATE usuario SET usuario_nombre=:nombre,usuario_apellido=:apellido,usuario_usuario=:usuario,usuario_clave=:clave,usuario_email=:email WHERE usuario_id=:id");

    $marcadores=[
        ":nombre"=>$nombre,
        ":apellido"=>$apellido,
        ":usuario"=>$usuario,
        ":clave"=>$clave,
        ":email"=>$email,
        ":id"=>$id
    ];

    if($actualizar_usuario->execute($marcadores)){
        echo '
            <div class="notification is-info is-light">
                <strong>¡USUARIO ACTUALIZADO!</strong><br>
                El usuario se actualizo con exito
            </div>
        ';
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No se pudo actualizar el usuario, por favor intente nuevamente
            </div>
        ';
    }
    $actualizar_usuario=null;