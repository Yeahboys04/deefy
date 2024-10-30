<?php

namespace iutnc\deefy\audio\tracks;

class AudioTrack {
    protected $titre;
    protected $auteur;
    protected $genre;
    protected $duree;
    protected $nom_fichier;
    protected $id;

    public function __construct($titre, $nom_fichier) {
        $this->titre = $titre;
        $this->nom_fichier = $nom_fichier;
    }

    public function __toString() {
        return json_encode($this);
    }

    public function __get(string $at){
        if(property_exists($this,$at)) return $this->$at;
        throw new \iutnc\deefy\exception\InvalidPropertyNameException("$at : invalid property");
    }

    public function __set(string $at,mixed $value){
        if($at === 'duree'){
            if($value>=0){
                $this->$at = $value;
            } else throw new \iutnc\deefy\exception\InvalidPropertyValueException("$value < 0 :  invalid value");
        } elseif (property_exists($this,$at) && $at !='titre' && $at !='nom_fichier'){
            $this->$at = $value;
        }
        else throw new \iutnc\deefy\exception\InvalidPropertyNameException("$at : invalid property");
    }

    // Setter pour auteur
    public function setAuteur($auteur) {
        $this->auteur = $auteur;
    }


    // Setter pour genre
    public function setGenre($genre) {
        $this->genre = $genre;
    }


    // Setter pour durée
    public function setDuree($duree) {
        if ($duree >= 0) {
            $this->duree = $duree;
        } else {
            throw new \iutnc\deefy\exception\InvalidPropertyValueException("$duree < 0 : invalid value");
        }
    }


    // Setter pour id
    public function setId($id) {
        $this->id = $id;
    }

    public function getType ()
    {
        return 'AudioTrack';
    }


}
?>
