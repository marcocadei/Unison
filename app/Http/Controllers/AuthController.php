<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class AuthController extends Controller
{
    /**
     * AuthController constructor
     * Questo metodo mi consente di far sì che solo gli utenti non autenticati (guest) possano
     * accedere a queste funzioni, fatta eccezione per quella di logout
     */
    public function __construct(){
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *          Ridirige l'utente alla pagina di login
     */
    public function create(){
        // In base all' URL specificata imposto delle variabili che servono per attivare
        // il tab corretto nella vista
        // Se si specifica '/login' verrà aperto il tab per fare il login
        // Se si specifica '/register' verrà aperto il tab per effettuare la registrazione
        $path = request()->path();
        $activeLogin = 'active show';
        $activeRegister = '';
        if ($path == 'register'){
            $activeRegister = $activeLogin;
            $activeLogin = '';
        }
        return view('login', compact(['activeLogin', 'activeRegister']));
    }

    /**
     * Controlla che le credenziali fornite siano quelle di un utente esistente
     * @return \Illuminate\Http\JsonResponse Il risultato, sottoforma di json, del risultato della
     *         verifica dei dati dell'utente.<br>
     *         <b>true</b> se l'utente esiste, <b>false</b> altrimenti
     *
     */
    public function checkUserCredentials(){
        $result = \DB::table('users')
                  ->where(function ($query) {
                    $query->where('username', '=', request('username'))
                          ->orWhere('email', '=', request('username'));
                  })
                  ->where('password', '=', request('password'))->exists();

        return response()->json(['result' => $result]);
    }

    /**
     * Controlla che le credenziali fornite NON coincidano con quelle di un utente esistente
     * @return \Illuminate\Http\JsonResponse Il risultato, sottoforma di json, del risultato della
     *         verifica dei dati dell'utente.<br>
     *         <b>true</b> se l'utente esiste, <b>false</b> altrimenti
     *
     */
    public function checkNewUserCredentials(){
        $result = \DB::table('users')
            ->where('username', '=', request('username'))
            ->orWhere('email', '=', request('email'))->exists();

        return response()->json(['result' => !$result]);
    }

    /**
     * Effettua il login dell'utente
     * @return \Illuminate\Http\RedirectResponse
     *          Ridirige l'utente alla home page
     */
    public function login(){
        // Devo controllare le credenziali dell'utente
        $user = User::where('username', '=', request('usernameSI'))
                ->orWhere('email', '=', request('usernameSI'))->first();

        // il secondo parametro viene usato per ricordarsi dell'utente fin quando esso non slogga manualmente
        auth()->login($user, false);

        return redirect()->home();
    }

    /**
     * Effettua il logout dell'utente attualmente autenticato
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     *          Ridirige l'utente alla pagina iniziale
     */
    public function logout(){
        auth()->logout();

        return redirect('/');
    }

    /**
     *  Crea l'utente a partire i dati ricevuti nella richiesta
     *  Lo salva nel database
     *  Effettua il login
     *  Lo ridirige alla home page
     */
    public function register(){
        $user = new User;

        $user->username = request('usernameSU');
        $user->email = request('emailSU');
        $user->password = md5(request('passwordSU'));

        $user->save();

        // il secondo parametro viene usato per ricordarsi dell'utente fin quando esso non slogga manualmente
        auth()->login($user, true);

        return redirect()->home();
    }
}
