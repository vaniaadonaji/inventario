/* Esta línea de código está utilizando el método querySelectorAll para seleccionar 
todos los elementos del DOM que tienen la clase CSS ".FormularioAjax" y los está almacenando 
en una variable llamada formularios_ajax*/
const formularios_ajax=document.querySelectorAll(".FormularioAjax");

function enviar_formulario_ajax(e){
    // Evita que el formulario se envíe automáticamente al servidor al hacer clic en el botón de envío
    e.preventDefault();

    //Pregunta al usuario si quiere enviar el formulario, la funcion confirm() ya trae botones de aceptar o cancelar
    let enviar=confirm("Quieres enviar el formulario");

    //Ahora si el usuario dio clic a aceptar(true) en el confirm() entonces:
    if(enviar==true){

        // En esta variable tenemos todos los datos que se quieren enviar
        let data= new FormData(this);
        //  Obtiene el atributo "method" del formulario actual como GET o POST
        let method=this.getAttribute("method");
        // Obtiene el atributo "action" del formulario actual. Este atributo especifica la URL a la que se enviarán los datos del formulario cuando se envíe.
        let action=this.getAttribute("action");

        //  Crea un nuevo objeto Headers. Los Headers permiten manipular y acceder a las cabeceras de una solicitud HTTP.
        let encabezados= new Headers();

        // Crea un objeto o array de configuración para la solicitud Fetch. 
        // Este objeto contiene el método HTTP, los encabezados, el modo (cors, same-origin, etc.), 
        // la política de caché y el cuerpo de la solicitud.
        let config={
            method: method,
            headers: encabezados,
            mode: 'cors',
            cache: 'no-cache',
            body: data
        };

        // Realiza una solicitud Fetch a la URL especificada por el atributo "action" del formulario
        fetch(action,config)
        // Cuando se recibe una respuesta del servidor, se convierte la respuesta a texto.
        .then(respuesta => respuesta.text())
        //  Aquí, se obtiene un contenedor HTML con la clase ".form-rest" y se establece el contenido HTML de ese contenedor como la respuesta del servidor.
        .then(respuesta =>{ 
            let contenedor=document.querySelector(".form-rest");
            contenedor.innerHTML = respuesta;
        });
    }
}

/* Recorre una lista de formularios seleccionamos anteriormente utilizando el selector 
de DOM querySelectorAll con el nombre de clase formularios_ajax. Luego, agrega un evento 
de escucha (addEventListener) a cada uno de estos formularios para el evento de "submit" 
(envío de formulario). Cuando se envía alguno de estos formularios, se llama a la función 
enviar_formulario_ajax. */
formularios_ajax.forEach(formularios => {
    formularios.addEventListener("submit",enviar_formulario_ajax);
});