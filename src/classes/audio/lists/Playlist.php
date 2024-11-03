<?php

namespace iutnc\deefy\audio\lists;

use iutnc\deefy\audio\tracks\AudioTrack;

/**
 * Classe représentant une playlist audio.
 */
class Playlist extends AudioList
{
    /**
     * Ajoute une piste à la playlist.
     *
     * @param AudioTrack $piste Piste à ajouter.
     */
    public function ajouterPiste(AudioTrack $piste): void
    {
        $this->pistes[$this->nbPistes] = $piste;
        $this->nbPistes++;
        $this->duree += $piste->duree;
    }

    /**
     * Supprime une piste de la playlist.
     *
     * @param int $indPiste Index de la piste à supprimer.
     */
    public function supprimerPiste(int $indPiste): void
    {
        if ($indPiste >= 0 && $indPiste < $this->nbPistes) {
            $this->duree -= $this->pistes[$indPiste]->duree;
            unset($this->pistes[$indPiste]);
            $this->pistes = array_values($this->pistes); // Réindexer le tableau
            $this->nbPistes--;
        }
    }

    /**
     * Ajoute plusieurs pistes à la playlist.
     *
     * @param array $pistes Pistes à ajouter.
     */
    public function ajouterPistes(array $pistes): void
    {
        foreach ($pistes as $piste) {
            if (!in_array($piste, $this->pistes, true)) { // Vérifie si la piste n'est pas déjà présente
                $this->ajouterPiste($piste);
            }
        }
    }
}
?>