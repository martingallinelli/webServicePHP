<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API - Post</title>
    <link rel="stylesheet" href="public/css/styles.css" type="text/css">
</head>
<body>
    <div class="container">
        <h1>API REST <br>- Posts -</h1>
        <div class="divbody">   
            <code>
            <b>GET</b>  /posts.php?page=<i>{numeroPagina}</i>
            <br>
            <b>GET</b>  /posts.php?id=<i>{idPost}</i>
            </code>

            <code>
            <b>POST  /posts.php</b>
            <br><br>
            {
                <br> 
                "title" : "", <b>-> REQUERIDO</b>
                <br> 
                "status" : "", <b>-> REQUERIDO</b>
                <br> 
                "content": "", <b>-> REQUERIDO</b>
                <br> 
                "user_id" : "" <b>-> REQUERIDO</b>       
                <br>       
            }
            </code>

            <code>
            <b>PUT  /posts.php</b>
            <br><br>
            {
                <br> 
                "id" : "", <b>-> REQUERIDO</b>
                <br> 
                "title" : "", 
                <br> 
                "status" : "",
                <br> 
                "content": "",
                <br> 
                "user_id" : ""       
                <br>      
            }
            </code>

            <code>
            <b>DELETE  /posts.php</b>
            <br><br>
            {       
                <br>       
                "id" : "", <b>-> REQUERIDO</b>
                <br> 
            }
            </code>
        </div>
    </div>
    
</body>
</html>