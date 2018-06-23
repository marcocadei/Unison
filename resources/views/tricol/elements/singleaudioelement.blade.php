{{-- Questo div vuoto serve per raggruppare assieme l'elemento audio e il campo per i dati di Spotify --}}
{{-- La classe audioElementContainer non è definita a livello di CSS ma serve alla funzione JS che imposta la
    destinazione dell'elemento puntina. Non rimuovere! --}}
<div class="audioElementContainer my-1">
    <div class="row align-items-center justify-content-center">
        <div class="col-7 col-sm-4 col-md-2 p-1 pl-3 pr-3 pr-sm-1">
            @if(!isset($displayCounter) || !$displayCounter)
                <img src="{{ asset($song["cover_art_url"]) }}" class="img-fluid w-100 p-0 m-auto"/>
            @else
                <div class="position-relative text-center">
                    <img src="{{ asset($song["cover_art_url"]) }}" class="img-fluid w-100 p-0 m-auto filteredCoverArt" />
                    <h1 class="position-absolute boldText text-primary audioElementCounter">{{ $counter + 1 }}</h1>
                </div>
            @endif
        </div>
        <div class="col-12 col-sm-8 col-md-10">
            <div class="py-1">
                <div>
                    @if($song["private"])
                        <span class="boldText text-primary buttonOn"><span class="fas fa-lock"></span></span>
                    @endif
                    <span class="boldText wordBreak">{{ $song["name"] }}</span> |
                    <span class="text-primary smallText wordBreak"><a class="noOutline noDecoration" href="{{ asset("/user/" . $song["artist"]) }}">{{ $song["artist"] }}</a></span>
                </div>
                <div>
                    <span class="smallText text-black-50">{{ $song["date"] }}</span>
                </div>
            </div>
            <div class="row align-items-center py-1">
                <div class="col-12 col-sm-4 col-md-2 mb-2 mb-sm-0">
                        <span class="badge badge-pill badge-primary buttonText d-block cursorPointer amplitude-play-pause" amplitude-song-index="{{ $counter }}"></span>
                </div>
                <div class="col-12 col-sm-8 col-md-10">
                    {{-- Nota: L'ID dell'elemento progress è usato dalla funzione JS setThumbtackDestination()
                        per determinare l'elemento a cui fare riferimento. Non rimuovere! --}}
                    <progress class="amplitude-song-played-progress" amplitude-song-index="{{ $counter }}" id="song-played-progress-{{ $counter }}"></progress>
                    <div class="currentTime">
                        @if($song["duration_hours"] > 0)<span class="amplitude-current-hours" amplitude-song-index="{{ $counter }}">00</span>:@endif<span class="amplitude-current-minutes" amplitude-song-index="{{ $counter }}">00</span>:<span class="amplitude-current-seconds" amplitude-song-index="{{ $counter }}">00</span> /
                        @if($song["duration_hours"] > 0)<span class="amplitude-duration-hours" amplitude-song-index="{{ $counter }}">{{ $song["duration_hours"] }}</span>:@endif<span class="amplitude-duration-minutes" amplitude-song-index="{{ $counter }}">{{ $song["duration_mins"] }}</span>:<span class="amplitude-duration-seconds" amplitude-song-index="{{ $counter }}">{{ $song["duration_secs"] }}</span>
                    </div>
                </div>
            </div>
            <div class="py-1 justify-content-center align-items-center">
                <div>
                    <span class="float-left">
                        <span>
                            <span class="@if($song["is_liked"]) buttonOn @endif cursorPointer">
                                <span class="fas fa-heart"></span>
                            </span>
                            {{-- TODO Realizzare lo script che fa mettere il mi piace cliccando qui --}}
                            {{-- ATTENZIONE! Le tracce in generale possono essere visualizzate anche da utenti non
                            loggati (ad es la top 50 è sempre accessibile): accertarsi che ci sia un utente loggato
                            prima di abilitare il collegamento del mipiace! --}}
                            <span class="smallText">{{ $song["likes"] }}</span>
                        </span>
                        |
                        <span>
                            <span>
                                <span class="fas fa-play"></span>
                            </span>
                            <span class="smallText">{{ $song["plays"] }}</span>
                        </span>
                        @if($song["dl_enabled"])
                        |
                        <span class="cursorPointer" >
                            <a href="{{ asset($song["url"]) }}" download="{{ $song["artist"] }}_{{ $song["name"] }}{{ substr($song["url"], strrpos($song["url"], ".")) }}">
                                <span class="fas fa-download"></span>
                            </a>
                        </span>
                        @endif
                    </span>
                    @if(strcmp($song["spotify_id"], "0000000000000000000000") != 0)
                    <span class="float-right text-right">
                        <a class="btn btn-primary buttonWithoutShadow d-inline" data-toggle="collapse" href="#spotifyMetadata-{{ $counter }}" role="button" aria-expanded="false" aria-controls="spotifyMetadata-{{ $counter }}">
                            <span class="fab fa-spotify"></span>
                        </a>
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @if(strcmp($song["spotify_id"], "0000000000000000000000") != 0)
    <div class="row collapse my-2" id="spotifyMetadata-{{ $counter }}">
        <div class="col-12">
            <div class="card card-body py-2">
                {{-- TODO da rimpiazzare con i dati veri --}}
                <span class="smallText">Caricamento dati da Spotify in corso... [Track ID: {{ $song["spotify_id"] }}]</span>
            </div>
        </div>
    </div>
    @endif
</div>
<hr class="d-sm-none bg-light">