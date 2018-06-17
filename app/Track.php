<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        return $tracksToReturn->orderByDesc('created_at')->limit(50)->get();
    }

}
