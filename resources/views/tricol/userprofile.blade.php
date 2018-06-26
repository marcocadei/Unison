@extends('tricol.layouts.mainlayout')

@section('title')
    Profilo di {{ $userInfo["username"] }}
@endsection

@section('middlecol_header')
    <div class="container">
        <div class="row align-items-center justify-content-center profileInfoBackground rounded py-3">
            <div class="col-8 col-md-4 mb-2 mb-md-0">
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
                            <div class="col-12">
                                @if($userInfo["followed_by_logged_user"])
                                    <button id="buttonUnfollow" class="btn btn-outline-primary" onclick="executeUnfollow()">Seguito</button>
                                @else
                                    <button id="buttonFollow" class="btn btn-primary" onclick="executeFollow()">Segui</button>
                                    {{-- TODO ATTENZIONE! si può accedere a questa pagina anche da non-loggati, quindi bisogna
                                    controllare che alla pressione del tasto "segui" l'utente sia effettivamente loggato!
                                    (sennò lo si ributta alla pagina di login) --}}
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