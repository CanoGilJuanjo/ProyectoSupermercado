<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pagina principal</title>
        <link rel="stylesheet" href="../css/bootstrap.min.css">
    </head>
    <body>
        <?php 
            session_start(); 
            $usuario = $_SESSION["usuario"];
            $rol = $_SESSION["rol"];
        ?>
        <div class="container">
            <h1>Esta es la pagina principal</h1>
            <h2>Bienvenido <?php echo $usuario; ?></h2>
        </div>
    </body>
</html>