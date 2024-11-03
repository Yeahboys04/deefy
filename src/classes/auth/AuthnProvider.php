<?php

namespace iutnc\deefy\auth;

use iutnc\deefy\exception\AuthException;
use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\users\User;

/**
 * Class AuthnProvider
 *
 * Fournit des méthodes pour l'authentification des utilisateurs.
 */
class AuthnProvider
{
    /**
     * Vérifie la force du mot de passe.
     *
     * @param string $pass Le mot de passe à vérifier.
     * @param int $minimumLength La longueur minimale du mot de passe (par défaut 8).
     * @return bool True si le mot de passe est fort, sinon false.
     */
    public static function checkPasswordStrength(string $pass, int $minimumLength = 8): bool
    {
        $length = (strlen($pass) >= $minimumLength); // La longueur doit être suffisante
        $digit = preg_match("#[\d]#", $pass); // Au moins un chiffre
        $special = preg_match("#[\W]#", $pass); // Au moins un caractère spécial
        $lower = preg_match("#[a-z]#", $pass); // Au moins une minuscule
        $upper = preg_match("#[A-Z]#", $pass); // Au moins une majuscule

        // Tous les critères doivent être vrais
        return $length && $digit && $special && $lower && $upper;
    }

    /**
     * Authentifie un utilisateur avec son email et son mot de passe.
     *
     * @param string $email L'email de l'utilisateur.
     * @param string $passwd2check Le mot de passe à vérifier.
     * @throws AuthException Si les informations d'identification sont invalides.
     */
    public static function signin(string $email, string $passwd2check): void
    {
        $repository = DeefyRepository::getInstance();
        $user = $repository->getUser ($email);

        if (!password_verify($passwd2check, $user->password)) {
            throw new AuthException("Impossible de se connecter : nom d'utilisateur ou mot de passe incorrect.");
        }

        $_SESSION['user'] = serialize($user);
    }

    /**
     * Obtient l'utilisateur actuellement connecté.
     *
     * @return User L'utilisateur connecté.
     * @throws AuthException Si aucun utilisateur n'est connecté.
     */
    public static function getSignedInUser (): User
    {
        if (!isset($_SESSION['user'])) {
            throw new AuthException("Vous n'avez pas l'autorisation d'accéder à cette fonctionnalité.");
        }

        return unserialize($_SESSION['user']);
    }

    /**
     * Enregistre un nouvel utilisateur.
     *
     * @param string $email L'email de l'utilisateur.
     * @param string $password Le mot de passe de l'utilisateur.
     * @throws AuthException Si l'email est invalide ou si le mot de passe ne respecte pas les critères de sécurité.
     */
    public static function register(string $email, string $password): void
    {
        $repo = DeefyRepository::getInstance();

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new AuthException("L'adresse email est invalide");
        }

        if (self::checkPasswordStrength($password, 10)) {
            $hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
            $repo->addUser ($email, $hash, User::ROLE_USER);
        } else {
            throw new AuthException("Le mot de passe doit contenir au moins 10 caractères, incluant une majuscule, une minuscule, un chiffre et un caractère spécial.");
        }
    }
}

?>