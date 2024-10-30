<?php
namespace iutnc\deefy\audio\lists;
class Playlist extends AudioList
{
    public function ajouterPiste($piste){
        $this->pistes[$this->nbPistes] = $piste;
        $this->nbPistes ++;
        $this->duree += $piste->duree;
    }
    public function supprimerPiste($indPiste){
        if($indPiste>0 && $indPiste<$this->nbPistes){
            $this->duree -= $this->pistes[$indPiste]->duree;
            unset($this->pistes[$indPiste]);
            $this->nbPistes --;
        }
    }

    public function ajouterPistes($pistes)
    {
        $this->pistes = array_unique(array_merge($this->pistes,$pistes));
    }
}