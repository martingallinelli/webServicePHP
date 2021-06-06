<?php

require_once 'classes/Post.php';

//! crear nuevo objeto de posts
$post = new Post;

// $_SERVER['REQUEST_METHOD'] = averiguar el metodo con el que se solicito este documento

/**
 * ? GET
 * ? se debe enviar por url el id del post [id] o el número de página [page]
 */
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    //* si existe page en url (listar todos los pacientes)
    if(isset($_GET["page"]))
    {
        // guardo el valor de page
        $pagina = $_GET["page"];
        //* obtener posts paginados
        $datosArray = $post->listaPost($pagina);
        //* delvolvemos una respuesta
        // indicar tipo de respuesta en el header
        header("Content-Type: application/json");
        // si existe error_id (hubo un error)
        if(isset($datosArray["result"]["error_id"]))
        {
            // capturar el codigo del error
            $responseCode = $datosArray["result"]["error_id"];
            // indicar el codigo de respuesta con el codigo de error
            http_response_code($responseCode);
        // si no existe error_id
        } else {
            // indicar el codigo de respuesta ok
            http_response_code(200);
        }
        // convertir array a string
        echo json_encode($datosArray);
    //* si existe id en url (listar post por id)
    } elseif(isset($_GET['id']))
    {
        // guardo el valor de id
        $idPost = $_GET['id'];
        //* obtener el post segun el id
        $datoPost = $post->obtenerPost($idPost);
        //* delvolvemos una respuesta
        // indicar tipo de respuesta en el header
        header("Content-Type: application/json");
        // si existe error_id (hubo un error)
        if(isset($datoPost["result"]["error_id"]))
        {
            // capturar el codigo del error
            $responseCode = $datoPost["result"]["error_id"];
            // indicar el codigo de respuesta con el codigo de error
            http_response_code($responseCode);
        // si no existe error_id
        } else {
            // indicar el codigo de respuesta ok
            http_response_code(200);
        }
        // convertir array a string
        echo json_encode($datoPost);
    //* si no existe o esta vacio id o page en url
    } else {
        //* informar error
        $post->mensajeError();
        // indicar el codigo de respuesta con el codigo de error
        http_response_code(400);
    }

/**
 * ? POST
 * ? se debe enviar en el body de la respuesta los campos a guardar
 */
} elseif($_SERVER['REQUEST_METHOD'] == "POST")
{
    // capturar datos recibidos
    $postBody = $_POST;
    //* guardar el post
    $datosArray = $post->capturarPost($postBody);
    //* delvolvemos una respuesta
    // indicar tipo de respuesta en el header
    header('Content-Type: application/json');
    // si existe error_id (hubo un error)
    if(isset($datosArray["result"]["error_id"]))
    {
        // capturar el codigo del error
        $responseCode = $datosArray["result"]["error_id"];
        // indicar el codigo de respuesta con el codigo de error
        http_response_code($responseCode);
    // si no existe error_id
    } else {
        // indicar el codigo de respuesta ok
        http_response_code(200);
    }
    // convertir array a string
    echo json_encode($datosArray); 

/**
 * ? PUT
 * ? se debe enviar en el body de la respuesta los campos a actualizar y el id del registro a actualizar
 */
} elseif($_SERVER['REQUEST_METHOD'] == "PUT")
{
    //* capturar datos recibidos
    $postBody = file_get_contents("php://input");
    //* actualizar el post
    $datosArray = $post->capturarPut($postBody);
    //* delvolvemos una respuesta
    // indicar tipo de respuesta en el header 
    header('Content-Type: application/json');
    // si existe error_id (hubo un error)
    if(isset($datosArray["result"]["error_id"]))
    {
        // enviar el error
        $responseCode = $datosArray["result"]["error_id"];
        http_response_code($responseCode);
    // si no existe error_id
    } else {
        http_response_code(200);
    }
    // convertir array a string
    echo json_encode($datosArray);

/**
 * ? DELETE
 * ? se debe enviar por url el id del post [id]
 */
} elseif($_SERVER['REQUEST_METHOD'] == "DELETE")
{
    //* capturar datos recibidos
    $idPost = file_get_contents("php://input");
    //* eliminar el post
    $datosArray = $post->capturarDelete($idPost);
    //* delvolvemos una respuesta
    // indicar tipo de respuesta en el header 
    header('Content-Type: application/json');
    // si existe error_id (hubo un error)
    if(isset($datosArray["result"]["error_id"]))
    {
        // enviar el error
        $responseCode = $datosArray["result"]["error_id"];
        http_response_code($responseCode);
    // si no existe error_id
    } else {
        http_response_code(200);
    }
    // convertir array a string
    echo json_encode($datosArray);

/**
 * ? OTRO CASO
 */
} else {
    // indicar tipo de respuesta en el header
    header('Content-Type: application/json');
    // error metodo no permitido
    $datosArray = $respuestas->error_405();
    // convertir array a string
    echo json_encode($datosArray);
}