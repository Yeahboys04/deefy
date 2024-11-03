<?php

namespace iutnc\deefy\render;

/**
 * Class AudioListRenderer
 *
 * Rendu d'une liste audio.
 */
class AudioListRenderer implements Renderer
{
    private $audioList;

    /**
     * AudioListRenderer constructor.
     *
     * @param mixed $audioList La liste audio à rendre.
     */
    public function __construct($audioList)
    {
        $this->audioList = $audioList;
    }

    /**
     * Rend la liste audio sous forme de chaîne HTML.
     *
     * @param int $selector Le sélecteur de rendu (par défaut Renderer::COMPACT).
     * @return string Le rendu HTML de la liste audio.
     */
    public function render(int $selector = Renderer::COMPACT): string
    {
        $res = <<<HTML
<h1 class="h3 mb-4 text-center">Nom : {$this->audioList->nom}</h1>
HTML;

        foreach ($this->audioList->pistes as $value) {
            $renderValue = $value->getRenderer();
            $res .= $renderValue->render(Renderer::COMPACT) . "\n";
        }

        $res .= "<p class=\"mt-4\">Nombre de pistes : <strong>{$this->audioList->nbPistes}</strong></p>\n";
        $res .= "<p>Durée totale : <strong>{$this->audioList->duree} secondes</strong></p>\n";

        return $res;
    }
}