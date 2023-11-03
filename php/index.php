<!DOCTYPE html>
<html lang="es">
    <head>
        <!-- Creador: Juanjo Cano Gil -->
        <!-- Fecha de ultima actualizacion:  03-11-2023 -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <style>
            .container{
                margin: 0 auto;
                background-color: #8c8c8c;
                color: black;
                width: 30%;
                height: 400px;
                padding: 20px;
                font-size: large;
                border-radius: 20px;
                margin-top: 100px;
            }
            input{
                margin: 10px;
            }
        </style>
    </head>
    <body>
        <?php 
            require "funciones.php";
            $conexion = sqlConexionProyectoSupermercado();
        ?>
        <div class="container">
            <h1>Formulario Supermercado</h1>
            <div class="login">
                <form action="" method="push">
                    <label for="usuario" class="form-label">Introduzca su nombre de usuario</label>
                    <input type="text" name="usuario" class="form-control" id="usuario">
                    <label for="contrasena" class="form-label">Introduzca su contraseña</label>
                    <input type="password" class="form-control" name="contrasena" id="contrasena">
                    <input type="submit" value="Iniciar sesion" class="btn btn-secondary border-light">
                </form>
                <?php 
                    if($_SERVER["REQUEST_METHOD"] == "POST"){
                        $sql = "SELECT * FROM usuarios where contrasena = '". $_POST["contrasena"] ."' and usuario = '". $_POST["usuario"] . "';";
                        $resultado = $conexion -> query($sql);
                        if($resultado -> num_rows == 0){
                            echo "Validado con exito";
                        }else{
                            echo "No existe el usuario, registrese para poder acceder";
                        }
                    }
                ?>
            </div>
            <div class="producto">
                
            </div>
        </div>
    </body>
</html>