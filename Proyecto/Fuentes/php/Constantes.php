<?php // En este archivo pondré todas las constantes
    const NOMBRE_BBDD = "todomanager"; // Constante indicando el nombre de la BBDD, para evitar números y texto mágico

    // Constantes referentes a los nombres de las tablas de la BBDD :
    const TABLA_USUARIOS = "usuarios";
    const TABLA_PRIVILEGIOS = "privilegios";
    const TABLA_ROLES = "roles";
    const TABLA_PROYECTOS = "proyectos";
    const TABLA_TAREAS = "tareas";
    const TABLA_TAREAS_FINALIZADAS = "tareas_finalizadas";

    // Constantes referentes a los roles de los usuarios
    const ROL_USUARIO = 1;
    const ROL_ADMIN = 2;

    // Constantes referentes a los privilegios de los roles de usuarios
    const PRIVILEGIOS_LECTURA = 1;
    const PRIVILEGIOS_ESCRITURA = 2;
    const PRIVILEGIOS_SOBRE_USUARIOS = 3;
?>