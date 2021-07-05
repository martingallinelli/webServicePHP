# **API REST** 

> Web service desarollado en PHP y con base de datos MySQL.  
> Permite listar todos los cursos almacenados en la DB, listar un curso en particular, crear un nuevo curso, actualizar un curso ya existence, y/o eliminar un curso.

<br>

### METHOD GET

    /service.php

    /service.php?id=:idPost

<br>

### METHOD POST

    /service.php

    {
        "nombre": ""       -> REQUERIDO
    }

<br>

### METHOD PUT

    /service.php?id=:idPost

    {
        "nombre": ""       -> REQUERIDO
    }

<br>

### METHOD DELETE

    /service.php?id=:idPost