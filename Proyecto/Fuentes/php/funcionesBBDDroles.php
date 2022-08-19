<?php
    require_once "Utils.php"; // Linkeo el archivo de Utils a este, para usar sus funciones
    require_once "Constantes.php"; // Linkeo el archivo de las constantes a este, para utilizarlas en las funciones de la BBDD
    

    //------------------------------------------------------------- CREATE -----------------------------------------------

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
        $sentencia = "INSERT INTO ".TABLA_ROLES." (nombre, privilegios) VALUES('".$nombre."', ".$privilegios.")";

        // Compruebo el resultado de la ejecución de la sentencia y devuelvo un booleano según corresponda
        return comprobarResultadoDeQuery($conexion, $sentencia);
    }




    //------------------------------------------------------------- READ -----------------------------------------------
    
    /**
     * Consigo todos los roles de la base de datos y devuelvo la info
     * 
     * @param $conexion La conexión con la base de datos
     * 
     * @return Array asociativo referente a los roles que están en la base de datos
     */
    function leerRoles($conexion){
        $sentencia = "SELECT * FROM ".TABLA_ROLES.";";
        $resultado = mysqli_query($conexion, $sentencia); // Guardo el resultado de la ejecución de la sentencia para recorrerse
    
        if(mysqli_num_rows($resultado) > 0){
            $roles = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
            return json_encode($roles);
        }
    }


    /**
     * Consigo la información de un rol en la base de datos según su ID
     * 
     * @param $conexion La conexión con la base de datos
     * @param $id La ID del rol a buscar
     * 
     * @return Array asociativo referente al rol que queremos buscar
     */
    function leerProyecto($conexion, $id){
        $sentencia = "SELECT * FROM ".TABLA_ROLES." WHERE id=".$id.";";
        $resultado = mysqli_query($conexion, $sentencia); // Guardo el resultado de la ejecución de la sentencia para recorrerse

        if(mysqli_num_rows($resultado) > 0){
            $rol = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
            return json_encode($rol);
        }
    }




    //------------------------------------------------------------- UPDATE -----------------------------------------------

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
        // Armo la sentencia
        $sentencia = "UPDATE ".TABLA_ROLES." SET nombre = '".$nombre."', privilegios = ".$privilegios." WHERE id = ".$id;
        // Compruebo el resultado de la ejecución de la sentencia y devuelvo un booleano según corresponda
        return comprobarResultadoDeQuery($conexion, $sentencia);
    }




    //------------------------------------------------------------- DELETE -----------------------------------------------

    /**
     * Elimina un rol de la BBDD
     * 
     * @param $conexion La conexión con la BBDD
     * @param $id La ID del rol que queremos eliminar
     * 
     * @return Boolean Indicando el resultado de la ejecución de la función
     */
    function eliminarRol($conexion, $id){
        
        // Armo la sentencia
        $sentencia = "DELETE FROM ".TABLA_ROLES." WHERE id = ".$id;
        
        // Compruebo el resultado de la ejecución de la sentencia y devuelvo un booleano según corresponda
        return comprobarResultadoDeQuery($conexion, $sentencia);
    }
?>