<?php

namespace iutnc\deefy\audio\lists;

use iutnc\deefy\exception\InvalidPropertyNameException;

/**
 * Classe représentant une liste de pistes audio.
 */
class AudioList
{
    /**
     * Nom de la liste de pistes.
     *
     * @var string
     */
    protected string $nom;

    /**
     * Nombre de pistes dans la liste.
     *
     * @var int
     */
    protected int $nbPistes;

    /**
     * Durée totale de la liste de pistes en secondes.
     *
     * @var int
     */
    protected int $duree;

    /**
     * Pistes audio contenues dans la liste.
     *
     * @var array
     */
    protected array $pistes;

    /**
     * Identifiant unique de la liste de pistes.
     *
     * @var int
     */
    protected int $id;

    /**
     * Constructeur de la classe AudioList.
     *
     * @param string $nom Nom de la liste.
     * @param int $id Identifiant unique de la liste (par défaut 0).
     * @param array $pistes Pistes audio à ajouter à la liste (par défaut un tableau vide).
     */
    public function __construct(string $nom, int $id = 0, array $pistes = [])
    {
        $this->nom = $nom;
        $this->pistes = $pistes;
        $this->nbPistes = count($pistes);
        $this->duree = $this->calculerDureePiste($pistes);
        $this->id = $id;
    }

    /**
     * Calcule la durée totale des pistes audio dans la liste.
     *
     * @param array $pistes Pistes audio à évaluer.
     * @return int Durée totale en secondes.
     */
    public function calculerDureePiste(array $pistes): int
    {
        $duree = 0;
        foreach ($pistes as $piste) {
            $duree += $piste->__get('duree');
        }
        return $duree;
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
     * Définit l'identifiant unique de la liste de pistes.
     *
     * @param int $id Identifiant unique de la liste.
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
?>