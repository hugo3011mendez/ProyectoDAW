<?php
    // Incluyo el archivo de funciones referente a tareas, necesario para que esto funcione
    require_once "funcionesBBDDtareas.php";

    include "cors.php"; // IMPORTANTE incluir el CORS

    $conexionBBDD = conectarBBDD(); // Consigo la conexión a la BBDD
    $conexionBBDD->autocommit(FALSE); // Desactivo el autocommit

   
    // CREATE :
    if (isset($_GET["crearTarea"])) {
        $method = $_SERVER["REQUEST_METHOD"]; // Consigo el método de petición del servidor
        switch ($method) { // Compruebo que el método sea POST
            case 'POST':
                $data = json_decode(file_get_contents("php://input")); // Consigo los datos necesarios para crear la tarea
                // Los guardo en sus variables correspondientes
                $nombre = $data->txtNombre;
                $descripcion = $data->txtDescripcion;
                $proyecto = $data->proyecto;
                $parentID = $data->parentID;
                $estado = $data->estado;
                
                if(crearTarea($conexionBBDD, $nombre, $descripcion, $proyecto, $parentID, $estado)){ // Compruebo que la tarea se haya registrado correctamente
                    echo json_encode(["success"=>1, "message"=>"Tarea creada correctamente"]);
                }
                else {echo json_encode(["success"=>0, "message"=>"Error al crear la tarea"]);}

                break;
        }
            
        exit();
    }


    // READ : $_GET["listaTareasDeProyecto"] es la ID del proyecto en el que están las tareas que se quieren listar
    if (isset($_GET["listaTareasDeProyecto"])) { // TODO : Ver si pasarlo por GET o hacerlo por POST para mejor seguridad y que no puedan ver otros usuarios
        echo leerTareasDeProyecto($conexionBBDD, $_GET["listaTareasDeProyecto"]);
    }

    // $_GET["listaSubtareas"] es la ID de la tarea padre sobre la que queremos ver las subtareas
    if (isset($_GET["listaSubtareas"])) {
        echo leerSubtareasDeTarea($conexion, $_GET["listaSubtareas"]);
    }
    
    // $_GET["conseguirTarea"] es la ID de la tarea
    if (isset($_GET["conseguirTarea"])) {
        echo leerTarea($conexionBBDD, $_GET["conseguirTarea"]);
    }

    // $_GET["listaTareasFinalizadas"] es la ID del proyecto en el que quiero buscar
    if (isset($_GET["listaTareasFinalizadas"])) { // TODO : Ver si pasarlo por GET o hacerlo por POST para mejor seguridad y que no puedan ver otros usuarios
        echo leerTareasFinalizadasDeProyecto($conexionBBDD, $_GET["conseguirTarea"]);
    }


    // UPDATE :
    if (isset($_GET["actualizarTarea"])){
        $method = $_SERVER["REQUEST_METHOD"]; // Consigo el método de petición del servidor
        switch ($method) { // Compruebo que el método sea POST
            case 'POST':
                $data = json_decode(file_get_contents("php://input")); // Consigo los datos que el usuario ha escrito
                // Los guardo en sus variables correspondientes
                $id = $data->id;
                $nombre = $data->txtNombre;
                $descripcion = $data->txtDescripcion;
                $parentID = $data->parentID;
                $estado = $data->estado;
                
                if(actualizarTarea($conexionBBDD, $id, $nombre, $descripcion, $parentID, $estado)){
                    echo json_encode(["success"=>1, "message"=>"Tarea ".$nombre." actualizada correctamente"]);
                }
                else {echo json_encode(["success"=>0, "message"=>"Error al actualizar la tarea".$nombre]);}
        
                break;
        }
            
        exit();
    }


    // DELETE : $_GET["eliminarTarea"] es la ID de la tarea
    if (isset($_GET["eliminarTarea"])){
        if(eliminarProyecto($conexionBBDD, $_GET["eliminarTarea"])){
            echo json_encode(["success"=>1, "message"=>"Tarea eliminada correctamente"]);
            exit();
        }
        else{echo json_encode(["success"=>0, "message"=>"Error al eliminar la tarea"]);}
    }
?>