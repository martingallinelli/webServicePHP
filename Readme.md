# **API REST** 

> Web service desarollado en PHP y con base de datos MySQL.

<br>

### METHOD GET

    /posts.php?page=:numeroPagina

    /posts.php?id=:idPost

<br>

### METHOD POST

    /posts.php

    {
        "title" : "",       -> REQUERIDO
        "status" : "",      -> REQUERIDO
        "content": "",      -> REQUERIDO
        "user_id" : ""      -> REQUERIDO
    }

<br>

### METHOD PUT

    /posts.php

    {
        "id" : "",          -> REQUERIDO
        "title" : "",  
        "status" : "", 
        "content": "", 
        "user_id" : "" 
    }

<br>

### METHOD DELETE

    /posts.php

    {
        "id" : ""          -> REQUERIDO
    }