@extends('tricol.layouts.mainlayout')

@section('title')
    Profilo di {{ $userInfo["username"] }}
@endsection

@section('middlecol_header')
    @if($userInfo["same_as_logged_user"])
        <!-- Modal conferma modifiche -->
        <div class="modal fade" id="modModal" tabindex="-1" role="dialog" aria-labelledby="modModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifica dati</h5>
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

        <!-- Modal conferma eliminazione traccia -->
        <div class="modal fade" id="succesfulDeleteModal" tabindex="-1" role="dialog" aria-labelledby="succesfulDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Eliminazione traccia</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h6 class="modal-title text-black-50" id="succesfulDeleteModalLabel">La traccia è stata correttamente eliminata.</h6>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="container">
        <div class="row align-items-center justify-content-center profileInfoBackground rounded py-3">
            <div class="col-8 col-md-4 mb-2 mb-md-0 text-center">
                <img class="img-fluid border border-light rounded-circle" src="{{ $userInfo["profile_pic"] }}">
            </div>
            <div class="col-12 col-md-8 text-left" id="refreshing-container">
                <div id="refreshing-contained">
                    <div class="row">
                        <div class="col-12">
                            <h1 class="boldText wordBreak text-left">{{ $userInfo["username"] }}</h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p class="text-black-50 wordBreak text-justify">
                                @if(strlen(trim($userInfo["bio"])) > 0)
                                    {{ $userInfo["bio"] }}
                                @else
                                    <i class="smallText">Nessuna descrizione disponibile.</i>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-4 border-left border-dark">
                            <span class="text-black-50 text-left boldText">Follower:</span>
                            <span class="text-black">{{ $userInfo["followers"] }}</span>
                        </div>
                        <div class="col-12 col-sm-4 border-left border-dark">
                            <span class="text-black-50 text-left boldText">Following:</span>
                            <span class="text-black">{{ $userInfo["following"] }}</span>
                        </div>
                        <div class="col-12 col-sm-4 border-left border-dark">
                            <span class="text-black-50 text-left boldText">Tracce:</span>
                            <span class="text-black">{{ $userInfo["uploads"] }}</span>
                        </div>
                    </div>
                    @if(!$userInfo["same_as_logged_user"])
                        <div class="row mt-3">
                            <div class="d-none col-12">
                                <span class="bg-primary text-primary" id="userID">
                                    {{ $userInfo["user_id"] }}
                                </span>
                                <span class="bg-primary text-primary" id="username">
                                    {{ $userInfo["username"] }}
                                </span>
                            </div>
                            <div class="col-12">
                                {{-- Il bottone per il follow non viene visualizzato se l'utente non è loggato. --}}
                                @if(auth()->check())
                                    @if($userInfo["followed_by_logged_user"])
                                        <button id="buttonFollow" class="btn btn-outline-primary" onclick="executeUnfollow()">Smetti di seguire</button>
                                    @else
                                        <button id="buttonFollow" class="btn btn-primary" onclick="executeFollow()">Segui</button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <hr class="bg-secondary my-4 hrThick">
@endsection

@section('script_footer')
    @parent
    <script type="text/javascript" src="{{ asset('/js/follow.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/like.js') }}"></script>

    @if($userInfo["same_as_logged_user"])
        {{-- Attiva la finestra modale con cui è confermata l'avvenuta modifica dei dati al caricamento della pagina.
        Nota: La variabile di sessione "viewMod" è settata solo quando la pagina viene caricata a seguito di un
        aggiornamento dei dati. --}}
        @if(session('viewMod'))
            <script type="text/javascript">
                $('#modModal').modal({
                    keyboard: true
                });
            </script>
        @endif

        {{-- Attiva la finestra modale con cui è confermata l'avvenuta eliminazione di una traccia al caricamento della pagina.
        Nota: La variabile di sessione "viewSuccesfulDeleteMod" è settata solo quando la pagina viene caricata a seguito
        dell'eliminazione di una traccia. --}}
        @if(session('viewSuccesfulDeleteMod'))
            <script type="text/javascript">
                $('#succesfulDeleteModal').modal({
                    keyboard: true
                });
            </script>
        @endif
    @endif
@endsection