<?php

namespace iutnc\deefy\action;

use iutnc\deefy\auth\Authz;
use iutnc\deefy\exception\AuthException;
use iutnc\deefy\users\User;

abstract class Action {

    protected ?string $http_method = null;
    protected ?string $hostname = null;
    protected ?string $script_name = null;

    public function __construct(){

        $this->http_method = $_SERVER['REQUEST_METHOD'];
        $this->hostname = $_SERVER['HTTP_HOST'];
        $this->script_name = $_SERVER['SCRIPT_NAME'];
    }
    /**
     * Exécute l'action en fonction de la méthode HTTP.
     *
     * @return string Le résultat de l'exécution de l'action.
     */
    public function execute() : string
    {
        try {
            // Vérifiez si l'utilisateur a le rôle approprié
            $checkRoleUser = Authz::checkRole(User::ROLE_USER);
            $checkRoleAdmin = Authz::checkRole(User::ROLE_ADMIN);
        } catch (AuthException $e){
            return $this->errorMessage($e->getMessage());
        }
        switch ($this->http_method) {
            case "GET":
                return $this->get(); // Affiche le formulaire pour ajouter une playlist
            case "POST":
                return $this->post(); // Traite la soumission du formulaire
            default:
                return "Methode non autorisée"; // Gestion des méthodes non autorisées
        }
    }


    /**
     * Méthode abstraite pour gérer les requêtes GET.
     *
     * @return string Le résultat de la gestion de la requête GET.
     */
    abstract protected function get() :string;

    /**
     * Méthode abstraite pour gérer les requêtes POST.
     *
     * @return string Le résultat de la gestion de la requête POST.
     */
    abstract protected function post() :string;


    /**
     * Génère un message d'erreur au format HTML.
     *
     * @param string $message Le message d'erreur à afficher.
     * @return string Le contenu HTML du message d'erreur.
     */
    protected function errorMessage(string $message): string
    {
        return "<div class='alert alert-danger'>$message</div>";
    }
}