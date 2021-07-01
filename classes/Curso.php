<?php

require_once './config/Conection.php';
require_once './classes/errors/Answers.php';

class Curso
{
    //! obtener cursos    
    /**
     * listaPost
     *
     * @param  mixed $pagina
     * @return array
     */
    public static function obtenerCursos()
    {
        //! conectar la bd
        $conn = Conection::conectar();
        //! consulta sql
        $sql = "SELECT * FROM cursos";
        //! guardar la consulta en memoria para ser analizada 
        $stmt = $conn->prepare($sql);
        //! ejecutar consulta
        if ($stmt->execute())
        {
            // traemos todos los cursos en un array asociativo
            $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // devolver cursos
            return $cursos;
        } else {
            // error 500 interno del servidor
            return Answers::error_500();
        }
    }

    //! obtener curso por id    
    /**
     * obtenerPost
     *
     * @param  mixed $id
     * @return array
     */
    public static function obtenerCurso($id)
    {
        //* si el id esta vacio en los datos de la url
        if($id == "")
        {
            // error 400 datos incompletos
            return Answers::error_400();
        //* si el id esta ok en los datos de la url
        } else {
            //! conectar la bd
            $conn = Conection::conectar();
            //! consulta sql
            $sql = "SELECT * FROM cursos WHERE id = :id";
            //! guardar la consulta en memoria para ser analizada 
            $stmt = $conn->prepare($sql);
            //! bindear parametros
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            //! ejecutar consulta
            if ($stmt->execute())
            {
                // traer el curso en un array asociativo
                $curso = $stmt->fetch(PDO::FETCH_ASSOC);
                // devolver curso o no encontrado
                return $curso ? $curso : Answers::error_404();
            } else {
                // error 500 interno del servidor
                return Answers::error_500();
            }
        }
    }

    //! capturar datos y crear curso    
    /**
     * capturarPost
     *
     * @param  mixed $array
     * @return array
     */
    public static function nuevoCurso($nombre)
    {
        //* si no existe alguno de los campos en los datos
        if($nombre == '')
        {
            // error 400 datos incompletos
             return Answers::error_400();
        //* si existen los campos en los datos
        } else {
            //* guardar curso 
            $resp = self::insertarCurso($nombre);
            // si se guardo
            if($resp)
            {
                // devolver 201 elemento guardado
                return Answers::cod_201();
            // si no se guardo
            } else {
                // error 500 interno del servidor
                return Answers::error_500();
            }
        }
    } 

    //! insertar curso    
    /**
     * insertarCurso
     *
     * @param  mixed $title
     * @param  mixed $status
     * @param  mixed $content
     * @param  mixed $user_id
     * @return bool
     */
    private static function insertarCurso($nombre)
    {
        //! conectar la bd
        $conn = Conection::conectar();
        //! consulta sql
        $sql = "INSERT INTO cursos (nombre) VALUES (:nombre)";
        //! guardar la consulta en memoria para ser analizada 
        $stmt = $conn->prepare($sql);
        //! bindear parametros
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        //! ejecutar consulta
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    //! capturar datos y actualizar curso    
    /**
     * actualizarCurso
     *
     * @param  mixed $json
     * @return array
     */
    public static function actualizarCurso($datos, $id)
    {
        //* si el id recibido esta vacio
        if($id == '')
        {
            // error 400 datos incompletos
            return Answers::error_400();
        } else {
            // convertir de json a array
            $datos = json_decode($datos, true);
            // si el nombre esta vacio o no existe
            if (!isset($datos['nombre']) || empty($datos['nombre']))
            {
                // error 400 datos incompletos
                return Answers::error_400();
            } else {
                // guardar valores recibidos
                $nombre = $datos['nombre'];
                //* actualizar curso 
                $resp = self::modificarCurso($id, $nombre);
                // si se actualizo
                if($resp)
                {
                    // devolver 200 curso eliminado
                    return Answers::cod_200('Curso actualizado!');
                // si no se guardo
                } else {
                    // error 404 interno del servidor
                    return Answers::error_404();
                }
            }
        }

    }

    //! actualizar curso    
    /**
     * modificarCurso
     *
     * @param  mixed $id
     * @param  mixed $nombre
     * @return bool
     */
    private static function modificarCurso($id, $nombre)
    {
        //! conectar la bd
        $conn = Conection::conectar();
        //! consulta sql
        $sql = "UPDATE cursos SET nombre = :nombre WHERE id = :id";
        //! guardar la consulta en memoria para ser analizada 
        $stmt = $conn->prepare($sql);
        //! bindear parametros
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        //! ejecutar consulta
        if ($stmt->execute()) {
            // numero de filas afectadas
            $row = $stmt->rowCount();
            return $row;
        } else {
            // error 500 interno del servidor
            return Answers::error_500();
        }
    }

    //! capturar datos y eliminar post    
    /**
     * eliminarCurso
     *
     * @param  mixed $id
     * @return array
     */
    public static function eliminarCurso($id)
    {
        //* si el id recibido esta vacio
        if($id == '')
        {
            // error 400 datos incompletos
            return Answers::error_400();
        } else {
            //* eliminar curso 
            $resp = self::borrarCurso($id);
            // si se elimino
            if($resp)
            {
                // devolver 200 curso eliminado
                return Answers::cod_200('Curso eliminado!');
            // si no se guardo
            } else {
                // error 404 interno del servidor
                return Answers::error_404();
            }
        }
    }  

    //! eliminar post    
    /**
     * borrarCurso
     *
     * @param  mixed $id
     * @return bool
     */
    private static function borrarCurso($id)
    {
        //! conectar la bd
        $conn = Conection::conectar();
        //! consulta sql
        $sql = "DELETE FROM cursos WHERE id = :id";
        //! guardar la consulta en memoria para ser analizada 
        $stmt = $conn->prepare($sql);
        //! bindear parametros
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        //! ejecutar consulta
        if ($stmt->execute()) {
            // numero de filas afectadas
            $row = $stmt->rowCount();
            return $row;
        } else {
            // error 500 interno del servidor
            return Answers::error_500();
        }
    }
}