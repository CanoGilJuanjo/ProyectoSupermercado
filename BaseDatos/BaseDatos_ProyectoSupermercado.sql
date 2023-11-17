#Schema
create schema proyectosupermercado;
use  proyectosupermercado;
#drop database proyectosupermercado;

#Creacion de tablas
create table usuarios(
	usuario varchar(12) check(length(usuario)>4) primary key not null, #Solo acepta letras y _
    contrasena varchar(255) not null,
    fechaNacimiento date #tiene que tener mas de 12 anos y menos de 120
);
alter table usuarios add column rol varchar(100);
create table cestas(
	idCesta int(8) primary key auto_increment,
    usuario varchar(12),
    precioTotal float default (0)
);

#Añadimos las claves foraneas
alter table cestas add constraint pk_cestas_usuarios foreign key (usuario) references usuarios(usuario) on delete cascade;

#Creamos el resto de tablas
create table productos(
	idProducto int(8) primary key not null auto_increment,
    nombreProducto varchar(40) not null, #Tiene que aceptar solo numeros letras y espacios en blanco
    precio float8 check(precio>=0) not null,
    descripcion varchar(255) not null,
    cantidad int8 check(cantidad>=0) not null
);

alter table productos add column imagen varchar(100);

create table productosCestas (
	idProducto int(8),
    idCesta int(8) ,
    cantidad int check(cantidad >= 0 and cantidad <= 10) not null,
    constraint pk_productosCestas primary key (idProducto,idCesta),
    constraint pk_productosCestas_productos foreign key (idProducto) references productos(idProducto) on delete cascade,
    constraint pk_productosCestas_cestas foreign key (idCesta) references cestas(idCesta) on delete cascade
);

#Creamos la tabla pedidos
create table pedidos(
	idPedido int(8) primary key auto_increment,
    usuario varchar(12) references usuarios(usuario),
    precioTotal float,
    fechaPedido timestamp on update current_timestamp
);

#Creamos la tabla lineas de pedidos

create table lineasPedidos(
	lineaPedido int(2) primary key auto_increment,
    idProducto int(8),
    idPedido int(8),
    precioUnitario float8,
    cantidad int(8),
    constraint pk_lineasPedidos_idProducto foreign key (idProducto) references productos(idProducto) on delete cascade,
    constraint pk_lineasPedidos_idPedido foreign key (idPedido) references pedidos(idPedido) on delete cascade
);
#Añadimos las referencias a esta tabla intermedia y viceversa
select * from productoscestas;
select *  from usuarios;
select * from productos;
select * from cestas;
select * from lineaspedidos;
select * from pedidos;
select * from lineaspedidos;


set SQL_SAFE_UPDATES = 0;