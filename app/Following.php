<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Following extends Model
{

    public function scopeMatchesPair($query, $follower, $followed) {
        return $query->where('follower', $follower)->where('followed', $followed);
    }

    public function scopeMatchesFollower($query, $follower) {
        return $query->where('follower', $follower);
    }

    public function scopeMatchesFollowed($query, $followed) {
        return $query->where('followed', $followed);
    }

}
