<?php

namespace iutnc\deefy\action;

class LogoutAction extends Action
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
        $_SESSION['user'] = null;
        return <<<HTML
        <div class="alert alert-success mt-3 text-center" role="alert">
            Vous vous êtes déconnecté(e) avec succès !
        </div>
HTML;

    }

    protected function post(): string
    {
        return "";
    }
}