<?php

require_once 'classes/Curso.php';
require_once './classes/Log.php';

// $_SERVER['REQUEST_METHOD'] = averiguar el metodo con el que se solicito este documento

// analizar request method
switch ($_SERVER['REQUEST_METHOD']) {
    /**
     * ? GET
     * ? se debe enviar por url el id del post [id] o nada
     */
    case 'GET':
        //* si existe id en url (listar un curso)
        if(isset($_GET['id']))
        {
            //* obtener el curso segun el id
            $curso = Curso::obtenerCurso($_GET['id']);
            // indicar tipo de respuesta en el header
            header("Content-Type: application/json");
            // si existe un error (400-500) o no (200)
            isset($curso['success']) ? http_response_code($curso['success']) : http_response_code(200);
            // convertir a json e imprimir
            echo json_encode($curso);
        //* si no existe id en url (listar todos los cursos)
        } else {
            //* obtener todos los cursos
            $cursos = Curso::obtenerCursos();
            // indicar tipo de respuesta en el header
            header("Content-Type: application/json");
            // si existe un error (400-500) o no (200)
            isset($cursos['success']) ? http_response_code($cursos['success']) : http_response_code(200);
            // convertir a json e imprimir
            echo json_encode($cursos);
        }
        break;

    /**
     * ? POST
     * ? se debe enviar en el body de la respuesta los campos a guardar
     */    
    case 'POST':
        // capturar el nombre
        $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
        //* guardar el curso
        $response = Curso::nuevoCurso($nombre);
        // indicar tipo de respuesta en el header
        header('Content-Type: application/json');
        // si existe un error (400-500) o no (201)
        http_response_code($response['success']);
        // convertir a json e imprimir
        echo json_encode($response);
        break;

    /**
     * ? PUT
     * ? se debe enviar en el body de la respuesta los campos a actualizar y el id del registro a actualizar
     */
    case 'PUT':
        // capturar datos recibidos
        $datos = file_get_contents("php://input");
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        //* actualizar el curso
        $response = Curso::actualizarCurso($datos, $id);
        // indicar tipo de respuesta en el header 
        header('Content-Type: application/json');
        // si existe un error (400-500) o no (200)
        http_response_code($response['success']);
        // convertir array a string
        echo json_encode($response);
        break;

    /**
     * ? DELETE
     * ? se debe enviar por url el id del post [id]
     */
    case 'DELETE':
        // capturar el id recibido
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        //* eliminar el curso
        $response = Curso::eliminarCurso($id);
        // indicar tipo de respuesta en el header 
        header('Content-Type: application/json');
        // si existe un error (400-500) o no (200)
        http_response_code($response['success']);
        // convertir array a string
        echo json_encode($response);
        break;
    
    /**
     * ? OTRO CASO
     */
    default:
        // guardar log (a+, seguir escribiendo sin sobreescribir lo existente)
        Log::saveLog('a+', 'Ocurrio un error! HTTP Status Code: 405 | Method: ' . $_SERVER['REQUEST_METHOD']);
        // indicar tipo de respuesta en el header
        header('Content-Type: application/json');
        // error 405 metodo no permitido
        $response = Answers::mensaje('405', 'Método no permitido');
        // convertir array a string
        echo json_encode($response);
        break;
}

/**
 * ? GET
 * ? se debe enviar por url el id del post [id] o nada
 */
/* if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    //* si existe id en url (listar un curso)
    if(isset($_GET['id']))
    {
        //* obtener el curso segun el id
        $curso = Curso::obtenerCurso($_GET['id']);
        // indicar tipo de respuesta en el header
        header("Content-Type: application/json");
        // si existe un error (400-500) o no (200)
        isset($curso['success']) ? http_response_code($curso['success']) : http_response_code(200);
        // convertir a json e imprimir
        echo json_encode($curso);
    //* si no existe id en url (listar todos los cursos)
    } else {
        //* obtener todos los cursos
        $cursos = Curso::obtenerCursos();
        // indicar tipo de respuesta en el header
        header("Content-Type: application/json");
        // si existe un error (400-500) o no (200)
        isset($cursos['success']) ? http_response_code($cursos['success']) : http_response_code(200);
        // convertir a json e imprimir
        echo json_encode($cursos);
    } */

/**
 * ? POST
 * ? se debe enviar en el body de la respuesta los campos a guardar
 */
/* } elseif($_SERVER['REQUEST_METHOD'] == "POST")
{
    // capturar el nombre
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
    //* guardar el curso
    $response = Curso::nuevoCurso($nombre);
    // indicar tipo de respuesta en el header
    header('Content-Type: application/json');
    // si existe un error (400-500) o no (201)
    http_response_code($response['success']);
    // convertir a json e imprimir
    echo json_encode($response);  */

/**
 * ? PUT
 * ? se debe enviar en el body de la respuesta los campos a actualizar y el id del registro a actualizar
 */
/* } elseif($_SERVER['REQUEST_METHOD'] == "PUT")
{
    // capturar datos recibidos
    $datos = file_get_contents("php://input");
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    //* actualizar el curso
    $response = Curso::actualizarCurso($datos, $id);
    // indicar tipo de respuesta en el header 
    header('Content-Type: application/json');
    // si existe un error (400-500) o no (200)
    http_response_code($response['success']);
    // convertir array a string
    echo json_encode($response); */

/**
 * ? DELETE
 * ? se debe enviar por url el id del post [id]
 */
/* } elseif($_SERVER['REQUEST_METHOD'] == "DELETE")
{
    // capturar el id recibido
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    //* eliminar el curso
    $response = Curso::eliminarCurso($id);
    // indicar tipo de respuesta en el header 
    header('Content-Type: application/json');
    // si existe un error (400-500) o no (200)
    http_response_code($response['success']);
    // convertir array a string
    echo json_encode($response); */

/**
 * ? OTRO CASO
 */
/* } else {
    // indicar tipo de respuesta en el header
    header('Content-Type: application/json');
    // error 405 metodo no permitido
    $response = Answers::mensaje('405', 'Método no permitido');
    // convertir array a string
    echo json_encode($response);
} */