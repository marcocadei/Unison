<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Track;
use App\User;
use App\Like;
use App\Following;
use App\Unison\GeneralUtils;

class TrackController extends Controller
{

    /**
     * Costruisce, a partire da un insieme di record letti dalla tabella 'tracks' del database, l'array contenente
     * tutti i metadati utilizzati relativi a tali tracce, che sarà poi passato all'oggetto JS Amplitude per
     * l'inizializzazione (vedi view tricol.elements.amplitudeinitializer) e che sarà utilizzato per costruire gli
     * elementi della pagina mostrati all'utente (vedi view tricol.elements.singleaudioelement).
     * @param $tracks array Insieme di record letti dalla tabella 'tracks' del database.
     * @return array Array contenente i metadati relativi alle tracce specificate nell'array in input.
     */
    private function buildJSONArrayFromQueryOutput($tracks) {
        $songs = array();
        foreach ($tracks as $track) {
            $trackLikes = Like::matchesTrackID($track->id);
            $numberOfLikes = $trackLikes->count('id');

            if (auth()->check()) {
                $currentUserID = auth()->user()->id;
            }
            $isLikedByCurrentUser = isset($currentUserID) ? $trackLikes->matchesUserID($currentUserID)->exists() : false;

            /*
             * Qui non viene eseguito alcun controllo su $track->uploader; si suppone che per via della presenza dei
             * vincoli di integrità referenziale all'interno del database l'uploader di una traccia sia sempre un
             * utente valido registrato nella tabella users.
             */
            $uploaderName = User::matchesID($track->uploader)->value('username');
            $track->uploader = $uploaderName;

            $duration = $track->duration;
            $duration_hours = GeneralUtils::formatNumberAsTwoDigits(floor($duration / 3600));
            $duration_mins = GeneralUtils::formatNumberAsTwoDigits(($duration % 3600) / 60);
            $duration_secs = GeneralUtils::formatNumberAsTwoDigits($duration % 60);

            $likesToBeDisplayed = GeneralUtils::formatNumberWithMultipliers($numberOfLikes);
            $playsToBeDisplayed = GeneralUtils::formatNumberWithMultipliers($track->plays);

            $songInfo = array(
                "name" => $track->title,
                "artist" => $track->uploader,
                "url" => asset($track->file),
                "duration" => $duration,
                "duration_hours" => $duration_hours,
                "duration_mins" => $duration_mins,
                "duration_secs" => $duration_secs,
                "cover_art_url" => asset($track->picture),
                "date" => $track->created_at,
                "plays" => $playsToBeDisplayed,
                "private" => $track->private,
                "dl_enabled" => $track->dl_enabled,
                "is_liked" => $isLikedByCurrentUser,
                "likes" => $likesToBeDisplayed,
                "spotify_id" => $track->spotify_id
            );

            array_push($songs, $songInfo);
        }

        return $songs;
    }

    /*
     * Di seguito i metodi che restituiscono le view vere e proprie.
     */

//    FIXME - Solo debug - Poi togliere!
    public function allTracks() {
        $tracks = Track::getAllTracks();
        $songs = $this->buildJSONArrayFromQueryOutput($tracks);
        return view('tricol.feed', compact(['songs']));
    }

    /**
     * Restituisce la pagina profilo dell'utente specificato.
     */
    public function userProfile($username) {
        /*
         * Prima di tutto viene verificata l'esistenza dell'utente; se è stato indicato un utente inesistente allora
         * viene visualizzata una pagina d'errore.
         */
        $userExists = User::matchesUsername($username)->exists();
        if (!$userExists) {
            return abort(404);
        }

        /*
         * Record del database associato all'utente di cui si vuole visualizzare la pagina profilo.
         */
        $userRecord = User::matchesUsername($username)->first();

        $sameAsLoggedUser = false;
        $followedByLoggedUser = false;
        if (auth()->check()) {
            $sameAsLoggedUser = (strcmp($username, auth()->user()->username) == 0);
            if (!$sameAsLoggedUser) {
                $followedByLoggedUser = Following::matchesPair(auth()->user()->id, $userRecord->id)->exists();
            }
        }

        $tracks = Track::getTracksByUser($userRecord->id, $sameAsLoggedUser);
        $songs = $this->buildJSONArrayFromQueryOutput($tracks);

        // Seguaci dell'utente di cui viene visualizzata la pagina profilo.
        $numberOfFollowers = GeneralUtils::formatNumberWithMultipliers(Following::matchesFollowed($userRecord->id)->count());
        // Utenti seguiti da quello di cui viene visualizzata la pagina profilo.
        $numberOfFollowed = GeneralUtils::formatNumberWithMultipliers(Following::matchesFollower($userRecord->id)->count());
        // Numero totale di tracce caricate (incluse quelle private).
        $numberOfUploadedTracks = GeneralUtils::formatNumberWithMultipliers(Track::matchesUserID($userRecord->id)->count());

        $userInfo = array(
            "same_as_logged_user" => $sameAsLoggedUser,
            "followed_by_logged_user" => $followedByLoggedUser,
            "username" => $userRecord->username,
            "profile_pic" => asset($userRecord->profile_pic),
            "bio" => $userRecord->bio,
            "followers" => $numberOfFollowers,
            "following" => $numberOfFollowed,
            "uploads" => $numberOfUploadedTracks
        );
        return view('tricol.userprofile', compact(['songs', 'userInfo']));
    }

}
