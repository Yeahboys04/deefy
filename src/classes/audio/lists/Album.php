<?php
namespace iutnc\deefy\audio\lists;

class Album extends AudioList
{
    private $artiste;
    private $dateSortie;

    /**
     * @param $artiste
     */
    public function __construct($nomAlbum,$artiste,$pistes)
    {
        parent::__construct($nomAlbum,$pistes);
        $this->artiste = $artiste;
    }

    public function setArtiste($value)
    {
        $this->artiste = $value;
    }

    public function setDateSortie($value)
    {
        $this->dateSortie = $value;
    }

}