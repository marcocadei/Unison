@php($usersSelectedInNavbarForm = true)

@extends('tricol.layouts.mainlayout')

@section('title')
    Risultati della ricerca di "{{ $queryString }}"
@endsection

@section('middlecol_header')
    <div class="container">
        <div class="row align-items-center justify-content-center profileInfoBackground rounded">
            <div class="col-12 text-left">
                <h2 class="boldText wordBreak text-left my-2">Risultati della ricerca utenti</h2>
                <p class="wordBreak">Hai cercato: <i>{{ $queryString }}</i></p>
            </div>
        </div>
    </div>

    <div class="container mt-3">
        @foreach($users as $userInfo)
        <div class="row align-items-center justify-content-center py-3">
            <div class="col-7 col-md-2 mb-2 mb-md-0">
                <img class="img-fluid border border-primary rounded-circle" src="{{ $userInfo["profile_pic"] }}">
            </div>
            <div class="col-12 col-md-10 text-left" id="refreshing-container">
                <div id="refreshing-contained">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="boldText wordBreak text-left">{{ $userInfo["username"] }} <a href="{{ asset('/user/' . $userInfo["user_id"]) }}"><span class="fas fa-external-link-alt"></span></a></h5>
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
                </div>
            </div>
        </div>
        <hr class="d-md-none">
        @endforeach
    </div>
@endsection

{{-- TODO nascondere il div con id allaudioelements --}}