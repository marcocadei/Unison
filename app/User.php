<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function scopeMatchesID($query, $id) {
        return $query->where('id', $id);
    }

    public function scopeMatchesUsername($query, $username) {
        return $query->where('username', $username);
    }

    /**
     * Restituisce gli utenti il cui username contiene la stringa specificata.
     * @param $queryString string Stringa da cercare nell'username degli utenti presenti nel database.
     */
    public static function getSearchedUsers($queryString) {
        return User::where('username', 'LIKE', '%' . $queryString . '%')
            ->limit(50)
            ->get();
    }

}
