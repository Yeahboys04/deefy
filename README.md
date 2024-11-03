# Projet Deefy

## Fonctionnalités

- **Mes Playlists** : Affiche la liste des playlists de l'utilisateur authentifié. Chaque élément est cliquable pour afficher la playlist correspondante, qui devient la playlist courante (stockée en session).
  
- **Créer une Playlist Vide** : Permet à l'utilisateur de créer une nouvelle playlist via un formulaire. La playlist devient la playlist courante après validation.
  
- **Afficher la Playlist Courante** : Affiche la playlist actuellement stockée en session.
  
- **S'inscrire** : Permet à l'utilisateur de créer un compte avec le rôle STANDARD.
  
- **S'authentifier** : Permet aux utilisateurs d'entrer leurs identifiants pour se connecter.

- **Ajout de Piste** : Lors de l'affichage d'une playlist, les utilisateurs peuvent ajouter une nouvelle piste. 
  - Un formulaire s'affiche pour saisir les détails de la piste.
  - Si certains champs ne sont pas remplis, les données seront récupérées à partir des métadonnées du fichier audio.
    - Si les métadonnées sont inexistantes pour un champ, une valeur par défaut sera attribuée à ce champ.
  - À la validation, la piste est créée, enregistrée dans la base de données et ajoutée à la playlist affichée.

## Script de Création de la Base de Données

- fichier create_database.sql

## Test de l'application
(Pour faciliter les tests, la sécurité du mot de passe n'est pas respectée pour les utilisateurs prédéfinis, Cependant, pour les comptes créés dans l'application, la sécurité du mot de passe est obligatoire.)

- **Utilisateur de test** : `user1@mail.com` (il y a 4 user, user[1-4])
- **Mot de passe** : `user1` (pour les autres user : user[numéro du user])

- **admin de test** : `admin@mail.com`
- **Mot de passe** : `admin`
