<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Following;

class Track extends Model
{

    public function scopeDownloadable($query) {
        return $query->where('dl_enabled', true);
    }

    public function scopeNotPrivate($query) {
        return $query->where('private', false);
    }

    public function scopeMatchesUserID($query, $userID) {
        return $query->where('uploader', $userID);
    }

    /**
     * @var int Numero (massimo) di tracce restituite da una singola interrogazione al database.
     */
    private static $chunkSize = 50;

//    FIXME - Solo debug - Poi togliere!
    public static function getAllTracks() {
        return Track::all();
    }

//    FIXME - Solo debug - Poi togliere!
    public static function getAllNonPrivateTracks() {
        return Track::notPrivate()->get();
    }

// TODO Tutte le funzioni getTracksQualcosa() devono poi avere agganciato un ->limit(XX)
// per impedire che ne vengano caricate chissà quante, le chiamate successive devono poi avere un ->offset(XX)

    public static function getTracksByUser($userID, $includePrivateTracks) {
        /*
         * Recupera tutte le tracce dell'utente specificato, incluse quelle private.
         */
        $tracksToReturn = Track::matchesUserID($userID);
        if (!$includePrivateTracks) {
            /*
             * Esclude le tracce private.
             */
            $tracksToReturn = $tracksToReturn->notPrivate();
        }
        /*
         * Nota: Le tracce sono ordinate in ordine cronologico inverso (da quella caricata più di recente a quella più
         * vecchia); nel caso in cui ci fossero due tracce caricate esattamente nello stesso istante (cioè aventi lo
         * stesso valore per il campo "created_at") queste risultano ordinate per ID.
         */
        return $tracksToReturn
            ->orderByDesc('created_at')
            ->limit(Track::$chunkSize)
            ->get();
    }

    /**
     * Restituisce le tracce del feed dell'utente specificato, ovvero le tracce caricate da utenti seguiti da quello
     * indicato, in ordine cronologico inverso.
     * @param $userID integer Utente di cui caricare il feed.
     */
    public static function getFeedTracks($userID) {
        return Following::matchesFollower($userID)
            ->join('tracks', 'tracks.uploader', '=', 'followings.followed')
            ->where('private', false)
            ->orderByDesc('tracks.created_at')
            ->offset(0)
            ->limit(Track::$chunkSize)
            ->get();
    }

    /**
     * Restituisce le tracce più ascoltate (cioè le prime ordinate per numero di riproduzioni decrescente).
     */
    public static function getTopTracks() {
        return Track::notPrivate()
            ->orderByDesc('plays')
            ->limit(Track::$chunkSize)
            ->get();
    }

    /**
     * Restituisce le tracce il cui titolo contiene la stringa specificata.
     * @param $queryString string Stringa da cercare nel titolo delle tracce presenti nel database.
     */
    public static function getSearchedTracks($queryString) {
        return Track::notPrivate()
            ->where('title', 'LIKE', '%' . $queryString . '%')
            ->limit(Track::$chunkSize)
            ->get();
    }

}
