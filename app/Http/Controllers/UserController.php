<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Following;
use App\User;
use Storage;

class UserController extends Controller
{
    //FIXME verificare che si debba passare per il middleware (cioè verificare che l'utente sia loggato) per ogni azione del controller
    public function __construct() {
        $this->middleware('auth');
    }

    public function edit() {
        return view('modify');
    }

    public function update() {
        // Chiamato da ajax restituisce json
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

        /*
         * Dopo aver eseguito la modifica del database viene ricaricata la stessa pagina; viene però impostata a true
         * la variabile di sessione "viewMod" in modo che al caricamento venga visualizzata la finestra modale che
         * conferma all'utente l'avvenuta applicazione delle modifiche.
         */
        return redirect('modify')->with('viewMod', true);
    }

    public function follow() {
        $following = new Following;

        $followerId = auth()->user()->id;
//        $followed = User::where('username', '=', request('followed'))->first();
        $followedId = request('followed');

        $following->follower = $followerId;
        $following->followed = $followedId;

        $following->save();

        return response()->json(['result' => true]);
    }

    public function unfollow() {

        $unfollowerId = auth()->user()->id;
//        $unfollowedId = User::where('username', '=', request('unfollowed'))->first();
        $unfollowedId = request('unfollowed');

        $following = Following::where('follower', '=', $unfollowerId)
                        ->where('followed', '=', $unfollowedId)->first();

        $following->delete();

        return response()->json(['result' => true]);
    }
}
