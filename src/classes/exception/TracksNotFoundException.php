<?php

namespace iutnc\deefy\exception;

class TracksNotFoundException extends \Exception
{
    public function __construct($message = ""){
        parent::__construct($message);
    }
}