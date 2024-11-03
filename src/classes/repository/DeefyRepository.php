<?php

namespace iutnc\deefy\repository;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\exception\AuthException;
use iutnc\deefy\exception\PlaylistNotFoundException;
use iutnc\deefy\users\User;

/**
 * Class DeefyRepository
 *
 * Classe de gestion des opérations de données pour Deefy.
 */
class DeefyRepository
{
    private \PDO $pdo;
    private static ?DeefyRepository $instance = null;
    private static array $config = [];

    /**
     * DeefyRepository constructor.
     *
     * @param array $conf Configuration pour la connexion à la base de données.
     */
    private function __construct(array $conf)
    {
        $this->pdo = new \PDO($conf['dsn'], $conf['user'], $conf['pass'],
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    }

    /**
     * Définit la configuration de la base de données.
     *
     * @param string $file Chemin vers le fichier de configuration.
     * @throws \Exception Si le fichier de configuration ne peut pas être lu.
     */
    public static function setConfig(string $file)
    {
        $conf = parse_ini_file($file);
        if ($conf === false) {
            throw new \Exception("Error reading configuration file.");
        }
        self::$config = $conf;
    }

    /**
     * Obtient l'instance unique de DeefyRepository.
     *
     * @return DeefyRepository
     */
    public static function getInstance() : DeefyRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new DeefyRepository(self::$config);
        }
        return self::$instance;
    }

    /**
     * Trouve une playlist par son identifiant.
     *
     * @param int $id Identifiant de la playlist.
     * @return Playlist
     * @throws PlaylistNotFoundException Si la playlist n'est pas trouvée.
     */
    public function findPlaylistById(int $id): Playlist
    {
        $query = "SELECT id, nom FROM playlist WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        if ($data) {
            $listeTrack = $this->findTrack2Playlist($id);

            return new Playlist($data['nom'], $data['id'], $listeTrack);
        } else {
            throw new PlaylistNotFoundException("Playlist introuvable");
        }
    }

    /**
     * Trouve les pistes d'une playlist.
     *
     * @param int $idPlaylist Identifiant de la playlist.
     * @return array Liste des pistes.
     */
    public function findTrack2Playlist(int $idPlaylist): array
    {
        $query = "SELECT * FROM playlist2track pt INNER JOIN track t ON pt.id_track = t.id WHERE pt.id_pl = :id ORDER BY no_piste_dans_liste";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $idPlaylist]);
        $listTracks = [];

        while ($data = $stmt->fetch()) {
            if ($data['type'] === 'A') {
                $track = new AlbumTrack(
                    $data['titre'],
                    $data['filename'],
                    $data['titre_album'],
                    $data['annee_album'],
                    $data['no_piste_dans_liste'],
                    $data['artiste_album'],
                    $data['duree'],
                    $data['genre'],
                    $data['id']
                );
            } else {
                $track = new PodcastTrack(
                    $data['titre'],
                    $data['filename'],
                    $data['auteur_podcast'],
                    $data['date_posdcast'],
                    $data['duree'],
                    $data['genre'],
                    $data['id_track']
                );
            }
            $listTracks[] = $track;
        }
        return $listTracks;
    }

    /**
     * Enregistre une playlist vide.
     *
     * @param Playlist $pk La playlist à enregistrer.
     * @return Playlist La playlist enregistrée.
     */
    public function saveEmptyPlaylist(Playlist $pk): Playlist
    {
        $query = "INSERT INTO playlist (nom) VALUES (:nom)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['nom' => $pk->nom]);
        $pk->setId($this->pdo->lastInsertId());
        return $pk;
    }

    public function assignPlaylite2User(int $id_user,int $id_pl)
    {
        $query = "INSERT INTO user2playlist (id_user, id_pl) VALUES (:id_user,:id_playlist)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id_user' => $id_user,'id_playlist' => $id_pl]);

    }

    /**
     * Enregistre une piste audio.
     *
     * @param AudioTrack $track La piste audio à enregistrer.
     * @return AudioTrack La piste audio enregistrée.
     */
    public function saveTrack(AudioTrack $track) : AudioTrack
    {

        $query = "";
        $params = [
            'titre' => $track->titre,
            'genre' => $track->genre,
            'duree' => $track->duree,
            'filename' => $track->nomFichier,
        ];
        if ($track->getType() === "AlbumTrack") {
            $query = "INSERT INTO track(titre, genre, duree, filename, type, artiste_album, titre_album, annee_album, numero_album) 
                  VALUES (:titre, :genre, :duree, :filename, 'A', :artiste_album, :titre_album, :annee_album, :numero_album)";
            $params = array_merge($params, [
                'artiste_album' => $track->auteur,
                'titre_album' => $track->album,
                'annee_album' => $track->annee,
                'numero_album' => $track->numeroPiste,
            ]);
        } elseif ($track->getType() === 'PodcastTrack') {
            $query = "INSERT INTO track(titre, genre, duree, filename, type, auteur_podcast, date_posdcast) 
                  VALUES (:titre, :genre, :duree, :filename, 'P', :auteur_podcast, STR_TO_DATE(:date_posdcast, '%Y-%m-%d'))";
            $params = array_merge($params, [
                'auteur_podcast' => $track->auteur,
                'date_posdcast' => $track->date,
            ]);
        }
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        $track->setId($this->pdo->lastInsertId());
        return $track;
    }

    /**
     * Liste toutes les playlists.
     *
     * @return array Liste des playlists.
     */
    public function listPlaylist(): array
    {
        $arrayPlaylist = [];

        $queryPlaylist = "SELECT p.id, p.nom FROM playlist p";
        $stmtPlaylist = $this->pdo->query($queryPlaylist);

        while ($data = $stmtPlaylist->fetch()) {
            $playlist = new Playlist($data["nom"]);
            $playlist->setId($data["id"]);
            $arrayPlaylist[] = $playlist;
        }
        return $arrayPlaylist;
    }


    /**
     * Liste toutes les playlists d'un user.
     *
     * @return array Liste des playlists d'un user.
     */
    public function listPlaylist2User(int $idUser): array
    {
        $arrayPlaylist = [];

        $queryPlaylist = "SELECT p.id, p.nom FROM user2playlist up inner join playlist p on up.id_pl = p.id WHERE id_user = :idUser";
        $stmtPlaylist = $this->pdo->prepare($queryPlaylist);
        $stmtPlaylist->execute(['idUser'=>$idUser]);
        while ($data = $stmtPlaylist->fetch()) {
            $playlist = new Playlist($data["nom"]);
            $playlist->setId($data["id"]);
            $arrayPlaylist[] = $playlist;
        }
        return $arrayPlaylist;
    }
    /**
     * Ajoute une piste à une playlist.
     *
     * @param int $id_pl Identifiant de la playlist.
     * @param int $id_track Identifiant de la piste.
     */
    public function addTrackToPlaylist(int $id_pl, int $id_track)
    {
        $queryNb = "SELECT id_pl FROM playlist2track WHERE id_pl = :id_pl";
        $stmtNb = $this->pdo->prepare($queryNb);
        $stmtNb->execute(["id_pl" => $id_pl]);
        $noTrack = $stmtNb->rowCount() + 1;
        $query = "INSERT INTO playlist2track(id_pl, id_track, no_piste_dans_liste) VALUES(:id_pl, :id_track, :no_piste_dans_liste)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id_pl' => $id_pl, 'id_track' => $id_track, 'no_piste_dans_liste' => $noTrack]);
    }

    /**
     * Trouve un utilisateur par son e-mail.
     *
     * @param string $email E-mail de l'utilisateur.
     * @return User
     * @throws AuthException Si l'utilisateur n'est pas trouvé.
     */
    public function getUser(string $email): User
    {
        $query = "SELECT id, passwd, role FROM User WHERE email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['email' => $email]);

        $data = $stmt->fetch();

        if ($data) {
            $user = new User($email, $data["passwd"], $data['role']);
            $user->setId($data['id']);
        } else {
            throw new AuthException("Auth error: invalid user");
        }

        return $user;
    }

    /**
     * Vérifie si un utilisateur existe déjà.
     *
     * @param string $email E-mail de l'utilisateur.
     * @return bool True si l'utilisateur existe, false sinon.
     */
    public function userExist(string $email): bool
    {
        $query = "SELECT email FROM User WHERE email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch();

        return $data !== false;
    }

    /**
     * Ajoute un nouvel utilisateur.
     *
     * @param string $email E-mail de l'utilisateur.
     * @param string $password Mot de passe de l'utilisateur.
     * @param int $role Rôle de l'utilisateur.
     * @throws AuthException Si l'utilisateur existe déjà.
     */
    public function addUser(string $email, string $password, int $role)
    {
        if (!$this->userExist($email)) {
            $query = "INSERT INTO User(email, passwd, role) VALUES (:email, :password, :role)";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['email' => $email, 'password' => $password, 'role' => $role]);
        } else {
            throw new AuthException("Un compte avec cet e-mail existe déjà. Veuillez utiliser un autre e-mail.");
        }
    }

    public function isPlaylistOwner(int $id_pl,int $id_user) : bool
    {
        $query = "SELECT * FROM user2playlist WHERE id_pl = :id_pl and id_user =:id_user";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id_pl'=>$id_pl,'id_user'=>$id_user]);
        if($data = $stmt->fetch()){

            return true;
        }
        return false;
    }
}