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
        $tracksToReturn = Track::matchesUserID($userID);
        if (!$includePrivateTracks) {
            $tracksToReturn = $tracksToReturn->notPrivate();
        }
        return $tracksToReturn->limit(50)->get();
    }

}
