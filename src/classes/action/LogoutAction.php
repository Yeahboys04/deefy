<?php

namespace iutnc\deefy\action;

/**
 * Class LogoutAction
 *
 * Gère la déconnexion de l'utilisateur.
 */
class LogoutAction extends Action
{
    /**
     * Exécute l'action en fonction de la méthode HTTP (GET ou POST).
     *
     * @return string Le contenu HTML à afficher après la déconnexion.
     */
    public function execute(): string
    {
        switch ($this->http_method) {
            case "GET":
                return $this->get();
            case "POST":
                return $this->post();
            default:
                return "Méthode non autorisée.";
        }
    }

    /**
     * Gère la requête GET pour déconnecter l'utilisateur.
     *
     * @return string Le message de succès de déconnexion.
     */
    protected function get(): string
    {
        $_SESSION['user'] = null; // Déconnexion de l'utilisateur
        $_SESSION['playlist'] = null;
        return <<<HTML
        <div class="alert alert-success mt-3 text-center" role="alert">
            Vous vous êtes déconnecté(e) avec succès !
        </div>
HTML;
    }

    /**
     * Gère la requête POST (non implémentée dans cette version).
     *
     * @return string Une chaîne vide.
     */
    protected function post(): string
    {
        // La déconnexion se fait uniquement via la méthode GET
        return "";
    }
}