<?php

namespace iutnc\deefy\render;

/**
 * Class AudioTrackRenderer
 *
 * Classe abstraite pour le rendu des pistes audio.
 */
abstract class AudioTrackRenderer implements Renderer
{
    protected $audio;

    /**
     * AudioTrackRenderer constructor.
     *
     * @param mixed $audio L'objet audio à rendre.
     */
    public function __construct($audio)
    {
        $this->audio = $audio;
    }

    /**
     * Rend la piste audio en fonction du sélecteur.
     *
     * @param int $selector Le sélecteur de rendu (Renderer::COMPACT ou Renderer::LONG).
     * @return string Le rendu HTML de la piste audio.
     */
    public function render(int $selector): string
    {
        $res = '';
        if ($selector === Renderer::COMPACT) {
            $res = $this->renderCompact();
        } elseif ($selector === Renderer::LONG) {
            $res = $this->renderLong();
        }
        return $res;
    }

    /**
     * Rend la piste audio sous une forme compacte.
     *
     * @return string Le rendu HTML compact de la piste.
     */
    protected abstract function renderCompact(): string;

    /**
     * Rend la piste audio sous une forme détaillée.
     *
     * @return string Le rendu HTML long de la piste.
     */
    protected abstract function renderLong(): string;
}
?>