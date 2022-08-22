<?php
    require_once "Utils.php"; // Linkeo el archivo de Utils a este, para usar sus funciones
    require_once "Constantes.php"; // Linkeo el archivo de las constantes a este, para utilizarlas en las funciones de la BBDD
    require_once "funcionesBBDDproyectos.php"; // Linkeo el archivo referente a las funciones sobre proyectos

    //------------------------------------------------------------- COMPROBACIONES Y LOGIN / LOGOUT -----------------------------------------------
    /**
     * Inicia la sesión del usuario que haya introucido bien sus credenciales
     * Comprueba que el email introducido coincida con el de un usuario guardado en la base de datos
     * Compruebo si la contraseña se ha introducido correctamente y en caso afirmativo, inicio la sesión
     * 
     * @param $conexion La conexión con la base de datos
     * @param $email El email introducido para comprobar
     * @param $password La contraseña del usuario para iniciar sesión
     * 
     * @return Boolean indicando si la acción resultó con errores
     */
    function login($conexion, $email, $password){
        $password = md5($password); // Encripto la contraseña introducida
        // Armo la sentencia para buscar el usuario con el email y la contraseña introducidos
        $sentencia = "SELECT * FROM ".TABLA_USUARIOS." WHERE email='".$email."' AND pwd='".$password."';";
        $resultado = mysqli_query($conexion, $sentencia); // Y guardo el resultado de su ejecución
    
        if ($resultado-> num_rows > 0) { // Si hay un usuario que coincida
            $usuario = $resultado -> fetch_object(); // Consigo los datos en forma de objeto
            return $usuario->id; // Devuelvo la ID del usuario encontrado
        }
        else { // Si no hay usuarios que coincidan, devuelvo false y un mensaje de error
            return accionesDeError($conexion);
        }
    }




    //------------------------------------------------------------- CREATE -----------------------------------------------

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
    function registrarUsuario($conexion, $email, $nickname, $password, $rol){

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
            $sentencia = "INSERT INTO ".TABLA_USUARIOS." (email, nickname, pwd, rol) VALUES('".$email."', '".$nickname."', '".md5($password)."', ".$rol.")";
            // Compruebo el resultado de la ejecución de la sentencia y devuelvo un booleano según corresponda
            return comprobarResultadoDeQuery($conexion, $sentencia);       
        }
        else {
            accionesDeError($conexion);
        }
    }




    //------------------------------------------------------------- READ -----------------------------------------------

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


    /**
     * Consigo todos los usuarios de la base de datos y devuelvo la info
     * 
     * @param $conexion La conexión con la base de datos
     * 
     * @return Array asociativo referente a los usuarios que están en la base de datos
     */
    function leerUsuarios($conexion){
        $sentencia = "SELECT * FROM ".TABLA_USUARIOS.";";
        $resultado = mysqli_query($conexion, $sentencia); // Guardo el resultado de la ejecución de la sentencia para recorrerse
    
        if(mysqli_num_rows($resultado) > 0){
            $usuarios = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
            return json_encode($usuarios);
        }
    }


    /**
     * Consigo la información de un usuarios en la base de datos según su ID
     * 
     * @param $conexion La conexión con la base de datos
     * @param $id La ID del usuario a buscar
     * 
     * @return Array asociativo referente al usuario que queremos buscar
     */
    function leerUsuario($conexion, $id){
        $sentencia = "SELECT * FROM ".TABLA_USUARIOS." WHERE id=".$id.";";
        $resultado = mysqli_query($conexion, $sentencia); // Guardo el resultado de la ejecución de la sentencia para recorrerse

        if(mysqli_num_rows($resultado) > 0){
            $usuario = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
            return json_encode($usuario);
        }
    }

    /**
     * Devuelve el nickname del usuario indicado
     * 
     * @param $conexion La conexión con la base de datos
     * @param $id La ID del usuario a buscar
     * 
     * @return String El nickname del usuario buscado
     */
    function conseguirNickname($conexion, $id){
        $sentencia = "SELECT nickname FROM ".TABLA_USUARIOS." WHERE id=".$id.";";
        $resultado = mysqli_query($conexion, $sentencia); // Guardo el resultado de la ejecución de la sentencia para recorrerse

        if(mysqli_num_rows($resultado) > 0){
            $datos = $resultado -> fetch_object();
            return $datos->nickname;
        }
    }




    //------------------------------------------------------------- UPDATE -----------------------------------------------
    
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
    function actualizarUsuario($conexion, $id, $email, $nickname, $password, $rol){
        // Armo la sentencia
        $sentencia = "UPDATE ".TABLA_USUARIOS." SET email = '".$email."', nickname = '".$nickname."', pwd = '".$password."', rol =".$rol." WHERE id = ".$id;
        
        // Compruebo el resultado de la ejecución de la sentencia y devuelvo un booleano según corresponda
        return comprobarResultadoDeQuery($conexion, $sentencia);       
    }


    //------------------------------------------------------------- DELETE -----------------------------------------------

    /**
     * Elimina el usuario cuya ID coincida con la pasada como parámetro
     * 
     * @param $conexion La conexión a la base de datos
     * @param $id La ID del usuario a eliminar
     * 
     * @return Boolean indicando si la acción resultó con errores
     */
    function eliminarUsuario($conexion, $id){
        // Primero tengo que eliminar los proyectos del usuario, debido a que su ID es clave foránea en la tabla de proyectos
        if (eliminarProyectosDeUsuario($conexion, $id)) {
            $sentencia = "DELETE FROM ".TABLA_USUARIOS." WHERE id = ".$id.";"; // Armo la sentencia
            return comprobarResultadoDeQuery($conexion, $sentencia);
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
            // Ejecuto la función que elimina un proyecto y todas sus tareas de la base de datos
            if (!eliminarProyecto($conexion, $proyecto, true)) {
                return accionesDeError($conexion);
            }
        }

        return true; // Si ha llegado hasta aquí se supone que todo ha salido bien, así que devuelvo directamente un true
    }
?>