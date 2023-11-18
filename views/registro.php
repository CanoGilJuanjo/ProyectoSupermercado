<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registro</title>
        <link rel="stylesheet" href="styles/bootstrap.min.css">
        <link rel="stylesheet" href="styles/estiloInicio.css">
        <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">
    </head>
    <body>
        <div class="container">
            <h1>Registro</h1>
            <form action="" method="post">
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuario</label>
                    <input type="text" name="usuario" id="usuario" minlength="4" maxlength="12" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="contrasena" class="form-label">Contraseña</label>
                    <input type="password" name="contrasena" id="contrasena" maxlength="255" minlength="" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="fecha" class="form-label">Fecha nacimiento</label>
                    <input type="date" name="fecha" id="fecha" class="form-control">
                </div>
                <input type="submit" value="Registarse" name="registro" class="btn btn-primary">
                <input type="submit" value="Iniciar sesion" name="registro" class="btn btn-primary">
            </form>
            <?php 
                require "../util/funciones.php";
                
                if($_SERVER["REQUEST_METHOD"]=="POST" && $_POST["registro"] == "Iniciar sesion"){
                    header("location: index.php");
                }else if($_SERVER["REQUEST_METHOD"] == "POST"){
                    //boleanos de comprobacion
                    $cond1 = false;
                    $cond2 = false;
                    $cond3 = false;
                    $cond4 = false;

                    //comprobar que acepte letras y _
                    $usuario = $_POST["usuario"];
                    $regex = "/^[a-zA-Z_]{4,12}$/";
                    if(strlen($usuario) >= 5 && preg_match($regex, $usuario)){
                        $cond1 = true;
                    }else{
                        switch(true){
                            case strlen($usuario) <=4: echo "<p class='text-danger bg-light p-4 rounded-3 text-center'>El campo usuario tiene que tener longitud 4 como minimo</p>"; break;
                            case !preg_match($regex, $usuario): echo "<p class='text-danger bg-light p-4 rounded-3 text-center'>Error el usuario solo puede tener letras y _</p>"; break;
                            default: echo "<p class='text-danger bg-light p-4 rounded-3 text-center'>Error desconocido</p>"; break;
                        }
                    }

                    #Validamos la contraseña
                    $contrasena = $_POST["contrasena"];
                    $contrasenaCifrada = null;
                    $regex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,20}$/";
                    if($cond1 && !preg_match($regex,$contrasena)){
                        echo "<p class='text-danger bg-light p-4 rounded-3 text-center'>Error la conraseña tiene que tener una mayuscula, una minuscula, un numero y un caracter especial</p>";
                    }else{
                        $cond2 = true;
                        #Codificamos la contrasena con los has
                        $contrasenaCifrada = password_hash($contrasena, PASSWORD_DEFAULT);
                    }
                    
                    //Comprobar que sea menor que 120 y mayor que 12
                    $fecha = $_POST["fecha"];
                    if($fecha && $cond1 && $cond2){;
                        $fecha = new DateTime($fecha);
                        $fechaHoy = new DateTime(date("Y-m-d"));
                        $diferencia = $fechaHoy->diff($fecha);
                        $diferencia =  $diferencia->format("%y");
                        if($diferencia<120 && $diferencia>12){
                            $cond3 = true;
                            $fecha = $_POST["fecha"];
                        }else{
                            echo "<p class='text-danger bg-light p-4 rounded-3 text-center'>Error la edad tiene que ser mayor que 12 y menor que 120</p>";
                        }
                    }

                    //Comprobamos que el usuario no este ya insertado
                    if($cond1 && $cond2 && $cond3 && !sqlUsuariosExistenteNombre($usuario)){
                        $cond4 = true;
                    }else if($cond1 && $cond2 && $cond3){
                        echo "<p class='text-danger bg-light p-4 rounded-3 text-center'>Error el usuario ya existe</p>";
                    }

                    //Salida
                    if($cond1 && $cond2 && $cond3 && $cond4){
                        #conexion
                        $file = fopen("../BaseDatos/InsertarUsuarios.sql","a");
                        $conexion = sqlConexionProyectoSupermercado();
                        
                        $rol = "cliente";
                        /*Administrador por defecto*/
                        if(strtolower($usuario) == "juanjo"){
                            $rol = "admin";
                        }

                        $sql = "INSERT INTO usuarios VALUES('$usuario','$contrasenaCifrada','$fecha','$rol');";
                        $conexion -> query($sql);
                        fwrite($file,$sql."\n");
                        $sql = "INSERT into cestas values(null,'$usuario','0');";
                        $conexion -> query($sql);
                        fwrite($file,$sql."\n");
                        fclose($file);
                        session_start();
                        $_SESSION["rol"] = $rol;
                        $_SESSION["usuario"] = $usuario;
                        header("location: paginaPrincipal.php");
                        
                    }
                }
            ?>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
</html>