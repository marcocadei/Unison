<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Track;
use App\Like;
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

    public function feed() {
        return redirect("/home");
    }

    public function update() {
        // Chiamato da ajax restituisce json
        $user = User::find(auth()->user()->id);
        $user->username = request('usernameMod');
        $user->email = request('emailMod');
        if (!is_null(request('passwordMod'))) {
            $user->password = md5(request('passwordMod'));
        }
        // Controllo che il campo per il caricamento dell'immagine non sia stato lasciato vuoto, altrimenti non faccio nulla
        if(request('photoMod') != null) {
            $profilePicFormat = explode(".", request('photoMod')->getClientOriginalName());
            $profilePicFormat = end($profilePicFormat);
            $folder = 'public/profilepics/';
            $fileName = $user->id . "_" . time() . "." . $profilePicFormat;
            $user->profile_pic = $folder . $fileName;

            // Carico l'immagine sul server
            Storage::putFileAs($folder, request()->file('photoMod'), $fileName);
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

    public function delete() {
        $user = User::find(auth()->user()->id);

        auth()->logout();

        $user->delete();

        return redirect('login');
    }

    public function follow() {
        $following = new Following;

        $followerId = auth()->user()->id;
//        $followed = User::where('username', '=', request('followed'))->first();
        $followedId = request('targetId');

        $following->follower = $followerId;
        $following->followed = $followedId;

        $following->save();

        return response()->json(['result' => true]);
    }

    public function unfollow() {

        $unfollowerId = auth()->user()->id;
//        $unfollowedId = User::where('username', '=', request('unfollowed'))->first();
        $unfollowedId = request('targetId');

        $following = Following::where('follower', '=', $unfollowerId)
                        ->where('followed', '=', $unfollowedId)->first();

        $following->delete();

        return response()->json(['result' => true]);
    }

    public function toggleLike() {

        $likerId = auth()->user()->id;
        $trackId = request('liked');

        $result = Like::where('user_id', '=', $likerId)
                    ->where('track', '=', $trackId)->exists();

        if ($result) {
            $like = Like::where('user_id', '=', $likerId)
                        ->where('track', '=', $trackId)->first();

            $like->delete();
        }
        else {
            $like = new Like;

            $like->user_id = $likerId;
            $like->track = $trackId;

            $like->save();
        }

        return response()->json(['result' => true]);
    }

    public function followedList(){
        $followed = Following::getFollowed(request('id'));

        $followedList = array();
        foreach($followed as $user){
            $userInfo = array(
                "id"=>$user->followed,
                "name"=>$user->username
            );
            array_push($followedList, $userInfo);
        }

        return response()->json(['result' => $followedList]);
    }

    public function followerList(){
        $followers = Following::getFollower(request('id'));

        $followerList = array();
        foreach($followers as $user){
            $userInfo = array(
                "id"=>$user->follower,
                "name"=>$user->username
            );
            array_push($followerList, $userInfo);
        }

        return response()->json(['result' => $followerList]);
    }
}
