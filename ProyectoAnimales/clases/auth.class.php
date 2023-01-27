<?php

require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';

class auth extends conexion{

    public function login($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);
        if(!isset($datos['usuario']) || !isset($datos['password'])){
            //error con los campos
            return $_respuestas->error_400();
        }else{
            //esta bien
            $usuario = $datos['usuario'];
            $password = $datos['password'];
            $datos = $this->obtenerDatosUsuario($usuario);
            if($datos){
                //verificar contraseña
                if($password == $datos[0]['Password']){
                    if($datos[0]['Estado'] == "Activo"){
                        //crear token
                        $verificar = $this->insertarToken($datos[0]['Id']);
                        if($verificar){
                            //Si se guardo
                            $result = $_respuestas->response;
                            $result["result"] = array(
                                "token" => $verificar
                            );
                            return $result;
                        }else{
                            return $_respuestas->error_500("Error interno");
                        }
                    }else{
                        return $_respuestas->error_200("El usuario no esta activo");
                    }
                }else{
                    return $_respuestas->error_200("El password es incorrecto");
                }
            }else{
                return $_respuestas->error_200("El usuario $usuario no existe");
            }
        }
    }

    private function obtenerDatosUsuario($correo){
        $query = "SELECT Id, Password, Estado FROM usuarios WHERE Usuario = '$correo'";
        $datos = parent::obtenerDatos($query);
        if(isset($datos[0]["Id"])){
            return $datos;
        }else{
            return 0;
        }
    }

    private function insertarToken($Id){
        $val = true;
        $token = bin2hex(openssl_random_pseudo_bytes(16, $val));
        $date = date("Y-m-d H:i");
        $estado = "Activo";
        $query = "INSERT INTO usuarios_token (UsuarioId, Token, Estado, Fecha) VALUES('$Id', '$token', '$estado', '$date')";
        $verificar = parent::nonQuery($query);
        if($verificar){
            return $token;
        }else{
            return 0;
        }
    }

}

?>