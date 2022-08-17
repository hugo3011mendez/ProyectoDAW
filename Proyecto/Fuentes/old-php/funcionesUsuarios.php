<?php
    // Linkeo el archivo referente a las funciones contra la BBDD, también incluye el link a los archivos de Constantes y Utils
    require_once "funcionesBBDD.php";
    
    
    /**
     * Comprueba que el email introducido coincida con el de un usuario guardado en la base de datos
     * 
     * @param $conexion La conexión con la base de datos
     * @param $email El email introducido para comprobar
     * 
     * @return Boolean Dependiendo del resultado de la función
     */
    function comprobarEmail($conexion, $email){
        $sentencia = "SELECT * FROM ".TABLA_USUARIOS." WHERE email='".$email."';"; // Armo la sentencia
        $resultado = mysqli_query($conexion, $sentencia); // Y guardo el resultado de su ejecución

        if ($resultado-> num_rows > 0) { // Compruebo que haya encontrado un usuario que coincida
            setcookie("email", $email); // Guardo el email correcto en una cookie para usarlo más adelante

            return true; // Devuelvo true
        }
        else { // Si no hay usuarios con los que coincida, devuelvo false y un error

            return accionesDeError($conexion, "No se encuentra el email introducido en la base de datos.");
        }
    }


    /**
     * Inicia la sesión del usuario que haya introucido bien sus credenciales
     * Compruebo si la contraseña se ha introducido correctamente y en caso afirmativo, inicio la sesión
     * 
     * @param $conexion La conexión con la base de datos
     * @param $password La contraseña del usuario para iniciar sesión
     */
    function login($conexion, $password){     
        $password = md5($password); // Primero encripto la contraseña introducida

        // Armo la sentencia para buscar el usuario con el email y la contraseña introducidos
        $sentencia = "SELECT * FROM ".TABLA_USUARIOS." WHERE email='".$_COOKIE["email"]."' AND pwd='".$password."';";
        $resultado = mysqli_query($conexion, $sentencia); // Y guardo el resultado de su ejecución

        if ($resultado-> num_rows > 0) { // Si hay un usuario que coincida
            setcookie("email", "", time() - 10000); // Elimino la cookie
            session_start(); // Inicio la sesión
            
            while ($usuario = $resultado -> fetch_object()) { // Consigo el resultado en formato objeto
                // Guardo los datos importantes del usuario en la sesión
                $_SESSION["id"] = $usuario-> id;
                $_SESSION["email"] = $usuario-> email;
                $_SESSION["nickname"] = $usuario-> nickname;
                $_SESSION["password"] = $usuario-> pwd;
                $_SESSION["rol"] = $usuario-> rol;
            }

            return true; // Devuelvo true     
        }
        else { // Si no hay usuarios que coincidan, devuelvo falso y un mensaje de error
            return accionesDeError($conexion, "No se encuentran coincidencias de las credeniales en la base de datos.");
        }
    }
?>