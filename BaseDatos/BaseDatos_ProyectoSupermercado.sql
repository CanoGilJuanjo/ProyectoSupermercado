create schema proyectosupermercado;
use  proyectosupermercado;

#Creacion de tablas
create table usuarios(
	usuario varchar(12) check(length(usuario)>4) primary key not null, #Solo acepta letras y _
    contrasena varchar(255) not null,
    fechaNacimiento date #tiene que tener mas de 12 anos y menos de 120
);

create table cestas(
	idCesta int(8) primary key auto_increment,
    usuario varchar(12) references usuarios(usuario),
    precioTotal float default (0)
);

create table productos(
	idProducto int(8) primary key not null,
    nombreProducto varchar(40) not null, #Tiene que aceptar solo numeros letras y espacios en blanco
    precio float8 check(precio>=0) not null,
    descripcion varchar(255) not null,
    cantidad int8 check(cantidad>=0) not null
);

create table productosCestas (
	idProducto int8 references productos(idProducto),
    idCesta int8 references cestas(idCesta),
    cantidad int check(cantidad >= 0 and cantidad <= 10) not null,
    constraint pk_productosCestas primary key (idProducto,idCesta),
    constraint pk_productosCestas_productos foreign key (idProducto) references productos(idProducto),
    constraint pk_productosCestas_cestas foreign key (idCesta) references cestas(idCesta)
);



#Aniadimos las referencias
alter table productos add fk_idCesta int8 references productosCestas(idCesta);


#Borrado
#drop database proyectosupermercado;
#drop table cestas;