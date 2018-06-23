<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Storage;

class UserController extends Controller
{
    //FIXME verificare che si debba passare per il middleware (cioè verificare che l'utente sia loggato) per ogni azione del controller
    public function __construct(){
        $this->middleware('auth');
    }

    public function edit(){
        return view('modify');
    }

    public function update(){
        //Chiamato da ajax restituisce json
        $user = User::find(auth()->user()->id);
        $user->username = request('usernameMod');
        $user->email = request('emailMod');
        $user->password = md5(request('passwordMod'));
        // Controllo che il campo per il caricamento dell'immagine non sia stato lasciato vuoto, altrimenti non faccio nulla
        if(request('photoMod') != null) {
            $user->profile_pic= 'public/profilepics/'.request('photoMod')->getClientOriginalName();
            // Carico l'immagine sul server
            Storage::putFileAs('public/profilepics', request()->file('photoMod'), request('photoMod')->getClientOriginalName());
        }
        $user->bio = request('bioMod');
        $user->save();


        // PROBABILMENTE DA TOGLIERE IN QUANTO VOGLIO FARE UNA COSA VIA AJAX, QUINDI DOVREBBE TORNARE JSON
        return redirect('/modify');
    }
}
