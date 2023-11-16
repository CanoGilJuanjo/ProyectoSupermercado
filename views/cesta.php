<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cesta</title>
        <link rel="stylesheet" href="styles/bootstrap.min.css">
        <link rel="stylesheet" href="styles/estiloCesta.css">
        <?php
            #Funciones requeridas para esta pagina
            require "../util/funciones.php";
            $conexion = sqlConexionProyectoSupermercado();
        ?>
    </head>
    <body>
        <?php
            #Iniciamos sesion para traer el usuario
            session_start();
            #Comprobamos la sesion
            if($_SESSION["usuario"] == null){
                header("location: index.php");
            }

            $usuario = $_SESSION["usuario"];
    
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                #El boton volver devuelve a la pagina principal
                if($_POST["envio"] == "Volver"){
                    header("location: paginaPrincipal.php");
                }else 
                #El boton cerra sesion cierra la sesion actual y devuelve al inicio de sesion
                if($_POST["envio"] == "Cerrar sesion"){
                    session_destroy();
                    header("location: index.php");
                }
            }
        ?>
        <div class="container">
            <h1>Cesta de <?php echo $usuario;?></h1>

            <?php 
                #Tenemos que mostrar en una tabla los productos de nuestra cesta
                $sql = "SELECT idCesta from cestas where usuario = '$usuario'";
                $resultado = $conexion->query($sql);
                $sql = "SELECT * from productoscestas where idCestas = '".$resultado->fetch_assoc()["idCesta"]."'";
                $resultado = $conexion->query($sql);
                while($fila = $resultado->fetch_assoc()){
                    $sql = "SELECT * from productos where idProducto = '".$fila["idProducto"]."'";

                }
            ?>

            <form action="" method="post">
                <input type="submit" value="Cerrar sesion" name="envio" class="btn btn-primary">
                <input type="submit" value="Volver" name="envio" class="btn btn-primary">
            </form>
        </div>
    </body>
</html>