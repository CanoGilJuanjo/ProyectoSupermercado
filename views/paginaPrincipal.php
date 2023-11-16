<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pagina principal</title>
        <link rel="stylesheet" href="styles/bootstrap.min.css">
        <?php 
            #Funciones/clases necesarias para esta pagina
            require "../util/funciones.php";
            require "../util/objetoProducto.php";
            $conexion = sqlConexionProyectoSupermercado();
        ?>
    </head>
    <body>
        <?php 
            #Iniciamos la sesion para traer las variables de incio de sesion
            session_start(); 
            #Comprobamos la sesion
            if($_SESSION["usuario"] == null){
                header("location: index.php");
            }
            $usuario = $_SESSION["usuario"];
            $rol = $_SESSION["rol"];
            #Comprobamos cuando se haga un post a que corresponde
            if($_SERVER["REQUEST_METHOD"]=="POST"){
                #Cuando se pulse el boton insertar producto debe de redirigir a otra pagina
                if($_POST["envio"] == "Insertar producto"){
                    header("location: insertarProductos.php");
                }else 
                #Con el boton cerrar sesion debemos eliminar la sesion actual y redirigir al inicio de sesion
                if($_POST["envio"] == "Cerrar Sesion"){
                    session_destroy();
                    header("location: index.php");
                }else 
                #Con este boton tenemos que comprobar si el usuario puede acceder a la compra e insertamos los productos en la tabla productosCestas
                if($_POST["envio"] == "Añadir"){
                    if($usuario == "Invitado"){
                        echo "<p class='text-bg-info'>Tiene que iniciar sesion para poder comprar</p>";
                    }else if($_POST["cantidad"]<="0"){
                        echo "<p class='text-bg-info'>No se pueden insertar 0 unidades</p>";
                    }else{
                        $sql = "SELECT idCesta from cestas where usuario = '".$usuario."';";
                        $resultado = $conexion->query($sql);
                        $idCesta = $resultado->fetch_assoc()["idCesta"];
                        if(sqlPedidoRealizado($_POST["idProducto"],$idCesta)){
                            switch(true){
                                case $usuario == "Invitado":;break;
                                case sqlPedidoRealizado($_POST["idProducto"],$idCesta): echo "<p class='text-bg-info'>Ya ha añadido este producto</p>";break;
                            }                        
                        }else{
                            $cantidad = $_POST["cantidad"];
                            $sql = "INSERT into productosCestas values('".$_POST["idProducto"]."','".$idCesta."','$cantidad');";
                            $conexion->query($sql);
                            $file = fopen("../BaseDatos/InsertarProductosCestas.sql","a");
                            fwrite($file,$sql."\n");
                            fclose($file);
                            $sql = "SELECT nombreProducto from productos where idProducto = '".$_POST["idProducto"]."';";
                            $resultado = $conexion->query($sql);
                            echo "<p class='text-bg-info'>Añadidas ".$cantidad." unidades de ".$resultado->fetch_assoc()["nombreProducto"]."</p>";
                        }
                    }
                }else
                #Boton de la cesta, redirige a la cesta de la compra
                if($_POST["envio"] == "🛒 Ver cesta"){
                    header("location: cesta.php");
                }
            }
        ?>
        <div class="container">
            <h1>Esta es la pagina principal</h1>
            <h2>Bienvenido <?php echo $usuario;?></h2>
            <h2>Lista de productos</h2>
            <form action="" method="post">
                <?php
                    if($usuario != "Invitado"){
                        echo "<input type='submit' name='envio' value='🛒 Ver cesta' class='btn btn-warning'>";
                    }
                ?>
            </form>
            <?php
                #Obtenemos la conexion y los productos de la base de datos
                $sql = "SELECT * from productos;";
                $resultado = $conexion->query($sql);
                #Usamos el objeto y lo guardamos en un array
                $productos = [];
                while($fila = $resultado->fetch_assoc()){
                    array_push($productos,new Producto($fila["idProducto"],$fila["nombreProducto"],$fila["precio"],$fila["descripcion"],$fila["cantidad"],$fila["imagen"]));
                }
                #Mostramos los productos en formato tabla
                echo "<table class='table table-dark mt-3'>";
                echo "<tr>";
                echo "<th>Id</th>";
                echo "<th>Nombre</th>";
                echo "<th>Precio</th>";
                echo "<th>Descipcion</th>";
                echo "<th>Disponibles</th>";
                echo "<th>Imagen</th>";
                echo "<th>Cantidad</th>";
                echo "</tr>";
                for($i = 0;$i<count($productos);$i++){
                    echo "<tr>";
                    echo "<td>".$productos[$i]->idProducto."</td>";
                    echo "<td>".$productos[$i]->nombreProducto."</td>";
                    echo "<td>".$productos[$i]->precio."</td>";
                    echo "<td>".$productos[$i]->descripcion."</td>";
                    echo "<td>".$productos[$i]->cantidad."</td>";
                    echo "<td><img src='".$productos[$i]->imagen."' width='80' height='80'></td>";
                    echo "<td>";
            ?>
                <form method='post'> 
                    <select name="cantidad" id="cantidad">
                        <?php 
                            #Controlamos que no se puedan añadir a la cesta más de 5 productos o los que haya disponibles si son menos de 5
                            if($productos[$i]->cantidad<5){
                                $limite = $productos[$i]->cantidad;
                            }else{
                                $limite = 5;
                            }
                            for($j = 0;$j <= $limite;$j++){
                                echo "<option value = '".($j)."'>".($j)."</option>";
                            }
                        ?>
                    </select>
                    <input type="hidden" name="idProducto" value = "<?php echo $productos[$i]->idProducto ?>">
                    <?php
                        if($productos[$i]->cantidad == 0){
                            echo "<p>Agotado</p>";
                        }else{
                            echo "<input type='submit' name='envio' value='Añadir' class='btn btn-warning'>";
                        }
                    ?>
                </form>
            <?php 
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>"; 
            ?>
            <form action="" method="post">
                <?php
                    #Si el rol es Admin
                    if($rol == "admin"){
                        echo "<input type='submit' name='envio' value='Insertar producto' class='btn btn-primary'>";
                    }
                    echo "<input type='submit' name='envio' value='Cerrar Sesion' class='btn btn-primary m-3'>";
                ?>
            </form>
        </div>
    </body>
</html>