<?php
    // Incluyo el archivo de funciones referente a roles, necesario para que esto funcione
    require_once "funcionesBBDDroles.php";

    include "cors.php"; // IMPORTANTE incluir el CORS

    $conexionBBDD = conectarBBDD(); // Consigo la conexión a la BBDD
    $conexionBBDD->autocommit(FALSE); // Desactivo el autocommit


    
    // CREATE :
    if (isset($_GET["crearRol"])) {
        $method = $_SERVER["REQUEST_METHOD"]; // Consigo el método de petición del servidor
        switch ($method) { // Compruebo que el método sea POST
            case 'POST':
                $data = json_decode(file_get_contents("php://input")); // Consigo los datos necesarios para crear el rol
                // Los guardo en sus variables correspondientes
                $nombre = $data->txtNombre;
                $privilegios = $data->privilegios;
                
                if(crearRol($conexionBBDD, $nombre, $privilegios)){ // Compruebo que el rol se haya registrado correctamente
                    echo json_encode(["success"=>1, "message"=>"Rol creado correctamente"]);
                }
                else {echo json_encode(["success"=>0, "message"=>"Error al crear el rol"]);}

                break;
        }
            
        exit();
    }


    // READ :
    if (isset($_GET["listaRoles"])) {
        echo leerRoles($conexionBBDD);
    }
    
    // $_GET["conseguirRol"] es la ID del rol
    if (isset($_GET["conseguirRol"])) {
        echo leerRol($conexionBBDD, $_GET["conseguirRol"]);
    }    


    // UPDATE :
    if (isset($_GET["actualizarRol"])){
        $method = $_SERVER["REQUEST_METHOD"]; // Consigo el método de petición del servidor
        switch ($method) { // Compruebo que el método sea POST
            case 'POST':
                $data = json_decode(file_get_contents("php://input")); // Consigo los datos que el usuario ha escrito
                // Los guardo en sus variables correspondientes
                $nombre = $data->txtNombre;
                $id = $data->id;
                $privilegios = $data->privilegios;
                
                if(actualizarRol($conexionBBDD, $nombre, $privilegios, $id)){
                    echo json_encode(["success"=>1, "message"=>"Rol ".$nombre." actualizado correctamente"]);
                }
                else {echo json_encode(["success"=>0, "message"=>"Error al actualizar el rol".$nombre]);}
        
                break;
        }
            
        exit();
    }


    // DELETE : $_GET["eliminarRol"] es la ID del rol
    if (isset($_GET["eliminarRol"])){
        if(eliminarRol($conexionBBDD, $_GET["eliminarRol"])){
            echo json_encode(["success"=>1, "message"=>"Rol eliminado correctamente"]);
            exit();
        }
        else{echo json_encode(["success"=>0, "message"=>"Error al eliminar el rol"]);}
    }
?>