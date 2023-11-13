<?php 
    class Producto{
        public $idProducto;
        public $nombreProducto;
        public $precio;
        public $descripcion;
        public $cantidad;
        public $imagen;

        function __construct($idProducto,$nombreProducto,$precio,$descripcion,$cantidad,$imagen)
        {   
            $this->idProducto = $idProducto;
            $this->nombreProducto = $nombreProducto;
            $this->precio = $precio;
            $this->descripcion = $descripcion;
            $this->cantidad = $cantidad;
            $this->imagen = $imagen;
        }
    }
?>