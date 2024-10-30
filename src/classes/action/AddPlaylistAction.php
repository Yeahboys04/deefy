<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\render\AudioListRenderer;

class AddPlaylistAction extends Action
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
    }

    protected function get(): string
    {
        return <<<HTML
        <div class="form-playlist">
            <h1 class="h3 mb-3 fw-normal text-center">Ajouter une Playlist</h1>
            <form method="post" action="?action=add-playlist">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="nomPlaylist" name="nomPlaylist" placeholder="Entrez le nom de la playlist" required>
                    <label for="nomPlaylist" >Nom de la playlist</label>
                </div>
                <button class="btn btn-primary w-100" type="submit" name="valider">Valider</button>
            </form>
        </div>
        HTML;
    }

    protected function post(): string
    {
        $nomPlaylist = filter_var($_POST["nomPlaylist"], FILTER_SANITIZE_SPECIAL_CHARS);
        $_SESSION["playlist"] = new Playlist($nomPlaylist);
        $render = new AudioListRenderer($_SESSION["playlist"]);
            $res = $render->render();

        return <<<HTML
<div class="playlist-container">
    $res
    <div class="text-center mt-4">
        <a href="?action=add-track" class="btn btn-primary">Ajouter une piste</a>
    </div>
</div>
HTML;

    }
}