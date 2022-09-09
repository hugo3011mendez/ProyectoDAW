<?php // En este archivo pondré las funciones que serán útiles en todos los archivos

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
     * 
     * @return Boolean Indicando el resultado de la función
     */
    function comprobarResultadoDeQuery(&$conexion, $sentencia){
        if (mysqli_query($conexion, $sentencia)) { // Intento eliminar las subtareas activas
            $conexion->commit(); // Realizo el commit si ha salido bien

            return true;
        }
        else {
            return accionesDeError($conexion); // Devuelvo el resultado de las acciones de error
        }
    }


    /**
     * Acciones a realizar cuando ocurre algún error en una consulta contra la base de datos
     * 
     * @param $conexion La conexión con la base de datos. Pasada por referencia para que los cambios hagan efecto en la conexión.
     * 
     * @return Boolean indicando que la operación tuvo errores, siempre es false
     */
    function accionesDeError(&$conexion){
        $conexion->rollback(); // Al salir algo mal, primero hago el rollback
        $conexion-> close(); // Finalmente cierro la conexión a la base de datos
        
        return false; // Devuelvo un false indicando que ha habido algún error
    }
?>