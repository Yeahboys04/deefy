<?php
namespace iutnc\deefy\render;
class PodcastRenderer extends AudioTrackRenderer{

  protected function renderCompact(): string {
      return <<<HTML
<div class="track-item mb-3"><strong>{$this->audio->titre}</strong> par {$this->audio->auteur} -  <span>{$this->audio->duree} secondes</span>
    <audio controls><source src='{$this->audio->nom_fichier}' type='audio/mpeg'></audio> 
</div>
HTML;
  }

  protected function renderLong(): string {
      return "<div><h1>{$this->audio->titre} </h1><p>Auteur: {$this->audio->auteur}</p><p>Date: {$this->audio->date}</p> <p>Genre: {$this->audio->genre}</p><p>Duree: {$this->audio->duree} seconds</p><audio controls><source src='{$this->audio->nom_fichier}' type='audio/mpeg'></audio></div>";
  }
}
?>