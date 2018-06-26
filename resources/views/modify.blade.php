@extends('layouts.layout')

@section('title')
    Modifica profilo
@endsection

@section('content')
    <!-- Modal -->
    <div class="modal fade" id="modModal" tabindex="-1" role="dialog" aria-labelledby="modModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifica informazioni utente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 class="modal-title text-success" id="modModalLabel"><span class="fas fa-check"></span> Modifica avvenuta con successo!</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="container h-100 mt-5 mb-3 pt-3">
        <div class="row h-100 justify-content-center align-items-center">
            <!-- Aggiungere al div anche la classe "align-items-center" se si vuole che l'immagine sia centrata
            anche rispetto al verticale -->
            <div class="col-sm-9 order-last col-md-3 order-md-first text-center">
                <img class="loginImage rounded-circle img-fluid mt-3 mt-md-0" src="{{asset('images/settings.jpeg')}}" alt="Che aspetti? Effettua velocemente le modifiche così può tornare alla tua musica!">
            </div>
            <div class="col-sm-12 col-md-9 border-left pl-3">
                <form class="px-2 px-md-5 py-3" action="/modify" method="post" id="mod" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row mb-3">
                        <div class="col-12">
                            <img class="img-fluid rounded-circle border border-primary profileImage" src="{{Storage::url(auth()->user()->profile_pic)}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="photoMod">Immagine di profilo:</label>
                        <div class="custom-file">
                            <input type="file" accept=".jpeg, .jpg, .png" class="custom-file-input" id="photoMod" name="photoMod">
                            <label class="custom-file-label modal-open" for="photoMod">Scegli il file della nuova immagine di profilo</label>
                            <div class="invalid-feedback">
                                L'immagine di profilo deve essere quadrata e almeno 150X150px!
                            </div>
                        </div>
                    </div>
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
                        <textarea rows="4" id="bioMod" name="bioMod" class="form-control unresizable">{{ auth()->user()->bio }}</textarea>
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
                    <input type="hidden" class="form-control" id="formModI">
                    <div class="invalid-feedback">
                        Nome utente o email già utilizzati.
                    </div>
                    {{--<input type="hidden" class="form-control" id="formModV">
                    <div class="valid-feedback">
                        Modifica avvenuta con successo!
                    </div>--}}
                    <button type="submit" class="btn btn-block btn-primary mt-4" id="buttonMod">Conferma modifiche</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script_footer')
    <!-- Altri script nostri -->
    <!-- Script che effettuano i controlli sulla form di modifica e le chiamate
         al server utilizzando Ajax -->
    <script type="text/javascript" src="{{asset('js/modifyCheck.js')}}"></script>

    <!-- Script che implementa MD5 per evitare di mandare la password al server in chiaro -->
    <script type="text/javascript" src="{{asset('js/md5.js')}}"></script>
@endsection