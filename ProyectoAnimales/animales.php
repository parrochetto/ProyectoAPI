<?php 

require_once 'clases/respuestas.class.php';
require_once 'clases/animales.class.php';

$_respuestas = new respuestas;
$_animales = new animales;

if ($_SERVER['REQUEST_METHOD'] == 'GET'){

    if(isset($_GET["page"])){

        $pagina = $_GET["page"];

        $listaAnimalesPublica = $_animales->listaAnimalesPublica();
        $object = (object) $listaAnimalesPublica;

        $listaAnimales = $_animales->listaAnimales($pagina);

        $soloNombres = array_column($listaAnimales, 'nombre');

        $object->message = array_merge((array)$object->message, $soloNombres);
        header("Content-Type: application/json");

        echo json_encode($object);
        http_response_code(200);

    }else if(isset($_GET['id'])){

        $animalid = $_GET['id'];

        $datosAnimal = $_animales->obtenerAnimal($animalid);

        header("Content-Type: application/json");
        echo json_encode($datosAnimal);
        http_response_code(200);

    }
    
}else if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $postBody = file_get_contents("php://input");
    $listaAnimalesPublica = $_animales->listaAnimalesPublica();
    $datosArray = $_animales->post($postBody, $listaAnimalesPublica);
    
    header('content-Type: application/json');

    if(isset($datosArray["result"]["error_id"])){
        $responseCode = $datosArray["result"]["error_id"];
        http_response_code($responseCode);
    }else{
        http_response_code(200);
    }
    echo json_encode($datosArray);


}else if($_SERVER['REQUEST_METHOD'] == 'DELETE') {

    $postBody = file_get_contents("php://input");

    $datosArray = $_animales->delete($postBody);   
    header("Content-Type: application/json");

    if(isset($datosArray['result']['error_id'])){
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