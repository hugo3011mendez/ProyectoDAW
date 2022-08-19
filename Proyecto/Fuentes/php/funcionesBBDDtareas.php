<?php
    require_once "Utils.php"; // Linkeo el archivo de Utils a este, para usar sus funciones
    require_once "Constantes.php"; // Linkeo el archivo de las constantes a este, para utilizarlas en las funciones de la BBDD
    
    //------------------------------------------------------------- CREATE -----------------------------------------------

    /**
     * Crea una tarea según los parámetros especificados
     * 
     * @param $conexion La conexión con la base de datos
     * @param $nombre El nombre de la nueva tarea
     * @param $descripcion La descripción de la nueva tarea
     * @param $proyecto La ID del proyecto donde crearemos una nueva tarea
     * @param $parentID La ID de la tarea padre
     * @param $estado Booleano indicando si es una tarea pendiente o finalizada
     * 
     * @return Boolean Indicando el resultado de la ejecución de la función
     */
    function crearTarea($conexion, $nombre, $descripcion, $proyecto, $parentID, $estado){

        // Quiero comprobar primero si el proyecto en el que se quiere insertar la tarea existe en su tabla, usando su ID
        $existe = false; // Booleana para comprobar si el proyecto existe
        $sentencia = "SELECT * FROM ".TABLA_PROYECTOS." WHERE id = ".$proyecto.";"; // Armo la sentencia
        $resultado = mysqli_query($conexion, $sentencia); // Guardo el resultado de la ejecución de la sentencia para recorrerse
        // Recorro el resultado de la consulta y compruebo si el proyecto existe
        while ($proyectoSentencia = $resultado -> fetch_object()) {
            if ($proyectoSentencia-> id == $proyecto) {
                $existe = true;
            }
        }

        if ($existe) { // Si resulta que el proyecto existe, inserto la tarea
            $parentID = is_null($parentID) ? "null" : $parentID; // Si es una tarea padre, establezco el valor como una cadena

            // Armo la sentencia de creación
            $sentencia = "INSERT INTO ".TABLA_TAREAS." (nombre, descripcion, fecha_creacion, fecha_modificacion, proyecto, parentID, estado) VALUES ('".$nombre.
            "', '".$descripcion.
            "', NOW(), NOW(), ". // Uso la función NOW() de MYSQL para las fechas de creación y modificación
            $proyecto.
            ", ".$parentID.
            ", ".$estado.");";
            
            // Compruebo el resultado de la ejecución de la sentencia y devuelvo un booleano según corresponda
            return comprobarResultadoDeQuery($conexion, $sentencia);
        }
        else { // Si el proyecto no existe, realizo las acciones de error
            accionesDeError($conexion);
        }
    }




    //------------------------------------------------------------- READ -----------------------------------------------

    /**
     * Consigo todos las tareas de un proyecto en la base de datos y devuelvo la info
     * 
     * @param $conexion La conexión con la base de datos
     * @param $idProyecto La ID del proyecto donde se encuentran las tareas que quiero ver
     * 
     * @return Array asociativo referente a las tareas del proyecto indicado que están en la base de datos
     */
    function leerTareasDeProyecto($conexion, $idProyecto){
        $sentencia = "SELECT * FROM ".TABLA_TAREAS." WHERE id=".$idProyecto.";"; // Realizo la sentencia
        $resultado = mysqli_query($conexion, $sentencia); // Guardo el resultado de la ejecución de la sentencia para recorrerse
    
        if(mysqli_num_rows($resultado) > 0){
            $tareas = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
            return json_encode($tareas);
        }
    }


    /**
     * Consigo todos las subtareas de otra en la base de datos y devuelvo la info
     * 
     * @param $conexion La conexión con la base de datos
     * @param $idPadre La ID de la tarea padre donde se encuentran las subtareas que quiero ver
     * 
     * @return Array asociativo referente a las subtareas de la tarea padre indicado que están en la base de datos
     */
    function leerSubtareasDeTarea($conexion, $idPadre){
        $sentencia = "SELECT * FROM ".TABLA_TAREAS." WHERE parentID=".$idPadre.";"; // Realizo la sentencia
        $resultado = mysqli_query($conexion, $sentencia); // Guardo el resultado de la ejecución de la sentencia para recorrerse
    
        if(mysqli_num_rows($resultado) > 0){
            $tareas = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
            return json_encode($tareas);
        }
    }


    /**
     * Consigo la información de una tarea en la base de datos según su ID
     * 
     * @param $conexion La conexión con la base de datos
     * @param $id La ID de la tarea a buscar
     * 
     * @return Array asociativo referente a la tarea que queremos buscar
     */
    function leerTarea($conexion, $id){
        $sentencia = "SELECT * FROM ".TABLA_TAREAS." WHERE id=".$id.";";
        $resultado = mysqli_query($conexion, $sentencia); // Guardo el resultado de la ejecución de la sentencia para recorrerse

        if(mysqli_num_rows($resultado) > 0){
            $tarea = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
            return json_encode($tarea);
        }
    }



    
    //------------------------------------------------------------- UPDATE -----------------------------------------------

    /**
     * Actualiza los datos de una tarea en la base de datos
     * 
     * @param $conexion La conexión con la base de datos
     * @param $id ID de la tarea que queremos actualizar
     * @param $nombre El nombre de la tarea
     * @param $descripcion La descripción de la tarea
     * @param $parentID La ID de su tarea padre
     * @param $estado El estado de la tarea
     * 
     * @return Boolean Indicando el resultado de la ejecución de la función
     */
    function actualizarTarea($conexion, $id, $nombre, $descripcion, $parentID, $estado){
        // Armo la sentencia
        $sentencia = "UPDATE ".TABLA_TAREAS." SET nombre = '".$nombre."', descripcion = '".$descripcion."', fecha_modificacion = NOW(), parentID = ".$parentID.", estado = ".$estado." WHERE id = ".$id;
        // Compruebo el resultado de la ejecución de la sentencia y devuelvo un booleano según corresponda
        return comprobarResultadoDeQuery($conexion, $sentencia);
    }




    //------------------------------------------------------------- DELETE -----------------------------------------------

    /**
     * Elimina una tarea y todas sus subtareas
     * 
     * @param $conexion La conexión con la base de datos
     * @param $tarea El objeto o la ID de la tarea que queremos eliminar
     * 
     * @return Boolean indicando si la acción resultó con errores
     */
    function eliminarTarea($conexion, $tarea){
        
        if (is_object($tarea)) { // Si la variable es un objeto de tipo tarea
            // Primero elimino las subtareas de la tarea que se quiere eliminar
            if (eliminarSubtareas($conexion, $tarea)) { 
                // Armo la consulta       
                $sentencia = "DELETE FROM ".TABLA_TAREAS." WHERE id=".$tarea-> id;
                
                // Compruebo el resultado de la ejecución de la sentencia y devuelvo un booleano según corresponda
                return comprobarResultadoDeQuery($conexion, $sentencia);
            }
            else {
                // Devuelvo el resultado de las acciones de error
                return accionesDeError($conexion);
            }
        }
        elseif (is_int($tarea)) { // Si la variable es el ID de la tarea
            // Primero elimino las subtareas de la tarea que se quiere eliminar
            if (eliminarSubtareas($conexion, $tarea)) { 
                // Armo la consulta       
                $sentencia = "DELETE FROM ".TABLA_TAREAS." WHERE id=".$tarea;
                
                // Compruebo el resultado de la ejecución de la sentencia y devuelvo un booleano según corresponda
                return comprobarResultadoDeQuery($conexion, $sentencia);
            }
            else {
                // Devuelvo el resultado de las acciones de error
                return accionesDeError($conexion);
            }            
        }
    }
    
    
    /**
     * Elimina todas las subtareas de una tarea
     * 
     * @param $conexion La conexión con la base de datos
     * @param $tarea La tarea o su ID sobre la que queremos eliminar sus subtareas
     * 
     * @return Boolean indicando si la acción resultó con errores
     */
    function eliminarSubtareas($conexion, $tarea){
        
        if (is_object($tarea)) { // Si la variable es un objeto de tipo tarea
            // Intento eliminar las subtareas
            $sentencia = "DELETE FROM ".TABLA_TAREAS." WHERE parentID=".$tarea-> id.";"; // Armo la sentencia para conseguir todas las subtareas
            return comprobarResultadoDeQuery($conexion, $sentencia);
        }
        elseif (is_int($tarea)) { // Si la variable es el ID de la tarea
            // Intento eliminar las subtareas
            $sentencia = "DELETE FROM ".TABLA_TAREAS." WHERE parentID=".$tarea.";";
            return comprobarResultadoDeQuery($conexion, $sentencia);
        }
    }




    //------------------------------------------------------------- OTROS -----------------------------------------------

    /**
     * Finaliza una tarea y todas sus subtareas
     * 
     * @param $conexion La conexión con la base de datos
     * @param $idTarea La ID de la tarea a finalizar junto con todas sus subtareas
     * 
     * @return Boolean Indicando el resultado de la función
     */
    function finalizarTarea($conexion, $idTarea){

        // Primero, intento finalizar sus subtareas
        if (finalizarSubtareas($conexion, $idTarea)) { // Compruebo el resultado de la función que intenta finalizar las subtareas de la tarea
            // Armo la sentencia para actualizar el estado de la tarea que quiero finalizar
            $sentencia = "UPDATE ".TABLA_TAREAS." SET fecha_modificacion= NOW(), estado =".ESTADO_FINALIZADO." WHERE id=".$idTarea.";";
            // Devuelvo un booleano según el resultado de la ejecución de la query
            return comprobarResultadoDeQuery($conexion, $sentencia);
        }
        else{
            // Devuelvo el resultado de las acciones de error
            return accionesDeError($conexion);
        }
    }


    /**
     * Finaliza todas las subtareas de una tarea padre
     * 
     * @param $conexion La conexión con la base de datos
     * @param $idTareaPadre La ID de la tarea padre, sobre la que se finalizarán todas sus subtareas
     * 
     * @return Boolean Indicando el resultado de la función
     */
    function finalizarSubtareas($conexion, $idTareaPadre){
        // No hace falta que desactive el autocommit porque ya lo está de antes

        // Armo la sentencia para actualizar las subtareas y finalizarlas
        $sentencia = "UPDATE ".TABLA_TAREAS." SET fecha_modificacion = NOW(), estado=".ESTADO_FINALIZADO." WHERE parentID=".$idTareaPadre.";";
        return comprobarResultadoDeQuery($conexion, $sentencia);
    }


    /**
     * Devuelve una tarea finalizada a pendiente
     * 
     * @param $conexion La conexión con la base de datos
     * @param $id La ID de la tarea finalizada que se pondrá como pendiente
     * 
     * @return Boolean Indicando el resultado de la función
     */
    function ponerEnPendiente($conexion, $id){

        // Primero consigo la ID de su tarea padre si la tiene
        $sentencia = "SELECT * FROM ".TABLA_TAREAS." WHERE id=".$id.";";
        $resultado = mysqli_query($conexion, $sentencia);
        // Así consigo su parentID
        while ($tarea = $resultado->fetch_object()) {
            $parentID = $tarea-> parentID;
        }

        if (!is_null($parentID)) { // Compruebo si tiene tarea padre
            // Pongo en pendiente su tarea padre
            $sentencia = "UPDATE ".TABLA_TAREAS." SET fecha_modificacion = NOW(), estado=".ESTADO_PENDIENTE." WHERE id=".$parentID.";";

            if (mysqli_query($conexion, $sentencia)) { // Ejecuto la sentencia y compruebo si ha salido bien
                $sentencia = "UPDATE ".TABLA_TAREAS." SET fecha_modificacion = NOW(), estado=".ESTADO_PENDIENTE." WHERE id=".$id.";"; // Armo la sentencia para poner la tarea en pendiente
                // Finalmente compruebo el resultado de la query y lo devuelvo
                return comprobarResultadoDeQuery($conexion, $sentencia);
            }
            else {
                return accionesDeError($conexion);
            }
        }
        else { // Si no tiene tarea padre
            $sentencia = "UPDATE ".TABLA_TAREAS." SET fecha_modificacion = NOW(), estado=".ESTADO_PENDIENTE." WHERE id=".$id.";"; // Armo la sentencia para poner la tarea en pendiente 
            // Compruebo el resultado de la query y lo devuelvo
            return comprobarResultadoDeQuery($conexion, $sentencia);
        }
    }
?>