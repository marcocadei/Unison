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

}
