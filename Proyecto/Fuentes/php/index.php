<?php
    require_once "funcionesBBDDusuarios.php";
    require_once "funcionesBBDDroles.php";
    require_once "funcionesBBDDproyectos.php";
    require_once "funcionesBBDDtareas.php";

    include "cors.php"; // IMPORTANTE incluir el CORS

    $conexionBBDD = conectarBBDD(); // Consigo la conexión a la BBDD
    $conexionBBDD->autocommit(FALSE); // Desactivo el autocommit


    
    // CREATE :
    if (isset($_GET["registrarUsuario"])) {
        $method = $_SERVER["REQUEST_METHOD"]; // Consigo el método de petición del servidor
        switch ($method) { // Compruebo que el método sea POST
            case 'POST':
                $data = json_decode(file_get_contents("php://input")); // Consigo los datos que el usuario ha escrito
                // Los guardo en sus variables correspondientes
                $email = $data->txtEmail;
                $nickname = $data->txtNickname;
                $password = $data->txtPassword;
                $rol = $data->rol;
                
                if(registrarUsuario($conexionBBDD, $email, $nickname, $password, $rol)){ // Compruebo que el usuario se haya registrado correctamente
                    echo json_encode(["success"=>1, "message"=>"Usuario registrado correctamente"]);
                }
                else {echo json_encode(["success"=>0, "message"=>"Error al registrar el usuario"]);}

                break;
        }
            
        exit();
    }


    // UPDATE :
    if (isset($_GET["actualizarUsuario"])){
        $method = $_SERVER["REQUEST_METHOD"]; // Consigo el método de petición del servidor
        switch ($method) { // Compruebo que el método sea POST
            case 'POST':
                $data = json_decode(file_get_contents("php://input")); // Consigo los datos que el usuario ha escrito
                // Los guardo en sus variables correspondientes
                $id = $data->id;
                $email = $data->txtEmail;
                $nickname = $data->txtNickname;
                $imagen = "a";
                $rol = $data->rol;

                $pwdCambiada = $data->flag; // Consigo la variable
                $password = ""; // Inicializo la variable referente a la PWD
                $pwdCambiada ? $password = md5($data->txtPassword) : $password = $data->txtPassword; // Compruebo la flag y establezco la PWD
                
                if(actualizarUsuario($conexionBBDD, $id, $email, $nickname, $password, $rol)){
                    echo json_encode(["success"=>1, "message"=>"Usuario ".$nickname." actualizado correctamente"]);
                }
                else {echo json_encode(["success"=>0, "message"=>"Error al actualizar el usuario".$nickname]);}
        
                break;
        }
            
        exit();
    }


    // DELETE : $_GET["eliminarUsuario"] es la ID del usuario
    if (isset($_GET["eliminarUsuario"])){
        if(eliminarUsuario($conexionBBDD, $_GET["eliminarUsuario"])){
            echo json_encode(["success"=>1, "message"=>"Usuario eliminado correctamente"]);
            exit();
        }
        else{echo json_encode(["success"=>0, "message"=>"Error al eliminar el usuario"]);}
    }


    // READ :
    if (isset($_GET["listaUsuarios"])) {
        echo leerUsuarios($conexionBBDD);
    }

    // $_GET["conseguirUsuario"] es la ID del usuario
    if (isset($_GET["conseguirUsuario"])) {
        echo leerUsuario($conexionBBDD);
    }
?>