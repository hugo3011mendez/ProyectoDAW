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
            return true; // Devuelvo verdadero
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
        $sentencia = "SELECT * FROM ".TABLA_USUARIOS." WHERE pwd='".$password."';"; // Armo la sentencia
        $resultado = mysqli_query($conexion, $sentencia); // Y guardo el resultado de su ejecución

        if ($resultado-> num_rows > 0) { // Compruebo que haya encontrado un usuario que coincida
            return true; // Devuelvo verdadero
        }
        else {
            return accionesDeError($conexion, "No se encuentra la contraseña introducida en la base de datos."); // Devuelvo falso
        }
    }


    /**
     * Inicia la sesión del usuario que haya introucido bien sus credenciales
     * 
     * @param $conexion La conexión con la base de datos
     * @param $email El email del usuario que a iniciar sesión
     */
    function login($conexion, $email){
        session_start(); // Inicio la sesión
        $_SESSION["email"] = $email; // Guardo el email, dato único, en la sesión para poder identificarla

        // Armo la sentencia para conseguir los datos del usuario
        $sentencia = "SELECT * FROM ".TABLA_USUARIOS." WHERE email = ".$email.";";
        $resultado = mysqli_query($conexion, $sentencia); // Guardo su resultado
        
        while ($usuario = $resultado -> fetch_object()) { // Consigo el resultado en formato objeto
            // Guardo los datos importantes del usuario en la sesión
            $_SESSION["id"] = $usuario-> id;
            $_SESSION["nickname"] = $usuario-> nickname;
            $_SESSION["rol"] = $usuario-> rol;
        }        
    }
?>