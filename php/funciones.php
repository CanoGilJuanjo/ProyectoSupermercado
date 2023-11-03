<?php 
    function sqlConexionProyectoSupermercado() {
        $servidor = "localhost";
        $user = "root";
        $contrasena = "medac";
        $baseDeDatos = "proyectoSupermercado";
    
       $conexion = new mysqli($servidor,$user,$contrasena,$baseDeDatos) or die("Error de conexion");
       return $conexion;
    }
?>