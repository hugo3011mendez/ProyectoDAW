<?php // En este archivo almacenaré las funciones que usaré para gestionar la base de datos
    const NOMBRE_BBDD = "todomanager"; // Constante indicando el nombre de la BBDD, para evitar números y texto mágico
    
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
    function registrarUsuario(){
        
    }

    function eliminarUsuario(){

    }
?>