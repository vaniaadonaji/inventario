<div class="container is-fluid mb-6">
    <h1 class="title">Usuarios</h1>
    <h2 class="subtitle">Lista de usuarios</h2>
</div>

<div class="container pb-6 pt-6">  
    <?php
        # Incluimos el archivo con nuestras funciones principales #
        require_once "./php/main.php";

        # Eliminar usuario #
        if(isset($_GET['user_id_del'])){
            // Incluimos el archivo para eliminar un usuario #
            require_once "./php/usuario_eliminar.php";
        }

        // Si la variable de tipo GET llamada page NO viene definida entonces:
        if(!isset($_GET['page'])){
            // Entonces creamos la variable pagina con valor 1 (para indicar que estamos ubicados en la pagina 1)
            $pagina=1;
        }else{
            // Si viene definida creamos la variable pagina y le asignamos el valor de la variable GET llamada page convertido a entero
            $pagina=(int) $_GET['page'];
            // Si el valor de la variable pagina es menor o igual a 1 entonces 
            if($pagina<=1){
                // A la variable pagina le asignamos el valor 1, esto para despues CARGAR AL USUARIO LA PAGINA 1 DEL PAGINADO y no una que NO existe como la 0
                $pagina=1;
            }
        }

        // Por seguridad limpiamos el valor de la variable pagina de inyeccion SQL o HTML
        $pagina=limpiar_cadena($pagina);
        // Creamos la variable url con la direccion a la pagina del paginador SIN valor todavia
        $url="index.php?vista=user_list&page=";
        // Creamos la variable registros y el valor que tiene es el numero de registros que se van a mostrar en cada pagina del paginado como MAXIMO
        $registros=10;
        // Esta variable nos va a permitir la busqueda de usuarios 
        $busqueda="";

        # Incluimos el archivo del Paginador usuario #
        require_once "./php/usuario_lista.php";
    ?>
</div>

<!-- Codigo HTML base que se ocupo para la tabla y el paginador: -->

<!-- <div class="container is-fluid mb-6">
    <h1 class="title">Usuarios</h1>
    <h2 class="subtitle">Lista de usuarios</h2>
</div>

<div class="container pb-6 pt-6">

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
                <tr class="has-text-centered" >
					<td>1</td>
                    <td>usuario_nombre</td>
                    <td>usuario_apellido</td>
                    <td>usuario_usuario</td>
                    <td>usuario_email</td>
                    <td>
                        <a href="#" class="button is-success is-rounded is-small">Actualizar</a>
                    </td>
                    <td>
                        <a href="#" class="button is-danger is-rounded is-small">Eliminar</a>
                    </td>
                </tr>

                <tr class="has-text-centered" >
                    <td colspan="7">
                        <a href="#" class="button is-link is-rounded is-small mt-4 mb-4">
                            Haga clic acá para recargar el listado
                        </a>
                    </td>
                </tr>

                <tr class="has-text-centered" >
                    <td colspan="7">
                        No hay registros en el sistema
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    <p class="has-text-right">Mostrando usuarios <strong>1</strong> al <strong>9</strong> de un <strong>total de 9</strong></p>

    <nav class="pagination is-centered is-rounded" role="navigation" aria-label="pagination">
        <a class="pagination-previous" href="#">Anterior</a>

        <ul class="pagination-list">
            <li><a class="pagination-link" href="#">1</a></li>
            <li><span class="pagination-ellipsis">&hellip;</span></li>
            <li><a class="pagination-link is-current" href="#">2</a></li>
            <li><a class="pagination-link" href="#">3</a></li>
            <li><span class="pagination-ellipsis">&hellip;</span></li>
            <li><a class="pagination-link" href="#">3</a></li>
        </ul>

        <a class="pagination-next" href="#">Siguiente</a>
    </nav>

</div> -->