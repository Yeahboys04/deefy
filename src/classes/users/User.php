<?php

namespace iutnc\deefy\users;

class User
{
    private $id;
    private $email;
    private $password;
    private $role;

    public function __construct(string $email,string $password,string $role)
    {
        $this->email =$email;
        $this->password = $password;
        $this->role = $role;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function __get($param){
        if(property_exists($this,$param)){
            return $this->$param;
        } else{
            throw new \iutnc\deefy\exception\InvalidPropertyNameException("$param : invalid property");
        }
    }

    public function setRole(string $role)
    {
        $this->role = $role;
    }

}