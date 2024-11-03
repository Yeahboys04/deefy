<?php

namespace iutnc\deefy\render;

interface Renderer{
  const COMPACT = 1;
  const LONG = 2;

    /**
     * Rend la liste audio sous forme de chaîne HTML.
     *
     * @param int $selector Le sélecteur de rendu.
     * @return string Le rendu HTML de la liste audio.
     */
  public function render(int $selector) :string;
}
?>