@extends('tricol.layouts.mainlayout')

@section('title')
    Feed | {{auth()->user()->username}}
@endsection

@section('middlecol_header')
    <div class="container">
        <div class="row align-items-center justify-content-center profileInfoBackground rounded">
            <div class="col-12 text-left">
                <h2 class="boldText wordBreak text-left my-2">Il tuo feed</h2>
                <p class="wordBreak">Qui puoi ascoltare le tracce pubblicate dagli artisti che segui</p>
            </div>
        </div>
    </div>
@endsection