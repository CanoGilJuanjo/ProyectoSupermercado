<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Insertar productos</title>
    </head>
    <body>
        <h1>Insertar productos</h1>
        <form action="" method="post">
            <div>
                <label for="idProducto">Id del producto</label>
                <input type="text" name="idProducto" id="idProducto">
            </div>
            <div>
                <label for="nombre">Nombre del producto</label>
                <input type="text" name="nombre" id="nombre">
            </div>
            <div>
                <label for="precio">Precio</label>
                <input type="number" name="precio" id="precio">
            </div>
            <div>
                <label for="descripcion">Descripcion</label>
                <input type="text" name="descripcion" id="descripcion">
            </div>
            <div>
                <label for="cantidad">Cantidad del producto</label>
                <input type="number" name="cantidad" id="cantidad">
            </div>
            <input type="submit" value="Enviar">
            <?php
                require "funciones.php";
                if($_SERVER["REQUEST_METHOD"] == "POST"){
                    $id = $_POST["idProducto"];
                    $nombre = $_POST["nombre"];
                    $precio = $_POST["precio"];
                    $descripcion = $_POST["descripcion"];
                    $cantidad = $_POST["cantidad"];

                    $conexion = sqlConexionProyectoSupermercado();
                    $sql = "INSERT into productos values('$id','$nombre','$precio','$descripcion','$cantidad')";
                    $conexion -> query($sql);
                }
            ?>
        </form>
    </body>
</html>