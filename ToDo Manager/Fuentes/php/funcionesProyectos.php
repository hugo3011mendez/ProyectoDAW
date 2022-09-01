<?php
    // Incluyo los archivos de funciones referentes a proyectos y a tareas, necesarios para que esto funcione
    require_once "funcionesBBDDproyectos.php";
    require_once "funcionesBBDDtareas.php";

    include "cors.php"; // IMPORTANTE incluir el CORS

    $conexionBBDD = conectarBBDD(); // Consigo la conexión a la BBDD
    $conexionBBDD->autocommit(FALSE); // Desactivo el autocommit

    
    // CREATE :
    if (isset($_GET["crearProyecto"])) {
        $method = $_SERVER["REQUEST_METHOD"]; // Consigo el método de petición del servidor
        switch ($method) { // Compruebo que el método sea POST
            case 'POST':
                $data = json_decode(file_get_contents("php://input")); // Consigo los datos necesarios para crear el proyecto
                // Los guardo en sus variables correspondientes
                $idCreador = $data->creador;
                $nombre = $data->txtNombre;
                $descripcion = $data->txtDescripcion;
                
                if(crearProyecto($conexionBBDD, $idCreador, $nombre, $descripcion)){ // Compruebo que el proyecto se haya registrado correctamente
                    echo json_encode(["success"=>1, "message"=>"Proyecto creado correctamente"]);
                }
                else {echo json_encode(["success"=>0, "message"=>"Error al crear el proyecto"]);}

                break;
        }
            
        exit();
    }


    // READ :
    if (isset($_GET["listaProyectos"])) {
        echo leerProyectos($conexionBBDD);
    }

    // $_GET["leerProyectosDeUsuario"] es la ID del usuario creador
    if (isset($_GET["leerProyectosDeUsuario"])) {
        echo leerProyectosDeUsuario($conexionBBDD, $_GET["leerProyectosDeUsuario"]);
    }
    
    // $_GET["conseguirProyecto"] es la ID del proyecto
    if (isset($_GET["conseguirProyecto"])) {
        echo leerProyecto($conexionBBDD, $_GET["conseguirProyecto"]);
    }    


    // UPDATE :
    if (isset($_GET["actualizarProyecto"])){
        $method = $_SERVER["REQUEST_METHOD"]; // Consigo el método de petición del servidor
        switch ($method) { // Compruebo que el método sea POST
            case 'POST':
                $data = json_decode(file_get_contents("php://input")); // Consigo los datos que el usuario ha escrito
                // Los guardo en sus variables correspondientes
                $id = $data->id;
                $nombre = $data->txtNombre;
                $descripcion = $data->txtDescripcion;
                
                if(actualizarProyecto($conexionBBDD, $nombre, $descripcion, $id)){
                    echo json_encode(["success"=>1, "message"=>"Proyecto ".$nombre." actualizado correctamente"]);
                }
                else {echo json_encode(["success"=>0, "message"=>"Error al actualizar el proyecto".$nombre]);}
        
                break;
        }
            
        exit();
    }


    // DELETE : $_GET["eliminarProyecto"] es la ID del proyecto
    if (isset($_GET["eliminarProyecto"])){
        if(eliminarProyecto($conexionBBDD, $_GET["eliminarProyecto"], false)){
            echo json_encode(["success"=>1, "message"=>"Proyecto eliminado correctamente"]);
            exit();
        }
        else{echo json_encode(["success"=>0, "message"=>"Error al eliminar el proyecto"]);}
    }
?>