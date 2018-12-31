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

                {{-- Il div seguente è mostrato solo nel caso in cui il feed sia vuoto. --}}
                <div id="emptyFeedMessage" class="d-none">
                    <hr>
                    <p class="wordBreak rounded bg-light text-primary p-3">
                        <span class="pb-4">
                            Attualmente il tuo feed è vuoto! Segui i tuoi artisti preferiti per far comparire qui le loro ultime
                            tracce.<br>
                        </span>
                        <small><br>Puoi utilizzare la barra di ricerca superiore per eseguire una ricerca per utente.</small>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection