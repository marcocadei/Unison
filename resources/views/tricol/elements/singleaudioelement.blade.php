{{-- Questo div vuoto serve per raggruppare assieme l'elemento audio e il campo per i dati di Spotify --}}
{{-- La classe audioElementContainer non è definita a livello di CSS ma serve alla funzione JS che imposta la
    destinazione dell'elemento puntina. Non rimuovere! --}}
<div class="audioElementContainer my-1">
    <div class="row align-items-center justify-content-center">
        <div class="col-7 col-sm-4 col-md-2 p-1 pl-3 pr-3 pr-sm-1">
            @if(!isset($displayCounter) || !$displayCounter)
                <img src="{{ $song["cover_art_url"] }}" class="img-fluid w-100 p-0 m-auto"/>
            @else
                <div class="position-relative text-center">
                    <img src="{{ $song["cover_art_url"] }}" class="img-fluid w-100 p-0 m-auto filteredCoverArt" />
                    <h1 class="position-absolute boldText text-primary audioElementCounter">{{ $counter + 1 }}</h1>
                </div>
            @endif
        </div>
        <div class="col-12 col-sm-8 col-md-10">
            <div class="py-1">
                <div>
                    @if(auth()->check())
                        @if($song["artist_id"] == auth()->user()->id)
                            <a class="boldText text-primary buttonOn" href="{{ asset("/track/edit/" . $song["id"]) }}"><span class="far fa-edit"></span></a>
                        @endif
                    @endif
                    @if($song["private"])
                        <span class="boldText text-primary buttonOn"><span class="fas fa-lock"></span></span>
                    @endif
                    <span class="boldText wordBreak">{{ $song["name"] }}</span> |
                    <span class="text-primary smallText wordBreak"><a class="noOutline noDecoration" href="{{ asset("/user/" . $song["artist_id"]) }}">{{ $song["artist"] }}</a></span>
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
                            {{-- L'icona del cuore è un bottone che esegue l'azione del like solo per gli utenti loggati. --}}
                            <span class="@if($song["is_liked"]) buttonOn @endif @if(auth()->check()) cursorPointer @endif"
                                  @if(auth()->check()) onclick="toggleLike(this, {{ $song["id"] }})" @endif
                            >
                                <span class="fas fa-heart"></span>
                            </span>
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
                            <a href="{{ $song["url"] }}" download="{{ $song["artist"] }}_{{ $song["name"] }}{{ substr($song["url"], strrpos($song["url"], ".")) }}">
                                <span class="fas fa-download"></span>
                            </a>
                        </span>
                        @endif
                    </span>
                    @if(strcmp($song["spotify_id"], "0000000000000000000000") != 0)
                    <span class="float-right text-right ml-1">
                        <a title="Visualizza dati Spotify" class="btn btn-primary buttonWithoutShadow d-inline" data-toggle="collapse" href="#spotifyMetadata-{{ $counter }}" role="button" aria-expanded="false" aria-controls="spotifyMetadata-{{ $counter }}" onclick="retrieveData(this)">
                            <span class="fab fa-spotify"></span>
                        </a>
                    </span>
                    @endif
                    @if(!is_null($song["description"]) && strlen(trim($song["description"])) > 0)
                    <span class="float-right text-right ml-1">
                        <a title="Visualizza descrizione traccia" class="btn btn-primary buttonWithoutShadow d-inline" data-toggle="collapse" href="#description-{{ $counter }}" role="button" aria-expanded="false" aria-controls="description-{{ $counter }}">
                            <span class="fas fa-info-circle"></span>
                        </a>
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @if(!is_null($song["description"]) && strlen(trim($song["description"])) > 0)
    <div class="row collapse my-2" id="description-{{ $counter }}">
        <div class="col-12">
            <div class="card card-body py-2">
                <span class="smallText"><b>Descrizione:</b><br>{{ $song["description"] }}</span>
            </div>
        </div>
    </div>
    @endif
    @if(strcmp($song["spotify_id"], "0000000000000000000000") != 0)
    <div class="row collapse my-2" id="spotifyMetadata-{{ $counter }}">
        <div class="col-12">
            <div class="card card-body py-2">
                <span class="smallText">Caricamento dati da Spotify in corso...</span>
                <input type="hidden" value="{{ $song["spotify_id"] }}">
            </div>
        </div>
    </div>
    @endif
</div>
<hr class="d-sm-none bg-light">

@section('script_footer')
    @parent
    <script type="text/javascript" src="{{ asset('/js/like.js') }}"></script>
@endsection