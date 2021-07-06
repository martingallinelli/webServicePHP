<?php 

class Answers
{    
    // mensajes
    private static $mensajes = [
        '200' => 'Ok',
        '201' => 'Curso guardado',
        '400' => 'Datos enviados incompletos o con formato incorrecto',
        '401' => 'Acceso no autorizado',
        '404' => 'Curso no encontrado',
        '405' => 'Método no permitido',
        '500' => 'Error interno del servidor'
    ];

    /**
     * mensaje
     *
     * @param  mixed $codigo
     * @param  mixed $mensaje
     * @return array
     */
    public static function mensaje($codigo, $mensaje = '')
    {
        $response['success'] = $codigo;
        $response['result'] = ($mensaje == '') ? self::$mensajes[$codigo] : $mensaje;
        return $response;
    }
}