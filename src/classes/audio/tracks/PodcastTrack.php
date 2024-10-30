<?php

namespace iutnc\deefy\audio\tracks;
use  \iutnc\deefy\render as R;

class PodcastTrack extends AudioTrack {
    protected $date;

    public function __construct(string $titre, string $nom_fichier,string $auteur ='inconnu',string $date='inconnu',int $duree=0,string $genre='inconnu') {
        parent::__construct($titre, $nom_fichier);
        $this->date = $date;
        $this->auteur = $auteur;
        $this->duree = $duree;
        $this->genre = $genre;
    }

    public function getRenderer() : R\PodcastRenderer{
        return new R\PodcastRenderer($this);
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getType ()
    {
        return 'PodcastTrack';
    }


}
?>
