<?php

namespace iutnc\deefy\auth;

use iutnc\deefy\exception\AuthException;
use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\users\User;

class AuthnProvider
{
    public static function checkPasswordStrength(string $pass, int $minimumLength = 8): bool
    {
        $length = (strlen($pass) >= $minimumLength); // la longueur doit être suffisante
        $digit = preg_match("#[\d]#", $pass); // au moins un chiffre
        $special = preg_match("#[\W]#", $pass); // au moins un caractère spécial
        $lower = preg_match("#[a-z]#", $pass); // au moins une minuscule
        $upper = preg_match("#[A-Z]#", $pass); // au moins une majuscule

        // Tous les critères doivent être vrais
        if ($length && $digit && $special && $lower && $upper) {
            return true;
        }
        return false;
    }



    /**
     * @throws AuthException
     */
    public static function signin(string $email, string $passwd2check): void {
        $repository = DeefyRepository::getInstance();
        $user = $repository->getUser($email);
        if (!password_verify($passwd2check, $user->password))
            throw new AuthException("Auth error : invalid credentials");
        $_SESSION['user'] = serialize($user);
    }

    public static function getSignedInUser( ): User {
        if ( !isset($_SESSION['user']))
            throw new AuthException("Auth error : not signed in");
        return unserialize($_SESSION['user'] ) ;
    }

    /**
     * @throws AuthException
     */
    public static function register(string $email, string $password): void
    {
        $repo = DeefyRepository::getInstance();
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            throw new AuthException("L'adresse email est invalide");
        }
        if(AuthnProvider::checkPasswordStrength($password,10)){
            $hash=password_hash($password, PASSWORD_DEFAULT,['cost'=> 12] );
            $repo->addUser($email,$hash,1);

        } else{
            throw new AuthException("Le mot de passe doit contenir au moins 8 caractères, incluant une majuscule, une minuscule, un chiffre et un caractère spécial.");
        }



    }
}