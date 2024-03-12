<!-- Aqui vamos a dirigir al usuario despues de que inicie la session
esta es la pagina principal -->

<div class="container is-fluid home-estilo">
    <h1 class="title">Home</h1>
    <h2 class="subtitle">¡Bienvenido <?php echo $_SESSION['nombre']." ".$_SESSION['apellido']; ?>!</h2>
</div>

<!-- CODIGO QUE UTILIZAMOS COMO GUIA PARA HACER LA FUNCION DEL PAGINADOR EN main.php -->
<!-- <br><br><br>

<nav class="pagination is-centered is-rounded" role="navigation"
aria-label="pagination">

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
</nav> -->