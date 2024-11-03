<?php
namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\AudioList;
use iutnc\deefy\auth\Authz;
use iutnc\deefy\exception\AuthException;
use iutnc\deefy\exception\PlaylistNotFoundException;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\users\User;

/**
 * Class DisplayPlaylistAction
 *
 * Gère l'affichage de la playlist.
 */
class DisplayPlaylistAction extends Action
{


    /**
     * Gère la requête GET pour afficher la playlist.
     *
     * @return string Le contenu HTML de la playlist ou un message d'erreur.
     */
    protected function get(): string
    {
        $repository = DeefyRepository::getInstance();

        $playlistId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if ($playlistId === false || $playlistId <= 0) {
            return $this->errorMessage("ID de playlist invalide.");
        }

        try {

            // Vérifiez si l'utilisateur est le propriétaire de la playlist
            if (!Authz::checkPlaylistOwner($playlistId)) {
                return $this->errorMessage("Vous n'avez pas l'autorisation d'accéder à cette playlist.");
            }

            $playlist = $repository->findPlaylistById($playlistId);
            $renderer = new AudioListRenderer($playlist);
            $resultat = $renderer->render();
        } catch (AuthException|PlaylistNotFoundException $e){
            return $this->errorMessage($e->getMessage());
        }

        // Ajout de la playlist courrante en session
        $_SESSION["playlist"] = serialize($playlist);


        return <<<HTML
<div class="playlist-container">
    $resultat
    <div class="text-center mt-4">
        <a href="?action=add-track" class="btn btn-primary">Ajouter une piste</a>
    </div>
</div>
HTML;
    }

    /**
     * Gère la requête POST .
     *
     * @return string Une chaîne vide.
     */
    protected function post(): string
    {
        return "";
    }


}