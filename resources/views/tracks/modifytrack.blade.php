@extends('layouts.layout')

@section('title')
    Modifica della traccia {{ $trackRecord->title }}
@endsection

@section('content')
    <!-- Modal -->
    <div class="modal fade" id="modTrackModal" tabindex="-1" role="dialog" aria-labelledby="modModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifica informazioni traccia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 class="modal-title text-success" id="modModalLabel"><span class="fas fa-check"></span> Modifica avvenuta con successo!</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal eliminazione traccia -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="modModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Eliminazione traccia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-justify">
                    <p class="modal-title">Desideri davvero eliminare questa traccia? Questa azione non può essere annullata.</p>
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
                <form class="px-2 px-md-5 py-3" action="{{ asset("/track/edit/" .  $trackRecord->id) }}" method="post" id="mod" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" class="form-control" id="formModify">
                    <div class="row mb-3">
                        <div class="col-12">
                            <img class="img-fluid border border-primary profileImage" src="{{Storage::url($trackRecord->picture)}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="photoMod">Inserisci la nuova immagine di copertina:</label>
                        <div class="custom-file">
                            <input type="file" accept=".jpeg, .jpg, .png" class="custom-file-input" id="photoMod" name="photoMod">
                            <label class="custom-file-label modal-open fileLabelHeightReset" for="photoMod">Scegli file...</label>
                            <div class="invalid-feedback">
                                Per favore seleziona un file valido [.jpeg, .jpg, .png] (l'immagine di copertina deve essere quadrata e almeno 150x150).
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="title">Inserisci il nuovo titolo:</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Inserisci titolo..." value="{{ $trackRecord->title }}">
                        <input type="hidden" id="originalTitle" name="originalTitle" value="{{ $trackRecord->title }}">
                        <div class="invalid-feedback">
                            Per favore specifica un titolo valido (lunghezza massima consentita 64 caratteri, solo caratteri ASCII stampabili).
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Inserisci la nuova descrizione:</label>
                        <textarea class="form-control unresizable" id="description" name="description" placeholder="Inserisci una descrizione...">{{ $trackRecord->description }}</textarea>
                        <div class="invalid-feedback">
                            Per favore specifica una descrizione valida (lunghezza massima consentita 200 caratteri, solo caratteri ASCII stampabili).
                        </div>
                    </div>
                    <div class="form-group form-check">
                        <input class="form-check-input" type="checkbox" value="on" @if($trackRecord->dl_enabled) checked @endif id="allowDownload" name="allowDownload">
                        <label class="form-check-label" for="allowDownload">
                            Consenti il download della traccia
                        </label>
                    </div>

                    <div class="form-group form-check">
                        <input class="form-check-input" type="checkbox" value="on" @if($trackRecord->private) checked @endif id="private" name="private">
                        <label class="form-check-label" for="private">
                            Traccia privata
                        </label>
                    </div>

                    <input type="hidden" name="userID" id="userID" value="{{ auth()->user()->id }}">
                    <div class="invalid-feedback">
                        Hai già caricato una canzone con quel titolo.
                    </div>

                    <button type="submit" class="btn btn-block btn-primary mt-4" id="buttonModify">Modifica informazioni</button>
                </form>

                <form class="px-2 px-md-5" action="{{ asset("/track/delete/" . $trackRecord->id) }}" method="post" id="del">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-block btn-danger mt-4" id="buttonDel">Elimina traccia</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script_footer')
    <!-- Altri script nostri -->
    <!-- Script che effettuano i controlli sulla form di modifica e le chiamate
         al server utilizzando Ajax -->
    <script type="text/javascript" src="{{asset('js/modifyTrackCheck.js')}}"></script>

    <!-- Script che implementa MD5 per evitare di mandare la password al server in chiaro -->
    <script type="text/javascript" src="{{asset('js/libs/md5.js')}}"></script>

    {{-- Attiva la finestra modale al caricamento della pagina; la variabile di sessione "viewMod" è settata solo
    quando la pagina di modifica viene ricaricata a seguito di un aggiornamento dei dati. --}}
    @if(session('viewMod'))
        <script type="text/javascript">
            $('#modTrackModal').modal({
                keyboard: true
            });
        </script>
    @endif
@endsection