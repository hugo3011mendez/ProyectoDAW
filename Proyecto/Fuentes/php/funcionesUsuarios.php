<?php
    // Linkeo el archivo referente a las funciones contra la BBDD, también incluye el link a los archivos de Constantes y Utils
    require_once "funcionesBBDD.php";
    
    
    /**
     * Comprueba que el email introducido coincida con el de un usuario guardado en la base de datos
     * 
     * @param $conexion La conexión con la base de datos
     * @param $email El email introducido para comprobar
     * 
     * @return Boolean Dependiendo si la consulta encuentra coincidencias
     */
    function comprobarEmail($conexion, $email){
        $sentencia = "SELECT * FROM ".TABLA_USUARIOS." WHERE email='".$email."';"; // Armo la sentencia
        $resultado = mysqli_query($conexion, $sentencia); // Y guardo el resultado de su ejecución

        if ($resultado-> num_rows > 0) { // Compruebo que haya encontrado un usuario que coincida
            return true;
        }
        else {
            return accionesDeError($conexion, "No se encuentra el email introducido en la base de datos.");
        }
    }


    /**
     * Comprueba que la contraseña introducida coincida con la de un usuario guardado en la base de datos
     * 
     * @param $conexion La conexión con la base de datos
     * @param $password La contraseña introducida para comprobar
     * 
     * @return Boolean Dependiendo si la consulta encuentra coincidencias
     */
    function comprobarPassword($conexion, $password){
        $password = md5($password); // Primero encripto la contraseña introducida
        var_dump($password);
        $sentencia = "SELECT * FROM ".TABLA_USUARIOS." WHERE pwd='".$password."';"; // Armo la sentencia
        $resultado = mysqli_query($conexion, $sentencia); // Y guardo el resultado de su ejecución

        if ($resultado-> num_rows > 0) { // Compruebo que haya encontrado un usuario que coincida
            return true;
        }
        else {
            return accionesDeError($conexion, "No se encuentra la contraseña introducida en la base de datos.");
        }
    }
?>