<?php

namespace iutnc\deefy\audio\lists;

/**
 * Classe représentant un album audio.
 */
class Album extends AudioList
{
    /**
     * Artiste de l'album.
     *
     * @var string
     */
    private string $artiste;

    /**
     * Date de sortie de l'album.
     *
     * @var string|null
     */
    private ?string $dateSortie;

    /**
     * Constructeur de la classe Album.
     *
     * @param string $nomAlbum Nom de l'album.
     * @param string $artiste Artiste de l'album.
     * @param array $pistes Pistes audio de l'album.
     */
    public function __construct(string $nomAlbum, string $artiste, array $pistes = [],$dateSortie = null)
    {
        parent::__construct($nomAlbum, 0, $pistes); // Appel du constructeur parent avec id par défaut à 0
        $this->artiste = $artiste;
        $this->dateSortie = $dateSortie;
    }

    /**
     * Définit l'artiste de l'album.
     *
     * @param string $value Nom de l'artiste.
     */
    public function setArtiste(string $value): void
    {
        $this->artiste = $value;
    }

    /**
     * Définit la date de sortie de l'album.
     *
     * @param string|null $value Date de sortie de l'album.
     */
    public function setDateSortie(?string $value): void
    {
        $this->dateSortie = $value;
    }

    /**
     * Retourne le nom de l'artiste.
     *
     * @return string
     */
    public function getArtiste(): string
    {
        return $this->artiste;
    }

    /**
     * Retourne la date de sortie de l'album.
     *
     * @return string|null
     */
    public function getDateSortie(): ?string
    {
        return $this->dateSortie;
    }
}
?>