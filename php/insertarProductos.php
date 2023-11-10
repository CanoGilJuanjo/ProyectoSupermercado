<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Insertar productos</title>
        <link rel="stylesheet" href="../css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <h1>Insertar productos</h1>
            <form action="" method="post" enctype="multipart/form-data">
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
                <div>
                    <label for="imagen">Imagen del producto</label>
                    <input type="file" name="imagen" id="imagen">
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
                        $imagen = $_FILES["imagen"];
                        
                        //Condiciones para que se inserte en la base de datos
                        $cond1 = false;
                        $cond2 = false;
                        $cond3 = false;

                        //Validacion id
                        if(strlen($id) == 0){
                            echo "Error el id es obligatorio";
                        }else{
                            if(sqlProductoExistenteId($id)){
                                echo "Error el producto ya existe o el ID se esta usando para otro producto";
                            }else{
                                $cond1 = true;
                            }

                        }

                        //Validacion de nombre
                        if(strlen($nombre) == 0){
                            echo "Error el nombre es obligatorio";
                        }else{
                            if(!sqlProductoExistenteNombre($nombre)){
                                echo "Error el producto ya existe";
                            }else{
                                $cond3 = true;
                            }
                        }

                        //Validacion Imagen
                        if($cond1 && strlen($imagen["name"]) == 0){
                            echo "Error la imagen es obligatoria";
                        }else{
                            if($imagen["type"] != "image/jpeg" && $imagen["type"] != "image/jpg" && $imagen["type"] != "image/png"){
                                echo "Error en el formato de imagen";
                            }else{
                                if($imagen["size"]>5*1024*1024){
                                    echo "Error el tamaño maximo de la imgen tiene que ser menor que 5MB";
                                }else{
                                    $ruta = "media/" . $imagen["name"];
                                    move_uploaded_file($imagen["tmp_name"],$ruta);
                                    $cond2 = true;
                                    
                                }
                            }
                        }

                        if($cond1 && $cond2){
                            $conexion = sqlConexionProyectoSupermercado();
                            $sql = "INSERT into productos values('$id','$nombre','$precio','$descripcion','$cantidad','$ruta');";
                            $conexion -> query($sql); 
                            $file = fopen("../BaseDatos/InsertarContenido.sql","a");
                            fwrite($file,$sql."\n");
                            echo "Todo insertado correctamente";
                            fclose($file);  
                        }
                    }
                ?>
            </form>
        </div>
    </body>
</html>