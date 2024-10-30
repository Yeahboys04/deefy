<?php
namespace iutnc\deefy\action;

class DefaultAction extends Action
{

    public function execute(): string
    {
        return <<<HTML
<h2>Bienvenue sur Mon Site de Streaming</h2>
<p>Connectez-vous pour accéder à votre bibliothèque et découvrir de nouveaux contenus.</p>
<a class="btn btn-custom" href="?action=add-user">Se connecter</a>
HTML;
    }

    protected function get() : string
    {
        return "";
    }

    protected function post() : string
    {

        return "";
    }
}