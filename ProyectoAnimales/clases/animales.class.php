<?php

require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';

class animales extends conexion{

    private $table = "animal";
    private $id = "";
    private $nombre = "";
    private $imagen = "";
    private $token = "";
    //2f29ea1207c2c81ab143e9345b835882

    
    public function listaAnimalesPublica(){
        $url = 'https://dog.ceo/api/breeds/list/all';
        $data = file_get_contents($url);
        $json = json_decode($data, true);
        return ($json);
    } 

    public function listaAnimales($pagina = 1){
        $inicio = 0;
        $cantidad = 100;
        if($pagina > 1){
            $inicio = ($cantidad * ($pagina - 1)) + 1;
            $cantidad = $cantidad * $pagina;
        }
        $query = "SELECT id, nombre, imagen FROM " . $this->table . " limit $inicio, $cantidad";
        $datos = parent::obtenerDatos($query);
        return ($datos);
    }

    public function obtenerAnimal($id){
        $query = "SELECT * FROM " . $this->table . " WHERE id = '$id'";
        return parent::obtenerDatos($query);
    }

    public function post($json, $listaAnimalesPublica){

        $_respuestas = new respuestas;
        $datos = json_decode($json, true);

        if(!isset($datos['token'])){
            return $_respuestas->error_401();
        }else{
            $this->token = $datos['token'];
            $arrayToken = $this->buscarToken();
            if($arrayToken){
                if(!isset($datos['nombre'])){
                    return $_respuestas->error_400();
                }else{
                    $this->nombre = $datos['nombre'];
                    $arrayNombre = $this->buscarAnimal();
                    if($arrayNombre){
                        return $_respuestas->error_200("El animal ya existe en la base de datos");
                    }else{
                        $this->nombre = $datos['nombre'];
                        if(isset($datos['imagen'])) $this->imagen = $datos['imagen'];
                        $resp = $this->insertaAnimal();
                        if($resp){
                            $respuesta = $_respuestas->response;
                            $respuesta["result"] = array(
                                "id" => $resp
                            );
                            return $respuesta;
                        }else{
                            return $_respuestas->error_500();
                        }
                    }   
                }
            }else{
                return $_respuestas->error_401();
            }
        }
    }

    public function insertaAnimal(){
        $query = "INSERT INTO " . $this->table . " (nombre, imagen) 
        VALUES
        ('" . $this->nombre . "','" . $this->imagen . "');";
        $resp = parent::nonQueryId($query);
        if($resp){
            return $resp;
        }else{
            return 0;
        }
    }

    public function delete($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);

        if(!isset($datos['token'])){
            return $_respuestas->error_401();
        }else{
            $this->token = $datos['token'];
            $arrayToken = $this->buscarToken();
            if($arrayToken){
                if(!isset($datos['nombre'])){
                    return $_respuestas->error_400();
                }else{
                    $this->nombre = $datos['nombre'];
        
                    $resp = $this->eliminaAnimal();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta['result'] = array(
                            "nombre" => $this->nombre
                        );
                        return $respuesta;
                    }else{
                        return $_respuestas->error_500();
                    }
                }
            }else{
                return $_respuestas->error_401();
            }
        }
    }

    private function eliminaAnimal(){
        $query = "DELETE FROM " . $this->table . " WHERE nombre = '" . $this->nombre . "';";
        $resp = parent::nonQuery($query);
        if($resp >= 1){
            return $resp;
        }else{
            return 0;
        }
    }

    private function buscarAnimal(){
        $query = "SELECT id, imagen from animal WHERE nombre = '" . $this->nombre . "';";
        $resp = parent::obtenerDatos($query);
        if($resp){
            return $resp;
        }else{
            return 0;
        }
    }
    
    private function buscarToken(){
        $query = "SELECT TokenId, UsuarioId, Estado from usuarios_token WHERE Token = '" . $this->token . "' AND Estado = 'Activo';";
        $resp = parent::obtenerDatos($query);
        if($resp){
            return $resp;
        }else{
            return 0;
        }
    }

    private function actualizarToken($tokenId){
        $date = date("Y-m-d H:i");
        $query = "UPDATE usuarios_token SET Fecha = '$date' WHERE TokenId = '$tokenId';";
        $resp = parent::nonQuery($query);
        if($resp >= 1){
            return $resp;
        }else{
            return 0;
        }
    }

}


?>