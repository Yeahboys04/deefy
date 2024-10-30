<?php
namespace iutnc\deefy\action;
use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthException;
use iutnc\deefy\repository\DeefyRepository;
use mysql_xdevapi\Exception;
use function Symfony\Component\String\s;

class AddUserAction extends Action
{

    public function execute(): string
    {
        $res = "";
        switch ($this->http_method){
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
        <form method="post" action="?action=add-user">
            <h1 class="h3 mb-3 fw-normal text-center">Inscription</h1>

            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="floatingEmail" name="email" placeholder="name@example.com" required>
                <label for="floatingEmail">Email</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password" required>
                <label for="floatingPassword">Mot de passe</label>
            </div>
            
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="floatingPassword-confirm" name="password-confirm" placeholder="Password" required>
                <label for="floatingPassword-confirm">Confirmer le mot de passe</label>
            </div>

            <button class="btn btn-primary w-100 py-2" type="submit">S'inscrire</button>
        </form>
    </div>
HTML;

        return $res;

    }

    protected function post(): string
    {
        $password = filter_var($_POST["password"], FILTER_SANITIZE_SPECIAL_CHARS);
        $passwordConfirm = filter_var($_POST["password-confirm"], FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $res = "";
        if($password !== false && $passwordConfirm !== false && $email !== false) {
            if ($password===$passwordConfirm){
                try {
                    AuthnProvider::register($email,$password);
                    $res = <<<HTML
        <div class="alert alert-success mt-3 text-center" role="alert">
            Bonjour, vous êtes inscrit(e) avec succès !
        </div>
HTML;
                    AuthnProvider::signin($email,$password);

                }catch (AuthException $e){
                    echo $e->getMessage();
                    $res = $this->errorMessage($e->getMessage());
                }
            } else{
                $res = $this->errorMessage("Les mots de passe ne correspondent pas. Veuillez les saisir à nouveau.");

            }
        }

        return $res;
    }

    private function errorMessage(string $message) : string
    {
        $errorMessage = <<<HTML
        <div class="alert alert-danger mt-3 text-center" role="alert">
             $message
        </div>
HTML;
        $res = $this->get();
        return str_replace(
            '<button class="btn btn-primary w-100 py-2" type="submit">S\'inscrire</button>',
            '<button class="btn btn-primary w-100 py-2" type="submit">S\'inscrire</button><br>' . $errorMessage,
            $res
        );
    }
}