<?php

namespace iutnc\deefy\exception;

class AuthException extends \Exception
{

    /**
     * @param string $message
     */
    public function __construct($message = ""){
        parent::__construct($message);
    }
}