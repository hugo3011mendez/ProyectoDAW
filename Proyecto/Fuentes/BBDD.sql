create database todomanager;

use todomanager;

/* Tabla Privilegios */
create table privilegios
(
    id smallint not null AUTO_INCREMENT,
    nombre varchar(20) character set utf8 collate utf8_general_ci not null,
    constraint pk_privilegios primary key (id) /* El campo ID es la clave primaria de la tabla */
);


/* Tabla Roles */
create table roles
(
    id smallint not null AUTO_INCREMENT,
    nombre varchar(20) character set utf8 collate utf8_general_ci not null,
    privilegios smallint not null,
    constraint pk_roles primary key (id), /* El campo ID es la clave primaria de la tabla */
    constraint foreign key (privilegios) references privilegios(id) /* Clave foránea haciendo referencia a la ID de la tabla Privilegios */
);


/* Tabla Usuarios */
create table usuarios
( 
    id smallint not null AUTO_INCREMENT,
    email varchar(40) character set utf8 collate utf8_general_ci not null,
    nickname varchar(20) character set utf8 collate utf8_general_ci not null,
    pwd varchar(200) character set utf8 collate utf8_general_ci not null,
    imagen varchar(10000) character set utf8 collate utf8_general_ci, /* TODO : Pendiente de como representar este dato */
    rol smallint not null,
    constraint pk_usuarios primary key (id), /* El campo ID es la clave primaria de la tabla */
    constraint fk_rol_usuario foreign key (rol) references roles(id) /* Clave foránea haciendo referencia a la ID de la tabla Roles */
);


/* Tabla Proyectos */
create table proyectos
(
    id smallint not null AUTO_INCREMENT,
    usuario_creador smallint not null,
    nombre varchar(20) character set utf8 collate utf8_general_ci not null,
    descripcion varchar(100) character set utf8 collate utf8_general_ci,
    fecha_creacion datetime not null, /* Su formato es (YYYY-MM-DD HH:MI:SS) */
    constraint pk_proyectos primary key (id), /* El campo ID es la clave primaria de la tabla */
    constraint foreign key (usuario_creador) references usuarios(id) /* El usuario creador es una clave foránea de la ID del usuario */
);


/* Tabla Tareas */
create table tareas
(
    id smallint not null AUTO_INCREMENT,
    nombre varchar(20) character set utf8 collate utf8_general_ci not null,
    descripcion varchar(100) character set utf8 collate utf8_general_ci,
    fecha_creacion datetime not null, /* Su formato es (YYYY-MM-DD HH:MI:SS) */
    fecha_modificacion datetime not null, /* Su formato es (YYYY-MM-DD HH:MI:SS) */
    proyecto smallint not null,
    parentID smallint,
    estado bit not null,
    constraint pk_tareas primary key (id), /* El campo ID es la clave primaria de la tabla */
    constraint foreign key (proyecto) references proyectos(id) /* El proyecto es una clave foránea de la ID del proyecto en cuestión */
);


/* Introduzco los 3 privilegios posibles que hay, en su tabla correspondiente */
INSERT INTO privilegios (nombre) VALUES ('Lectura');

INSERT INTO privilegios (nombre) VALUES ('Escritura');

INSERT INTO privilegios (nombre) VALUES ('Sobre otros usuarios');


/* Introduzco los 3 roles que va a haber, en su tabla correspondiente */
INSERT INTO roles (nombre, privilegios) VALUES ('Usuario', 2); /* Un usuario que puede leer y modificar sus propios datos */

INSERT INTO roles (nombre, privilegios) VALUES ('Administrador', 3); /* Un administrador que puede leer y modificar sus propios datos, además de gestionar a los demás usuarios */