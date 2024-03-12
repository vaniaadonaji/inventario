<?php
    // Incluimos el archivo de main.php con las funciones
	require_once "./php/main.php";

    // Si la variable de tipo GET llamada user_id_up viene definida entonces (?) 
    // En caso de que si venga definida vamos a almacenarle el valor de la variable de tipo GET user_id_up a la variable $id
    // En caso de que no venga defindia vamos a colocarle a la variable $id el valor de 0
    $id = (isset($_GET['user_id_up'])) ? $_GET['user_id_up'] : 0;
    // Limpiamos su contenido de inyeccion sql o html
    $id=limpiar_cadena($id);
?>
<div class="container is-fluid mb-6">
    <!-- Si el id es igual al id se la sesion iniciada osea a la variable de sesion llamada id  significa que quiere actualizar su propia cuenta-->
	<?php if($id==$_SESSION['id']){ ?>
        <!-- Entonces se van a poner los titulos de la cuenta del usuario -->
		<h1 class="title">Mi cuenta</h1>
		<h2 class="subtitle">Actualizar datos de mi cuenta</h2>
    <!-- En caso de que no -->
	<?php }else{ ?>
        <!-- Significa que quiere actualizar un usuario que no es el suyo, y el contenido de los titulos sera diferente -->
		<h1 class="title">Usuarios</h1>
		<h2 class="subtitle">Actualizar usuario</h2>
	<?php } ?>
</div>

<div class="container pb-6 pt-6">
	<?php

        // Incluimos el boton que tenemos para retroceder de la carpeta inc
		include "./inc/btn_back.php";

        /*== Verificando usuario ==*/
        // Hacemos la conexion a la base de datos almacenada en $check_usuario
    	$check_usuario=conexion();
        // Hacemos una consulta select de todo en la tabla usuario donde usuario_id coincida con el id que tenemos almacenado en $id
    	$check_usuario=$check_usuario->query("SELECT * FROM usuario WHERE usuario_id='$id'");

        // Si el select anterior selecciono algun registro, eso quiere decir que el id almacenado en $id si existe entonces
        if($check_usuario->rowCount()>0){
            // convertimos los datos que se seleccionaron en un array con fetch y almacenamos ese array en $datos, 
            // solo son datos de UN SOLO REGISTRO, por eso ocupamos fetch y no fetchAll
        	$datos=$check_usuario->fetch();
	?>

    <!-- Aqui vamos a obtener la respuesta del formulario via ajax de abajo, por eso el div vacio tiene la clase form-rest con
    la que trabajamos en el archivo llamado ajax.js -->
	<div class="form-rest mb-6 mt-6"></div>

    <!-- Aqui en este formulario tenemos la clase FormularioAjax para enviar los datos por via ajax usando el archivo ajax.php -->
	<!-- igual tenemos en action (a que archivo se van a mandar los datos) en este caso a usuario_actualizar.php de la carpeta php -->
	<form action="./php/usuario_actualizar.php" method="POST" class="FormularioAjax" autocomplete="off" >

		<!-- A traves de este input vamos a mandar el id al archivo llamado usuario_actulizar.php -->
		<input type="hidden" name="usuario_id" value="<?php echo $datos['usuario_id']; ?>" required >
		
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Nombres</label>
					<!-- Aqui se mostrara lo que hay en el array que creamos arriba con la clave usuario_nombre ya que es array asociativo -->
				  	<input class="input" type="text" name="usuario_nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" 
					required value="<?php echo $datos['usuario_nombre']; ?>" >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Apellidos</label>
					<!-- Aqui se mostrara lo que hay en el array que creamos arriba con la clave usuario_apellido ya que es array asociativo -->
				  	<input class="input" type="text" name="usuario_apellido" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" 
					required value="<?php echo $datos['usuario_apellido']; ?>" >
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Usuario</label>
					<!-- Aqui se mostrara lo que hay en el array que creamos arriba con la clave usuario_usuario ya que es array asociativo -->
				  	<input class="input" type="text" name="usuario_usuario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" 
					required value="<?php echo $datos['usuario_usuario']; ?>" >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Email</label>
					<!-- Aqui se mostrara lo que hay en el array que creamos arriba con la clave usuario_email ya que es array asociativo -->
				  	<input class="input" type="email" name="usuario_email" maxlength="70" value="<?php echo $datos['usuario_email']; ?>" >
				</div>
		  	</div>
		</div>
		<br><br>
		<p class="has-text-centered">
			SI desea actualizar la clave de este usuario por favor llene los 2 campos. Si NO desea actualizar la clave deje los campos vacíos.
		</p>
		<br>
		<div class="columns">
			<div class="column">
		    	<div class="control">
					<label>Clave</label>
				  	<input class="input" type="password" name="usuario_clave_1" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Repetir clave</label>
				  	<input class="input" type="password" name="usuario_clave_2" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" >
				</div>
		  	</div>
		</div>
		<br><br><br>
		<p class="has-text-centered">
			Para poder actualizar los datos de este usuario por favor ingrese su USUARIO y CLAVE con la que ha iniciado sesión
		</p>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Usuario</label>
				  	<input class="input" type="text" name="administrador_usuario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Clave</label>
				  	<input class="input" type="password" name="administrador_clave" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required >
				</div>
		  	</div>
		</div>
		<p class="has-text-centered">
			<button type="submit" class="button is-success is-rounded">Actualizar</button>
		</p>
	</form>
	<?php 
        // Si el select anterior NO selecciono algun registro, eso quiere decir que el id almacenado en $id NO existe entonces:
		}else{
            // Incluimos el codigo html del mensaje de error
			include "./inc/error_alert.php";
		}
        // Cerramos la conexion a la base de datos
		$check_usuario=null;
	?>
</div>