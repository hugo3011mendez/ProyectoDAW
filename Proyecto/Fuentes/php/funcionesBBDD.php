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
    function registrarUsuario($conexion, $email, $nickname, $password, $imagen, $rol){ // TODO : Encriptar la contraseña
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
    function actualizarUsuario($conexion, $id, $email, $nickname, $password, $imagen, $rol){ // TODO : Encriptar la contraseña
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
     * @param $estado Booleano indicando si es una tarea pendiente o finalizada
     * 
     * @return Boolean Indicando el resultado de la ejecución de la función
     */
    function crearTarea($conexion, $nombre, $descripcion, $proyecto, $parentID, $estado){
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
            $sentencia = "INSERT INTO ".TABLA_TAREAS." (nombre, descripcion, fecha_creacion, fecha_modificacion, proyecto, parentID, estado) VALUES ('".$nombre.
            "', '".$descripcion.
            "', NOW(), NOW(), ". // Uso la función NOW() de MYSQL para las fechas de creación y modificación
            $proyecto.
            ", ".$parentID.
            ", ".$estado.");";
            
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
     * @param $estado El estado de la tarea
     * 
     * @return Boolean Indicando el resultado de la ejecución de la función
     */
    function actualizarTarea($conexion, $id, $nombre, $descripcion, $parentID, $estado){
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

        if ($estado == null) {
            $estado = conseguirDatoTarea($conexion, $id, 3);
        }

        // Armo la sentencia
        $sentencia = "UPDATE ".TABLA_TAREAS." SET nombre = '".$nombre."', descripcion = '".$descripcion."', fecha_modificacion = NOW(), parentID = ".$parentID.", estado = ".$estado." WHERE id = ".$id;
        
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
    function conseguirDatoTarea($conexion, $id, $codigoDato){
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

                    case 3:
                        return $tarea-> estado;
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
    function eliminarSubtareas($conexion, $tarea){
        $conexion->autocommit(FALSE); // Desactivo el autocommit
        
        if (is_object($tarea)) { // Si la variable es un objeto de tipo tarea
            // Intento eliminar las subtareas
            $sentencia = "DELETE FROM ".TABLA_TAREAS." WHERE parentID=".$tarea-> id.";"; // Armo la sentencia para conseguir todas las subtareas
            return comprobarResultadoDeQuery($conexion, $sentencia, "Se ha producido un error al intentar eliminar las subtareas de la tarea ".$tarea-> nombre.". ".$conexion-> connect_error);
        }
        elseif (is_int($tarea)) { // Si la variable es el ID de la tarea
            // Intento eliminar las subtareas
            $sentencia = "DELETE FROM ".TABLA_TAREAS." WHERE parentID=".$tarea.";";
            return comprobarResultadoDeQuery($conexion, $sentencia, "Se ha producido un error al intentar eliminar las subtareas de la tarea. ".$conexion-> connect_error);
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
    function finalizarTarea($conexion, $idTarea){ // TODO : Actualizar fecha de modificación
        $conexion->autocommit(FALSE); // Desactivo el autocommit

        // Primero, intento finalizar sus subtareas
        if (finalizarSubtareas($conexion, $idTarea)) { // Compruebo el resultado de la función que intenta finalizar las subtareas de la tarea
            // Armo la sentencia para actualizar el estado de la tarea que quiero finalizar
            $sentencia = "UPDATE ".TABLA_TAREAS." SET estado =".ESTADO_FINALIZADO." WHERE id=".$idTarea.";";

            // Devuelvo un booleano según el resultado de la ejecución de la query
            return comprobarResultadoDeQuery($conexion, $sentencia, "Se ha producido un error al intentar finalizar la tarea con ID ".$idTarea.". ".$conexion-> connect_error);
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
    function finalizarSubtareas($conexion, $idTareaPadre){ // TODO : Actualizar fecha de modificación
        // No hace falta que desactive el autocommit porque ya lo está de antes

        // Armo la sentencia para actualizar las subtareas y finalizarlas
        $sentencia = "UPDATE ".TABLA_TAREAS." SET estado=".ESTADO_FINALIZADO." WHERE parentID=".$idTareaPadre.";";
        return comprobarResultadoDeQuery($conexion, $sentencia, "Ha ocurrido un error al finalizar las subtareas de la tarea con ID ".$idTareaPadre.". ".$conexion-> connect_error);
    }


    /**
     * Devuelve una tarea finalizada a pendiente
     * 
     * @param $conexion La conexión con la base de datos
     * @param $id La ID de la tarea finalizada que se pondrá como pendiente
     * 
     * @return Boolean Indicando el resultado de la función
     */
    function ponerEnPendiente($conexion, $id){ // TODO : Actualizar fechas de modificación
        $conexion->autocommit(FALSE); // Desactivo el autocommit

        // Primero consigo la ID de su tarea padre si la tiene
        $sentencia = "SELECT parentID FROM ".TABLA_TAREAS." WHERE id=".$id.";";
        $parentID = mysqli_query($conexion, $sentencia); // TODO : Comprobar si pilla bien el dato
        if ($parentID != null) { // Compruebo si tiene tarea padre
            // Pongo en pendiente su tarea padre
            $sentencia = "UPDATE ".TABLA_TAREAS." SET estado=".ESTADO_PENDIENTE." WHERE id=".$parentID.";";

            if (mysqli_query($conexion, $sentencia)) { // Ejecuto la sentencia y compruebo si ha salido bien
                $sentencia = "UPDATE ".TABLA_TAREAS." SET estado=".ESTADO_PENDIENTE." WHERE id=".$id.";"; // Armo la sentencia para poner la tarea en pendiente
                // Finalmente compruebo el resultado de la query y lo devuelvo
                return comprobarResultadoDeQuery($conexion, $sentencia, "Ha ocurrido un error al poner en pendiente la subtarea con ID ".$id.". ".$conexion-> connect_error);
            }
            else {
                return accionesDeError($conexion, "Ha ocurrido un error poniendo en pendiente la tarea padre de la subtarea que se quiere poner en pendiente. ".$conexion-> connect_error);
            }
        }
        else { // Si no tiene tarea padre
            $sentencia = "UPDATE ".TABLA_TAREAS." SET estado=".ESTADO_PENDIENTE." WHERE id=".$id.";"; // Armo la sentencia para poner la tarea en pendiente 
            // Compruebo el resultado de la query y lo devuelvo
            return comprobarResultadoDeQuery($conexion, $sentencia, "Ha ocurrido un error al poner en pendiente la subtarea con ID ".$id.". ".$conexion-> connect_error);
        }
    }
?>