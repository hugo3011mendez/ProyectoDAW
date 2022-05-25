<?php
    /**
     * Escribe un mensaje en la consola del navegador, para más información a la hora de que se ejecute la app
     */
    function consoleLog($mensaje){
        echo "<script>console.log(".$mensaje.");</script>";    
    }
?>