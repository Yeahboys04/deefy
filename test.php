<?php

require_once  __DIR__ . '/vendor/autoload.php';
if (!file_exists(__DIR__ . '/config/deefy.db.ini')) {
    echo "Le fichier n'existe pas.";
}
try {
    \iutnc\deefy\repository\DeefyRepository::setConfig(__DIR__ . '/config/deefy.db.ini');
} catch (Exception $e){
    echo $e->getMessage();
}

$repo = \iutnc\deefy\repository\DeefyRepository::getInstance();

//$playlists = $repo->listPlaylist();
//foreach ($playlists as $pl) {
//    print "playlist  : " . $pl->nom . ":". $pl->id . "<br>";
//}
//
//
//$pl = new \iutnc\deefy\audio\lists\PlayList('test');
//$pl = $repo->saveEmptyPlaylist($pl);
//print "playlist  : " . $pl->nom . ":". $pl->id . "<br>";
//
//$track = new \iutnc\deefy\audio\tracks\PodcastTrack('test', 'test.mp3', 'auteur', '2021-01-01', 10, 'genre');
//$track = $repo->saveTrack($track);
//print "track 2 : " . $track->titre . ":". get_class($track). "<br>";
//$repo->addTrackToPlaylist($pl->id, $track->id);
try{
    \iutnc\deefy\auth\AuthnProvider::signin("user1@mail.com","user1");
    $user = unserialize( $_SESSION['user']);
    echo $user->email;
} catch (\iutnc\deefy\exception\AuthException $ex){
    echo $ex->getMessage();
}

;

