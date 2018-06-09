@extends('layouts.layout')

@section('script_header')
    <!-- Costanti -->
    <script type="text/javascript" src="./js/constants.js"></script>
    <!-- Utilità generale -->
    <script type="text/javascript" src="./js/general.js"></script>
@endsection

@section('content')
    <div id="homepageSlideshow" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#homepageSlideshow" data-slide-to="0" class="active"></li>
            <li data-target="#homepageSlideshow" data-slide-to="1"></li>
            <li data-target="#homepageSlideshow" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner" role="listbox">
            <div class="carousel-item active carouselElement">
                <img class="h-100 w-100 d-block imageCover" src="/images/homeDrums.jpg" alt="Registra nuovo account">
                <div class="carousel-caption d-block">
                    <a class="btn btn-primary my-5 p-4 buttonText" href="{{ route('login') }}">Crea nuovo account</a>
                    <h3 class="font-weight-bold">Personalizza la tua esperienza su Unison.</h3>
                    <p>Segui i tuoi artisti preferiti e ascolta per primo le loro nuove produzioni.</p>
                </div>
            </div>
            <div class="carousel-item carouselElement">
                <img class="h-100 w-100 d-block imageCover" src="./images/homeBand.jpg" alt="Vai alla top 50">
                <div class="carousel-caption d-block">
                    <a class="btn btn-primary my-5 p-4 buttonText" href="{{ route('home') }}">Vai alla top 50</a>
                    <h3 class="font-weight-bold">Rimani sempre aggiornato sulle ultime novità.</h3>
                    <p>Ascolta subito i brani più di tendenza in questo momento.</p>
                </div>
            </div>
            <div class="carousel-item carouselElement">
                <img class="h-100 w-100 d-block imageCover" src="./images/homeMicrophone.jpg" alt="Carica nuova traccia">
                <div class="carousel-caption d-md-block">
                    <a class="btn btn-primary my-5 p-4 buttonText" href="{{ route('home') }}">Carica nuova traccia</a>
                    <h3 class="font-weight-bold">Condividi la tua musica con il mondo intero.</h3>
                    <p>Unison è la piattaforma giusta per far sentire la tua voce.</p>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#homepageSlideshow" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#homepageSlideshow" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
@endsection

