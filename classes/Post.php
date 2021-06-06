<?php

require_once './config/Conection.php';
require_once './classes/errors/Answers.php';

class Post
{
    // datos
    private $title;
    private $status;
    private $content;
    private $user_id;
    private $idPost;

    //! obtener posts
    public function listaPost($pagina)
    {
        // nuevo objeto respuesta
        $respuestas = new Answers;
        //* si el page esta vacio en los datos de la url
        if($pagina == "")
        {
            // error datos incompletos
            return $respuestas->error_400();
        //* si el page esta ok en los datos de la url
        } else {
            //* paginador
            // primer registro primera pagina
            $inicio = 0;
            // cantidad de registros a mostrar
            $cantidad = 50;
            // si la pagina es mayor a 1
            if($pagina > 1)
            {
                // primer registro pagina siguiente
                $inicio = ($cantidad * ($pagina - 1)) + 1;
                // cantidad de registros a mostrar
                $cantidad = $cantidad * $pagina;
            }

            //! conectar la bd
            $conn = Conection::conectar();
            //! consulta sql
            $sql = "SELECT * FROM posts LIMIT :inicio, :fin";
            //! guardar la consulta en memoria para ser analizada 
            $stmt = $conn->prepare($sql);
            //! bindear parametros
            $stmt->bindParam(':inicio', $inicio, PDO::PARAM_INT);
            $stmt->bindParam(':fin', $cantidad, PDO::PARAM_INT);
            //! ejecutar consulta
            if ($stmt->execute())
            {
                // traemos todos los post en un array asociativo
                $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
                //* ejecutar consulta y devolver datos
                return $posts;
            } else {
                // error interno del servidor
                return $respuestas->error_500();
            }
        }
    }

    //! obtener post por id
    public function obtenerPost($id)
    {
        // nuevo objeto respuesta
        $respuestas = new Answers;
        //* si el id esta vacio en los datos de la url
        if($id == "")
        {
            // error datos incompletos
            return $respuestas->error_400();
        //* si el id esta ok en los datos de la url
        } else {
            //! conectar la bd
            $conn = Conection::conectar();
            //! consulta sql
            $sql = "SELECT * FROM posts WHERE id = :id";
            //! guardar la consulta en memoria para ser analizada 
            $stmt = $conn->prepare($sql);
            //! bindear parametros
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            //! ejecutar consulta
            if ($stmt->execute())
            {
                // traemos el post en un array asociativo
                $post = $stmt->fetch(PDO::FETCH_ASSOC);
                //* ejecutar consulta y devolver datos
                return $post;
            } else {
                // error interno del servidor
                return $respuestas->error_500();
            }
        }
    }

    //! capturar datos y crear post
    public function capturarPost($array)
    {
        // nuevo objeto respuesta
        $respuestas = new Answers;
        // capturo el array
        $datos = $array;
        //* si no existe alguno de los campos en los datos
        if(!isset($datos['title']) || !isset($datos['status']) || !isset($datos['content']) || !isset($datos['user_id']))
        {
            // error datos incompletos
             return $respuestas->error_400();
        //* si existen los campos en los datos
        } else {
            // guardar valores enviados por post
            $this->setTitle($datos['title']);
            $this->setStatus($datos['status']);
            $this->setContent($datos['content']);
            $this->setUserId($datos['user_id']);
            //* insertar post 
            $resp = $this->insertarPost();
            // si se guardo
            if($resp)
            {
                // guardar contenido del atributo response
                $respuesta = $respuestas->response;
                // guardar el id del post
                $respuesta["result"] = array(
                    "idPost" => $resp
                );
                // devolver el array con los datos 
                return $respuesta;
            // si no se guardo
            } else {
                // error interno del servidor
                return $respuestas->error_500();
            }
        }
    } 

    //! insertar post
    private function insertarPost()
    {
        //! conectar la bd
        $conn = Conection::conectar();
        //! consulta sql
        $sql = "INSERT INTO posts (title, status, content, user_id) 
                    VALUES (:title, :status, :content, :userid)";
        //! guardar la consulta en memoria para ser analizada 
        $stmt = $conn->prepare($sql);
        //! bindear parametros
        $title = $this->getTitle();
        $status = $this->getStatus();
        $content = $this->getContent();
        $user_id = $this->getUserId();
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':content', $content, PDO::PARAM_STR);
        $stmt->bindParam(':userid', $user_id, PDO::PARAM_INT);
        //! ejecutar consulta
        if ($stmt->execute()) {
            // devolver el id del elemento guardado
            return $conn->lastInsertId();
        } else {
            return false;
        }
    }

    //! capturar datos y actualizar post
    public function capturarPut($json)
    {
        // nuevo objeto respuesta
        $respuestas = new Answers;
        // convertir de json a array
        $datos = json_decode($json,true);
        //* si no existe el id en los datos
        if(!isset($datos['id']))
        {
            // error datos incompletos
            return $respuestas->error_400();
        //* si existe el id en los datos
        } else {
            // guardar valor enviado por put
            $this->setIdPost($datos['id']);
            //* actualizar post 
            $resp = $this->modificarPost($datos);
            // si se guardo
            if($resp)
            {
                // guardar contenido del atributo response
                $respuesta = $respuestas->response;
                // guardar el id del post
                $respuesta["result"] = array(
                    "idPost" => $resp
                );
                // devolver el array con los datos 
                return $respuesta;
            // si no se guardo
            } else {
                // error interno del servidor
                return $respuestas->error_500();
            }
        }

    }

    //! actualizar post
    private function modificarPost($datos)
    {
        //! conectar la bd
        $conn = Conection::conectar();
        /**
         * getParams() = capturar y dividir los parámetros
         * $fields = parametro = :parametro
         */ 
        $parametros = $this->getParams($datos);
        // id del post
        $idPost = $this->getIdPost();
        //! consulta sql
        $sql = "UPDATE posts 
                SET $parametros
                WHERE id = $idPost";
        //! guardar la consulta en memoria para ser analizada 
        $stmt = $conn->prepare($sql);
        //! bindear parametros
        /**
         * bindValues() = prepara la sentencia bindParam()
         * $stmt->bindParam(:parametro, valor)
         */
        $stmt = $this->bindValues($stmt, $datos);
        //! ejecutar consulta
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    //! capturar parametros
    private function getParams($datos)
    {
        // nuevo array
        $parametros = [];
        // recorrer el array con los datos ingresados
        foreach($datos as $param => $value)
        {
            //* agrego al array el nombre del parametro = :parametro
            $parametros[] = "$param = :$param";
        }
        //* concatenar elementos del array con la ,
        return implode(", ", $parametros);
	}

    //! asociar todos los parametros a un sql
	private function bindValues($stmt, $datos)
    {
        // recorrer el array con los datos ingresados 
        foreach($datos as $param => $value)
        {
            //* asociar un parametro con un valor
            $stmt->bindValue(':'.$param, $value);
        }
        // devolver la sentencia bindeada
        return $stmt;
    }

    //! capturar datos y eliminar post
    public function capturarDelete($json)
    {
        // nuevo objeto respuesta
        $respuestas = new Answers;
        // convertir de json a array
        $datos = json_decode($json,true);
        //* si no existe o esta vacio el id en los datos 
        if(!isset($datos['id']) || $datos['id'] == "")
        {
            // error datos incompletos
            return $respuestas->error_400();
        //* si el id esta ok en los datos 
        } else {
            // guardar valor enviado por delete
            $this->setIdPost($datos['id']);
            //* eliminar paciente 
            $resp = $this->eliminarPost();
            // si se elimino
            if($resp)
            {
                // guardar contenido del atributo response
                $respuesta = $respuestas->response;
                // guardar el id del paciente
                $respuesta["result"] = array(
                    "delete" => $resp
                );
                // devolver el array con los datos 
                return $respuesta;
            // si no se guardo
            } else {
                // error interno del servidor
                return $respuestas->error_500();
            }
        }
    }  

    //! eliminar post
    private function eliminarPost()
    {
        //! conectar la bd
        $conn = Conection::conectar();
        //! consulta sql
        $sql = "DELETE FROM posts WHERE id = :idPost";
        //! guardar la consulta en memoria para ser analizada 
        $stmt = $conn->prepare($sql);
        //! bindear parametros
        $id = $this->getIdPost();
        $stmt->bindParam(':idPost', $id, PDO::PARAM_STR);
        //! ejecutar consulta
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    //! mensaje de error
    public function mensajeError()
    {
        // nuevo objeto respuesta
        $respuestas = new Answers;
        $mensaje = json_encode($respuestas->error_400());
        echo($mensaje);
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getIdPost()
    {
        return $this->idPost;
    }

    /**
     * @param mixed $idPost
     */
    public function setIdPost($idPost): void
    {
        $this->idPost = $idPost;
    }
}