<?php
namespace iutnc\deefy\exception;
class InvalidPropertyValueException extends \Exception{
    public function __construct($message = ""){
        parent::__construct($message);
    }
}
?>