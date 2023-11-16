<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Insertar productos</title>
        <link rel="stylesheet" href="styles/bootstrap.min.css">
        <link rel="stylesheet" href="styles/estilo.css">
        <?php 
            #Funciones/clases requeridas para esta pagina
            require "../util/funciones.php";
        ?>
    </head>
    <body>
        <div class="container mt-4">
            <h1>Insertar productos</h1>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del producto</label>
                    <input type="text" name="nombre" id="nombre" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="precio" class="form-label">Precio</label>
                    <input type="number" step="0.01" name="precio" id="precio" min="0" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripcion</label>
                    <input type="text" name="descripcion" id="descripcion" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="cantidad" class="form-label">Cantidad del producto</label>
                    <input type="number" name="cantidad" id="cantidad" min="0" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="imagen" class="form-label">Imagen del producto</label>
                    <input type="file" name="imagen" id="imagen" class="form-control">
                </div>
                <input type="submit" value="Enviar" class="btn btn-primary">
                <input type="submit" name="envio" value="Cerrar Sesion" class='btn btn-primary'>
                <input type="submit" value="Volver" name="envio" class="btn btn-primary">
            </form>
            
            <?php
                session_start();
                #Comprobamos la sesion
                if($_SESSION["usuario"] == null){
                    header("location: index.php");
                }
                
                #Comprobamos el rol del usuario
                if($_SESSION["rol"] != "admin"){ 
                    session_destroy();
                    header("location: index.php");
                }
                
                if($_SERVER["REQUEST_METHOD"] == "POST"){
                    if( isset($_POST["envio"]) && $_POST["envio"] == "Cerrar Sesion"){
                        session_destroy();
                        header("location: index.php");
                    }else if(isset($_POST["envio"]) && $_POST["envio"] == "Volver"){
                        header("location: paginaPrincipal.php");
                    }
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
                        echo "<p class='text-danger bg-light p-4 rounded-3 mt-3'>Error el nombre es obligatorio</p>";
                    }else{
                        if(sqlProductoExistenteNombre($nombre)){
                            echo "<p class='text-danger bg-light p-4 rounded-3 mt-3'>Error el producto ya existe</p>";
                        }else{
                            $cond1 = true;
                        }
                    }

                    //Validacion Imagen
                    if($cond1 && strlen($imagen["name"]) == 0){
                        echo "<p class='text-danger bg-light p-4 rounded-3 mt-3'>Error la imagen es obligatoria</p>";
                    }else if($cond1){
                        if($imagen["type"] != "image/jpeg" && $imagen["type"] != "image/jpg" && $imagen["type"] != "image/png"){
                            echo "<p class='text-danger bg-light p-4 rounded-3 mt-3'>Error en el formato de imagen</p>";
                        }else{
                            if($imagen["size"]>5*1024*1024){
                                echo "<p class='text-danger bg-light p-4 rounded-3 mt-3'>Error el tamaño maximo de la imgen tiene que ser menor que 5MB</p>";
                            }else{
                                $ruta = "images/" . $imagen["name"];
                                move_uploaded_file($imagen["tmp_name"],$ruta);
                                $cond2 = true;
                            }
                        }
                    }

                    //Validacion de Precio
                    if($cond1 && $cond2 &&  !is_numeric($precio)){
                        echo "<p class='text-danger bg-light p-4 rounded-3 mt-3'>Error el precio es obligatorio y solo se aceptan numeros</p>";
                    }else if($cond1 && $cond2){
                        /*$regex = "/^[0-9]+\.[0-9]+$/";
                        if(!preg_match($regex, $precio)){
                            echo "Error el precio tiene que indicarse con . no con ,";
                        }else*/ 
                        if($precio<0){
                            echo "<p class='text-danger bg-light p-4 rounded-3 mt-3'>Error en precio no puede ser negativo</p>";
                        }else{
                            $cond3 = true;
                        }
                    }

                    //Validacion de la cantidad
                    if($cond1 && $cond2 && $cond3 && (!is_numeric($cantidad)||is_null($cantidad))){
                        echo "<p class='text-danger bg-light p-4 rounded-3 mt-3'>Error la cantidad es obligatoria y solo acepta numeros</p>";
                    }else if($cond1 && $cond2 && $cond3){
                        if($cantidad<0){
                            echo "<p class='text-danger bg-light p-4 rounded-3 mt-3'>Error la cantidad no puede ser menor que 0</p>";
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
                        echo "<p class='text-danger bg-light p-4 rounded-3 mt-3'>Todo insertado correctamente</p>";
                        fclose($file);  
                    }
                }
            ?>
        </div>
    </body>
</html>