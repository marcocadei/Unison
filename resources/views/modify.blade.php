@extends('layouts.layout')

@section('title')
    Modifica profilo
@endsection

@section('content')
    <!-- Modal eliminazione profilo -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="modModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Eliminazione profilo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-justify">
                    <p class="modal-title">Desideri davvero eliminare il tuo profilo e tutte le tracce associate? Questa azione non può essere annullata.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                    <button type="button" class="btn btn-danger" id="buttonDefDel">Elimina</button>
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
                        <label for="photoMod">Nuova immagine di profilo:</label>
                        <div class="custom-file">
                            <input type="file" accept=".jpeg, .jpg, .png" class="custom-file-input" id="photoMod" name="photoMod" aria-describedby="photoHelpBlock">
                            <label class="custom-file-label modal-open fileLabelHeightReset" for="photoMod">Scegli file...</label>
                            <small id="photoHelpBlock" class="form-text text-muted">
                                Per favore seleziona un'immagine quadrata, di dimensione superiore a 150x150 e in un formato valido (.jpeg, .jpg, .png).
                            </small>
                            <div class="invalid-feedback">
                                L'immagine inserita non rispetta alcune delle indicazioni fornite.
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" id="originalEmailMod" name="originalEmailMod" value="{{ auth()->user()->email }}">
                        <label for="emailMod">Nuova email:</label>
                        <input type="text" class="form-control" id="emailMod" name="emailMod" placeholder="Inserisci email..." value="{{ auth()->user()->email }}" aria-describedby="emailHelpBlock">
                        <small id="emailHelpBlock" class="form-text text-muted">
                            Per favore inserisci una mail valida. Esempio: mariorossi@libero.it <br>
                            La mail non deve essere già associata ad un altro utente.
                        </small>
                        <div class="invalid-feedback">
                            La mail inserita non rispetta alcune delle indicazioni fornite.
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" id="originalUsernameMod" name="originalUsernameMod" value="{{ auth()->user()->username }}">
                        <label for="usernameMod">Nuovo username:</label>
                        <input type="text" class="form-control" id="usernameMod" name="usernameMod" placeholder="Inserisci username..." value="{{ auth()->user()->username }}" aria-describedby="usernameHelpBlock">
                        <small id="usernameHelpBlock" class="form-text text-muted">
                            Per favore inserisci uno username valido. Sono ammesse lettere da A a Z (maiuscole e minuscole), lettere accentate, numeri da 0 a 9 e punteggiatura (anche spazi).<br>
                            Lo username non deve essere già associato ad un altro utente.
                        </small>
                        <div class="invalid-feedback">
                            Lo username inserito non rispetta alcune delle indicazioni fornite.
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bioMod">Nuova bio:</label>
                        <textarea rows="4" id="bioMod" name="bioMod" class="form-control unresizable" placeholder="Inserisci una bio..." aria-describedby="bioHelpBlock">{{ auth()->user()->bio }}</textarea>
                        <small id="bioHelpBlock" class="form-text text-muted">
                            Per favore inserisci una bio valida. Sono ammesse lettere da A a Z (maiuscole e minuscole), lettere accentate, numeri da 0 a 9 e punteggiatura (anche spazi). <br>
                        </small>
                        <div class="invalid-feedback">
                            La bio inserita non rispetta alcune delle indicazioni fornite.
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="passwordCheckbox">
                            <label class="form-check-label" for="passwordCheckbox">
                                Modifica password
                            </label>
                        </div>
                        <small class="form-text text-muted">
                            Spunta questa casella solo se intendi cambiare la tua password.<br>
                            I due campi sottostanti saranno considerati solo se la casella è spuntata.
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="passwordMod">Nuova password:</label>
                        <input type="password" class="form-control" id="passwordMod" name="passwordMod" placeholder="Inserisci la nuova password..." aria-describedby="passwordHelpBlock" disabled>
                        <small id="passwordHelpBlock" class="form-text text-muted">
                            Per favore inserisci una password valida. La password deve contenere almeno 8 caratteri, con una lettera minuscola, una lettera maiuscola, un numero e un simbolo di punteggiatura.
                        </small>
                        <div class="invalid-feedback">
                            La password inserita non rispetta alcune delle indicazioni fornite.
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="repeatPasswordMod">Ripeti password:</label>
                        <input type="password" class="form-control" id="repeatPasswordMod" name="repeatPasswordMod" placeholder="Reinserisci password..." aria-describedby="repeatPasswordHelpBlock" disabled>
                        {{--<small id="repeatPasswordHelpBlock" class="form-text text-muted">--}}
                        {{--</small>--}}
                        <div class="invalid-feedback">
                            La password non corrisponde a quella inserita nel campo precedente.
                        </div>
                    </div>
                    <input type="hidden" class="form-control" id="formMod">
                    <div class="invalid-feedback border border-danger text-center p-1 mb-4">
                        È già presente un utente con quel nome utente o con quella e-mail.
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <a class="btn-block btn btn-outline-secondary mb-1" id="buttonUndo" href="{{ asset("/user/" . auth()->user()->id) }}">Annulla modifiche</a>
                        </div>
                        <div class="col">
                            <button type="submit" class="btn-block btn btn-primary" id="buttonMod">Conferma modifiche</button>
                        </div>
                    </div>
                </form>
                <form class="px-2 px-md-5" action="/delete" method="post" id="del">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-block btn-danger mt-3" id="buttonDel">Elimina profilo</button>
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
    <script type="text/javascript" src="{{asset('js/libs/md5.js')}}"></script>
@endsection