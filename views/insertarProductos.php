<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Insertar productos</title>
        <link rel="stylesheet" href="styles/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <h1>Insertar productos</h1>
            <form action="" method="post" enctype="multipart/form-data">
                <div>
                    <label for="nombre">Nombre del producto</label>
                    <input type="text" name="nombre" id="nombre">
                </div>
                <div>
                    <label for="precio">Precio</label>
                    <input type="number" step="0.01" name="precio" id="precio">
                </div>
                <div>
                    <label for="descripcion">Descripcion</label>
                    <input type="text" name="descripcion" id="descripcion">
                </div>
                <div>
                    <label for="cantidad">Cantidad del producto</label>
                    <input type="number" name="cantidad" id="cantidad">
                </div>
                <div>
                    <label for="imagen">Imagen del producto</label>
                    <input type="file" name="imagen" id="imagen">
                </div>
                <input type="submit" value="Enviar">
                <?php
                    session_start();
                    if($_SESSION["rol"] != "admin"){
                        session_destroy();
                        header("location: index.php");
                    }
                    require "../util/funciones.php";
                    if($_SERVER["REQUEST_METHOD"] == "POST"){
                        $nombre = $_POST["nombre"];
                        $precio = $_POST["precio"];
                        $descripcion = $_POST["descripcion"];
                        $cantidad = $_POST["cantidad"];
                        $imagen = $_FILES["imagen"];
                        
                        //Condiciones para que se inserte en la base de datos
                        $cond1 = false;
                        $cond2 = false;
                        $cond3 = false;
                        $cond4 = false;

                        //Validacion de nombre
                        if(strlen($nombre) == 0){
                            echo "Error el nombre es obligatorio";
                        }else{
                            if(sqlProductoExistenteNombre($nombre)){
                                echo "Error el producto ya existe";
                            }else{
                                $cond1 = true;
                            }
                        }

                        //Validacion Imagen
                        if($cond1 && strlen($imagen["name"]) == 0){
                            echo "Error la imagen es obligatoria";
                        }else if($cond1){
                            if($imagen["type"] != "image/jpeg" && $imagen["type"] != "image/jpg" && $imagen["type"] != "image/png"){
                                echo "Error en el formato de imagen";
                            }else{
                                if($imagen["size"]>5*1024*1024){
                                    echo "Error el tamaño maximo de la imgen tiene que ser menor que 5MB";
                                }else{
                                    $ruta = "images/" . $imagen["name"];
                                    move_uploaded_file($imagen["tmp_name"],$ruta);
                                    $cond2 = true;
                                }
                            }
                        }

                        //Validacion de Precio
                        if($cond1 && $cond2 &&  !is_numeric($precio)){
                            echo "Error el precio es obligatorio y solo se aceptan numeros";
                        }else if($cond1 && $cond2){
                            $regex = "/^[0-9]+\.[0-9]+$/";
                            if(!preg_match($regex, $precio)){
                                echo "Error el precio tiene que indicarse con . no con ,";
                            }else if($precio<0){
                                echo "Error en precio no puede ser negativo";
                            }else{
                                $cond3 = true;
                            }
                        }

                        //Validacion de la cantidad
                        if($cond1 && $cond2 && $cond3 && (!is_numeric($cantidad)||is_null($cantidad))){
                            echo "Error la cantidad es obligatoria y solo acepta numeros";
                        }else if($cond1 && $cond2 && $cond3){
                            if($cantidad<0){
                                echo "Error la cantidad no puede ser menor que 0";
                            }else{
                                $cond4 = true;
                            }
                        }

                        if($cond1 && $cond2 && $cond3 && $cond4){
                            $conexion = sqlConexionProyectoSupermercado();
                            $sql = "INSERT into productos values(null,'$nombre','$precio','$descripcion','$cantidad','$ruta');";
                            $conexion -> query($sql); 
                            $file = fopen("../BaseDatos/InsertarProductos.sql","a");
                            fwrite($file,$sql."\n");
                            echo "Todo insertado correctamente";
                            fclose($file);  
                        }
                    }
                ?>
            </form>
            <a href="paginaPrincipal.php"><button class="btn btn-primary">Volver a incio</button></a>
        </div>
    </body>
</html>