<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cesta</title>
        <link rel="stylesheet" href="styles/bootstrap.min.css">
        <link rel="stylesheet" href="styles/estiloCesta.css">
        <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">
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
            #Comprobamos el rol del usuario
            if($_SESSION["rol"] != "admin" && $_SESSION["rol"] != "cliente"){ 
                session_destroy();
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
                }else
                #Este boton termina la compra añadiendo el precio total a la cesta
                if($_POST["envio"] == "Terminar compra"){
                    $sql = "SELECT idCesta from cestas where usuario = '$usuario'";
                    $resultado = $conexion->query($sql);
                    $idCesta = $resultado->fetch_assoc()["idCesta"];
                    if(sqlCestaVacia($idCesta)){
                        #Añadimos el precio total de la compra a la cesta
                        $sql = "UPDATE cestas set precioTotal = '".$_POST["totalCesta"]."' WHERE usuario = '$usuario';"; 
                        $conexion->query($sql);
                        echo "<p class='text-bg-info'>Pedido realizado</p>";
                        #Realizamos el pedido y la lineapedido

                        #Reseteamos las tablas cestas y productoscestas

                    }else{
                        $sql = "UPDATE cestas set precioTotal = '0' where usuario = '$usuario';";
                        $conexion->query($sql);
                    }
                }
            }
        ?>
        <div class="container">
            <h1>Cesta de <?php echo $usuario;?></h1>
            <?php 
                #Tenemos que mostrar en una tabla los productos de nuestra cesta
                $sql = "SELECT idCesta from cestas where usuario = '$usuario'";
                $resultado = $conexion->query($sql);
                $sql = "SELECT * from productoscestas where idCesta = '".$resultado->fetch_assoc()["idCesta"]."'";
                $resultado = $conexion->query($sql);
                #Representacion
                $totalCesta = 0;
                echo "<table class = 'table table-hover table-striped text-center'>";
                echo "<thead class='table-dark'>";
                    echo "<tr>";
                        echo "<th> Producto </th>";
                        echo "<th> Imagen </th>";
                        echo "<th> Precio </th>";
                        echo "<th> Cantidad </th>";
                    echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                #Traemos los productos para poder mostrarlos
                while($fila = $resultado->fetch_assoc()){
                    $sql = "SELECT * from productos where idProducto = '".$fila["idProducto"]."'";
                    $resultado2 = $conexion->query($sql);
                    while($fila2 = $resultado2->fetch_assoc()){
                        $totalCesta += $fila2["precio"] * $fila["cantidad"];
                        echo "<tr>";
                        echo "<td>".$fila2["nombreProducto"]."</td>";
                        echo "<td><img src='".$fila2["imagen"]."' width='80' height='80'></td>";
                        echo "<td>".$fila2["precio"]." €</td>";
                        echo "<td>".$fila["cantidad"]."</td>";
                        echo "</tr>";
                    }
                }
                echo "<tr class=' table-dark'>";
                    echo "<td >Precio total</td>";
                    echo "<td></td>";
                    echo "<td>".$totalCesta." €</td>";
                    echo "<td>";
                    ?>
                    <form action="" method="post">
                        <input type="submit" value="Terminar compra" name="envio" class="btn btn-warning">
                        <input type="hidden" value="<?php echo $totalCesta;?>" name="totalCesta">
                    </form>
                    <?php
                    echo "</td>";
                echo"</tr>";
                echo "</tbody>";
                echo "</table>";
            ?>

            <form action="" method="post">
                <input type="submit" value="Cerrar sesion" name="envio" class="btn btn-primary">
                <input type="submit" value="Volver" name="envio" class="btn btn-primary m-2">
            </form>
        </div>
    </body>
</html>