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
            <h2>Lista de productos</h2>
            <?php
                #Obtenemos la conexion y los productos de la base de datos
                require "funciones.php";
                $conexion = sqlConexionProyectoSupermercado();
                $sql = "SELECT * from productos;";
                $resultado = $conexion->query($sql);

                #Usamos el objeto y lo guardamos en un array
                require "objetoProducto.php";
                $productos = [];
                while($fila = $resultado->fetch_assoc()){
                    array_push($productos,new Producto($fila["idProducto"],$fila["nombreProducto"],$fila["precio"],$fila["descripcion"],$fila["cantidad"],$fila["imagen"]));
                }

                #Mostramos los productos en formato tabla
                echo "<table class='table table-dark mt-5'>";
                echo "<tr>";
                echo "<th>Id</th>";
                echo "<th>Nombre</th>";
                echo "<th>Precio</th>";
                echo "<th>Descipcion</th>";
                echo "<th>Cantidad</th>";
                echo "<th>Imagen</th>";
                echo "<th></th>";
                echo "</tr>";
                for($i = 0;$i<count($productos);$i++){
                    echo "<tr>";
                    echo "<td>".$productos[$i]->idProducto."</td>";
                    echo "<td>".$productos[$i]->nombreProducto."</td>";
                    echo "<td>".$productos[$i]->precio."</td>";
                    echo "<td>".$productos[$i]->descripcion."</td>";
                    echo "<td>".$productos[$i]->cantidad."</td>";
                    echo "<td><img src='".$productos[$i]->imagen."' width='80' height='80'></td>";
                    echo "<td>";?>
                    <form method='post'>
                        <input type="hidden" name="idProducto" value = "<?php echo $productos[$i]->idProducto ?>">
                        <input type="submit" name="envio" value="Añadir" class="btn btn-warning">
                    </form>
            <?php 
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";

                //Si el rol es Admin 
                if($rol == "Admin"){
                    echo "<a href='insertarProductos.php'><button class='btn btn-primary'>Insertar producto</button></a>";
                }
            ?>
            <form method="post">
                <input type="submit" name="envio" value="Cerrar Sesion" class='btn btn-primary mt-3'  >
            </form>
            <?php
                if($_SERVER["REQUEST_METHOD"]=="POST"){
                    if($_POST["envio"] == "Cerrar Sesion"){
                        header("location: index.php");
                        session_destroy();
                    }else if($_POST["envio"] == "Añadir"){
                        $sql = "SELECT nombreProducto from productos where idProducto = '".$_POST["idProducto"]."';";
                        $conexion = sqlConexionProyectoSupermercado();
                        $resultado = $conexion->query($sql);
                        echo "Añadido el producto: " . $_POST["idProducto"]. " ".$resultado->fetch_assoc()["nombreProducto"] ;
                    }
                }
            ?>
        </div>
    </body>
</html>