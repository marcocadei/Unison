@extends('layouts.layout')

@section('title')
    Modifica profilo
@endsection

@section('content')
    <br><br>
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
            <!-- Aggiungere al div anche la classe "align-items-center" se si vuole che l'immagine sia centrata
            anche rispetto al verticale -->
            <div class="col-sm-9 order-last col-md-3 order-md-first text-center">
                <img src="{{asset('images/settings.jpeg')}}" alt="Che aspetti? Effettua velocemente le modifiche così può tornare alla tua musica!" class="loginImage rounded-circle img-fluid mt-5 mt-md-0">
            </div>
            <div class="col-sm-12 col-md-9 border-left pl-3">
                <form class="p-5" action="{{'/modify'}}" method="post" id="mod" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{--<input type="hidden" class="form-control" id="formMod">
                    <div class="invalid-feedback">
                        Nome utente o email già utilizzati.
                    </div>--}}
                    <input type="hidden" class="form-control" id="formModI">
                    <div class="invalid-feedback">
                        Nome utente o email già utilizzati.
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-4 border border-primary">
                            <img class="img-fluid rounded-circle" src="{{Storage::url('public/profilepics/LgQ9uovyXRf7pLrlqXT2bOyHKvEKeFJPXImYNRNl.jpeg')}}"{{--src="{{Storage::url(auth()->user()->profile_pic)}}"--}}>
                        </div>
                        {{Storage::url('public/profilepics/LgQ9uovyXRf7pLrlqXT2bOyHKvEKeFJPXImYNRNl.jpeg')}}
                    </div>
                    <div class="form-group">
                        <label for="photoMod">Immagine di profilo:</label>
                        <div class="custom-file">
                            <input type="file" accept=".jpeg, .jpg, .png" class="custom-file-input" id="photoMod" name="photoMod">
                            <label class="custom-file-label" for="photoMod">Scegli il file della nuova immagine di profilo</label>
                            <div class="invalid-feedback">
                                L'immagine di profilo deve essere selezionata e quadrata!
                            </div>
                        </div>
                    </div>
                    {{--<input type="hidden" class="form-control" id="formModI">
                    <div class="invalid-feedback">
                        Username o e-mail già presente.
                    </div>--}}
                    <div class="form-group">
                        <input type="hidden" id="originalEmailMod" name="originalEmailMod" value="{{ auth()->user()->email }}">
                        <label for="emailMod">Inserisci la nuova email:</label>
                        <input type="email" class="form-control" id="emailMod" name="emailMod" placeholder="Inserisci email..." value="{{ auth()->user()->email }}">
                        <div class="invalid-feedback">
                            Per favore specifica un'e-mail valida (lunghezza massima consentita 64 caratteri).
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" id="originalUsernameMod" name="originalUsernameMod" value="{{ auth()->user()->username }}">
                        <label for="usernameMod">Inserisci il nuovo username:</label>
                        <input type="text" class="form-control" id="usernameMod" name="usernameMod" placeholder="Inserisci username..." value="{{ auth()->user()->username }}">
                        <div class="invalid-feedback">
                            Per favore specifica uno username valido: può contenere solamente lettere e/o numeri (lunghezza massima consentita 64 caratteri).
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bioMod">Inserisci la nuova bio:</label>
                        <textarea rows="4" cols="50" id="bioMod" name="bioMod" class="form-control">{{ auth()->user()->bio }}</textarea>
                        <div class="invalid-feedback">
                            Per favore specifica una bio valida: può contenere al massimo 500 caratteri.
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="passwordMod">Password:</label>
                        <input type="password" class="form-control" id="passwordMod" name="passwordMod" placeholder="Inserisci la nuova password...">
                        <div class="invalid-feedback">
                            Per favore specifica una password valida: deve contenere almeno 8 caratteri, di cui almeno una lettera minuscola, una maiuscola,
                            un numero e un carattere speciale (lunghezza massima consentita 64 caratteri, solo caratteri ASCII stampabili).
                            <br>
                             ATTENZIONE: Se non desideri cambiare la tua password inserisci la tua password attuale per confermare le modifiche
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="repeatPasswordMod">Ripeti password:</label>
                        <input type="password" class="form-control" id="repeatPasswordMod" name="repeatPasswordMod" placeholder="Reinserisci password...">
                        <div class="invalid-feedback">
                            Per favore reinserisci la stessa password.
                        </div>
                    </div>
                    <input type="hidden" class="form-control" id="formModV">
                    <div class="valid-feedback">
                        Modifica avvenuta con successo!
                    </div>
                    <button type="submit" class="btn btn-block btn-primary mt-4" id="buttonMod">Conferma modifiche</button>
                </form>
            </div>
        </div>
    </div>
    <br><br>
@endsection

@section('script_footer')
    <!-- Altri script nostri -->
    <!-- Script che effettuano i controlli sulla form di modifica e le chiamate
         al server utilizzando Ajax -->
    <script type="text/javascript" src="{{asset('js/modifyCheck.js')}}"></script>

    <!-- Script che implementa MD5 per evitare di mandare la password al server in chiaro -->
    <script type="text/javascript" src="{{asset('js/md5.js')}}"></script>
@endsection