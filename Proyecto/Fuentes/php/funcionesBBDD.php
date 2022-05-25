<?php // En este archivo almacenaré las funciones que usaré para gestionar la base de datos
    require_once "Utils.php"; // Linkeo el archivo de Utils a este, para usar sus funciones

    const NOMBRE_BBDD = "todomanager"; // Constante indicando el nombre de la BBDD, para evitar números y texto mágico
    // Constantes referentes a los nombres de las tablas de la BBDD
    const TABLA_USUARIOS = "usuarios";
    const TABLA_PRIVILEGIOS = "privilegios";
    const TABLA_ROLES = "roles";
    const TABLA_PROYECTOS = "proyectos";
    const TABLA_TAREAS = "tareas";
    const TABLA_TAREAS_FINALIZADAS = "tareas_finalizadas";
    

    /**
     * Intenta conectarse a la base de datos especificada y guarda en una variable el tipo de error en cada caso.
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


    // Funciones referentes a los usuarios :
    /**
     * Inserta los datos de un nuevo Usuario en la BBDD
     * 
     * @param $conexion Conexión a la BBDD
     * @param $email Email del nuevo usuario
     * @param $nickname Nickname del nuevo usuario
     * @param $password Contraseña del nuevo usuario
     * @param $imagen base64 de la imagen del nuevo usuario
     * @param $rol Rol que va a tener el nuevo usuario
     */
    function registrarUsuario($conexion, $email, $nickname, $password, $imagen, $rol){ // TODO : El rol seguramente vaya a mano
        $sentencia = "INSERT INTO ".TABLA_USUARIOS." (email, nickname, pwd, imagen, rol) VALUES(".$email.", ".$nickname.", ".$password.", ".$imagen.", ".$rol.")"; // Armo la sentencia
        if (!mysqli_query($conexion, $sentencia)) { // Compruebo que al ejecutar la consulta haya salido algo mal
            consoleLog("Se ha producido un error al registrar al usuario : ".$conexion-> connect_error); // Y en ese caso muestro un mensaje de error en la consola
            $conexion-> close(); // Finalmente cierro la conexión a la base de datos
            
            return false; // Devuelvo un false indicando que ha habido algún error
        }
        else {
            return true; // Devuelvo un true indicando que todo ha ido bien
        }
    }


    /**
     * Elimina el usuario cuya ID coincida con la pasada como parámetro
     * 
     * @param $conexion La conexión a la base de datos
     * @param $id La ID del usuario a eliminar
     * 
     * @return Una booleana indicando si se ha realizado satisfactoriamente
     */
    function eliminarUsuario($conexion, $id){
        $sentencia = "DELETE FROM ".TABLA_USUARIOS." WHERE id = ".$id; // Armo la sentencia
        if (mysqli_query($conexion, $sentencia)) { // Compruebo si la consulta se ha ejecutado correctamente
            // Llamo a la función para que elimine todos los proyectos del usuario que acabo de eliminar
            return eliminarProyectosDeUsuario($conexion, $id); // Y devuelvo una booleana según su resultado
        }
        else {
            consoleLog("Se ha producido un error al eliminar al usuario con ID ".$id." : ".$conexion-> connect_error); // En caso incorrecto, muestro un mensaje de error en la consola
            $conexion-> close(); // Y cierro la conexión a la base de datos

            return false; // Devuelvo un false indicando que ha ocurrido un error
        }
    }


    /**
     * Elimina de la base de datos todos los proyectos y las tareas a cargo de un usuario
     * @param $conexion La conexión con la base de datos
     * @param $idUsuario La ID del usuario sobre el que vamos a eliminar los proyectos
     */
    function eliminarProyectosDeUsuario($conexion, $idUsuario){
        $sentencia = "SELECT * FROM ".TABLA_PROYECTOS." WHERE usuario_creador = ".$idUsuario; // Consigo todos los proyectos de la base de datos
        $resultado = mysqli_query($conexionBBDD, $sentencia); // Guardo su resultado

        while ($proyecto = $resultado -> fetch_object()) { // Recorro todos los proyectos en su tabla correspondiente
            if ($proyecto -> usuario_creador == $idUsuario) { // En el caso de que el proyecto actual pertenece al usuario
                // Ejecuto la función que elimina un proyecto y todas sus tareas de la base de datos
                return eliminarProyecto($conexion, $proyecto); // Y devuelvo un booleano según su resultado
            }
        }
    }


    /**
     * Elimina de la base de datos todas las tareas de un proyecto
     * @param $conexion La conexión con la base de datos
     * @param $proyecto El proyecto sobre el que se van a eliminar todas las tareas
     */
    function eliminarProyecto($conexion, $proyecto){
        eliminarTodasTareas($conexion, $proyecto);

        // Si todo sale bien, armo la consulta para eliminar el proyecto
        $sentencia = "DELETE FROM ".TABLA_PROYECTOS." WHERE id =".$proyecto-> id;
        if (!mysqli_query($onexion, $sentencia)) {
            // En caso incorrecto, muestro un mensaje de error en la consola
            consoleLog("Se ha producido un error al intentar eliminar el proyecto ".$proyecto-> nombre." : ".$conexion-> connect_error);
            $conexion-> close(); // Y cierro la conexión a la base de datos                
        }        
    }


    /**
     * 
     */
    function eliminarTodasTareas($conexion, $proyecto){
        // Armo la consulta para eliminar primero las tareas finalizadas del proyecto
        $sentencia = "DELETE FROM ".TABLA_TAREAS_FINALIZADAS." WHERE proyecto =".$proyecto-> id;
        if (mysqli_query($conexion, $sentencia)) { // Intento eliminar las tareas finalizadas
        
            // Armo la consulta para eliminar ahora las tareas del proyecto
            $sentencia = "DELETE FROM ".TABLA_TAREAS." WHERE proyecto =".$proyecto-> id;
            if (!mysqli_query($conexion, $sentencia)) { // Intento eliminar las tareas
                return true;
            }
            else {
                // En caso incorrecto, muestro un mensaje de error en la consola
                consoleLog("Se ha producido un error al intentar eliminar las tareas correspondientes al proyecto ".$proyecto-> nombre." : ".$conexion-> connect_error);
                $conexion-> close(); // Y cierro la conexión a la base de datos
                
                return false;
            }
        }
        else {
            // En caso incorrecto, muestro un mensaje de error en la consola
            consoleLog("Se ha producido un error al intentar eliminar las tareas finalizadas correspondientes al proyecto ".$proyecto-> nombre." : ".$conexion-> connect_error);
            $conexion-> close(); // Y cierro la conexión a la base de datos

            return false;
        }           
    }
?>