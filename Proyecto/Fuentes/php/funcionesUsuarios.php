<?php
    // Incluyo los archivos de funciones referentes a usuarios y a proyectos, necesarios para que esto funcione
    require_once "funcionesBBDDusuarios.php";
    require_once "funcionesBBDDproyectos.php";

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
                else {echo json_encode(["success"=>0, "message"=>"El email ya se encuentra en la base de datos"]);}

                break;
        }
            
        exit();
    }


    // READ :
    if (isset($_GET["listaUsuarios"])) {
        echo leerUsuarios($conexionBBDD);
    }

    // $_GET["conseguirUsuario"] es la ID del usuario
    if (isset($_GET["conseguirUsuario"])) { // TODO : Ver si por temas de seguridad es mejor pasar la ID por POST
        echo leerUsuario($conexionBBDD);
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
    if (isset($_GET["eliminarUsuario"])){ // TODO : Ver si por temas de seguridad es mejor pasar la ID por POST
        if(eliminarUsuario($conexionBBDD, $_GET["eliminarUsuario"])){
            echo json_encode(["success"=>1, "message"=>"Usuario eliminado correctamente"]);
            exit();
        }
        else{echo json_encode(["success"=>0, "message"=>"Error al eliminar el usuario"]);}
    }


    // COMPROBACIONES Y LOGIN :
    if (isset($_GET["loginUsuario"])) {
        // TODO : Mirar lo de inicio de sesión
    }
?>