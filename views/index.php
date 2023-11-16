<!DOCTYPE html>
<html lang="es">
    <head>
        <!-- Creador: Juanjo Cano Gil -->
        <!-- Fecha de ultima actualizacion:  03-11-2023 -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="styles/bootstrap.min.css">
        <link rel="stylesheet" href="styles/estilo.css">
        <?php 
            require "../util/funciones.php";
            $conexion = sqlConexionProyectoSupermercado();
        ?>
    </head>
    <body>
        
        <div class="container">
            <h1>Inicio de sesion</h1>
            <form action="" method="post">
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuario</label>
                    <input type="text" name="usuario" id="usuario" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="contrasena" class="form-label">Contraseña</label>
                    <input type="password" name="contrasena" id="" class="form-control">
                </div>
                <input type="submit" value="Iniciar sesion" name="acceso" class="btn btn-primary">
                <input type="submit" value="Invitado" name="acceso" class="btn btn-primary">
                <input type="submit" value="Registrarse" name="acceso" class="btn btn-primary">
            </form>
            <?php 
                if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["acceso"]=="Invitado"){
                    //Si iniciamos sesion como invitado
                    session_start();
                    $_SESSION["usuario"] = "Invitado";
                    $_SESSION["rol"] = "cliente";
                    header("location: paginaPrincipal.php");
                }else if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["acceso"]=="Registrarse"){
                    //Cambiamos de pagina a la de registro
                    header("location: registro.php");
                }else if($_SERVER["REQUEST_METHOD"] == "POST"){
                    //Guardamos los datos del formulario
                    $usuario = $_POST["usuario"];
                    $contrasena = $_POST["contrasena"];
                    
                    //Condiciones que se tienen que cumplir para acceder
                    $cond1 = false;
                    $cond2 = false;

                    //Comprobamos que exista el usuario
                    if(sqlUsuariosExistenteNombre($usuario)){ 
                        $cond1 = true;
                    }else{
                        echo "<p class='text-danger bg-light p-4 rounded-3'>Error el usuario no existe</p>";
                    }

                    //Traemos los registros que coincidan con el usuario y realizamos el resto de comprobaciones
                    if($cond1){
                        $sql = "SELECT * from usuarios where usuario = '$usuario'";
                        $resultado = $conexion -> query($sql);
                        
                        $contrasenaCifrada = "";
                        while($fila = $resultado -> fetch_assoc()){
                            $contrasenaCifrada = $fila["contrasena"];
                            $rol = $fila["rol"];
                        }
                        
                        $acceso = password_verify($contrasena, $contrasenaCifrada);
                        
                        if($acceso){
                            session_start();
                            $_SESSION["usuario"] = $usuario ;
                            $_SESSION["rol"] = $rol;
                            echo "Bienvenido ".$usuario;
                            header("location: paginaPrincipal.php");
                        }else{
                            echo "<p class='text-danger bg-light p-4 rounded-3'>Error en la contraseña o usuario </p>";
                        }
                    }
                }
            ?>
        </div>
    </body>
</html>