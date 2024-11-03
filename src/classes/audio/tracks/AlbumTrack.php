<?php

namespace iutnc\deefy\audio\tracks;

use \iutnc\deefy\render as R;

/**
 * Class AlbumTrack
 *
 * Représente une piste d'album dans le système audio.
 */
class AlbumTrack extends AudioTrack
{
    /**
     * @var string Le nom de l'album.
     */
    protected string $album;

    /**
     * @var string L'année de sortie de la piste.
     */
    protected string $annee;

    /**
     * @var int Le numéro de la piste dans l'album.
     */
    protected int $numeroPiste;

    /**
     * AlbumTrack constructor.
     *
     * @param string $titre Le titre de la piste.
     * @param string $nomFichier Le nom du fichier audio.
     * @param string $album Le nom de l'album.
     * @param string $annee L'année de sortie.
     * @param int $numeroPiste Le numéro de la piste.
     * @param string $auteur L'auteur de la piste (par défaut 'inconnu').
     * @param int $duree La durée de la piste en secondes (par défaut 0).
     * @param string $genre Le genre de la musique (par défaut 'inconnu').
     * @param int $id L'identifiant de la piste (par défaut 0).
     */
    public function __construct(
        string $titre,
        string $nomFichier,
        string $album,
        string $annee,
        int $numeroPiste,
        string $auteur = 'inconnu',
        int $duree = 0,
        string $genre = 'inconnu',
        int $id = 0
    ) {
        parent::__construct($titre, $nomFichier);
        $this->album = $album;
        $this->annee = $annee;
        $this->numeroPiste = $numeroPiste;
        $this->auteur = $auteur;
        $this->duree = $duree;
        $this->genre = $genre;
    }

    /**
     * Obtient le renderer associé à cette piste d'album.
     *
     * @return R\AlbumTrackRenderer Le renderer de la piste d'album.
     */
    public function getRenderer(): R\AlbumTrackRenderer
    {
        return new R\AlbumTrackRenderer($this);
    }

    /**
     * Retourne une représentation sous forme de chaîne de l'objet AlbumTrack.
     *
     * @return string La représentation sous forme de chaîne de l'objet.
     */
    public function __toString(): string
    {
        return parent::__toString() . json_encode($this);
    }

    /**
     * Obtient le type de l'objet.
     *
     * @return string Le type de l'objet.
     */
    public function getType(): string
    {
        return 'AlbumTrack';
    }

    /**
     * Obtient le nom de l'album.
     *
     * @return string Le nom de l'album.
     */
    public function getAlbum(): string
    {
        return $this->album;
    }

    /**
     * Obtient l'année de sortie de la piste.
     *
     * @return string L'année de sortie.
     */
    public function getAnnee(): string
    {
        return $this->annee;
    }

    /**
     * Obtient le numéro de la piste dans l'album.
     *
     * @return int Le numéro de la piste.
     */
    public function getNumeroPiste(): int
    {
        return $this->numeroPiste;
    }
}

?>