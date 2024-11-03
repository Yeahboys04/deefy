<?php
namespace iutnc\deefy\action;
use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthException;

/**
 * Classe DefaultAction
 *
 * Cette classe gère l'affichage de la page d'accueil de l'application.
 */
class DefaultAction extends Action
{
    /**
     * Exécute l'action par défaut et retourne le contenu HTML de la page d'accueil.
     *
     * @return string Le contenu HTML de la page d'accueil.
     */
    public function execute(): string
    {
        try {
            $user = AuthnProvider::getSignedInUser();
            $message = <<<HTML

<p>Bonjour $user->email, Vous avez accès à toutes vos playlists ! </p>
HTML;

        } catch (AuthException $e){
            $message = <<<HTML

<p>Connectez-vous pour accéder à votre bibliothèque et découvrir de nouveaux contenus.</p>
HTML;

        }

        return <<<HTML

<div class="main-container mt-4">
<h2>Bienvenue sur Deefy</h2>
    $message

</div>
<div class="main-container mt-4 mb-4">

    <section class="mb-4">
        <h2 class="h4 text-center mb-4">Fonctionnalités disponibles</h2>
        <div class="row">
            <!-- Mes Playlists -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card bg-secondary text-light">
                    <div class="card-body">
                        <h5 class="card-title">Mes Playlists</h5>
                        <p class="card-text">
                            Affiche la liste des playlists de l’utilisateur authentifié. Chaque playlist est cliquable et devient la playlist courante, stockée en session.
                        </p>
                    </div>
                </div>
            </div>
            <!-- Créer une Playlist Vide -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card bg-secondary text-light">
                    <div class="card-body">
                        <h5 class="card-title">Créer une Playlist Vide</h5>
                        <p class="card-text">
                            Permet de créer une nouvelle playlist en saisissant un nom. Une fois créée, elle est ajoutée à la base de données et définie comme la playlist courante.
                        </p>
                    </div>
                </div>
            </div>
            <!-- Afficher la Playlist Courante -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card bg-secondary text-light">
                    <div class="card-body">
                        <h5 class="card-title">Afficher la Playlist Courante</h5>
                        <p class="card-text">
                            Affiche la playlist actuellement sélectionnée, facilitant l'accès rapide à la lecture.
                        </p>
                    </div>
                </div>
            </div>
            <!-- S’inscrire -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card bg-secondary text-light">
                    <div class="card-body">
                        <h5 class="card-title">S’inscrire</h5>
                        <p class="card-text">
                            Création d’un compte utilisateur avec le rôle STANDARD, offrant accès aux fonctionnalités personnalisées.
                        </p>
                    </div>
                </div>
            </div>
            <!-- S’authentifier -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card bg-secondary text-light">
                    <div class="card-body">
                        <h5 class="card-title">S’authentifier</h5>
                        <p class="card-text">
                            Permet de se connecter en tant qu’utilisateur enregistré pour accéder à ses playlists et options personnalisées.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

HTML;
    }
    /**
     * Méthode GET non utilisée.
     *
     * @return string Une chaîne vide.
     */
    protected function get() : string
    {
        return "";
    }


    /**
     * Méthode POST non utilisée.
     *
     * @return string Une chaîne vide.
     */
    protected function post() : string
    {

        return "";
    }
}