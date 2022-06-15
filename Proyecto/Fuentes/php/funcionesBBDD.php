<?php // En este archivo almacenaré las funciones que usaré para gestionar la base de datos

    require_once "Utils.php"; // Linkeo el archivo de Utils a este, para usar sus funciones
    require_once "Constantes.php"; // Linkeo el archivo de las constantes a este, para utilizarlas en las funciones de la BBDD

    //------------------------------------------------------------- FUNCIONES GENERALES -----------------------------------------------

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
            consoleLog("No se ha podido conectar con la base de datos. ".$conexionBD-> connect_error); // Si hay algún error, muestro un mensaje en la consola
            return false;
        }
    }


    /**
     * Comprueba el resultado de la ejecución de una consulta
     * 
     * @param $conexion La conexión con la base de datos. Pasada por referencia para que los cambios hagan efecto en la conexión.
     * @param $sentencia La sentencia a ejecutar
     * @param $mensaje El mensaje a mostrar en consola si algo sale mal
     * 
     * @return Boolean Indicando el resultado de la función
     */
    function comprobarResultadoDeQuery(&$conexion, $sentencia, $mensaje){
        if (mysqli_query($conexion, $sentencia)) { // Intento eliminar las subtareas activas
            $conexion->commit(); // Realizo el commit si ha salido bien

            return true;
        }
        else {
            return accionesDeError($conexion, $mensaje); // Devuelvo el resultado de las acciones de error
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




    //------------------------------------------------------------- FUNCIONES PARA USUARIOS -----------------------------------------------

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

        $yaRegistrado = false; // Booleano para indicar si el email del usuario ya está en la BBDD
        $sentencia = "SELECT * FROM ".TABLA_USUARIOS." WHERE email = '".$email."'"; // Armo la sentencia
        $resultado = mysqli_query($conexion, $sentencia); // Guardo el resultado de la ejecución de la sentencia para recorrerse

        // Recorro el resultado de la consulta y compruebo si el email ya está registrado
        while ($usuario = $resultado -> fetch_object()) {
            if ($usuario-> email == $email) {
                $yaRegistrado = true;
            }
        }

        if (!$yaRegistrado) { // Si finalmente el email no está en la tabla de la BBDD
            // Armo la sentencia
            $sentencia = "INSERT INTO ".TABLA_USUARIOS." (email, nickname, pwd, imagen, rol) VALUES('".$email."', '".$nickname."', '".$password."', '".$imagen."', ".$rol.")";
            // Compruebo el resultado de la ejecución de la sentencia y devuelvo un booleano según corresponda
            return comprobarResultadoDeQuery($conexion, $sentencia, "Se ha producido un error al registrar al usuario. ".$conexion-> connect_error);       
        }
        else {
            accionesDeError($conexion, "Error al registrarse : El email del usuario ya se encuentra en la base de datos");
        }
    }


    /**
     * Actualiza la información de un usuario en la base de datos
     * 
     * @param $conexion La conexión con la base de datos
     * @param $id La ID del usuario que queremos actualizar
     * @param $email El nuevo email del usuario
     * @param $nickname El nuevo nickname del usuario
     * @param $password La nueva password del usuario
     * @param $imagen La nueva imagen del usuario
     * @param $rol El nuevo rol del usuario
     * 
     * @return Boolean Indicando el resultado de la ejecución de la función
     */
    function actualizarUsuario($conexion, $id, $email, $nickname, $password, $imagen, $rol){
        // Compruebo si algún dato es null, para guardar en su lugar el que hay en la BBDD
        if ($email == null) {
            $email = conseguirDatoUsuario($conexion, $id, 0);
        }

        if ($nickname == null) {
            $nickname = conseguirDatoUsuario($conexion, $id, 1);
        }

        if ($password == null) {
            $password = conseguirDatoUsuario($conexion, $id, 2);
        }

        if ($imagen == null) {
            $imagen = conseguirDatoUsuario($conexion, $id, 3);
        }

        if ($rol == null) {
            $rol = conseguirDatoUsuario($conexion, $id, 4);
        }

        // Armo la sentencia
        $sentencia = "UPDATE ".TABLA_USUARIOS." SET email = '".$email."', nickname = '".$nickname."', pwd = '".$password."', imagen = '".$imagen."' WHERE id = ".$id;
        
        // Compruebo el resultado de la ejecución de la sentencia y devuelvo un booleano según corresponda
        return comprobarResultadoDeQuery($conexion, $sentencia, "Se ha producido un error al actualizar el usuario. ".$conexion-> connect_error);       
    }


    /**
     * Según la ID del usuario y el código del dato, consigo y devuelvo el indicado de la BBDD
     * 
     * @param $conexion La conexión con la base de datos
     * @param $id La ID del usuario sobre el que buscaremos el dato
     * @param $dato Código numérico que indicará qué dato tenemos que obtener
     * 
     * @return Dato El dato que necesitamos conseguir
     */
    function conseguirDatoUsuario($conexion, $id, $codigoDato){
        $sentencia = "SELECT * FROM ".TABLA_USUARIOS." WHERE id = ".$id;
        $resultado = mysqli_query($conexion, $sentencia); // Guardo el resultado de la ejecución de la sentencia para recorrerse
        // Recorro el resultado de la consulta y compruebo si la ID coincide
        while ($usuario = $resultado -> fetch_object()) {
            if ($usuario-> id == $id) {
                switch ($codigoDato) { // Según el código de dato, devuelvo el dato correspondiente
                    case 0:
                        return $usuario-> email; 
                        break;
        
                    case 1:
                        return $usuario-> nickname; 
                        break;
        
                    case 2:
                        return $usuario-> pwd; 
                        break;
        
                    case 3:
                        return $usuario-> imagen; 
                        break;
        
                    case 4:
                        return $usuario-> rol;
                        break;
                }
            }
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

        // Primero tengo que eliminar los proyectos del usuario, debido a que su ID es clave foránea en la tabla de proyectos
        if (eliminarProyectosDeUsuario($conexion, $id)) {
            $sentencia = "DELETE FROM ".TABLA_USUARIOS." WHERE id = ".$id.";"; // Armo la sentencia
            return comprobarResultadoDeQuery($conexion, $sentencia, "Se ha producido un error al eliminar al usuario con ID ".$id.". ".$conexion-> connect_error);
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


    /**
     * Devuelve el rol de un usuario según su ID
     * 
     * @param $conexion La conexión con la base de datos
     * @param $idUsuario La ID del usuario sobre el que queremos saber su rol
     * 
     * @return Int La ID de su rol
     */
    function conseguirRolDeUsuario($conexion, $idUsuario){
        // Armo la sentencia para conseguir los datos del usuario en cuestión
        $sentencia = "SELECT * FROM ".TABLA_USUARIOS." WHERE id = ".$idUsuario.";";
        $resultado = mysqli_query($conexion, $sentencia); // Guardo su resultado

        while ($usuario = $resultado -> fetch_object()) { // Consigo el resultado en formato objeto
            return $usuario -> rol; // Y devuelvo el rol del usuario
        }
    }




    //------------------------------------------------------------- FUNCIONES PARA ROLES -----------------------------------------------
    
    /**
     * Crea un nuevo rol y lo guarda en la base de datos
     * 
     * @param $conexion La conexión con la BBDD
     * @param $nombre El nombre del nuevo rol
     * @param $privilegios Los privilegios del nuevo rol
     * 
     * @return Boolean Indicando el resultado de la ejecución de la función
     */
    function crearRol($conexion, $nombre, $privilegios){
        $conexion->autocommit(FALSE); // Desactivo el autocommit

        $sentencia = "INSERT INTO ".TABLA_ROLES." (nombre, privilegios) VALUES('".$nombre."', ".$privilegios.")";

        // Compruebo el resultado de la ejecución de la sentencia y devuelvo un booleano según corresponda
        return comprobarResultadoDeQuery($conexion, $sentencia, "Se ha producido un error al crear el rol ".$nombre.". ".$conexion-> connect_error);
    }


    /**
     * Actualiza un rol existente en la BBDD
     * 
     * @param $conexion La conexión con la BBDD
     * @param $nombre El nuevo nombre del rol
     * @param $privilegios Los nuevos privilegios del rol
     * @param $id La ID del rol que queremos actualizar
     * 
     * @return Boolean Indicando el resultado de la ejecución de la función
     */
    function actualizarRol($conexion, $nombre, $privilegios, $id){
        $conexion->autocommit(FALSE); // Desactivo el autocommit
        
        // Compruebo si alguno de estos dos valores es null, para autocompletarlo con el existente en la BBDD
        if ($nombre == null) {
            $nombre = conseguirDatoRol($conexion, $id, 0);
        }

        if ($privilegios == null) {
            $privilegios = conseguirDatoRol($conexion, $id, 1);
        }


        // Armo la sentencia
        $sentencia = "UPDATE ".TABLA_ROLES." SET nombre = '".$nombre."', privilegios = ".$privilegios." WHERE id = ".$id;
        // Compruebo el resultado de la ejecución de la sentencia y devuelvo un booleano según corresponda
        return comprobarResultadoDeQuery($conexion, $sentencia, "Se ha producido un error al intentar actualizar el rol con ID ".$id.". ".$conexion-> connect_error);
    }


    /**
     * Según la ID del rol y el código del dato, consigo y devuelvo el indicado de la BBDD
     * 
     * @param $conexion La conexión con la base de datos
     * @param $id La ID del rol sobre el que buscaremos el dato
     * @param $dato Código numérico que indicará qué dato tenemos que obtener
     * 
     * @return Dato El dato que necesitamos conseguir
     */
    function conseguirDatoRol($conexion, $id, $codigoDato){
        $sentencia = "SELECT * FROM ".TABLA_ROLES." WHERE id = ".$id;
        $resultado = mysqli_query($conexion, $sentencia); // Guardo el resultado de la ejecución de la sentencia para recorrerse
        // Recorro el resultado de la consulta y compruebo si la ID coincide
        while ($rol = $resultado -> fetch_object()) {
            if ($rol-> id == $id) {
                switch ($codigoDato) { // Según el código de dato, devuelvo el dato correspondiente
                    case 0:
                        return $rol-> nombre; 
                        break;
        
                    case 1:
                        return $rol-> privilegios;
                        break;
                    }
            }
        }
    }


    /**
     * Elimina un rol de la BBDD
     * 
     * @param $conexion La conexión con la BBDD
     * @param $id La ID del rol que queremos eliminar
     * 
     * @return Boolean Indicando el resultado de la ejecución de la función
     */
    function eliminarRol($conexion, $id){
        $conexion->autocommit(FALSE); // Desactivo el autocommit
        
        // Armo la sentencia
        $sentencia = "DELETE FROM ".TABLA_ROLES." WHERE id = ".$id;
        
        // Compruebo el resultado de la ejecución de la sentencia y devuelvo un booleano según corresponda
        return comprobarResultadoDeQuery($conexion, $sentencia, "Se ha producido un error al intentar eliminar el rol con ID ".$id.". ".$conexion-> connect_error);
    }




    //------------------------------------------------------------- FUNCIONES PARA PROYECTOS -----------------------------------------------
    
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
        $conexion->autocommit(FALSE); // Desactivo el autocommit
        
        // Armo la sentencia de creación
        $sentencia = "INSERT INTO ".TABLA_PROYECTOS." (usuario_creador, nombre, descripcion, fecha_creacion) VALUES (".
        $idCreador.
        ", '".$nombre.
        "', '".$descripcion.
        "', NOW());";
        
        // Compruebo el resultado de la ejecución de la sentencia y devuelvo un booleano según corresponda
        return comprobarResultadoDeQuery($conexion, $sentencia, "Se ha producido un error al intentar crear el proyecto ".$nombre.". ".$conexion-> connect_error);
    }
    

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
        $conexion->autocommit(FALSE); // Desactivo el autocommit
        
        // Compruebo si alguno de estos dos datos es null para autocompletarlo con el existente en la BBDD
        if ($nombre == null) {
            $nombre = conseguirDatoProyecto($conexion, $id, 0);
        }

        if ($descripcion == null) {
            $descripcion = conseguirDatoProyecto($conexion, $id, 1);
        }

        // Armo la sentencia de creación
        $sentencia = "UPDATE ".TABLA_PROYECTOS." SET nombre = '".$nombre."', descripcion = '".$descripcion."' WHERE id = ".$id;

        // Compruebo el resultado de la ejecución de la sentencia y devuelvo un booleano según corresponda
        return comprobarResultadoDeQuery($conexion, $sentencia, "Se ha producido un error al intentar actualizar el proyecto con ID ".$id.". ".$conexion-> connect_error);
    }


    /**
     * Según la ID del proyecto y el código del dato, consigo y devuelvo el indicado de la BBDD
     * 
     * @param $conexion La conexión con la base de datos
     * @param $id La ID del proyecto sobre el que buscaremos el dato
     * @param $dato Código numérico que indicará qué dato tenemos que obtener
     * 
     * @return Dato El dato que necesitamos conseguir
     */
    function conseguirDatoProyecto($conexion, $id, $codigoDato){
        $sentencia = "SELECT * FROM ".TABLA_PROYECTOS." WHERE id = ".$id;
        $resultado = mysqli_query($conexion, $sentencia); // Guardo el resultado de la ejecución de la sentencia para recorrerse
        // Recorro el resultado de la consulta y compruebo si la ID coincide
        while ($proyecto = $resultado -> fetch_object()) {
            if ($proyecto-> id == $id) {
                switch ($codigoDato) { // Según el código de dato, devuelvo el dato correspondiente
                    case 0:
                        return $proyecto-> nombre; 
                        break;
        
                    case 1:
                        return $proyecto-> descripcion;
                        break;
                    }
            }
        }
    }
    

    /**
     * Elimina un proyecto y todas sus tareas de la base de datos
     * 
     * @param $conexion La conexión con la base de datos
     * @param $proyecto El objeto o la ID del proyecto que va a ser eliminado, y sobre el que se van a eliminar todas las tareas
     * 
     * @return Boolean indicando si la acción resultó con errores
     */
    function eliminarProyecto($conexion, $proyecto){
        $conexion->autocommit(FALSE); // Desactivo el autocommit
        
        if (is_object($proyecto)) { // Si la variable se trata de un objeto representando al proyecto
            if (eliminarTodasTareas($conexion, $proyecto)) { // Compruebo que haya salido bien la realización de esta función
                
                // Si sale bien, armo la consulta para eliminar el proyecto
                $sentencia = "DELETE FROM ".TABLA_PROYECTOS." WHERE id =".$proyecto-> id;
                // Compruebo el resultado de la ejecución de la sentencia y devuelvo un booleano según corresponda
                return comprobarResultadoDeQuery($conexion, $sentencia, "Se ha producido un error al intentar eliminar el proyecto ".$proyecto-> nombre.". ".$conexion-> connect_error);
            }
            else {
                // Devuelvo el resultado de las acciones de error
                return accionesDeError($conexion, "Se ha producido un error al intentar eliminar todas las tareas del proyecto ".$proyecto-> nombre.". ".$conexion-> connect_error);
            }
        }
        elseif (is_int($proyecto)) { // Si resulta que la variable se trata de la ID del proyecto
            if (eliminarTodasTareas($conexion, $proyecto)) { // Compruebo que haya salido bien la realización de esta función
                // Si sale bien, armo la consulta para eliminar el proyecto
                $sentencia = "DELETE FROM ".TABLA_PROYECTOS." WHERE id =".$proyecto;
                // Compruebo el resultado de la ejecución de la sentencia y devuelvo un booleano según corresponda
                return comprobarResultadoDeQuery($conexion, $sentencia, "Se ha producido un error al intentar eliminar el proyecto. ".$conexion-> connect_error);
            }
            else {
                // Devuelvo el resultado de las acciones de error
                return accionesDeError($conexion, "Se ha producido un error al intentar eliminar todas las tareas del proyecto. ".$conexion-> connect_error);
            }
        }
    }    
    

    /**
     * Elimina todas las tareas y tareas finalizadas de un proyecto
     * 
     * @param $conexion La conexión con la base de datos
     * @param $proyecto El objeto o la ID del proyecto sobre el que se van a eliminar todas sus tareas
     * 
     * @return Boolean indicando si la acción resultó con errores
     */
    function eliminarTodasTareas($conexion, $proyecto){
        $conexion->autocommit(FALSE); // Desactivo el autocommit
        
        if (is_object($proyecto)) { // Si la variable se trata de un objeto representando al proyecto
            // Armo la consulta para eliminar primero las tareas finalizadas del proyecto
            $sentencia = "DELETE FROM ".TABLA_TAREAS_FINALIZADAS." WHERE proyecto =".$proyecto-> id;
            if (mysqli_query($conexion, $sentencia)) { // Intento eliminar las tareas finalizadas
            
                // Armo la consulta para eliminar ahora las tareas del proyecto
                $sentencia = "DELETE FROM ".TABLA_TAREAS." WHERE proyecto =".$proyecto-> id;
                // Compruebo el resultado de la ejecución de la sentencia y devuelvo un booleano según corresponda
                return comprobarResultadoDeQuery($conexion, $sentencia, "Se ha producido un error al intentar eliminar las tareas correspondientes al proyecto ".$proyecto-> nombre.". ".$conexion-> connect_error);
            }
            else {
                // Devuelvo el resultado de las acciones de error
                return accionesDeError($conexion, "Se ha producido un error al intentar eliminar las tareas finalizadas correspondientes al proyecto ".$proyecto-> nombre.". ".$conexion-> connect_error);
            }            
        }
        elseif (is_int($proyecto)) { // Si resulta que la variable se trata de la ID del proyecto
            // Armo la consulta para eliminar primero las tareas finalizadas del proyecto
            $sentencia = "DELETE FROM ".TABLA_TAREAS_FINALIZADAS." WHERE proyecto =".$proyecto;
            if (mysqli_query($conexion, $sentencia)) { // Intento eliminar las tareas finalizadas
            
                // Armo la consulta para eliminar ahora las tareas del proyecto
                $sentencia = "DELETE FROM ".TABLA_TAREAS." WHERE proyecto =".$proyecto;
                // Compruebo el resultado de la ejecución de la sentencia y devuelvo un booleano según corresponda
                return comprobarResultadoDeQuery($conexion, $sentencia, "Se ha producido un error al intentar eliminar las tareas correspondientes al proyecto. ".$conexion-> connect_error);
            }
            else {
                // Devuelvo el resultado de las acciones de error
                return accionesDeError($conexion, "Se ha producido un error al intentar eliminar las tareas finalizadas correspondientes al proyecto. ".$conexion-> connect_error);
            }            
        }
    }
    
    
    
    
    //------------------------------------------------------------- FUNCIONES PARA TAREAS Y SUBTAREAS -----------------------------------------------
    
    /**
     * Crea una tarea según los parámetros especificados
     * 
     * @param $conexion La conexión con la base de datos
     * @param $nombre El nombre de la nueva tarea
     * @param $descripcion La descripción de la nueva tarea
     * @param $proyecto La ID del proyecto donde crearemos una nueva tarea
     * @param $parentID La ID de la tarea padre
     * 
     * @return Boolean Indicando el resultado de la ejecución de la función
     */
    function crearTarea($conexion, $nombre, $descripcion, $proyecto, $parentID){ // TODO : Añadir el estado de la tarea
        $conexion->autocommit(FALSE); // Desactivo el autocommit

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
            // Armo la sentencia de creación
            $sentencia = "INSERT INTO ".TABLA_TAREAS." (nombre, descripcion, fecha_creacion, fecha_modificacion, proyecto, parentID) VALUES ('".$nombre.
            "', '".$descripcion.
            "', NOW(), NOW(), ". // Uso la función NOW() de MYSQL para las fechas de creación y modificación
            $proyecto.
            ", ".$parentID.");";
            
            // Compruebo el resultado de la ejecución de la sentencia y devuelvo un booleano según corresponda
            return comprobarResultadoDeQuery($conexion, $sentencia, "Se ha producido un error al intentar crear la tarea ".$nombre.". ".$conexion-> connect_errno);
        }
        else { // Si el proyecto no existe, realizo las acciones de error
            accionesDeError($conexion, "El proyecto en el que se quiere insertar la tarea no existe ");
        }
    }
    
    
    /**
     * Actualiza los datos de una tarea en la base de datos
     * 
     * @param $conexion La conexión con la base de datos
     * @param $id ID de la tarea que queremos actualizar
     * @param $nombre El nombre de la tarea
     * @param $descripcion La descripción de la tarea
     * @param $parentID La ID de su tarea padre
     * 
     * @return Boolean Indicando el resultado de la ejecución de la función
     */
    function actualizarTarea($conexion, $id, $nombre, $descripcion, $parentID){ // TODO : Añadir el estado de la tarea
        $conexion->autocommit(FALSE); // Desactivo el autocommit
        
        // Compruebo si alguno de estos dos datos es null para autocompletarlo con el existente en la BBDD
        if ($nombre == null) {
            $nombre = conseguirDatoTarea($conexion, $id, 0);
        }
        
        if($descripcion == null){
            $descripcion = conseguirDatoTarea($conexion, $id, 1);
        }

        if($parentID == null){
            $parentID = conseguirDatoTarea($conexion, $id, 2);
        }

        // Armo la sentencia
        $sentencia = "UPDATE ".TABLA_TAREAS." SET nombre = '".$nombre."', descripcion = '".$descripcion."', fecha_modificacion = NOW(), parentID = ".$parentID." WHERE id = ".$id;
        
        // Compruebo el resultado de la ejecución de la sentencia y devuelvo un booleano según corresponda
        return comprobarResultadoDeQuery($conexion, $sentencia, "Se ha producido un error al intentar actualizar la tarea con ID ".$id.". ".$conexion-> connect_error);
    }
    

    /**
     * Según la ID de la tarea y el código del dato, consigo y devuelvo el indicado de la BBDD
     * 
     * @param $conexion La conexión con la base de datos
     * @param $id La ID de la tarea sobre el que buscaremos el dato
     * @param $dato Código numérico que indicará qué dato tenemos que obtener
     * 
     * @return Dato El dato que necesitamos conseguir
     */
    function conseguirDatoTarea($conexion, $id, $codigoDato){ // TODO : Añadir el estado de la tarea
        $sentencia = "SELECT * FROM ".TABLA_TAREAS." WHERE id = ".$id;
        $resultado = mysqli_query($conexion, $sentencia); // Guardo el resultado de la ejecución de la sentencia para recorrerse
        // Recorro el resultado de la consulta y compruebo si la ID coincide
        while ($tarea = $resultado -> fetch_object()) {
            if ($tarea-> id == $id) {
                switch ($codigoDato) { // Según el código de dato, devuelvo el dato correspondiente
                    case 0:
                        return $tarea-> nombre; 
                        break;
                        
                    case 1:
                        return $tarea-> descripcion;
                        break;

                    case 2:
                        if (is_null($tarea-> parentID)) {
                            return "null";
                        }
                        else{
                            return $tarea-> parentID;
                        }
                        break;
                }
            }
        }
    }
    

    /**
     * Elimina una tarea y todas sus subtareas
     * 
     * @param $conexion La conexión con la base de datos
     * @param $tarea El objeto o la ID de la tarea que queremos eliminar
     * 
     * @return Boolean indicando si la acción resultó con errores
     */
    function eliminarTarea($conexion, $tarea){
        $conexion->autocommit(FALSE); // Desactivo el autocommit
        
        if (is_object($tarea)) { // Si la variable es un objeto de tipo tarea
            // Primero elimino las subtareas de la tarea que se quiere eliminar
            if (eliminarSubtareas($conexion, $tarea)) { 
                // Armo la consulta       
                $sentencia = "DELETE FROM ".TABLA_TAREAS." WHERE id=".$tarea-> id;
                
                // Compruebo el resultado de la ejecución de la sentencia y devuelvo un booleano según corresponda
                return comprobarResultadoDeQuery($conexion, $sentencia, "Se ha producido un error al intentar eliminar la tarea ".$tarea-> nombre.". ".$conexion-> connect_error);
            }
            else {
                // Devuelvo el resultado de las acciones de error
                return accionesDeError($conexion, "Se ha producido un error al intentar eliminar las subtareas de la tarea ".$tarea-> nombre.". ".$conexion-> connect_error);
            }
        }
        elseif (is_int($tarea)) { // Si la variable es el ID de la tarea
            // Primero elimino las subtareas de la tarea que se quiere eliminar
            if (eliminarSubtareas($conexion, $tarea)) { 
                // Armo la consulta       
                $sentencia = "DELETE FROM ".TABLA_TAREAS." WHERE id=".$tarea;
                
                // Compruebo el resultado de la ejecución de la sentencia y devuelvo un booleano según corresponda
                return comprobarResultadoDeQuery($conexion, $sentencia, "Se ha producido un error al intentar eliminar la tarea. ".$conexion-> connect_error);
            }
            else {
                // Devuelvo el resultado de las acciones de error
                return accionesDeError($conexion, "Se ha producido un error al intentar eliminar las subtareas de la tarea. ".$conexion-> connect_error);
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
    function eliminarSubtareas($conexion, $tarea){ // TODO : Eliminar la tabla de tareas finalizadas
        $conexion->autocommit(FALSE); // Desactivo el autocommit
        
        if (is_object($tarea)) { // Si la variable es un objeto de tipo tarea
            // Primero intento eliminar las subtareas finalizadas
            $sentencia = "DELETE FROM ".TABLA_TAREAS_FINALIZADAS." WHERE parentID=".$tarea-> id;
            if (mysqli_query($conexion, $sentencia)) { // Intento eliminar las subtareas finalizadas
    
                // Ahora intento eliminar las subtareas activas
                $sentencia = "DELETE FROM ".TABLA_TAREAS." WHERE parentID=".$tarea-> id;
                // Compruebo el resultado de la ejecución de la sentencia y devuelvo un booleano según corresponda
                return comprobarResultadoDeQuery($conexion, $sentencia, "Se ha producido un error al intentar eliminar las subtareas de la tarea ".$tarea-> nombre.". ".$conexion-> connect_error);
            }
            else {
                // Devuelvo el resultado de las acciones de error
                return accionesDeError($conexion, "Se ha producido un error al intentar eliminar las subtareas finalizadas de la tarea ".$tarea-> nombre.". ".$conexion-> connect_error);
            }            
        }
        elseif (is_int($tarea)) { // Si la variable es el ID de la tarea
            // Primero intento eliminar las subtareas finalizadas
            $sentencia = "DELETE FROM ".TABLA_TAREAS_FINALIZADAS." WHERE parentID=".$tarea;
            if (mysqli_query($conexion, $sentencia)) { // Intento eliminar las subtareas finalizadas
    
                // Ahora intento eliminar las subtareas activas
                $sentencia = "DELETE FROM ".TABLA_TAREAS." WHERE parentID=".$tarea;
                // Compruebo el resultado de la ejecución de la sentencia y devuelvo un booleano según corresponda
                return comprobarResultadoDeQuery($conexion, $sentencia, "Se ha producido un error al intentar eliminar las subtareas de la tarea. ".$conexion-> connect_error);
            }
            else {
                // Devuelvo el resultado de las acciones de error
                return accionesDeError($conexion, "Se ha producido un error al intentar eliminar las subtareas finalizadas de la tarea. ".$conexion-> connect_error);
            }            
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
    function finalizarTarea($conexion, $idTarea){ // TODO : Eliminar la tabla de tareas finalizadas, añadir el estado de la tarea
        $conexion->autocommit(FALSE); // Desactivo el autocommit

        // Primero, intento finalizar sus subtareas
        if (finalizarSubtareas($conexion, $idTarea)) { // Compruebo el resultado de la función que intenta finalizar las subtareas de una tarea
            $sentencia = "SELECT * FROM ".TABLA_TAREAS." WHERE id=".$idTarea; // Establezco la sentencia para conseguir los datos de la tarea
            $resultado = mysqli_query($conexion, $sentencia); // Guardo el resultado en una variable para ser recorrido

            $sentencia = "DELETE FROM ".TABLA_TAREAS." WHERE id=".$idTarea; // Establezco la sentencia para eliminar la tarea de la tabla
            if (mysqli_query($conexion, $sentencia)) { // Ejecuto la consulta y compruebo si ha resultado satisfactoria
                while ($tarea = $resultado -> fetch_object()) {
                    // Creo una variable auxiliar por si el campo parentID es null
                    if (is_null($tarea-> parentID)) {
                        $idPadre = "null";
                    }
                    else {
                        $idPadre = $tarea-> parentID;
                    }

                    // Establezco la sentencia para insertar los datos de la tarea en la tabla de tareas finalizadas
                    $sentencia = "INSERT INTO ".TABLA_TAREAS_FINALIZADAS." (nombre, descripcion, fecha_creacion, fecha_modificacion, proyecto, parentID) VALUES ('".
                    $tarea-> nombre.
                    "', '".$tarea-> descripcion.
                    "', NOW()".
                    ", NOW()".
                    ", ".$tarea-> proyecto.
                    ", ".$idPadre.");";

                    // Compruebo el resultado de la ejecución de la sentencia y devuelvo un booleano según corresponda                    
                    return comprobarResultadoDeQuery($conexion, $sentencia, "Se ha producido un error al intentar insertar la tarea ".$tarea-> nombre." a la tabla de tareas finalizadas. ".$conexion-> connect_error);
                }
            }
            else{
                // Devuelvo el resultado de las acciones de error
                return accionesDeError($conexion, "Se ha producido un error al intentar eliminar la tarea con ID ".$idTarea." de su tabla. ".$conexion-> connect_error);
            }
        }
        else{
            // Devuelvo el resultado de las acciones de error
            return accionesDeError($conexion, "Se ha producido un error al intentar finalizar las subtareas de la tarea. ".$conexion-> connect_error);
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
    function finalizarSubtareas($conexion, $idTareaPadre){ // TODO : Actualizar el parentID de las subtareas
        // TODO : Eliminar la tabla de tareas finalizadas, añadir el estado de la tarea
        // No hace falta que desactive el autocommit porque ya lo está de antes

        $sentencia = "SELECT * FROM ".TABLA_TAREAS." WHERE parentID=".$idTareaPadre; // Consulto todas las subtareas activas
        $resultado = mysqli_query($conexion, $sentencia); // Guardo el resultado de la query

        $error = true; // Creo una variable para saber si ha habido algún error dentro del bucle

        while ($subtarea = $resultado -> fetch_object()) { // Recorro todos los proyectos en su tabla correspondiente
            $sentencia = "DELETE FROM ".TABLA_TAREAS." WHERE id=".$subtarea-> id; // Armo la sentencia para eliminar la subtarea actual
            if (mysqli_query($conexion, $sentencia)) { // Ejecuto la sentencia y compruebo que haya salido bien
                $conexion->commit();
                // Armo la sentencia para introducir la subtarea finalizada en la otra tabla
                $sentencia = "INSERT INTO ".TABLA_TAREAS_FINALIZADAS." (nombre, descripcion, fecha_creacion, fecha_modificacion, proyecto, parentID) VALUES ('".
                $subtarea->nombre.
                "', '".$subtarea-> descripcion.
                "', NOW()".
                ", NOW()".
                ", ".$subtarea-> proyecto.
                ", ".$subtarea-> parentID.");";

                if (mysqli_query($conexion, $sentencia)) {
                    $conexion->commit();
                }
                else{
                    $error = false;
                    $conexion->rollback(); // Al salir algo mal, primero hago el rollback

                    // En caso incorrecto, muestro un mensaje de error en la consola
                    consoleLog("Se ha producido un error al intentar introducir la subtarea ".$subtarea-> nombre.
                    " en la tabla de tareas finalizadas. ".$conexion-> connect_error);
                }
            }
            else{
                $error = false;
                $conexion->rollback(); // Al salir algo mal, primero hago el rollback

                // En caso incorrecto, muestro un mensaje de error en la consola
                consoleLog("Se ha producido un error al intentar finalizar la subtarea".$subtarea-> nombre.". ".$conexion-> connect_error);
            }
        }

        if (!$error) { // Si ha habido algún error en el bucle
            // Muestro un mensaje de error
            consoleLog("Se ha producido un error al intentar finalizar las subtareas de la tarea padre con ID ".$idTareaPadre.". ".$conexion-> connect_error);
        }

        return $error; // Devuelvo el valor del error como resultado de la función
    }

    // TODO : Hacer función que se dedique a actualizar el parentID de las subtareas de una tarea que se haya cambiado de estado

    /**
     * Actualiza los datos de una tarea finalizada en la base de datos
     * 
     * @param $conexion La conexión con la base de datos
     * @param $id ID de la tarea finalizada que queremos actualizar
     * @param $nombre El nombre de la tarea finalizada
     * @param $descripcion La descripción de la tarea finalizada
     * @param $parentID La ID de su tarea padre
     * 
     * @return Boolean Indicando el resultado de la ejecución de la función
     */
    function actualizarTareaFinalizada($conexion, $id, $nombre, $descripcion, $parentID){
        // TODO : Eliminar la tabla de tareas finalizadas, añadir el estado de la tarea
        $conexion->autocommit(FALSE); // Desactivo el autocommit
        
        // Compruebo si alguno de estos dos datos es null para autocompletarlo con el existente en la BBDD
        if ($nombre == null) {
            $nombre = conseguirDatoTareaFinalizada($conexion, $id, 0);
        }
        
        if($descripcion == null){
            $descripcion = conseguirDatoTareaFinalizada($conexion, $id, 1);
        }

        if($parentID == null){
            $parentID = conseguirDatoTareaFinalizada($conexion, $id, 2);
        }

        // Armo la sentencia
        $sentencia = "UPDATE ".TABLA_TAREAS_FINALIZADAS." SET nombre = '".$nombre."', descripcion = '".$descripcion."', fecha_modificacion = NOW(), parentID = ".$parentID." WHERE id = ".$id;
        
        // Compruebo el resultado de la ejecución de la sentencia y devuelvo un booleano según corresponda
        return comprobarResultadoDeQuery($conexion, $sentencia, "Se ha producido un error al intentar actualizar la tarea finalizada con ID ".$id.". ".$conexion-> connect_errno);
    }
    
    
    /**
     * Según la ID de la tarea finalizada y el código del dato, consigo y devuelvo el indicado de la BBDD
     * 
     * @param $conexion La conexión con la base de datos
     * @param $id La ID de la tarea finalizada sobre el que buscaremos el dato
     * @param $dato Código numérico que indicará qué dato tenemos que obtener
     * 
     * @return Dato El dato que necesitamos conseguir
     */
    function conseguirDatoTareaFinalizada($conexion, $id, $codigoDato){ // TODO : Eliminar esta función porque sólo hay una tabla
        $sentencia = "SELECT * FROM ".TABLA_TAREAS_FINALIZADAS." WHERE id = ".$id;
        $resultado = mysqli_query($conexion, $sentencia); // Guardo el resultado de la ejecución de la sentencia para recorrerse
        // Recorro el resultado de la consulta y compruebo si la ID coincide
        while ($tarea = $resultado -> fetch_object()) {
            if ($tarea-> id == $id) {
                switch ($codigoDato) { // Según el código de dato, devuelvo el dato correspondiente
                    case 0:
                        return $tarea-> nombre; 
                        break;
                        
                    case 1:
                        return $tarea-> descripcion;
                        break;
                        
                    case 2:
                        if (is_null($tarea-> parentID)) {
                            return "null";
                        }
                        else{
                            return $tarea-> parentID;
                        }
                        break;
                }
            }
        }
    }


    /**
     * Devuelve una tarea finalizada a pendiente
     * 
     * @param $conexion La conexión con la base de datos
     * @param $id La ID de la tarea finalizada que se pondrá como pendiente
     * 
     * @return Boolean Indicando el resultado de la función
     */
    function ponerTareaEnPendiente($conexion, $id){ 
        $conexion->autocommit(FALSE); // Desactivo el autocommit

        // Armo la sentencia para recoger los datos de la tarea finalizada que quiero buscar
        $sentencia = "SELECT * FROM ".TABLA_TAREAS_FINALIZADAS." WHERE id = ".$id.";";
        $tareaFinalizada = mysqli_query($conexion, $sentencia)-> fetch_object();

        if (is_null($tareaFinalizada-> parentID)) { // Compruebo si es una tarea padre
            $sentencia = "DELETE FROM ".TABLA_TAREAS_FINALIZADAS." WHERE id = ".$tareaFinalizada-> id.";"; // Armo la sentencia de eliminación
            if (mysqli_query($conexion, $sentencia)) { // Compruebo que la eliminación de la tarea de la tabla haya salido bien
                $sentencia = "INSERT INTO ".TABLA_TAREAS." (nombre, descripcion, fecha_creacion, fecha_modificacion, proyecto, parentID) VALUES ('".
                $tareaFinalizada-> nombre."', '".
                $tareaFinalizada-> descripcion."', NOW(), NOW(), ". // Uso las funciones NOW() de SQL para representar las fechas
                $tareaFinalizada-> proyecto.", null);"; // Pongo null como string para que SQL lo interprete como un valor null

                if (!mysqli_query($conexion, $sentencia)) { // Si ha ocurrido un error en la sentencia de inserción, hago rollback y muestro el error
                    return accionesDeError($conexion, "Ha ocurrido un error insertando la tarea ".$tareaFinalizada-> nombre." en la tabla de tareas pendientes. ".$conexion-> connect_error);
                }
            }
            else {
                return accionesDeError($conexion, "Ha ocurrido un error eliminando la tarea ".$tareaFinalizada-> nombre." de la tabla de tareas finalizadas. ".$conexion-> connect_error);
            }

            $sentencia = "SELECT id FROM ".TABLA_TAREAS.";"; // Armo una sentencia para conseguir las ID de las tareas pendientes
            $ids = mysqli_query($conexion, $sentencia)-> fetch_array(); // Hago un array con ellas
            $nuevaParentID = max($ids); // Y consigo la ID más alta, que será la del padre que he devuelto a la tabla

            // TODO : Actualizar el parentID de las subtareas
            $sentencia = "SELECT * FROM ".TABLA_TAREAS_FINALIZADAS." WHERE parentID = ".$tareaFinalizada-> id.";"; // Armo la sentencia para conseguir sus hijos finalizados
            $resultado = mysqli_query($conexion, $sentencia); // Guardo el resultado de la consulta

            $todoBien = true; // Variable para controlar si ha ido todo bien
            while ($hijosFinalizados = $resultado->fetch_object()) { // Recorro todos sus hijos finalizados
                // Aquí actualizo los hijos con la nueva ID de su padre, que ha sido devuelto a la tabla de tareas pendientes
                $todoBien = actualizarTareaFinalizada($conexion, $hijosFinalizados-> id, null, null, $nuevaParentID); // Actualizo la variable según el resultado de la consulta
            }

            // Comprobando si ha salido todo bien
            if ($todoBien) {
                $conexion->commit(); // Realizo commit en caso afirmativo
            }
            else {
                $conexion-> rollback(); // O hago un rollback en formato positivo
            }

            return $todoBien; // Finalmente devuelvo el booleano de la variable que uso para saber si ha ido todo bien
        }
        else { // Si es una subtarea
            // Armo la sentencia para saber si su tarea padre está en pendientes
            $sentencia = "SELECT * FROM ".TABLA_TAREAS." WHERE id = ".$tareaFinalizada-> parentID;
            if (!mysqli_query($conexion, $sentencia)) { // Si su tarea padre no se encuentra en pendientes

                // Intento ponerla en pendiente
                if (ponerTareaEnPendiente($conexion, $tareaFinalizada-> parentID)) { // Si se pone bien en pendiente su tarea padre

                    // Consigo la info actualizada de la subtarea
                    $tareaFinalizada = mysqli_query($conexion, "SELECT * FROM ".TABLA_TAREAS_FINALIZADAS." WHERE id = ".$id.";")-> fetch_object();

                    $sentencia = "DELETE FROM ".TABLA_TAREAS_FINALIZADAS." WHERE id = ".$tareaFinalizada-> id.";"; // Armo la sentencia de eliminación
                    if (mysqli_query($conexion, $sentencia)) { // Compruebo si ha salido bien la eliminación
                        $sentencia = "INSERT INTO ".TABLA_TAREAS." (nombre, descripcion, fecha_creacion, fecha_modificacion, proyecto, parentID) VALUES ('".
                        $tareaFinalizada-> nombre."', '".
                        $tareaFinalizada-> descripcion."', NOW(), NOW(), ". // Uso las funciones NOW() de SQL para representar las fechas
                        $tareaFinalizada-> proyecto.",".
                        $tareaFinalizada-> parentID.");";
                        
                        // Finalmente devuelvo un booleano resultante de la ejecución de la consulta para introducir la subtarea en pendiente
                        return comprobarResultadoDeQuery($conexion, $sentencia, "Ha ocurrido un error al poner en pendiente la subtarea ".$tareaFinalizada-> nombre.". ".$conexion-> connect_error);
                    }
                    else{ // Si ha fallado algo en la eliminación, muestro un mensaje de error
                        return accionesDeError($sentencia, "Ha ocurrido un error eliminando la subtarea ".$tareaFinalizada-> nombre." de la tabla ".TABLA_TAREAS_FINALIZADAS.". ".$conexion-> connect_error);
                    }
                }
                else { // Si ocurre algún error al intentar poner la tarea padre en pendiente
                    return false; // Devuelvo simplemente un false, ya que la conexión estará cerrada
                }
            }
        }
    }
?>