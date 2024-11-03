<?php

namespace iutnc\deefy\render;

/**
 * Class PodcastRenderer
 *
 * Rendu d'une piste de podcast, étendant AudioTrackRenderer.
 */
class PodcastRenderer extends AudioTrackRenderer
{
    /**
     * Rend la piste de podcast sous une forme compacte.
     *
     * @return string Le rendu HTML compact de la piste.
     */
    protected function renderCompact(): string
    {

        return <<<HTML
<div class="track-item mb-3 p-3 bg-dark text-light rounded shadow-sm d-flex flex-column flex-md-row align-items-md-center">
    <div class="track-info flex-grow-1">
     <strong>{$this->audio->titre}</strong> par {$this->audio->auteur}
     <span class="d-block d-md-inline">-</span>
     <span>{$this->audio->duree} secondes</span>
    </div>
   

    <audio controls class="mt-3 mt-md-0 ml-md-3 audio-playe"><source src='../../../audio/{$this->audio->nomFichier}' type='audio/mpeg'></audio> 
</div>
HTML;
    }

    /**
     * Rend la piste de podcast sous une forme détaillée.
     *
     * @return string Le rendu HTML long de la piste.
     */
    protected function renderLong(): string
    {
        return "<div>
            <h1>{$this->audio->titre}</h1>
            <p>Auteur: {$this->audio->auteur}</p>
            <p>Date: {$this->audio->date}</p>
            <p>Genre: {$this->audio->genre}</p>
            <p>Durée: {$this->audio->duree} secondes</p>
            <audio controls><source src='../../../audio/{$this->audio->nomFichier}' type='audio/mpeg'></audio>
        </div>";
    }
}
?>