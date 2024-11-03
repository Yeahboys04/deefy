<?php

namespace iutnc\deefy\audio\tracks;

use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\exception\InvalidPropertyValueException;

/**
 * Classe représentant une piste audio.
 */
class AudioTrack
{
    /**
     * Titre de la piste audio.
     *
     * @var string
     */
    protected string $titre;

    /**
     * Auteur de la piste audio.
     *
     * @var string
     */
    protected string $auteur;

    /**
     * Genre de la piste audio.
     *
     * @var string
     */
    protected string $genre;

    /**
     * Durée de la piste audio en secondes.
     *
     * @var int
     */
    protected int $duree;

    /**
     * Nom du fichier de la piste audio.
     *
     * @var string
     */
    protected string $nomFichier;

    /**
     * Identifiant unique de la piste audio.
     *
     * @var int
     */
    protected int $id;

    /**
     * Constructeur de la classe AudioTrack.
     *
     * @param string $titre Titre de la piste audio.
     * @param string $nomFichier Nom du fichier de la piste audio.
     */
    public function __construct(string $titre, string $nomFichier)
    {
        $this->titre = $titre;
        $this->nomFichier = $nomFichier;
    }

    /**
     * Retourne une représentation JSON de l'objet.
     *
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this);
    }

    /**
     * Getter magique pour les propriétés de l'objet.
     *
     * @param string $at Nom de la propriété à récupérer.
     * @return mixed Valeur de la propriété.
     * @throws InvalidPropertyNameException Si la propriété n'existe pas.
     */
    public function __get(string $at)
    {
        if (property_exists($this, $at)) {
            return $this->$at;
        }
        throw new InvalidPropertyNameException("$at : invalid property");
    }

    /**
     * Setter magique pour les propriétés de l'objet.
     *
     * @param string $at Nom de la propriété à définir.
     * @param mixed $value Valeur à attribuer à la propriété.
     * @throws InvalidPropertyNameException Si la propriété n'existe pas.
     * @throws InvalidPropertyValueException Si la valeur est invalide.
     */
    public function __set(string $at, mixed $value): void
    {
        if ($at === 'duree') {
            if ($value >= 0) {
                $this->$at = $value;
            } else {
                throw new InvalidPropertyValueException("$value < 0 : invalid value");
            }
        } elseif (property_exists($this, $at) && $at !== 'titre' && $at !== 'nomFichier') {
            $this->$at = $value;
        } else {
            throw new InvalidPropertyNameException("$at : invalid property");
        }
    }

    /**
     * Définit l'auteur de la piste audio.
     *
     * @param string $auteur Auteur de la piste audio.
     */
    public function setAuteur(string $auteur): void
    {
        $this->auteur = $auteur;
    }

    /**
     * Définit le genre de la piste audio.
     *
     * @param string $genre Genre de la piste audio.
     */
    public function setGenre(string $genre): void
    {
        $this->genre = $genre;
    }

    /**
     * Définit la durée de la piste audio.
     *
     * @param int $duree Durée de la piste audio en secondes.
     * @throws InvalidPropertyValueException Si la valeur est négative.
     */
    public function setDuree(int $duree): void
    {
        if ($duree >= 0) {
            $this->duree = $duree;
        } else {
            throw new InvalidPropertyValueException("$duree < 0 : invalid value");
        }
    }

    /**
     * Définit l'identifiant unique de la piste audio.
     *
     * @param int $id Identifiant unique de la piste audio.
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Retourne le type de l'objet .
     *
     * @return string
     */
    public function getType(): string
    {
        return 'AudioTrack';
    }
}
?>