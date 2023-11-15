#Schema
create schema proyectosupermercado;
use  proyectosupermercado;

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

#Añadimos las referencias a esta tabla intermedia y viceversa
select * from productoscestas;

set SQL_SAFE_UPDATES = 0;