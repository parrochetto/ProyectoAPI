# Aplicación web con php

Este es un proyecto desarrollado en php el cual permite hacer un CRUD de manera local y alimentarse de una API pública "https://dog.ceo/dog-api/documentation/random" para el listado de animales.


# Ejecución


## Endpoints

A continuación se listan los endpoints y ejemplos de peticiones que se pueden hacer a estos.

## Auth - login
Endpoint encargado de la verificación de credenciales del usuario.

``` POST / auth.php```

Body de petición: 

  ```
  {
	"usuario" : "usuario1@gmail.com",
	"password" : "Qa654321"
  }
  ```
 
 Respuesta 200 ok
 
 ```
 {
    "status": "ok",
    "result": {
        "token": "12580c521fa35ec64c8568553f5dd76b"
    }
 }
```

Respuesta 200 ok con credenciales invalidas: 

```
{
   "status": "error",
   "result": {
	"error_id": "200",
	"error_msg": "El password es incorrecto"
    }
}
```

## Consultar Animales

Endpoint encargado de responder con la lista completa de animales o con un animal en especifico.

Lista de animales completa:

`GET /animales.php?page=1`

Respuesta 200 ok

```
[
    {
        "id": "2",
        "nombre": "elefante",
        "imagen": "http/imagen.jpg"
    },
    {
        "id": "3",
        "nombre": "gato",
        "imagen": "http/imagenDeGato.jpg"
    }
]
```

Busqueda de un animal en especifico:

`GET /animales.php?id=(número de id)`

Respuesta 200 ok

```
[
    {
        "id": "2",
        "nombre": "elefante",
        "imagen": "http/imagen.jpg"
    }
]
```


## Agregar Animal

Este endpoint con el método POST se encarga de agregar nuevos animales a la lista de animales que se tiene de manera local.

`POST /animales`

Ejemplo de Body en la petición:

```
{  
    "nombre" : "",
    "imagen" : "",
    "token" : "" 
}
```

Los parametros nombre y token son obligatorios.

Respuesta 200 ok

```
{
    "status": "ok",
    "result": {
        "id": 8
    }
}
```

Respuesta 401 unauthorized

```
{
    "status": "error",
    "result": {
        "error_id": "401",
        "error_msg": "No autorizado"
    }
}
```







