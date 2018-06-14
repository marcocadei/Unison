<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{

    public function scopeMatchesTrackID($query, $trackID) {
        return $query->where('track', $trackID);
    }

    public function scopeMatchesUserID($query, $userID) {
        return $query->where('user_id', $userID);
    }

}
