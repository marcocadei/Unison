<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpotifyController extends Controller
{
    // Funzione che si occupa di recuperare un token necessario per autenticarsi
    // nell'effettuare le richieste alle API di Spotify
    // I parametri utilizzati per ottenere il token sono il client_id e il client_secret
    // che sono stati ottenuti registrandosi come sviluppatori sul sito di Spotify
    public function token(){
        $client_id = 'd310fd518ac44b1287561bf297091271';
        $client_secret = 'e1285ca811a14013905cf827625c27ed';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,            'https://accounts.spotify.com/api/token' );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_POST,           1 );
        curl_setopt($ch, CURLOPT_POSTFIELDS,     'grant_type=client_credentials' );
        curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Authorization: Basic '.base64_encode($client_id.':'.$client_secret)));

        $result=curl_exec($ch);
        $response = json_decode($result, true);
        $token = $response['access_token'];

        return response()->json(['result' => $token]);
    }
}
