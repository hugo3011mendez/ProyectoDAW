<?php // Archivo CORS para aceptar peticiones desde React
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: content-type");
    header("Access-Control-Allow-Methods: OPTIONS,GET,PUT,POST,DELETE");
?>