<?php

 /* Aqui en principal (main vamos a tener todas las funciones que se repiten en el sistema */
 
#  Conexion a la base de datos
function conexion(){
    #  Creamos una variable de instancia a la clase PDO y la conexion a la BD inventario dentro de esa variable
    $pdo = new PDO('mysql:host=localhost;dbname=inventario','root','Benji2003');
    return $pdo;
}

# Verificar datos
function verificar_datos($filtro,$cadena){
    // Si la cadena coincide con el filtro entonces:
    if(preg_match("/^".$filtro."$/",$cadena)){
        return false;
    }else{
        return true;
    }
}

# Limpiar cadenas de texto #
function limpiar_cadena($cadena){
    $cadena=trim($cadena);
    $cadena=stripslashes($cadena);
    $cadena=str_ireplace("<script>", "", $cadena);
    $cadena=str_ireplace("</script>", "", $cadena);
    $cadena=str_ireplace("<script src", "", $cadena);
    $cadena=str_ireplace("<script type=", "", $cadena);
    $cadena=str_ireplace("SELECT * FROM", "", $cadena);
    $cadena=str_ireplace("DELETE FROM", "", $cadena);
    $cadena=str_ireplace("INSERT INTO", "", $cadena);
    $cadena=str_ireplace("DROP TABLE", "", $cadena);
    $cadena=str_ireplace("DROP DATABASE", "", $cadena);
    $cadena=str_ireplace("TRUNCATE TABLE", "", $cadena);
    $cadena=str_ireplace("SHOW TABLES;", "", $cadena);
    $cadena=str_ireplace("SHOW DATABASES;", "", $cadena);
    $cadena=str_ireplace("<?php", "", $cadena);
    $cadena=str_ireplace("?>", "", $cadena);
    $cadena=str_ireplace("--", "", $cadena);
    $cadena=str_ireplace("^", "", $cadena);
    $cadena=str_ireplace("<", "", $cadena);
    $cadena=str_ireplace("[", "", $cadena);
    $cadena=str_ireplace("]", "", $cadena);
    $cadena=str_ireplace("==", "", $cadena);
    $cadena=str_ireplace(";", "", $cadena);
    $cadena=str_ireplace("::", "", $cadena);
    $cadena=trim($cadena);
    $cadena=stripslashes($cadena);
    return $cadena;
}

# Funcion renombrar fotos 
function renombrar_fotos($nombre){
    $nombre=str_ireplace(" ", "_", $nombre);
    $nombre=str_ireplace("/", "_", $nombre);
    $nombre=str_ireplace("#", "_", $nombre);
    $nombre=str_ireplace("-", "_", $nombre);
    $nombre=str_ireplace("$", "_", $nombre);
    $nombre=str_ireplace(".", "_", $nombre);
    $nombre=str_ireplace(",", "_", $nombre);
    $nombre=$nombre."_".rand(0,100);
    return $nombre;
}

# Funcion paginador de tablas 
function paginador_tablas($pagina,$Npaginas,$url,$botones){
    $tabla='<nav class="pagination is-centered is-rounded" role="navigation" aria-label="pagination">';

    # PARTE DEL BOTON ANTERIOR #
    //Si estamos colocados en la pagina 1 entonces
    if($pagina<=1){
        //Agregaremos el boton de Anterior (deshabilitado) y el inicio de la etiqueta <ul>
        $tabla.='
        <a class="pagination-previous is-disabled" disabled >Anterior</a>
        <ul class="pagination-list">';
    }else{
        //Agregaremos el boton de Anterior (habilitado) y configurado para ir a la pagina anterior por medio de la url
        //Y ponemos el boton para ir a la pagina 1 por url ya que NO estamos ubicados en la 1 
        //Y ponemos el ultimo <li> que es los 3 puntitos osea el (separador) de botones que se ve
        $tabla.='
        <a class="pagination-previous" href="'.$url.($pagina-1).'" >Anterior</a>
        <ul class="pagination-list"> 
            <li><a class="pagination-link" href="'.$url.'1">1</a></li>
            <li><span class="pagination-ellipsis">&hellip;</span></li>
        ';
    }

    # PARTE DONDE SE VAN A MOSTRAR LOS BOTONES DEL PAGINADOR #
    //Ocupare un contador llamado ci (contador de iteraciones)
    $ci=0;
    for($i=$pagina; $i<=$Npaginas; $i++){
        
        //Si el contador es mayor o igual al numero de botones
        if($ci>=$botones){
            // Detenemos el ciclo for
            break;
        }

        // CREACION DINAMICA DE LOS BOTONES:
        //Si la pagina actual es igual al valor de i de mi ciclo entonces:
        if($pagina==$i){
            // Se agrega un boton con un color distinto (azul)
            $tabla.='<li><a class="pagination-link is-current" href="'.$url.$i.'">'.$i.'</a></li>';
        }else{
            // Se agrega un boton que va a mostrar el numero de paginador que corresponda
            $tabla.='<li><a class="pagination-link" href="'.$url.$i.'">'.$i.'</a></li>';
        }
        $ci++;
    }

    # PARTE DEL BOTON SIGUIENTE #
    //Si estamos colocados en la ultima pagina
    if($pagina==$Npaginas){
        //Cerramos la etiqueta <ul> y agregaremos el boton de Siguiente (deshabilitado) 
        $tabla.='
        </ul>
        <a class="pagination-next is-disabled" disabled >Siguiente</a>
        ';
    }else{
        //Agregamos el (separador de botones)
        //Cerramos etiqueta <ul>
        //Agregamos el boton de siguiente (habilitado) y configurado para su funcionamiento mediante url
        $tabla.='
            <li><span class="pagination-ellipsis">&hellip;</span></li>
            <li><a class="pagination-link" href="'.$url.$Npaginas.'">'.$Npaginas.'</a></li>
        </ul>
        <a class="pagination-next" href="'.$url.($pagina+1).'" >Siguiente</a>
        ';
    }

    $tabla.='</nav>';
    return $tabla;
}

?>