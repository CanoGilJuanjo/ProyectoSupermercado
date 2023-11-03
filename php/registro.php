<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registro</title>
        <link rel="stylesheet" href="../css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <h1>Registro</h1>
            <form action="" method="post">
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuario</label>
                    <input type="text" name="usuario" id="usuario" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="contrasena" class="form-label">Contraseña</label>
                    <input type="password" name="contrasena" id="" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="fecha" class="form-label">Fecha nacimiento</label>
                    <input type="date" name="fecha" id="fecha" class="form-control">
                </div>
                <input type="submit" value="Registarse" class="btn btn-primary">
            </form>
        </div>
        <?php 
            require "funciones.php";
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                //comprobar que acepte letras y _
                $usuario = $_POST["usuario"];

                //Sin restricciones
                $contrasena = $_POST["contrasena"];

                //Comprobar que sea menor que 120 y mayor que 12
                $fecha = $_POST["fecha"];
                

                #Codificamos los datos con los has
                $contrasenaCifrada = password_hash($contrasena, PASSWORD_DEFAULT);

                #conexion
                $conexion = sqlConexionProyectoSupermercado();
                $sql = "INSERT INTO usuarios VALUES('$usuario','$contrasenaCifrada','$fecha')";
                $conexion -> query($sql);
            }
        ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
</html>