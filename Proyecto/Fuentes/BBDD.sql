create database todomanager;

use todomanager;

/* Tabla Usuarios */
create table usuarios
( 
    id smallint not null AUTO_INCREMENT,
    email varchar(40) not null,
    nickname varchar(20) not null,
    pwd varchar(25) not null,
    imagen varchar(),
    rol smallint not null,
    constraint pk_usuarios primary key (id), /* El campo ID es la clave primaria de la tabla */
    constraint fk_rol_usuario foreign key (rol) references roles(id) /* Clave foránea haciendo referencia a la ID de la tabla Roles */
);


/* Tabla Roles */
create table roles
(
    id smallint not null AUTO_INCREMENT,
    nombre varchar(20) not null,
    privilegios smallint not null,
    constraint pk_roles primary key (id), /* El campo ID es la clave primaria de la tabla */
    constraint foreign key (privilegios) references privilegios(id) /* Clave foránea haciendo referencia a la ID de la tabla Privilegios */
);


/* Tabla Privilegios */
create table privilegios
(
    id smallint not null AUTO_INCREMENT,
    nombre varchar(20) not null,
    constraint pk_privilegios primary key (id), /* El campo ID es la clave primaria de la tabla */
)



/* Introduzco los 3 privilegios posibles que hay, en su tabla correspondiente */
insert into privilegios values ('1','Lectura');
insert into privilegios values ('2', 'Escritura');
insert into privilegios values ('3', 'Sobre otros usuarios');