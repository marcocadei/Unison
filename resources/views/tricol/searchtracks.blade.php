@extends('tricol.layouts.mainlayout')

@section('title')
    Risultati della ricerca di "{{ $queryString }}"
@endsection

@section('middlecol_header')
    <div class="container">
        <div class="row align-items-center justify-content-center profileInfoBackground rounded">
            <div class="col-12 text-left">
                <h2 class="boldText wordBreak text-left my-2">Risultati della ricerca brani</h2>
                <p class="wordBreak">Hai cercato: <i>{{ $queryString }}</i></p>
            </div>
        </div>
    </div>
@endsection

@section('script_footer')
    @parent
    <script type="text/javascript">
        preloadSearchBar('{!! addslashes($queryString) !!}');
    </script>
@endsection