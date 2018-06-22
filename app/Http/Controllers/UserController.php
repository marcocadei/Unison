<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

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
        $user->profile_pic= 'public/profilepics/'.request('photoMod')->getClientOriginalName()  ;
        $user->bio = request('bioMod');
        $user->save();

        // Carico l'immagine sul server
        request()->file('photoMod')->store('public/profilepics');

        // PROBABILMENTE DA TOGLIERE IN FUTURO, OPPURE BISOGNA MODIFICARE LA ROUTE
        return redirect('/modify');
    }
}
