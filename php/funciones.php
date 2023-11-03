<?php 
    function sqlConexionProyectoSupermercado() {
        $servidor = "localhost";
        $user = "root";
        $contraseña = "medac";
        $baseDeDatos = "proyectoSupermercado";
    
       $conexion = new mysqli($servidor,$user,$contraseña,$baseDeDatos) or die("Error de conexion");
       return $conexion;
    }
?>