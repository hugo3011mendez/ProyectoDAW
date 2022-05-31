<?php
    const NOMBRE_BBDD = "todomanager"; // Constante indicando el nombre de la BBDD, para evitar números y texto mágico

    // Constantes referentes a los nombres de las tablas de la BBDD :
    const TABLA_USUARIOS = "usuarios";
    const TABLA_PRIVILEGIOS = "privilegios";
    const TABLA_ROLES = "roles";
    const TABLA_PROYECTOS = "proyectos";
    const TABLA_TAREAS = "tareas";
    const TABLA_TAREAS_FINALIZADAS = "tareas_finalizadas";


    /**
     * Escribe un mensaje en la consola del navegador, para más información a la hora de que se ejecute la app
     */
    function consoleLog($mensaje){
        echo "<script>console.log(".$mensaje.");</script>";    
    }


    
?>