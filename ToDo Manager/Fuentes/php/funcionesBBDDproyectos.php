<?php
    require_once "Utils.php"; // Linkeo el archivo de Utils a este, para usar sus funciones
    require_once "Constantes.php"; // Linkeo el archivo de las constantes a este, para utilizarlas en las funciones de la BBDD
    require_once "funcionesBBDDtareas.php";
    
    //------------------------------------------------------------- CREATE -----------------------------------------------

    /**
     * Crea un proyecto según los parámetros especificados
     * 
     * @param $conexion La conexión con la base de datos
     * @param $idCreador La ID del usuario creador del proyecto
     * @param $nombre El nombre del nuevo proyecto
     * @param $descripcion La descripción del nuevo proyecto
     * 
     * @return Boolean Indicando el resultado de la ejecución de la función
     */
    function crearProyecto($conexion, $idCreador, $nombre, $descripcion){
        
        // Armo la sentencia de creación
        $sentencia = "INSERT INTO ".TABLA_PROYECTOS." (usuario_creador, nombre, descripcion, fecha_creacion) VALUES (".
        $idCreador.
        ", '".$nombre.
        "', '".$descripcion.
        "', NOW());";
        
        // Compruebo el resultado de la ejecución de la sentencia y devuelvo un booleano según corresponda
        return comprobarResultadoDeQuery($conexion, $sentencia);
    }


    

    //------------------------------------------------------------- READ -----------------------------------------------
    /**
     * Consigo todos los proyectos de un usuario de la base de datos y devuelvo la info
     * 
     * @param $conexion La conexión con la base de datos
     * 
     * @return Array asociativo referente a los proyectos que están en la base de datos
     */
    function leerProyectos($conexion){
        $sentencia = "SELECT * FROM ".TABLA_PROYECTOS.";";
        $resultado = mysqli_query($conexion, $sentencia); // Guardo el resultado de la ejecución de la sentencia para recorrerse
    
        if(mysqli_num_rows($resultado) > 0){
            $proyectos = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
            return json_encode($proyectos);
        }
    }


    /**
     * Consigo todos los proyectos de un usuario de la base de datos y devuelvo la info
     * 
     * @param $conexion La conexión con la base de datos
     * @param $idUsuario La ID del usuario creador del proyecto
     * 
     * @return Array asociativo referente a los proyectos que están en la base de datos
     */
    function leerProyectosDeUsuario($conexion, $idUsuario){
        $sentencia = "SELECT * FROM ".TABLA_PROYECTOS." WHERE usuario_creador=".$idUsuario.";";
        $resultado = mysqli_query($conexion, $sentencia); // Guardo el resultado de la ejecución de la sentencia para recorrerse
    
        if(mysqli_num_rows($resultado) > 0){
            $proyectos = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
            return json_encode($proyectos);
        }
    }


    /**
     * Consigo la información de un proyecto en la base de datos según su ID
     * 
     * @param $conexion La conexión con la base de datos
     * @param $id La ID del proyecto a buscar
     * 
     * @return Array asociativo referente al proyecto que queremos buscar
     */
    function leerProyecto($conexion, $id){
        $sentencia = "SELECT * FROM ".TABLA_PROYECTOS." WHERE id=".$id.";";
        $resultado = mysqli_query($conexion, $sentencia); // Guardo el resultado de la ejecución de la sentencia para recorrerse

        if(mysqli_num_rows($resultado) > 0){
            $proyecto = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
            return json_encode($proyecto);
        }
    }




    //------------------------------------------------------------- UPDATE -----------------------------------------------

    /**
     * Actualiza la información de un proyecto en la BBDD
     * 
     * @param $conexion La conexión con la base de datos
     * @param $nombre El nuevo nombre del proyecto
     * @param $descripcion La nueva descripción del proyecto
     * @param $id La ID del proyecto que queremos cambiar
     * 
     * @return Boolean Indicando el resultado de la ejecución de la función
     */
    function actualizarProyecto($conexion, $nombre, $descripcion, $id){
        // Armo la sentencia de creación
        $sentencia = "UPDATE ".TABLA_PROYECTOS." SET nombre = '".$nombre."', descripcion = '".$descripcion."' WHERE id = ".$id;

        // Compruebo el resultado de la ejecución de la sentencia y devuelvo un booleano según corresponda
        return comprobarResultadoDeQuery($conexion, $sentencia);
    }




    //------------------------------------------------------------- DELETE -----------------------------------------------

    /**
     * Elimina un proyecto y todas sus tareas de la base de datos
     * 
     * @param $conexion La conexión con la base de datos
     * @param $proyecto La ID del proyecto que va a ser eliminado, y sobre el que se van a eliminar todas las tareas
     * @param $grupo Booleano indicando si se están eliminando todos los proyectos
     * 
     * @return Boolean indicando si la acción resultó con errores
     */
    function eliminarProyecto($conexion, $proyecto, $grupo){
        if (eliminarTodasTareas($conexion, $proyecto)) { // Compruebo que haya salido bien la realización de esta función
            // Si sale bien, armo la consulta para eliminar el proyecto
            $sentencia = "DELETE FROM ".TABLA_PROYECTOS." WHERE id =".$proyecto;
            // Compruebo el resultado de la ejecución de la sentencia y devuelvo un booleano según corresponda
            return comprobarResultadoDeQuery($conexion, $sentencia);
        }
        else {
            // Devuelvo el resultado de las acciones de error
            return accionesDeError($conexion);
        }
    }


    /**
     * Elimina todas las tareas y tareas finalizadas de un proyecto
     * 
     * @param $conexion La conexión con la base de datos
     * @param $proyecto La ID del proyecto sobre el que se van a eliminar todas sus tareas
     * 
     * @return Boolean indicando si la acción resultó con errores
     */
    function eliminarTodasTareas($conexion, $proyecto){
        // TODO : Pendiente de ver si quito la comprobación de ser un objeto o un int
        $sentencia = "DELETE FROM ".TABLA_TAREAS." WHERE proyecto =".$proyecto.";";

        // Devuelvo el resultado de ejecutar la consulta previamente armada
        return comprobarResultadoDeQuery($conexion, $sentencia);
    }
?>