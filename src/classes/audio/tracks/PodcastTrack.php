<?php

namespace iutnc\deefy\audio\tracks;

use iutnc\deefy\render as R;

/**
 * Classe représentant un podcast audio.
 */
class PodcastTrack extends AudioTrack
{
    /**
     * Date de publication du podcast.
     *
     * @var string
     */
    protected string $date;

    /**
     * Constructeur de la classe PodcastTrack.
     *
     * @param string $titre Titre du podcast.
     * @param string $nomFichier Nom du fichier du podcast.
     * @param string $auteur Auteur du podcast.
     * @param string $date Date de publication du podcast.
     * @param int $duree Durée du podcast en secondes.
     * @param string $genre Genre du podcast.
     * @param int $id Identifiant unique du podcast.
     */
    public function __construct(
        string $titre,
        string $nomFichier,
        string $auteur = 'inconnu',
        string $date = 'inconnu',
        int $duree = 0,
        string $genre = 'inconnu',
        int $id = 0
    ) {
        parent::__construct($titre, $nomFichier);
        $this->date = $date;
        $this->auteur = $auteur;
        $this->duree = $duree;
        $this->genre = $genre;
        $this->id = $id; // Ajout de l'ID
    }

    /**
     * Retourne un renderer pour le podcast.
     *
     * @return R\PodcastRenderer
     */
    public function getRenderer(): R\PodcastRenderer
    {
        return new R\PodcastRenderer($this);
    }

    /**
     * Définit la date de publication du podcast.
     *
     * @param string $date Date de publication.
     */
    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    /**
     * Retourne le type de l'objet.
     *
     * @return string
     */
    public function getType(): string
    {
        return 'PodcastTrack';
    }


}
?>