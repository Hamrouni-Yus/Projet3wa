<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class NotFoundException extends Exception

{

    //fonction de construction de l'exception NotFoundException depuis la classe Exception deja predefinie
    public function __construct($message = "", $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    //fonction de redirection en cas d'erreur 404
    public function error404()
    {

        //reponce dans le reseau
        http_response_code(404);

        //redirection a la page 404 personnel
        return require VIEWS . '/errors/404.php';
    }
}
