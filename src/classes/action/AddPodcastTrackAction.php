<?php

namespace iutnc\deefy\action;

use getID3;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\exception\InvalidPropertyValueException;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\repository\DeefyRepository;

class AddPodcastTrackAction extends Action
{
    protected function get(): string
    {
        return <<<HTML
<div class="main-container mb-5 p-4 text-light rounded shadow">
    <h3 class="text-center mb-3">Gestion des informations de votre Piste</h3>
    <p>
        Sur notre site, nous vous offrons une grande flexibilité pour la gestion des informations associées aux morceaux de musique/Podcast. Vous avez le choix de remplir ou non les informations de chaque piste musicale, telles que le titre de l'album, l'année, le numéro de piste, l'auteur, etc.
    </p>
    <p>
        Si vous choisissez de les renseigner, les informations fournies seront enregistrées directement. Si vous préférez ne pas remplir certains champs, notre système complétera automatiquement les informations manquantes en récupérant les métadonnées du fichier musical si elles sont disponible. Ainsi, même en laissant des champs vides, vous bénéficierez d'un affichage cohérent et complet pour chaque piste.
    </p>
</div>
<div class="form-playlist mb-5">
    <form method="post" action="?action=add-track" enctype="multipart/form-data">
        <h1 class="h3 mb-3 fw-normal text-center">Ajouter un Podcast</h1>
        <input type="hidden" name="typeTrack" value="1">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="floatingNom1" name="titre" placeholder="Titre">
            <label for="floatingNom1">Titre</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="auteur" placeholder="Auteur">
            <label>Auteur</label>
        </div>
        <div class="form-floating mb-3">
            <input type="date" class="form-control" name="date" placeholder="Date de publication">
            <label>Date de publication</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" name="duree" placeholder="Durée (en secondes)">
            <label>Durée</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="genre" placeholder="Genre">
            <label>Genre</label>
        </div>
        <div>
            <input class="form-control" type="file" name="fichier" required>
        </div>
        <button class="btn btn-primary w-100 py-2" type="submit">Valider</button>
    </form>
</div>

<div class="form-playlist mb-5">
    <form method="post" action="?action=add-track" enctype="multipart/form-data">
        <h1 class="h3 mb-3 fw-normal text-center">Ajouter une Musique</h1>
        <input type="hidden" name="typeTrack" value="0">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="floatingNom2" name="titre" placeholder="Titre">
            <label for="floatingNom2">Titre</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="album" placeholder="Album">
            <label>Album</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" name="annee" placeholder="Année de sortie">
            <label>Année de sortie</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" name="numeroPiste" placeholder="Numéro de piste">
            <label>Numéro de piste</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="auteur" placeholder="Auteur">
            <label>Auteur</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" name="duree" placeholder="Durée (en secondes)">
            <label>Durée</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="genre" placeholder="Genre">
            <label>Genre</label>
        </div>
        <div>
            <input class="form-control" type="file" name="fichier" required>
        </div>
        <button class="btn btn-primary w-100 py-2" type="submit">Valider</button>
    </form>
</div>
HTML;
    }


    /**
     * @throws InvalidPropertyValueException
     */
    protected function post(): string
    {
        if (!isset($_SESSION["playlist"])) {
            return "Aucune playlist trouvée.";
        }

        $upload_dir = __DIR__ . "/../../../audio/";
        $filename = uniqid();
        $tmp = $_FILES['fichier']['tmp_name'];
        $playlist = unserialize($_SESSION["playlist"]);

        // Vérification des erreurs de téléchargement
        if ($_FILES['fichier']['error'] !== UPLOAD_ERR_OK) {
            return "Upload du fichier échoué : " . $this->getUploadErrorMessage($_FILES['fichier']['error']);
        }

        // Vérification du type de fichier
        $extension = strtolower(pathinfo($_FILES['fichier']['name'], PATHINFO_EXTENSION));
        if ($extension !== 'mp3' && $_FILES['fichier']['type'] !== 'audio/mpeg') {
            return "Upload du fichier échoué : type non autorisé.";
        }

        // Déplacement du fichier
        $dest = $upload_dir . $filename . '.mp3';
        if (!move_uploaded_file($tmp, $dest)) {
            return "Upload du fichier échoué : impossible de déplacer le fichier.";
        }

        // Extraction des métadonnées
        $getID3 = new getID3;
        $infoFichier = $getID3->analyze($dest);
        if (isset($infoFichier['error'])) {
            return 'Erreur lors de l\'analyse du fichier : ' . implode(', ', $infoFichier['error']);
        }

        // Validation et nettoyage des données
        $titre = filter_var(trim($_POST['titre'] ?? ''), FILTER_SANITIZE_SPECIAL_CHARS);

        if($titre === ''){
            $titre = $infoFichier['tags']['id3v2']['title'][0] ?? 'Titre non disponible';
        }

        $typeTrack = filter_var($_POST['typeTrack'], FILTER_VALIDATE_INT);
        if ($titre === false || $typeTrack === false) {
            return "Données invalides fournies.";
        }

// Récupération et validation des valeurs soumises via le formulaire
        $auteur = filter_var(trim($_POST['auteur'] ?? ''), FILTER_SANITIZE_SPECIAL_CHARS);
        if ($auteur === '') {
            $auteur = $infoFichier['tags']['id3v2']['artist'][0] ?? 'Artiste non disponible';
        }

        $date = filter_var(trim($_POST['date'] ?? ''), FILTER_SANITIZE_SPECIAL_CHARS);
        if ($date === '') {
            $date = $infoFichier['tags']['id3v2']['year'][0] ?? '';
        }

        $duree = filter_var(trim($_POST['duree'] ?? ''), FILTER_VALIDATE_INT);
        if ($duree === false) {
            $duree = $this->convertionDureeEnSeconde($infoFichier['playtime_string'] ?? '0:00');
        }

        $genre = filter_var(trim($_POST['genre'] ?? ''), FILTER_SANITIZE_SPECIAL_CHARS);
        if ($genre === '') {
            $genre = $infoFichier['tags']['id3v2']['genre'][0] ?? 'Genre inconnu';
        }

        // Création de la piste
        try {
            $track = $this->creationTrack($typeTrack, $titre, $filename, $infoFichier, $auteur, $date, $duree, $genre);

            $repository = DeefyRepository::getInstance();
            $track = $repository->saveTrack($track);

            $playlist->ajouterPiste($track);
            $repository->addTrackToPlaylist($playlist->id, $track->id);
            $_SESSION["playlist"] = serialize($playlist);

            $render = new AudioListRenderer($playlist);
            $res = $render->render();
            return <<<HTML
<div class="playlist-container">
        <div class="alert alert-success mt-3 text-center" role="alert">
            Upload du fichier réussi
        </div>
$res
    <div class="text-center mt-4">
        <a href="?action=add-track" class="btn btn-primary">Ajouter une piste</a>
    </div>
</div>
HTML;
        } catch (InvalidPropertyValueException $e) {
            return $this->errorMessage("Erreur lors de la création de la piste : " . $e->getMessage());
        }
    }

    /**
     * @throws InvalidPropertyValueException
     */
    private function creationTrack(int $typeTrack, string $titre, string $filename, array $infoFichier, string $auteur, string $date, int $duree, string $genre): AudioTrack
    {
        $url = $filename . ".mp3";

        if ($typeTrack === 1) { // Podcast
            return new PodcastTrack($titre, $url, $auteur, $date, $duree, $genre);
        } else { // Album
            $titreAlbum = filter_var(trim($_POST['album'] ?? ''), FILTER_SANITIZE_SPECIAL_CHARS);
            if ($titreAlbum === '') {
                $titreAlbum = $infoFichier['tags']['id3v2']['album'][0] ?? 'Titre inconnu';
            }

            $annee = filter_var(trim($_POST['annee'] ?? ''), FILTER_SANITIZE_SPECIAL_CHARS);
            if ($annee === '') {
                $annee = $infoFichier['tags']['id3v2']['year'][0] ?? null; // Ou une autre valeur par défaut appropriée
            }

// Gestion du numéro de piste
            $num = filter_var(trim($_POST['numeroPiste'] ?? ''), FILTER_VALIDATE_INT);
            if ($num === false) {
                $num = $infoFichier['tags']['id3v2']['track_number'][0] ?? null; // Ou 0 ou une autre valeur par défaut
            }
            $track = new AlbumTrack($titre, $url, $titreAlbum, $annee, $num);
            $track->setGenre($genre);
            $track->setDuree($duree);
            $track->setAuteur($auteur);
            return $track;
        }
    }

    /**
    /**
     * Convertit une durée au format "MM:SS" ou "HH:MM:SS" en secondes.
     *
     * @param string $duration La durée au format "MM:SS" ou "HH:MM:SS".
     * @return int La durée en secondes.
     */
    private function convertionDureeEnSeconde(string $duration): int
    {
        $parties = explode(':', $duration);
        $secondes = 0;

        if (count($parties) === 2) {
            $minutes = (int)$parties[0];
            $secondes = (int)$parties[1] + ($minutes * 60);
        } elseif (count($parties) === 3) {
            $heures = (int)$parties[0];
            $minutes = (int)$parties[1];
            $secondes = (int)$parties[2] + ($minutes * 60) + ($heures * 3600);
        }

        return $secondes;
    }

    /**
     * Retourne un message d'erreur basé sur le code d'erreur du téléchargement.
     */
    private function getUploadErrorMessage(int $errorCode): string
    {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
                return "Le fichier dépasse la taille autorisée par le serveur.";
            case UPLOAD_ERR_FORM_SIZE:
                return "Le fichier dépasse la taille autorisée par le formulaire.";
            case UPLOAD_ERR_PARTIAL:
                return "Le fichier a été partiellement téléchargé.";
            case UPLOAD_ERR_NO_FILE:
                return "Aucun fichier n'a été téléchargé.";
            case UPLOAD_ERR_NO_TMP_DIR:
                return "Le dossier temporaire est manquant.";
            case UPLOAD_ERR_CANT_WRITE:
                return "Erreur lors de l'écriture du fichier.";
            case UPLOAD_ERR_EXTENSION:
                return "Erreur due à une extension PHP.";
            default:
                return "Erreur inconnue lors du téléchargement.";
        }
    }
}
