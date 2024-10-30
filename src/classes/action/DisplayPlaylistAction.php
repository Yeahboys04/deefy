<?php
namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\AudioList;
use iutnc\deefy\render\AudioListRenderer;


class DisplayPlaylistAction extends Action
{

    public function execute(): string
    {
        switch ($this->http_method) {
            case "GET":
                $res = $this->get();
                break;
            case "POST":
                $res = $this->post();
                break;
        }

        return $res;

        return $resultat;
    }

    protected function get() : string
    {
        if(isset($_SESSION['playlist'])){
            $p = $_SESSION['playlist'];
            $render = new AudioListRenderer($p);
            $resultat = $render->render();
        } else{
            $resultat= "La playListExiste pas";
        }
        return <<<HTML
<div class="playlist-container">
    $resultat
    <div class="text-center mt-4">
        <a href="?action=add-track" class="btn btn-primary">Ajouter une piste</a>
    </div>
</div>

HTML;


    }

    protected function post() : string
    {
        return "";
    }
}