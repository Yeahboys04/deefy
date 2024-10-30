<?php

namespace iutnc\deefy\action;

use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthException;

class SigninAction extends Action
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
        $res = <<<HTML
<div class="form-signin w-100 m-auto">
        <form method="post" action="?action=signin">
            <h1 class="h3 mb-3 fw-normal text-center">Connection</h1>

            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="floatingEmail" name="email" placeholder="name@example.com" required>
                <label for="floatingEmail">Email</label>
            </div>
            
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password" required>
                <label for="floatingPassword">Mot de passe</label>
            </div>

            <div class="form-check text-start my-3">
                <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                    Se souvenir de moi
                </label>
            </div>
            <button class="btn btn-primary w-100 py-2" type="submit">Se connecter</button>
        </form>
    </div>
HTML;

        return $res;
    }

    protected function post(): string
    {
        $res = "";
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $password = filter_var($_POST["password"], FILTER_SANITIZE_SPECIAL_CHARS);
        try {
            AuthnProvider::signin($email, $password);

            $res = <<<HTML
        <div class="alert alert-success mt-3 text-center" role="alert">
            Bonjour, vous êtes connecté(e) avec succès !
        </div>
HTML;
        } catch (AuthException $e) {
            // Afficher le message d'erreur dans le formulaire en cas d'échec
            $errorMessage = <<<HTML
        <div class="alert alert-danger mt-3 text-center" role="alert">
            Impossible de se connecter : nom d'utilisateur ou mot de passe incorrect.
        </div>
HTML;
            $res = $this->get();
            $res = str_replace(
                '<button class="btn btn-primary w-100 py-2" type="submit">Se connecter</button>',
                '<button class="btn btn-primary w-100 py-2" type="submit">Se connecter</button><br>' . $errorMessage,
                $res
            );
        }

        return $res;
    }

}