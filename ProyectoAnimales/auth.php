<?php

require_once 'clases/auth.class.php';
require_once 'clases/respuestas.class.php';

$_auth = new auth;
$_respuestas = new respuestas;

if($_SERVER['REQUEST_METHOD']=="POST"){
    //recibir datos de la peticion
    $postBody = file_get_contents("php://input");
    
    //Enviar datos al manejador
    $datosArray = $_auth->login($postBody);

    //devolver una respuesta
    header('content-Type: application/json');
    if(isset($datosArray["result"]["error_id"])){
        $responseCode = $datosArray["result"]["error_id"];
        http_response_code($responseCode);
    }else{
        http_response_code(200);
    }
    echo json_encode($datosArray);
}else{
    header('content-Type: application/json');
    $datosArray = $_respuestas->error_405();
    echo json_encode($datosArray);
}

?>



