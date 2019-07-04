# Json Exception Response

Captura las Exception que se generen y las formatea antes de generar el Response JSON.

````bash
composer require irontec/json-exception-response-bundle
````

Si el proyecto esta en modo **dev** se devuelve un parametro "debug" con una pequeña traza de donde se genero la excepción.

````json
{
    "error": {
        "code": 400,
        "message": "Error message!",
        "debug": {
            "file": "/opt/symfony/vendor/symfony/http-kernel/HttpKernel.php",
            "line": 151,
            "function": "index",
            "class": "App\\Controller\\ExampleController",
            "type": "->",
            "args": [
                {}
            ]
        }
    }
}
````
