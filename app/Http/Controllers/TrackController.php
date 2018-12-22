<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Track;
use App\User;
use App\Like;
use App\Following;
use App\Unison\GeneralUtils;
use Storage;

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
            $track->uploaderName = User::matchesID($track->uploader)->value('username');

            $duration = $track->duration;
            $duration_hours = GeneralUtils::formatNumberAsTwoDigits(floor($duration / 3600));
            $duration_mins = GeneralUtils::formatNumberAsTwoDigits(($duration % 3600) / 60);
            $duration_secs = GeneralUtils::formatNumberAsTwoDigits($duration % 60);

            $likesToBeDisplayed = GeneralUtils::formatNumberWithMultipliers($numberOfLikes);
            $playsToBeDisplayed = GeneralUtils::formatNumberWithMultipliers($track->plays);

            $songInfo = array(
                "id" => $track->id,
                "name" => $track->title,
                "artist" => $track->uploaderName,
                "artist_id" => $track->uploader,
                "url" => Storage::url($track->file),
                "description" => $track->description,
                "duration" => $duration,
                "duration_hours" => $duration_hours,
                "duration_mins" => $duration_mins,
                "duration_secs" => $duration_secs,
                "cover_art_url" => Storage::url($track->picture),
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

    /**
     * Costruisce, a partire da un insieme di record letti dalla tabella 'users' del database, l'array contenente
     * tutti i metadati utilizzati relativi a tali utenti. Questo metodo è utilizzato unicamente per la pagina di
     * ricerca utenti(vedi view tricol.searchusers).
     * @param $users array Insieme di record letti dalla tabella 'users' del database.
     * @return array Array contenente i metadati relativi agli utenti specificati nell'array in input.
     */
    private function buildUsersArrayFromQueryOutput($users) {
        $usersArray = array();

        foreach ($users as $user) {
            $userID = $user->id;

            // Seguaci dell'utente di cui viene visualizzata la pagina profilo.
            $numberOfFollowers = GeneralUtils::formatNumberWithMultipliers(Following::matchesFollowed($userID)->count());
            // Utenti seguiti da quello di cui viene visualizzata la pagina profilo.
            $numberOfFollowed = GeneralUtils::formatNumberWithMultipliers(Following::matchesFollower($userID)->count());
            // Numero totale di tracce caricate (incluse quelle private).
            $numberOfUploadedTracks = GeneralUtils::formatNumberWithMultipliers(Track::matchesUserID($userID)->count());

            $userInfo = array(
                "user_id" => $userID,
                "username" => $user->username,
                "profile_pic" => Storage::url($user->profile_pic),
                "followers" => $numberOfFollowers,
                "following" => $numberOfFollowed,
                "uploads" => $numberOfUploadedTracks
            );

            array_push($usersArray, $userInfo);
        }

        return $usersArray;
    }

    /**
     * Estrae, a partire dalle righe selezionate con una query sul database specificate in input, la porzione corretta
     * basandosi sul parametro 'page' della query string con cui è stato effettuato l'accesso alla pagina.
     * @param $queryResult array Porzione di tracce determinata in base al valore indicato per il parametro 'page'
     * nella query string (in assenza del quale si utilizza il valore 0).
     */
    private function getRightChunk($queryResult) {
        $page = request('page');
        $offset = ctype_digit($page) ? intval($page) : 0;
        return $queryResult->extractChunk($offset)->get();
    }

    public function upload() {
        // Restituisco una pagina che presenta un'interfaccia per poter caricare una canzone
        $username = auth()->user()->username;
        $userID = auth()->user()->id;
        $maxFileSize = ini_get('upload_max_filesize');
        return view('tracks.upload', compact(['username', 'maxFileSize', 'userID']));
    }

    public function store() {
        // Recupero la durata della canzone caricata
        $duration = request('duration');

        // Creo la traccia in modo tale da poterla salvare sul DB
        $track = new Track;

        $track->title = request('title');
        $track->description = request('description');
        $track->duration = $duration;
        // Do al file il nome del titolo -> NO! Potrebbero nascere dei problemi con caratteri come / sul FS
        // Chiamo la traccia come: userID_timestamp.formato
        $timestamp = time();
        $trackFormat = explode(".", request('trackSelect')->getClientOriginalName());
        $trackFormat = end($trackFormat);
        $track->file = 'public/tracks/'.request('userID')."_".$timestamp.".".$trackFormat;
        // La cover art per la track può non essere specificata
        $coverArtFormat = null;
        if (request('photoSelect') != null) {
            $coverArtFormat = explode(".", request('photoSelect')->getClientOriginalName());
            $coverArtFormat = end($coverArtFormat);
            $track->picture = 'public/trackthumbs/' .request('userID')."_".$timestamp.".".$coverArtFormat;
        }
        $track->uploader = auth()->user()->id;
        $track->dl_enabled = (request('allowDownload') ? 1 : 0);
        $track->private = (request('private') ? 1 : 0);
        $tmp = request('spotifyID');
        if (isset($tmp))
            $track->spotify_id = request('spotifyID');

        $track->save();

        // Carico i file (traccia e relativa copertina) e li memorizzo sul server
        Storage::putFileAs('public/tracks', request()->file('trackSelect'), request('userID')."_".$timestamp.".".$trackFormat);
        // La cover art per la track può non essere specificata
        if (request('photoSelect') != null) {
            Storage::putFileAs('public/trackthumbs', request()->file('photoSelect'), request('userID')."_".$timestamp.".".$coverArtFormat);
        }
        return redirect('/user/' .request('userID'));
    }

    public function editTrack($trackID) {

        $user = User::find(auth()->user()->id);
        $userID = $user->id;

        /*
         * Si controlla che il trackID specificato nell'URL sia composto di sole cifre da 0 a 9.
         */
        if (ctype_digit($trackID)) {
            /*
             * Prima di tutto viene verificata l'esistenza della traccia; se è stata indicato una traccia inesistente o
             * associata ad un altro utente, allora viene visualizzata una pagina d'errore.
             */
            $trackExists = Track::where('id', '=', $trackID)
                ->where('uploader', '=', $userID)->exists();
            if (!$trackExists) {
                return abort(404);
            }
        }
        else {
            return abort(404);
        }

        $trackRecord =  Track::where('id', '=', $trackID)->first();

        return view('tracks.modifytrack', compact(['trackRecord']));
    }

    public function updateTrack($trackID) {

        $user = User::find(auth()->user()->id);
        $userID = $user->id;

        /*
         * Si controlla che il trackID specificato nell'URL sia composto di sole cifre da 0 a 9.
         */
        if (ctype_digit($trackID)) {
            /*
             * Prima di tutto viene verificata l'esistenza della traccia; se è stata indicato una traccia inesistente o
             * associata ad un altro utente, allora viene visualizzata una pagina d'errore.
             */
            $trackExists = Track::where('id', '=', $trackID)
                ->where('uploader', '=', $userID)->exists();
            if (!$trackExists) {
                return abort(404);
            }
        }
        else {
            return abort(404);
        }

        /*
         * Record del database associato alla track di cui si vuole visualizzare la pagina profilo.
         */
        $trackRecord =  Track::where('id', '=', $trackID)->first();

        $trackRecord->title = request('title');
        $trackRecord->description = request('description');
        // Controllo che il campo per il caricamento dell'immagine non sia stato lasciato vuoto, altrimenti non faccio nulla
        if(request('photoMod') != null) {
            $coverArtFormat = explode(".", request('photoMod')->getClientOriginalName());
            $coverArtFormat = end($coverArtFormat);
            $trackRecord->picture = 'public/trackthumbs/' .request('userID')."_".time().".".$coverArtFormat;

            Storage::putFileAs('public/trackthumbs', request()->file('photoMod'), request('userID')."_".time().".".$coverArtFormat);
        }
        $trackRecord->dl_enabled = (request('allowDownload') ? 1 : 0);
        $trackRecord->private = (request('private') ? 1 : 0);


        $trackRecord->save();

        /*
         * Dopo aver eseguito la modifica del database viene ricaricata la stessa pagina; viene però impostata a true
         * la variabile di sessione "viewMod" in modo che al caricamento venga visualizzata la finestra modale che
         * conferma all'utente l'avvenuta applicazione delle modifiche.
         */
        return redirect()->route('modifyTrack', compact(['trackRecord']))->with('viewMod', true);
    }

    public function updateSpotifyTrackID($trackID) {
        $user = User::find(auth()->user()->id);
        $userID = $user->id;

        /*
         * Si controlla che il trackID specificato nell'URL sia composto di sole cifre da 0 a 9.
         */
        if (ctype_digit($trackID)) {
            /*
             * Prima di tutto viene verificata l'esistenza della traccia; se è stata indicato una traccia inesistente o
             * associata ad un altro utente, allora viene visualizzata una pagina d'errore.
             */
            $trackExists = Track::where('id', '=', $trackID)
                ->where('uploader', '=', $userID)->exists();
            if (!$trackExists) {
                return abort(404);
            }
        }
        else {
            return abort(404);
        }
        /*
         * Record del database associato alla track di cui si vuole visualizzare la pagina profilo.
         */
        $trackRecord =  Track::where('id', '=', $trackID)->first();
        $trackRecord->spotify_id = "0000000000000000000000";
        $trackRecord->save();
        /*
         * Dopo aver eseguito la modifica del database viene ricaricata la stessa pagina; viene però impostata a true
         * la variabile di sessione "viewModSpotifyID" in modo che al caricamento venga visualizzata la finestra modale che
         * conferma all'utente l'avvenuta applicazione delle modifiche.
         */
        return redirect()->route('modifyTrack', compact(['trackRecord']))->with('viewMod', true);
    }

    public function deleteTrack($trackID) {

        $track =  Track::where('id', '=', $trackID)->first();

        $track->delete();

        return redirect('/user/' . auth()->user()->id);
    }

    /*
     * Funzioni che restituiscono un JSON chiamate via AJAX.
     */

    public function updatePlayCount($trackID) {
        $track = Track::matchesID($trackID)->first();
        $playCount = $track->plays;
        $track->plays = $playCount + 1;
        $track->save();

        return response()->json(['result' => true]);
    }

    public function checkSongExistence(){
        $result = \DB::table('tracks')
            ->join('users', 'tracks.uploader', '=', 'users.id')
            ->where('users.id', '=', request('userID'))
            ->where('tracks.title', '=', request('title'))
            ->exists();

        return response()->json(['result' => !$result]);
    }

    /*
     * Di seguito i metodi che restituiscono le view vere e proprie.
     */

    /**
     * Restituisce la pagina profilo dell'utente specificato.
     */
    public function userProfile($userID) {
        /*
         * Si controlla che lo userID specificato nell'URL sia composto di sole cifre da 0 a 9.
         */
        if (ctype_digit($userID)) {
            /*
             * Prima di tutto viene verificata l'esistenza dell'utente; se è stato indicato un utente inesistente allora
             * viene visualizzata una pagina d'errore.
             */
            $userExists = User::matchesID($userID)->exists();
            if (!$userExists) {
                return abort(404);
            }
        }
        else {
            return abort(404);
        }

        /*
         * Record del database associato all'utente di cui si vuole visualizzare la pagina profilo.
         */
        $userRecord = User::matchesID($userID)->first();

        $sameAsLoggedUser = false;
        $followedByLoggedUser = false;
        if (auth()->check()) {
            $sameAsLoggedUser = (strcmp($userID, auth()->user()->id) == 0);
            if (!$sameAsLoggedUser) {
                $followedByLoggedUser = Following::matchesPair(auth()->user()->id, $userID)->exists();
            }
        }

        $tracks = Track::getTracksByUser($userID, $sameAsLoggedUser);
        $trackCount = $tracks->count();
        $songs = $this->buildJSONArrayFromQueryOutput($this->getRightChunk($tracks));

        // Seguaci dell'utente di cui viene visualizzata la pagina profilo.
        $numberOfFollowers = GeneralUtils::formatNumberWithMultipliers(Following::matchesFollowed($userID)->count());
        // Utenti seguiti da quello di cui viene visualizzata la pagina profilo.
        $numberOfFollowed = GeneralUtils::formatNumberWithMultipliers(Following::matchesFollower($userID)->count());
        // Numero totale di tracce caricate (incluse quelle private).
        $numberOfUploadedTracks = GeneralUtils::formatNumberWithMultipliers(Track::matchesUserID($userID)->count());

        $userInfo = array(
            "user_id" => $userID,
            "same_as_logged_user" => $sameAsLoggedUser,
            "followed_by_logged_user" => $followedByLoggedUser,
            "username" => $userRecord->username,
            "profile_pic" => Storage::url($userRecord->profile_pic),
            "bio" => $userRecord->bio,
            "followers" => $numberOfFollowers,
            "following" => $numberOfFollowed,
            "uploads" => $numberOfUploadedTracks
        );
        return view('tricol.userprofile', compact(['songs', 'trackCount', 'userInfo']));
    }

    /**
     * Restituisce il feed dell'utente attualmente loggato.
     */
    public function userFeed() {
        $tracks = Track::getFeedTracks(auth()->user()->id);
        $trackCount = $tracks->count();
        $songs = $this->buildJSONArrayFromQueryOutput($this->getRightChunk($tracks));
        return view('tricol.feed', compact(['songs', 'trackCount']));
    }

    /**
     * Restituisce la pagina con i brani più ascoltati di sempre.
     */
    public function top50() {
        $tracks = Track::getTopTracks();
        $trackCount = $tracks->count();
        $songs = $this->buildJSONArrayFromQueryOutput($tracks->get());
        return view('tricol.top50', compact(['songs', 'trackCount']));
    }

    /**
     * Restituisce la pagina con i risultati della ricerca.
     */
    public function search() {
        if (!request('searchInput')) {
            return abort(404);
        }

        /*
         * Rimozione di tutti i caratteri non-ASCII.
         */
        $queryString = preg_replace('/[^\x20-\x7E\xC0-\xFF]/u','', request('searchInput'));

        // Ricerca utenti
        if (request('searchSelect') == 1) {
            /*
             * Nota: Questo array vuoto è necessario poiché anche la pagina con i risultati della ricerca per utente
             * è una pagina "a tre colonne" e quindi si aspetta di avere un player audio. Eliminare questa variabile
             * causa un errore durante la costruzione della view.
             * NON TOGLIERE!
             */
            $songs = array();

            $users = User::getSearchedUsers($queryString);
            $users = $this->buildUsersArrayFromQueryOutput($users);
            return view('tricol.searchusers', compact(['users', 'songs', 'queryString']));
        }
        // Ricerca brani
        else {
            $tracks = Track::getSearchedTracks($queryString);
            $trackCount = $tracks->count();
            $songs = $this->buildJSONArrayFromQueryOutput($this->getRightChunk($tracks));
            return view('tricol.searchtracks', compact(['songs', 'trackCount', 'queryString']));
        }
    }

}
