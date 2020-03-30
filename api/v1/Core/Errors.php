<?php


namespace MzQ\Core;


use MzQ\app;

class Errors
{



    public function __construct($message , $data = null)
    {
        http_response_code(HTTP_BAD_REQUEST);
        app::outApi([
            "success" => false,
            "message" => $message,
            "data" => $data
        ]);
    }
}
