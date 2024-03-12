<?php

    # ESTE CODIGO VA A SERVIR PARA la parte de busqueda en los 3 modulos (usuario, categoria y producto)

    // Almacenamos en la variable $modulo_buscador el valor de la variable de tipo post que tenemos en user_search.php
    // llamada modulo_buscador ya limpia de inyeccion SQL
	$modulo_buscador=limpiar_cadena($_POST['modulo_buscador']);

    // Creamos un array que va a almacenar una lista de los modulos ó apartados que tenemos en el sistema
	$modulos=["usuario","categoria","producto"];

    // Si el valor almacenado en la variable $modulo_buscador esta dentro de mi array llamado $modulos entonces:
	if(in_array($modulo_buscador, $modulos)){
		//Creamos un array que va a tener las vistas donde vamos a redireccionar al usuario depndiendo del modulo
		$modulos_url=[
			"usuario"=>"user_search",
			"categoria"=>"category_search",
			"producto"=>"product_search"
		];

        // Esta variable va a almacenar uno de los 3 posibles valores: user_search  category_search  ó  product_search
		$modulos_url=$modulos_url[$modulo_buscador];
        // Reescribimos esta variable, va a almacenar uno de los 3 posibles valores: usuario, categoria, producto
        // Por ejemplo busqueda_usuario o busqueda_categoria
		$modulo_buscador="busqueda_".$modulo_buscador;


		# Iniciar busqueda #
        // Si la variable de tipo post txt_buscador el cual es el name del input de user_search, si viene definida entonces:
		if(isset($_POST['txt_buscador'])){

            // Evitamos la inyeccion SQL y almacenamos el valor ya limio de txt_buscador en la variable txt
			$txt=limpiar_cadena($_POST['txt_buscador']);

            // Si el valor de txt viene vacio entonces
			if($txt==""){
                // Mandamos una notificacion solicitando que se introduzca el termino de busqueda
				echo '
		            <div class="notification is-danger is-light">
		                <strong>¡Ocurrio un error inesperado!</strong><br>
		                Introduce el termino de busqueda
		            </div>
		        ';
			}else{
                // Verificamos que el valor de $txt cumpla con el formato solicitado
				if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}",$txt)){
                    // Si NO coincide con el filtro entonces:
			        echo '
			            <div class="notification is-danger is-light">
			                <strong>¡Ocurrio un error inesperado!</strong><br>
			                El termino de busqueda no coincide con el formato solicitado
			            </div>
			        ';
			    }else{
                    // Creamos la variable de sesion llamada $modulo_buscador y le asignamos el valor de la busqueda que se hizo en el input
			    	$_SESSION[$modulo_buscador]=$txt;
                    // Redireccionamos al usuario con un encabezado url, dependiendo la vista que corresponda por ejemplo:
                    // index.php?vista=user_search 
			    	header("Location: index.php?vista=$modulos_url",true,303); 
 					exit();  
			    }
			}
		}


		# Eliminar busqueda #
        // Si la variable de tipo post eliminar_buscador el cual es el name del input de user_search, si viene definida entonces:
		if(isset($_POST['eliminar_buscador'])){
            // Eliminamos el valor de la variable de sesion llamada $modulo_buscador con unset()
			unset($_SESSION[$modulo_buscador]);
            // Redireciconamos al usuario por medio de la URL
			header("Location: index.php?vista=$modulos_url",true,303); 
 			exit();
		}

	}else{
        // Si el valor almacenado en la variable $modulo_buscador NO esta dentro de mi array llamado $modulos entonces:
        // Muestro un mensaje de error
		echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No podemos procesar la peticion
            </div>
        ';
	}