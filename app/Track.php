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

    public function scopeMatchesID($query, $trackID) {
        return $query->where('id', $trackID);
    }

    public function scopeExtractChunk($query, $offset = 0) {
        return $query->offset($offset * Track::$chunkSize)->limit(Track::$chunkSize);
    }

    /**
     * @var int Numero (massimo) di tracce restituite da una singola interrogazione al database.
     */
    public static $chunkSize = 10;

    /**
     * @var int Numero (massimo) di tracce restituite per l'interrogazione relativa alle top tracks.
     */
    public static $topTracksChunkSize = 50;

    /**
     * Restituisce tutte le tracce dell'utente specificato, in ordine cronologico inverso.
     * @param $userID integer ID dell'utente.
     * @param $includePrivateTracks boolean Indica se includere o meno le tracce private.
     */
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
            ->orderByDesc('created_at');
    }

    /**
     * Restituisce le tracce del feed dell'utente specificato, ovvero le tracce caricate da utenti seguiti da quello
     * indicato, in ordine cronologico inverso.
     * @param $userID integer Utente di cui caricare il feed.
     */
    public static function getFeedTracks($userID) {
        return Track::notPrivate()
            ->join('followings', 'tracks.uploader', '=', 'followings.followed')
            ->where('followings.follower', $userID)
            ->select('tracks.*')
            ->orderByDesc('tracks.created_at');
    }

    /**
     * Restituisce le tracce più ascoltate (cioè le prime ordinate per numero di riproduzioni decrescente).
     */
    public static function getTopTracks() {
        return Track::notPrivate()
            ->orderByDesc('plays')
            ->limit(Track::$topTracksChunkSize);
    }

    /**
     * Restituisce le tracce il cui titolo contiene la stringa specificata.
     * @param $queryString string Stringa da cercare nel titolo delle tracce presenti nel database.
     */
    public static function getSearchedTracks($queryString) {
        return Track::notPrivate()
            ->where('title', 'LIKE', '%' . $queryString . '%');
    }

}
