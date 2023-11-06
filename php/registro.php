<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registro</title>
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/inicioRegistro.css">
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
                    <input type="password" name="contrasena" id="contrasena" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="fecha" class="form-label">Fecha nacimiento</label>
                    <input type="date" name="fecha" id="fecha" class="form-control">
                </div>
                <input type="submit" value="Registarse" name="registro" class="btn btn-primary">
                <input type="submit" value="Iniciar sesion" name="registro" class="btn btn-primary">
            </form>
            <?php 
                require "funciones.php";
                
                if($_SERVER["REQUEST_METHOD"]=="POST" && $_POST["registro"] == "Iniciar sesion"){
                    header("location: index.php");
                }else if($_SERVER["REQUEST_METHOD"] == "POST"){
                    //boleanos de comprobacion
                    $cond1 = false;
                    $cond2 = false;

                    //comprobar que acepte letras y _
                    $usuario = $_POST["usuario"];
                    $regex = "/^[a-zA-Z_]+$/";
                
                    if(strlen($usuario) >= 0 && preg_match($regex, $usuario)){
                        $cond1 = true;
                    }else{
                        switch(true){
                            case strlen($usuario) == 0: echo "<p class='text-danger bg-light p-4 rounded-3'>El campo usuario no puede estar vacio</p>"; break;
                            case !preg_match($regex, $usuario): echo "<p class='text-danger bg-light p-4 rounded-3'>Error el usuario solo puede tener letras y _</p>"; break;
                            default: echo "<p class='text-danger bg-light p-4 rounded-3'>Error desconocido</p>"; break;
                        }
                    }

                    //Sin restricciones
                    $contrasena = $_POST["contrasena"];
                    #Codificamos la contrasena con los has
                    $contrasenaCifrada = password_hash($contrasena, PASSWORD_DEFAULT);
                    
                    //Comprobar que sea menor que 120 y mayor que 12
                    $fecha = $_POST["fecha"];
                    $fechaHoy = date("o-m-d");     
                    if($fecha){
                        $añoHoy = (int) explode("-",$fechaHoy)[0];
                        $año = (int)explode("-",$fecha)[0];
                        $mes = (int)explode("-",$fecha)[1];
                        $mesHoy = (int)explode("-",$fechaHoy)[1];
                        $diaHoy = (int)explode("-",$fechaHoy)[2];
                        $dia = (int)explode("-",$fecha)[2];
                      
                        if($cond1 && $añoHoy-$año>12 && $mesHoy-$mes<0 && $añoHoy-$año<=120){
                            $cond2 = true;
                        }else if($cond1 && $añoHoy-$año>12 && $mesHoy-$mes==0 && $añoHoy-$año<=120){
                            if($diaHoy - $dia <= 0){
                                $cond2 = true;
                            }else{
                                echo "<p class='text-danger bg-light p-4 rounded-3'>Error en la fehca de nacimiento, tiene que superar los 12 años y ser menor de 120</p>";
                            }
                        }else if($cond1){
                            echo "<p class='text-danger bg-light p-4 rounded-3'>Error en la fehca de nacimiento, tiene que superar los 12 años y ser menor de 120</p>";
                        }
                    }

                    //Salida
                    if($cond1 && $cond2){
                        #conexion
                        $conexion = sqlConexionProyectoSupermercado();
                        $sql = "INSERT INTO usuarios VALUES('$usuario','$contrasenaCifrada','$fecha');";
                        $conexion -> query($sql);
                        $sql = "INSERT into cestas values(null,'$usuario','0');";
                        $conexion -> query($sql);
                    }
                }
            ?>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
</html>