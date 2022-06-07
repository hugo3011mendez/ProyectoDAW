create database todomanager;

use todomanager;

/* Tabla Usuarios */
create table usuarios
( 
    id smallint not null AUTO_INCREMENT,
    email varchar(40) not null,
    nickname varchar(20) not null,
    pwd varchar(25) not null,
    imagen varchar(), /* TODO : Pendiente de como representar este dato */
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
);


/* Tabla Proyectos */
create table proyectos
(
    id smallint not null AUTO_INCREMENT,
    usuario_creador smallint not null,
    nombre varchar(20) not null,
    descripcion varchar(100),
    fecha_creacion datetime not null, /* Su formato es (YYYY-MM-DD HH:MI:SS) */
    constraint pk_proyectos primary key (id), /* El campo ID es la clave primaria de la tabla */
    constraint foreign key (usuario_creador) references usuarios(id) /* El usuario creador es una clave foránea de la ID del usuario */
);


/* Tabla Tareas */
create table tareas
(
    id smallint not null AUTO_INCREMENT,
    nombre varchar(20) not null,
    descripcion varchar(100),
    fecha_creacion datetime not null, /* Su formato es (YYYY-MM-DD HH:MI:SS) */
    fecha_modificacion datetime not null, /* Su formato es (YYYY-MM-DD HH:MI:SS) */
    proyecto smallint not null,
    parentID smallint,
    constraint pk_tareas primary key (id), /* El campo ID es la clave primaria de la tabla */
    constraint foreign key (proyecto) references proyectos(id) /* El proyecto es una clave foránea de la ID del proyecto en cuestión */
);


/* Tabla Tareas Finalizadas */
create table tareas_finalizadas
(
    id smallint not null AUTO_INCREMENT,
    nombre varchar(20) not null,
    descripcion varchar(100),
    fecha_creacion datetime not null, /* Su formato es (YYYY-MM-DD HH:MI:SS) */
    fecha_modificacion datetime not null, /* Su formato es (YYYY-MM-DD HH:MI:SS) */
    proyecto smallint not null,
    parentID smallint,
    constraint pk_tareas primary key (id), /* El campo ID es la clave primaria de la tabla */
    constraint foreign key (proyecto) references proyectos(id) /* El proyecto es una clave foránea de la ID del proyecto en cuestión */
)


/* Introduzco los 3 privilegios posibles que hay, en su tabla correspondiente */
insert into privilegios values ('Lectura');
insert into privilegios values ('Escritura');
insert into privilegios values ('Sobre otros usuarios');

/* Introduzco los 3 roles que va a haber, en su tabla correspondiente */
insert into roles values ('Usuario', 2); /* Un usuario que puede leer y modificar sus propios datos */
insert into roles values ('Administrador', 3); /* Un administrador que puede leer y modificar sus propios datos, además de gestionar a los demás usuarios */