<?php
	// Esta variable va a servir para saber desde donde vamos a empezar a contar los registros que vamos a mostrar en las tablas de usuarios, contendra el indice 
    // Si la pagina viene definida osea es mayor a 0 entonces hace el calculo para saber desde donde contar
    // Sino se le agrega el valor 0 a la variable inicio (empezaremos a contar desde el indice 0)
    $inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

    // Esta variable llamada tabla va a ir generando toda la tabla con el listado de los usuarios
	$tabla="";

    // Aqui veremos si generaremos el listado con busqueda o el listado normal sin nungun filtro de busqueda
    // Si la variable busqueda viene definida y tiene algo almacenado entonces
	if(isset($busqueda) && $busqueda!=""){

        // Es la misma consulta de abajo (del else) pero añadimos filtros de busqueda en el WHERE
		$consulta_datos="SELECT * FROM usuario WHERE ((usuario_id!='".$_SESSION['id']."') AND (usuario_nombre LIKE '%$busqueda%' OR usuario_apellido LIKE '%$busqueda%' OR usuario_usuario LIKE '%$busqueda%' OR usuario_email LIKE '%$busqueda%')) ORDER BY usuario_nombre ASC LIMIT $inicio,$registros";

        // Es la misma consulta de abajo (del else) pero añadimos filtros de busqueda en el WHERE tambien
		$consulta_total="SELECT COUNT(usuario_id) FROM usuario WHERE ((usuario_id!='".$_SESSION['id']."') AND (usuario_nombre LIKE '%$busqueda%' OR usuario_apellido LIKE '%$busqueda%' OR usuario_usuario LIKE '%$busqueda%' OR usuario_email LIKE '%$busqueda%'))";

	}else{

        // Sino mostramos los datos, evitando que se muestre el usuario que esta haciendo la consulta con el WHERE a traves de su id en caso de que queramos que SI
        //se muestre tambien el usuario que esta haciendo la consulta, simplemente le quitamos la parte del WHERE ( WHERE usuario_id!='".$_SESSION['id']."' )
        // y agregando un limite con limit de lo que contanga la variable inicio (indice) y registros (15 registros maximo como limite)
		$consulta_datos="SELECT * FROM usuario WHERE usuario_id!='".$_SESSION['id']."' ORDER BY usuario_nombre ASC LIMIT $inicio,$registros";

        // Aqui vamos a tener el total de registros que tenemos en la base de datos exceptuando el que esta haciendo la consulta
		$consulta_total="SELECT COUNT(usuario_id) FROM usuario WHERE usuario_id!='".$_SESSION['id']."'";
		
	}
 
    // Creamos la conexion a la BD en la variable conexion usando la funcion conexion() del archivo main.php
	$conexion=conexion();

    // Hacemos la consulta almacenada en la variable $consulta_datos 
	$datos = $conexion->query($consulta_datos);
    // Y hacemos un array asociativo de todos los registros seleccionados de la consulta, con la funcion fetchAll() en la variable datos
	$datos = $datos->fetchAll();

    // Hacemos la consulta almacenada en la variable $consulta_total  
	$total = $conexion->query($consulta_total);
    // Y almacenamos el valor de la columna, que nos devolvio la consulta, ya convertido a int en mi variable llamada $total
	$total = (int) $total->fetchColumn();

    // En la variable Npaginas que estamos creando vamos a almacenar el numero de paginas que se tienen que crear en el paginador
    // para eso divido la cantidad de datos ($total) entre el numero maximo de registros permitidos en cada pagina ($registros) osea 15
    // Y el resultado lo redondeo con la funcion ceil() a su numero proximo, por ejemplo 2.1 se redondea a 3
	$Npaginas = ceil($total/$registros);

    // Creo la parte de la tabla del listado y sus campos de la tabla, abro la etiqueta del div con clase table-container, table y dbody.
	$tabla.='	
	<div class="table-container">
        <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
            <thead>
                <tr class="has-text-centered">
                	<th>#</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th colspan="2">Opciones</th>
                </tr>
            </thead>
            <tbody>
	';

	// Lo que hace este if es ver si hay registros y si estamos ubicados en una pagina existente es decir en una pagina valida.
	// Si la cantidad de datos ($total) es mayor o igual a 1 AND la pagina en la que estamos ubicados ($pagina) es menor o igual al 
	// numero de paginas que se tienen que crear en el paginador ($Npaginas) entonces:
	if($total>=1 && $pagina<=$Npaginas){	
		// Sumamos al indice ($inicio) un 1, para que empiece a contar en 1 el primer registro y no en 0 en la tabla y se lo agregamos a la variable ($contador)
		$contador=$inicio+1;
		// En esta variable se almacenara el numero del primer registro en esa pagina del paginador.
		// Esto para mostrar un mensaje como por ej. Mostrando usuarios del (1) al 5 de un total de 17 registros.
		// Esta variable tendria el numero 1 en ese ejemplo, ese mensaje se mostrara debajo de la tabla de los registros en el sistema.
		$pag_inicio=$inicio+1;
		// Recorremos todos los datos almacenados en el array ($datos) con una variable apenas creada llamada ($rows)
		foreach($datos as $rows){
			// Y vamos construyendo las filas($rows) de la tabla de datos, por medio de el nombre de su campo en la base de datos o su clave
			// añadiendo tambien sus botones para actualizar y eliminar en CADA fila de la tabla que se vaya generando dinamicamente.
			$tabla.='
				<tr class="has-text-centered" >
					<td>'.$contador.'</td>
                    <td>'.$rows['usuario_nombre'].'</td>
                    <td>'.$rows['usuario_apellido'].'</td>
                    <td>'.$rows['usuario_usuario'].'</td>
                    <td>'.$rows['usuario_email'].'</td>
                    <td>
                        <a href="index.php?vista=user_update&user_id_up='.$rows['usuario_id'].'" class="button is-success is-rounded is-small">Actualizar</a>
                    </td>
                    <td>
                        <a href="'.$url.$pagina.'&user_id_del='.$rows['usuario_id'].'" class="button is-danger is-rounded is-small">Eliminar</a>
                    </td>
                </tr>
            ';
            $contador++;
		}
		 
		// En esta variable se almacenara el numero del ultimo registro en esa pagina del paginador.
		// Esto para mostrar un mensaje como por ej. Mostrando usuarios del 1 al (5) de un total de 17 registros.
		// Esta variable tendria el numero 5 en ese ejemplo, ese mensaje se mostrara debajo de la tabla de los registros en el sistema.
		$pag_final=$contador-1;
	}else{
		// Si si hay registros entonces:
		if($total>=1){
			// Redirigimos al usuario con la URL a la pagina 1 usando la variable url que tenemos en el archivo user_list.php,
			// esto ocurrira ya que se mostrara la etiqueta <a> Haga clic acá para recargar el listado, y al dar clic, va a reedireccionar.
			// Recordemos que la variable url contiene esto: index.php?vista=user_list&page=
			$tabla.='
				<tr class="has-text-centered" >
					<td colspan="7">
						<a href="'.$url.'1" class="button is-link is-rounded is-small mt-4 mb-4">
							Haga clic acá para recargar el listado
						</a>
					</td>
				</tr>
			';
		}else{
			// En caso de no tener registros me mandara un msj de No hay regstros en el sistema que se mostrara en mi tabla
			$tabla.='
				<tr class="has-text-centered" >
					<td colspan="7">
						No hay registros en el sistema
					</td>
				</tr>
			';
		}
	}

	// Cierro las etiquetas del div, table y tbody que se abrieron al inicio del codigo para tener el HTML completo en la variable tabla
	$tabla.='</tbody></table></div>';

	// Lo que hace este if es ver si hay registros y si estamos ubicados en una pagina existente es decir en una pagina valida.
	// Si la cantidad de datos ($total) es mayor a 0 AND la pagina en la que estamos ubicados ($pagina) es menor o igual al 
	// numero de paginas que se tienen que crear en el paginador ($Npaginas) entonces:
	if($total>0 && $pagina<=$Npaginas){
		// Mostramos el mensaje abajo de la tabla que diria algo asi por ej. Mostrando usuarios del 1 al 5 de un total de 17
		$tabla.='<p class="has-text-right">Mostrando usuarios <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
	}

	// Cerramos la conexion
	$conexion=null;
	// Mostramos la tabla ya constuida
	echo $tabla;

	// Lo que hace este if es ver si hay registros y si estamos ubicados en una pagina existente es decir en una pagina valida.
	if($total>=1 && $pagina<=$Npaginas){
		// Mostramos el paginador usando la funcion del archivo main.php y mandandole los parametros necesarios.
		// Decimos que por cada paginador solo me muestre 7 botones
		echo paginador_tablas($pagina,$Npaginas,$url,7);
	}