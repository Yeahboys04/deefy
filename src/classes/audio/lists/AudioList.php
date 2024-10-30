<?php

namespace iutnc\deefy\audio\lists;


use iutnc\deefy\exception\InvalidPropertyNameException;

class AudioList
{
    protected $nom;
    protected $nbPistes;
    protected $duree;
    protected $pistes;
    protected $id;

    /**
     * @param $nom
     */
    public function __construct($nom,$pistes = [])
    {
        $this->nom = $nom;
        $this->pistes = $pistes;
        $this->nbPistes = count($pistes);
        $this->duree = $this->calculerDureePiste($pistes);
    }



    /**
     * @param $pistes
     * @return int
     */
    public function calculerDureePiste($pistes): int
    {
        $duree = 0;
        foreach ($pistes as $value) {
            $duree += $value->__get('duree');
        }
        return $duree;
    }

    public function __get($at)
    {
        if(property_exists($this,$at)){
            return $this->$at;
        } else throw new InvalidPropertyNameException("$at : invalid property");
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }


}