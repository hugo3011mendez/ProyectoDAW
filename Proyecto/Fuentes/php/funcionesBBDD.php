<?php // En este archivo almacenaré las funciones que usaré para gestionar la base de datos

    require_once "Utils.php"; // Linkeo el archivo de Utils a este, para usar sus funciones
    require_once "Constantes.php"; // Linkeo el archivo de las constantes a este, para utilizarlas en las funciones de la BBDD
  

    /**
     * Intenta conectarse a la base de datos especificada y guarda en una variable el tipo de error en cada caso
     * 
     * @return La conexión a la BBDD si no hay errores, o false si ocurre algún error
     */
    function conectarBBDD(){
        $conexionBD = new mysqli("localhost", "root", "", NOMBRE_BBDD); // Me conecto a la BBDD
        $error = $conexionBD-> connect_error; // Recojo el código de error que la conexión haya generado
        
        // Compruebo si hay algún error, y devuelvo la conexión o devuelvo un booleano indicando que no ha podido conectarse
        if (is_null($error)) {
            return $conexionBD;
        }
        else {
            return false;
        }
    }


    /**
     * Acciones a realizar cuando ocurre algún error en una consulta contra la base de datos
     * 
     * @param $conexion La conexión con la base de datos. Pasada por referencia para que los cambios hagan efecto en la conexión.
     * @param $mensaje El mensaje de error que se quiere escribir en la consola
     * 
     * @return Boolean indicando que la operación tuvo errores, siempre es false
     */
    function accionesDeError(&$conexion, $mensaje){
        $conexion->rollback(); // Al salir algo mal, primero hago el rollback

        consoleLog($mensaje); // Y en ese caso muestro un mensaje de error en la consola
        $conexion-> close(); // Finalmente cierro la conexión a la base de datos
        
        return false; // Devuelvo un false indicando que ha habido algún error
    }


    // ----------------------------------------------------------- Funciones referentes a los usuarios ---------------------------------------------------------

    /**
     * Inserta los datos de un nuevo Usuario en la BBDD
     * 
     * @param $conexion Conexión a la BBDD
     * @param $email Email del nuevo usuario
     * @param $nickname Nickname del nuevo usuario
     * @param $password Contraseña del nuevo usuario
     * @param $imagen base64 de la imagen del nuevo usuario
     * @param $rol Rol que va a tener el nuevo usuario
     * 
     * @return Boolean indicando si la acción resultó con errores
     */
    function registrarUsuario($conexion, $email, $nickname, $password, $imagen, $rol){ // TODO : El rol seguramente vaya a mano
        $conexion->autocommit(FALSE); // Desactivo el autocommit

        // Armo la sentencia
        $sentencia = "INSERT INTO ".TABLA_USUARIOS." (email, nickname, pwd, imagen, rol) VALUES(".$email.", ".$nickname.", ".$password.", ".$imagen.", ".$rol.")";
        if (!mysqli_query($conexion, $sentencia)) { // Compruebo que al ejecutar la consulta haya salido algo mal
            return accionesDeError($conexion, "Se ha producido un error al registrar al usuario : ".$conexion-> connect_error); // Devuelvo el resultado de las acciones de error
        }
        else {
            $conexion->commit(); // Realizo el commit si ha salido bien
            return true; // Devuelvo un true indicando que todo ha ido bien
        }
    }


    /**
     * Elimina el usuario cuya ID coincida con la pasada como parámetro
     * 
     * @param $conexion La conexión a la base de datos
     * @param $id La ID del usuario a eliminar
     * 
     * @return Boolean indicando si la acción resultó con errores
     */
    function eliminarUsuario($conexion, $id){
        $conexion->autocommit(FALSE); // Desactivo el autocommit

        $sentencia = "DELETE FROM ".TABLA_USUARIOS." WHERE id = ".$id; // Armo la sentencia
        if (mysqli_query($conexion, $sentencia)) { // Compruebo si la consulta se ha ejecutado correctamente
            $conexion->commit(); // Realizo el commit si ha salido bien

            // Llamo a la función para que elimine todos los proyectos del usuario que acabo de eliminar
            return eliminarProyectosDeUsuario($conexion, $id); // Y devuelvo una booleana según su resultado
        }
        else {
            return accionesDeError($conexion, "Se ha producido un error al eliminar al usuario con ID ".$id." : ".$conexion-> connect_error); // Devuelvo el resultado de las acciones de error
        }
    }


    /**
     * Elimina de la base de datos todos los proyectos y las tareas a cargo de un usuario
     * 
     * @param $conexion La conexión con la base de datos
     * @param $idUsuario La ID del usuario sobre el que vamos a eliminar los proyectos
     * 
     * @return Boolean indicando si la acción resultó con errores
     */
    function eliminarProyectosDeUsuario($conexion, $idUsuario){
        $sentencia = "SELECT * FROM ".TABLA_PROYECTOS." WHERE usuario_creador = ".$idUsuario; // Consigo todos los proyectos de la base de datos
        $resultado = mysqli_query($conexion, $sentencia); // Guardo su resultado

        while ($proyecto = $resultado -> fetch_object()) { // Recorro todos los proyectos en su tabla correspondiente
            if ($proyecto -> usuario_creador == $idUsuario) { // En el caso de que el proyecto actual pertenece al usuario
                // Ejecuto la función que elimina un proyecto y todas sus tareas de la base de datos
                return eliminarProyecto($conexion, $proyecto); // Y devuelvo un booleano según su resultado
            }
        }
    }



    // ----------------------------------------------------------- Funciones generales ---------------------------------------------------------

    /**
     * Elimina un proyecto y todas sus tareas de la base de datos
     * 
     * @param $conexion La conexión con la base de datos
     * @param $proyecto El objeto del proyecto que va a ser eliminado, y sobre el que se van a eliminar todas las tareas
     * 
     * @return Boolean indicando si la acción resultó con errores
     */
    function eliminarProyecto($conexion, $proyecto){
        $conexion->autocommit(FALSE); // Desactivo el autocommit

        if (eliminarTodasTareas($conexion, $proyecto)) { // Compruebo que haya salido bien la realización de esta función

            // Si sale bien, armo la consulta para eliminar el proyecto
            $sentencia = "DELETE FROM ".TABLA_PROYECTOS." WHERE id =".$proyecto-> id;
            if (!mysqli_query($onexion, $sentencia)) {               
                return accionesDeError($conexion, "Se ha producido un error al intentar eliminar el proyecto ".$proyecto-> nombre." : ".$conexion-> connect_error); // Devuelvo el resultado de las acciones de error
            }
            else {
                $conexion->commit(); // Realizo el commit si ha salido bien

                return true; // Devuelvo true si todo ha ido bien
            }                    
        }
        else { // En el caso de que haya algún error
            return accionesDeError($conexion, "Se ha producido un error al intentar eliminar todas las tareas del proyecto ".$proyecto-> nombre." : ".$conexion-> connect_error); // Devuelvo el resultado de las acciones de error
        }
    }


    /**
     * Elimina todas las tareas y tareas finalizadas de un proyecto
     * 
     * @param $conexion La conexión con la base de datos
     * @param $proyecto El objeto del proyecto sobre el que se van a eliminar todas sus tareas
     * 
     * @return Boolean indicando si la acción resultó con errores
     */
    function eliminarTodasTareas($conexion, $proyecto){
        $conexion->autocommit(FALSE); // Desactivo el autocommit

        // Armo la consulta para eliminar primero las tareas finalizadas del proyecto
        $sentencia = "DELETE FROM ".TABLA_TAREAS_FINALIZADAS." WHERE proyecto =".$proyecto-> id;
        if (mysqli_query($conexion, $sentencia)) { // Intento eliminar las tareas finalizadas
        
            // Armo la consulta para eliminar ahora las tareas del proyecto
            $sentencia = "DELETE FROM ".TABLA_TAREAS." WHERE proyecto =".$proyecto-> id;
            if (!mysqli_query($conexion, $sentencia)) { // Intento eliminar las tareas
                $conexion->commit(); // Realizo el commit si ha salido bien

                return true;
            }
            else {
                return accionesDeError($conexion, "Se ha producido un error al intentar eliminar las tareas correspondientes al proyecto ".$proyecto-> nombre." : ".$conexion-> connect_error); // Devuelvo el resultado de las acciones de error
            }
        }
        else {
            return accionesDeError($conexion, "Se ha producido un error al intentar eliminar las tareas finalizadas correspondientes al proyecto ".$proyecto-> nombre." : ".$conexion-> connect_error); // Devuelvo el resultado de las acciones de error
        }           
    }


    /**
     * Elimina una tarea y todas sus subtareas
     * 
     * @param $conexion La conexión con la base de datos
     * @param $tarea El objeto de la tarea que queremos eliminar
     * 
     * @return Boolean indicando si la acción resultó con errores
     */
    function eliminarTarea($conexion, $tarea){
        $conexion->autocommit(FALSE); // Desactivo el autocommit

        // Primero elimino las subtareas de la tarea que se quiere eliminar
        if (eliminarSubtareas($conexion, $tarea)) { 
            // Armo la consulta       
            $sentencia = "DELETE FROM ".TABLA_TAREAS." WHERE id=".$tarea-> id;

            if (mysqli_query($conexion, $sentencia)) { // Intento eliminar la tarea
                $conexion->commit(); // Realizo el commit si ha salido bien

                return true;
            }
            else {
                return accionesDeError($conexion, "Se ha producido un error al intentar eliminar la tarea ".$tarea-> nombre." : ".$conexion-> connect_error); // Devuelvo el resultado de las acciones de error
            }
        }
        else {
            return accionesDeError($conexion, "Se ha producido un error al intentar eliminar las subtareas de la tarea ".$tarea-> nombre." : ".$conexion-> connect_error); // Devuelvo el resultado de las acciones de error
        }
    }


    /**
     * Elimina todas las subtareas de una tarea
     * 
     * @param $conexion La conexión con la base de datos
     * @param $tarea La tarea sobre la que queremos eliminar sus subtareas
     * 
     * @return Boolean indicando si la acción resultó con errores
     */
    function eliminarSubtareas($conexion, $tarea){
        $conexion->autocommit(FALSE); // Desactivo el autocommit

        // Primero intento eliminar las subtareas finalizadas
        $sentencia = "DELETE FROM ".TABLA_TAREAS_FINALIZADAS." WHERE parentID=".$tarea-> id;
        if (mysqli_query($conexion, $sentencia)) { // Intento eliminar las subtareas finalizadas

            // Ahora intento eliminar las subtareas activas
            $sentencia = "DELETE FROM ".TABLA_TAREAS." WHERE parentID=".$tarea-> id;
            if (mysqli_query($conexion, $sentencia)) { // Intento eliminar las subtareas activas
                $conexion->commit(); // Realizo el commit si ha salido bien

                return true;
            }
            else {
                return accionesDeError($conexion, "Se ha producido un error al intentar eliminar las subtareas de la tarea ".$tarea-> nombre." : ".$conexion-> connect_error); // Devuelvo el resultado de las acciones de error       
            }
        }
        else {
            return accionesDeError($conexion, "Se ha producido un error al intentar eliminar las subtareas finalizadas de la tarea ".$tarea-> nombre." : ".$conexion-> connect_error); // Devuelvo el resultado de las acciones de error
        }
    }


    /**
     * Finaliza una tarea y todas sus subtareas
     * 
     * @param $conexion La conexión con la base de datos
     * @param $idTarea La ID de la tarea a finalizar junto con todas sus subtareas
     * 
     * @return Boolean Indicando el resultado de la función
     */
    function finalizarTarea($conexion, $idTarea){
        $conexion->autocommit(FALSE); // Desactivo el autocommit

        // Primero, intento finalizar sus subtareas
        if (finalizarSubtareas($conexion, $idTarea)) { // Compruebo el resultado de la función que intenta finalizar las subtareas de una tarea
            $sentencia = "SELECT * FROM ".TABLA_TAREAS." WHERE id=".$idTarea; // Establezco la sentencia para conseguir los datos de la tarea
            $tarea = mysqli_query($conexion, $sentencia)-> fetch_object(); // Guardo el resultado de la query

            $sentencia = "DELETE FROM".TABLA_TAREAS." WHERE id=".$idTarea; // Establezco la sentencia para eliminar la tarea de la tabla
            if (mysqli_query($conexion, $sentencia)) { // Ejecuto la consulta y compruebo si ha resultado satisfactoria
                while ($tarea) {
                    // Establezco la sentencia para insertar los datos de la tarea en la tabla de tareas finalizadas
                    $sentencia = "INSERT INTO ".TABLA_TAREAS_FINALIZADAS."values ('".$tarea->nombre.
                    "', '".$tarea-> descripcion.
                    ", ".$tarea-> fecha_modificacion.
                    ", ".$tarea-> proyecto.
                    ", ".$tarea-> parentID;

                    if (mysqli_query($conexion, $sentencia)) { // Ejecuto la consulta y compruebo que haya salido bien
                        $conexion->commit(); // Realizo el commit si ha salido bien

                        return true;
                    }
                    else{
                        return accionesDeError($conexion, "Se ha producido un error al intentar insertar la tarea a la tabla de tareas finalizadas : ".$conexion-> connect_error); // Devuelvo el resultado de las acciones de error
                    }
                }
            }
            else{
                return accionesDeError($conexion, "Se ha producido un error al intentar eliminar la tarea con ID ".$idTarea." de su tabla : ".$conexion-> connect_error); // Devuelvo el resultado de las acciones de error
            }
        }
        else{
            return accionesDeError($conexion, "Se ha producido un error al intentar finalizar las subtareas de la tarea : ".$conexion-> connect_error); // Devuelvo el resultado de las acciones de error
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
        $conexion->autocommit(FALSE); // Desactivo el autocommit

        $sentencia = "SELECT * FROM ".TABLA_TAREAS." WHERE parentID=".$idTareaPadre; // Consulto todas las subtareas activas
        $resultado = mysqli_query($conexion, $sentencia); // Guardo el resultado de la query

        $error = true; // Creo una variable para saber si ha habido algún error dentro del bucle

        while ($subtarea = $resultado -> fetch_object()) { // Recorro todos los proyectos en su tabla correspondiente
            $sentencia = "DELETE FROM ".TABLA_TAREAS." WHERE id=".$subtarea-> id; // Armo la sentencia para eliminar la subtarea actual
            if (mysqli_query($conexion, $sentencia)) { // Ejecuto la sentencia y compruebo que haya salido bien
                $conexion->commit();
                // TODO : Ver si hay una mejor manera de hacer esto
                // Armo la sentencia para introdducir la subtarea finalizada en la otra tabla
                $sentencia = "INSERT INTO ".TABLA_TAREAS_FINALIZADAS."values ('".$subtarea->nombre.
                "', '".$subtarea-> descripcion.
                ", ".$subtarea-> fecha_modificacion.
                ", ".$subtarea-> proyecto.
                ", ".$subtarea-> parentID;

                if (mysqli_query($conexion, $sentencia)) {
                    $conexion->commit();
                }
                else{
                    $error = false;
                    $conexion->rollback(); // Al salir algo mal, primero hago el rollback

                    // En caso incorrecto, muestro un mensaje de error en la consola
                    consoleLog("Se ha producido un error al intentar introducir la subtarea".$subtarea-> nombre.
                    " en la tabla de tareas finalizadas : ".$conexion-> connect_error);
                }
            }
            else{
                $error = false;
                $conexion->rollback(); // Al salir algo mal, primero hago el rollback

                // En caso incorrecto, muestro un mensaje de error en la consola
                consoleLog("Se ha producido un error al intentar finalizar la subtarea".$subtarea-> nombre." : ".$conexion-> connect_error);
            }
        }

        if (!$error) { // Si ha habido algún error en el bucle
            // Muestro un mensaje de error
            consoleLog("Se ha producido un error al intentar finalizar las subtareas : ".$conexion-> connect_error);
        }

        return $error; // Devuelvo el valor del error como resultado de la función
    }
?>