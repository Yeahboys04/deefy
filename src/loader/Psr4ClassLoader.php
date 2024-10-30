<?php
class Psr4ClassLoader
{
    private string $prefixe;
    private string $chemin;

    function __construct(string $prefixe, string $chemin)
    {
        $this->prefixe = $prefixe;
        $this->chemin = $chemin;
    }

    function loadClass(string $class)
    {
        $file_name = str_replace($this->prefixe,$this->chemin,$class);
        $file_name = str_replace('\\', DIRECTORY_SEPARATOR,$file_name) . ".php";

        if(is_file($file_name)) {
            require_once ($file_name);

        }
    }

    public function register()
    {
        spl_autoload_register([$this,'loadClass']);
    }
}