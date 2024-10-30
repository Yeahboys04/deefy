<?php

namespace iutnc\deefy\action;

use getID3;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\exception\InvalidPropertyValueException;

class AddPodcastTrackAction extends Action
{

    public function execute(): string
    {
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

    protected function get() :string
    {
        return <<<HTML
<div class="form-playlist">
    <form method="post" action="?action=add-track" enctype="multipart/form-data">
        <h1 class="h3 mb-3 fw-normal text-center">Ajouter une Track</h1>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="floatingNom" name="titre" placeholder="titre" required>
            <label for="floatingTitre">Titre</label>
        </div>
        
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="floatingDate" name="date" placeholder="date" required>
            <label for="floatingDate">Date du fichier</label>
        </div>
        
        <div>
            <input class="form-control" type="file" name="fichier">
        </div>
        <button class="btn btn-primary w-100 py-2" type="submit">Valider</button>
    </form>
</div>
HTML;
    }

    /**
     * @throws InvalidPropertyValueException
     */
    protected function post() : string
    {
        $message = "";
        if(isset($_SESSION["playlist"])){

            $upload_dir = __DIR__ . "/../../../audio/";

            $filename = uniqid();
            $tmp = $_FILES['fichier']['tmp_name'];
            $playlist = $_SESSION["playlist"];
            $message ="";
            if(($_FILES['fichier']['error'] === UPLOAD_ERR_OK) ){

                $extention = substr($_FILES['fichier']['name'],-4);
                if($extention === '.mp3' ||  $_FILES['fichier']['type'] === 'audio/mpeg'){
                    $dest = $upload_dir . $filename.'.mp3';
                    if(move_uploaded_file($tmp,$dest)){
                        $track = new PodcastTrack($_POST['titre'],"http://$this->hostname/audio/" . $filename . ".mp3",$_POST['date']);
                        $getID3 = new getID3;
                        $infoFichier = $getID3->analyze($dest);
                        if (isset($infoFichier['error'])) {
                            echo 'Erreur : ' . implode(', ', $infoFichier['error']);
                        } else {
                            $titre = $infoFichier['tags']['id3v2']['titre'][0] ?? 'Titre non disponible';
                            $duree = $infoFichier['playtime_string'] ?? 'Durée non disponible';
                            list($minutes, $secondes) = explode(":", $duree);
                            $totalSecondes = ($minutes * 60) + $secondes;
                            $duree = $totalSecondes;
                            $auteur = $infoFichier['tags']['id3v2']['artist'][0] ?? "Artiste non disponible";
                            $album = $infoFichier['tags']['id3v2']['artist'][0] ?? "Artiste non disponible";
                            $numPiste = $fileInfo['tags']['id3v2']['track_number'][0] ?? 'Numéro de piste inconnu';
                            $genre = $fileInfo['tags']['id3v2']['genre'][0] ?? 'Genre inconnu';
                            $date = $fileInfo['tags']['id3v2']['year'][0] ?? $fileInfo['tags']['id3v2']['date'][0] ?? 'Date inconnue';

                            $track->setDuree($duree);
                            $track->setAuteur($auteur);
                            $track->setGenre($genre);
                            $track->setDate($date);
                        }
                        $playlist->ajouterPiste($track);
                        $_SESSION["playlist"] = $playlist;
                        $message = "Upload du fichier réussi";
                    } else{
                        $message =  "Upload du fichier échoué";

                    }
                } else{
                    $message =  "Upload du fichier échoué : type non autorisé";
                }
            } else {
                $message = "Upload du fichier échoué : echec du téléchargement ";
            }

        }
        return  <<< HTML
                    <p>$message</p>
                    <a href="?action=add-track"> Ajouter encore une piste </a>
                HTML;
    }
}