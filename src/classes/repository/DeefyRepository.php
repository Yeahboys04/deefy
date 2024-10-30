<?php

namespace iutnc\deefy\repository;


use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\exception\AuthException;
use iutnc\deefy\users\User;
use function MongoDB\BSON\toRelaxedExtendedJSON;

class DeefyRepository
{
    private \PDO $pdo;
    private static ?DeefyRepository $instance = null;
    private static array $config = [ ];

    private function __construct(array $conf) {
        $this->pdo = new \PDO($conf['dsn'], $conf['user'], $conf['pass'],
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    }
    public static function setConfig(string $file) {
        $conf = parse_ini_file($file);
        if ($conf === false) {
            throw new \Exception("Error reading configuration file: " );
        }
        self::$config = $conf;
    }


    public static function getInstance(){
        if (is_null(self::$instance)) {
            self::$instance = new DeefyRepository(self::$config);
        }
        return self::$instance;
    }


    public function saveEmptyPlaylist(PlayList $pk): PlayList
    {
        $query = "INSERT INTO playlist (nom) VALUES (:nom)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['nom' => $pk->nom]);
        $pk->setID($this->pdo->lastInsertId());
        return $pk;
    }

    public function saveTrack(AudioTrack $track)
    {
        $query = "";
        $params = [
            'titre' => $track->titre,
            'genre' => $track->genre,
            'duree' => $track->duree,
            'filename' => $track->nom_fichier,
        ];

        if ($track->getType() === "AlbumTrack") {
            $query = "INSERT INTO track(titre, genre, duree, filename, type, artiste_album, titre_album, annee_album, numero_album) 
                  VALUES (:titre, :genre, :duree, :filename, 'A', :artiste_album, :titre_album, :annee_album, :numero_album)";
            $params = array_merge($params, [
                'artiste_album' => $track->auteur,
                'titre_album' => $track->album,
                'annee_album' => $track->annee,
                'numero_album' => $track->numero_piste,
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

    public function listPlaylist() : array
    {
        $arrayPlaylist = array();

        $queryPlaylist = "SELECT p.id,p.nom FROM playlist p";
        $stmtPlaylist = $this->pdo->query($queryPlaylist);
        while ($data =  $stmtPlaylist->fetch()){
            $playlist = new Playlist($data["nom"]);
            $playlist->setId($data["id"]);
            $arrayPlaylist[] = $playlist;
        }
        return $arrayPlaylist;

    }

    public function addTrackToPlaylist(int $id_pl,int $id_track)
    {
        $queryNb = "SELECT id_pl FROM playlist2track WHERE id_pl = :id_pl";
        $stmtNb = $this->pdo->prepare($queryNb);
        $stmtNb->execute(["id_pl"=>$id_pl]);
        $noTrack = $stmtNb->rowCount() +1;
        $query = "INSERT INTO playlist2track(id_pl, id_track, no_piste_dans_liste) VALUES(:id_pl,:id_track,:no_piste_dans_liste)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id_pl'=>$id_pl,'id_track'=>$id_track,'no_piste_dans_liste'=>$noTrack]);


    }

    public function getUser($email) : User
    {
        $query = "select id,passwd,role from User where email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['email'=>$email]);

        $data = $stmt->fetch();
        if ($data) {
            $user = new User($email, $data["passwd"],$data['role']);
            $user->setId($data['id']);
        } else {
            throw new AuthException("Auth error: invalid user");
        }

        return $user;
    }

    public function userExist($email) : bool
    {
        $query= "SELECT email from User WHERE email=:email";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['email'=>$email]);
        $data = $stmt->fetch();

        return $data !== false;
    }

    public function addUser(string $email, string $password, int $role)
    {
        if(!$this->userExist($email)){
            $query = "INSERT INTO User(email, passwd,role) VALUES (:email,:password,:role)";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['email'=>$email,'password'=>$password,'role'=>$role]);
        } else{
            throw new AuthException("Un compte avec cet e-mail existe déjà. Veuillez utiliser un autre e-mail.");
        }
    }


}