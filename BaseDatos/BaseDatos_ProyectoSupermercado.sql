#Schema
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

#Añadimos las claves foraneas
ALTER TABLE usuarios ADD idCestas_fk int(8) NOT NULL;
ALTER TABLE usuarios ADD CONSTRAINT pk_usuarios_cestas FOREIGN KEY (idCestas_fk) REFERENCES cestas(idCesta);

alter table cestas add usuarios_fk varchar(12) not null;
alter table cestas add constraint pk_cestas_usuarios foreign key (usuarios_fk) references usuarios(usuario);

#Creamos el resto de tablas
create table productos(
	idProducto int(8) primary key not null,
    nombreProducto varchar(40) not null, #Tiene que aceptar solo numeros letras y espacios en blanco
    precio float8 check(precio>=0) not null,
    descripcion varchar(255) not null,
    cantidad int8 check(cantidad>=0) not null
);
create table productosCestas (
	idProducto int(8),
    idCesta int(8) ,
    cantidad int check(cantidad >= 0 and cantidad <= 10) not null,
    constraint pk_productosCestas primary key (idProducto,idCesta),
    constraint pk_productosCestas_productos foreign key (idProducto) references productos(idProducto),
    constraint pk_productosCestas_cestas foreign key (idCesta) references cestas(idCesta)
);

#Añadimos las referencias a esta tabla intermedia y viceversa
alter table productos add idCestas_fk int(8) not null;
alter table productos add constraint pk_productos_productosCestas foreign key (idCestas_fk) references productosCestas(idCesta);
alter table cestas add idProductos_fk int(8) not null;
alter table cestas add constraint pk_cestas_productosCestas foreign key (idProductos_fk) references productosCestas(idProducto);

#Borrado
#drop database proyectosupermercado;
#drop table cestas;