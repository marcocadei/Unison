@extends('layouts.layout')

@section('title')
    Entra in Unison
@endsection

@section('content')
    <div class="jumbotron jumbotron-fluid text-center bgLogin mt-5">
        <div class="container text-light">
            <h1 class="display-4 boldText">Entra su Unison</h1>
            <div class="d-none d-md-block">
                <p class="lead">Connettiti con migliaia di persone<br>Scopri, ascolta e condividi i brani più di tendenza</p>
            </div>
        </div>
    </div>
    <div class="container h-100 mb-5">
        <div class="row h-100 justify-content-center">
            <!-- Aggiungere al div anche la classe "align-items-center" se si vuole che l'immagine sia centrata
            anche rispetto al verticale -->
            <div class="col-9 order-last col-md-6 order-md-first text-center">
                <img src="{{asset('images/tape.png')}}" alt="Non perdere altro tempo: iscriviti subito!" class="loginImage img-fluid mt-5 mt-md-0">
            </div>
            <div class="col-12 col-md-6">
                <ul class="nav nav-tabs" id="tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{$activeLogin}}" id="signInTab" data-toggle="tab" href="#signInContent" role="tab" aria-controls="signInContent" aria-selected="{{ json_encode($activeRegister == '') }}">Accedi</a>
                        {{-- Nota: Viene utilizzato json_encode dal momento che print(variabileBooleana) stampa '0' oppure '1' mentre qui serve 'false' o 'true' --}}
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$activeRegister}}" id="signUpTab" data-toggle="tab" href="#signUpContent" role="tab" aria-controls="signUpContent" aria-selected="{{ json_encode($activeLogin == '') }}">Iscriviti</a>
                    </li>
                </ul>
                <div class="tab-content" id="tabContent">
                    <div class="tab-pane fade {{$activeLogin}} bg-white border-bottom border-left border-right" id="signInContent" role="tabpanel" aria-labelledby="signInTab">
                        <form class="p-5" action="{{route('login')}}" method="post" id="SI">
                            {{ csrf_field() }}
                            <input type="hidden" class="form-control" id="formSI">
                            <div class="form-group">
                                <label for="usernameSI">Username o email:</label>
                                <input type="text" class="form-control" id="usernameSI" name="usernameSI" placeholder="Inserisci username o email...">
                                {{--<div class="invalid-feedback">--}}
                                    {{--Per favore specifica uno username o un'e-mail valida (lunghezza massima consentita 64 caratteri, solo caratteri ASCII stampabili e lettere accentate).--}}
                                {{--</div>--}}
                                <div class="invalid-feedback">
                                    Per favore inserisi il tuo username o email
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="passwordSI">Password:</label>
                                <input type="password" class="form-control" id="passwordSI" name="passwordSI" placeholder="Inserisci password...">
                                {{--<div class="invalid-feedback">--}}
                                    {{--Per favore specifica una password valida (lunghezza massima consentita 64 caratteri, solo caratteri ASCII stampabili).--}}
                                {{--</div>--}}
                                <div class="invalid-feedback">
                                    Per favore inserisi la tua password
                                </div>
                            </div>
                            <div class="invalid-feedback border border-danger text-center p-1">
                                Nome utente o password non validi.
                            </div>
                            <button type="submit" class="btn btn-block btn-primary mt-4" id="buttonSI">Accedi</button>
                        </form>
                    </div>
                    <div class="tab-pane fade {{$activeRegister}} bg-white border-bottom border-left border-right" id="signUpContent" role="tabpanel" aria-labelledby="signUpTab">
                        <form class="p-5" action="{{ route("registration") }}" method="post" id="SU">
                            {{ csrf_field() }}
                            <input type="hidden" class="form-control" id="formSU">
                            <div class="form-group">
                                <label for="emailSU">Email:</label>
                                <input type="text" class="form-control" id="emailSU" name="emailSU" placeholder="Inserisci email..." aria-describedby="emailHelpBlock">
                                <small id="emailHelpBlock" class="form-text text-muted">
                                    Per favore inserisci una mail valida. Esempio: mariorossi@libero.it
                                </small>
                                <div class="invalid-feedback">
                                    La mail inserita non rispetta alcune delle indicazioni fornite
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="usernameSU">Username:</label>
                                <input type="text" class="form-control" id="usernameSU" name="usernameSU" placeholder="Inserisci username..." aria-describedby="usernameHelpBlock">
                                <small id="usernameHelpBlock" class="form-text text-muted">
                                    Per favore inserisci uno username valido. Sono ammesse lettere da A a Z (maiuscole e minuscole), lettere accentate, numeri da 0 a 9 e punteggiatura.
                                </small>
                                <div class="invalid-feedback">
                                    Lo username inserito non rispetta alcune delle indicazioni fornite
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="passwordSU">Password:</label>
                                <input type="password" class="form-control" id="passwordSU" name="passwordSU" placeholder="Inserisci password..." aria-describedby="passwordHelpBlock">
                                <small id="passwordHelpBlock" class="form-text text-muted">
                                    Per favore inserisci una password valida. La password deve contenere almeno 8 caratteri, con una lettera minuscola, una lettera maiuscola, un numero e un simbolo di punteggiatura.
                                </small>
                                <div class="invalid-feedback">
                                    La password inserita non rispetta alcune delle indicazioni fornite
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="repeatPasswordSU">Ripeti password:</label>
                                <input type="password" class="form-control" id="repeatPasswordSU" name="repeatPasswordSU" placeholder="Reinserisci password..." aria-describedby="repeatPasswordHelpBlock">
                                <small id="repeatPasswordHelpBlock" class="form-text text-muted">
                                    Per favore reinserisci la stessa password.
                                </small>
                                <div class="invalid-feedback">
                                    La password non corrisponde a quella inserita nel campo precedente.
                                </div>
                            </div>
                            <div class="invalid-feedback border border-danger text-center p-1">
                                È già presente un utente con quel nome utente o con quella e-mail.
                            </div>
                            <button type="submit" class="btn btn-block btn-primary mt-4" id="buttonSU">Registra account</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script_footer')
    <!-- Altri script nostri -->
    <!-- Script che effettuano i controlli sulle form di signIn e signUp e le chiamate
         al server utilizzando Ajax -->
    <script type="text/javascript" src="{{asset('js/signInCheck.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/signUpCheck.js')}}"></script>

    <!-- Script che implementa MD5 per evitare di mandare la password al server in chiaro -->
    <script type="text/javascript" src="{{asset('js/libs/md5.js')}}"></script>
@endsection