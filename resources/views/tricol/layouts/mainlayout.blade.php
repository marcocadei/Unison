@php($dropFooter = true)

@extends('layouts.layout')

@section('script_header')
    <link rel="stylesheet" href="{{ asset('/css/audioelements.css') }}">
@append

@section('body_classes')
    bgImage
@append

@section('content')
    @include('tricol.elements.audionav')
    @include('tricol.elements.sidecolumnstogglers')
    <div class="container-fluid font-weight-normal mt-xl-0 mt-5" id="mainElement">
        <div class="row flex-xl-nowrap text-justify">
            <div class="d-none d-xl-block col-xl-2 sideColumn order-first wordBreak mt-xl-0 mt-4" id="leftCol">
                @include('tricol.elements.leftcolcontent')
            </div>
            <div class="d-none d-xl-block col-xl-2 sideColumn order-last wordBreak mt-xl-0 mt-4" id="rightCol">
                @include('tricol.elements.rightcolcontent')
            </div>
            <main class="col-12 col-xl-8 my-5 py-4 mainMinHeight" id="middleCol">
                @include('tricol.elements.middlecolcontent')
            </main>
        </div>
    </div>
@overwrite

@section('script_footer')
    <!-- Amplitude JS (Player audio) -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/amplitudejs@3.2.3/dist/amplitude.js"></script>

    <!-- Timer (utilizzati per il contatore riproduzioni) -->
    <script type="text/javascript" src="{{ asset('/js/libs/jquery.timer.js') }}"></script>

    <script type="text/javascript" src="{{ asset('/js/tricolElements.js') }}"></script>

    <script type="text/javascript" src="{{ asset('/js/amplitudeElements.js') }}"></script>

    <!-- Script usato per recuperare i dati di spotify -->
    <script type="text/javascript" src="{{ asset('js/retrieveSpotifyData.js') }}"></script>

    <!-- Script usato per recuperare la lista di followed e follower dell'utente-->
    <script type="text/javascript" src="{{ asset('js/followList.js') }}"></script>


    {{-- IMPORTANTE: Lasciare questa inclusione sempre come ultima riga di questa sezione! --}}
    @include('tricol.elements.amplitudeinitializer')
@endsection
