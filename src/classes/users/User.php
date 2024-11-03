<?php

namespace iutnc\deefy\users;

use InvalidArgumentException;
use iutnc\deefy\exception\InvalidPropertyNameException;

class User
{
    // Constantes pour les rôles
    public const ROLE_ADMIN = 100;
    public const ROLE_USER = 1;
    public const ROLE_GUEST = 0;

    private int $id; // Type défini pour l'identifiant
    private string $email; // Type défini pour l'e-mail
    private string $password; // Type défini pour le mot de passe
    private int $role; // Type défini pour le rôle

    /**
     * User constructor.
     *
     * @param string $email L'adresse e-mail de l'utilisateur.
     * @param string $password Le mot de passe de l'utilisateur.
     * @param int $role Le rôle de l'utilisateur (doit être une constante définie).
     */
    public function __construct(string $email, string $password, int $role=self::ROLE_GUEST)
    {
        $this->email = $email;
        $this->password = $password;
        $this->setRole($role); // Utilisation de setRole pour valider le rôle
    }

    /**
     * Définit l'identifiant de l'utilisateur.
     *
     * @param int $id L'identifiant de l'utilisateur.
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Accesseur pour les propriétés de l'utilisateur.
     *
     * @param string $param Le nom de la propriété.
     * @return mixed La valeur de la propriété.
     * @throws InvalidPropertyNameException Si la propriété n'existe pas.
     */
    public function __get(string $param)
    {
        if (property_exists($this, $param)) {
            return $this->$param;
        } else {
            throw new InvalidPropertyNameException("$param : invalid property");
        }
    }

    /**
     * Définit le rôle de l'utilisateur.
     *
     * @param int $role Le rôle de l'utilisateur (doit être une constante définie).
     */
    public function setRole(int $role): void
    {
        if (!in_array($role, [self::ROLE_ADMIN, self::ROLE_USER, self::ROLE_GUEST])) {
            throw new InvalidArgumentException("Invalid role: $role");
        }
        $this->role = $role;
    }
}