<?php

namespace iutnc\deefy\audio\tracks;
use  \iutnc\deefy\render as R;

class AlbumTrack extends AudioTrack {
    protected $album;
    protected $annee;
    protected $numero_piste;

    public function __construct(string $titre, string $nom_fichier,string $album,string $annee,int $numero_piste) {
        parent::__construct($titre, $nom_fichier);
        $this->album = $album;
        $this->annee = $annee;
        $this->numero_piste = $numero_piste;
    }

    public function getRenderer() : R\AlbumTrackRenderer{
        return new R\AlbumTrackRenderer($this);
    }

    public function __toString() {

        return AlbumTrack . phpparent::__toString() . json_encode($this);
    }

    public function getType ()
    {
        return 'AlbumTrack';
    }

}


?>