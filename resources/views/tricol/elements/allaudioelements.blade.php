@php($counter = 0)
{{-- Nota: È necessaria una variabile contatore per fare in modo che alcuni elementi della pagina associati ad una
traccia possano avere ID pari a [stringa]+[indice della traccia audio]. --}}
@foreach($songs as $song)
    @include('tricol.elements.singleaudioelement')
    @php($counter++)
@endforeach

@if($counter > 0)
    @if(!isset($dropPageCount) || !$dropPageCount)
    <div class="mt-3 row align-items-center justify-content-center">
        <div class="col-12 text-center">
            @php($pageNumber = ctype_digit(request('page')) ? intval(request('page')) : 0)
            @if(($pageNumber - 4) > 0)
                <a class="badge badge-secondary disabledAnchor" href="#">...</a>
            @endif
            @for($i = max(0, $pageNumber - 4); $i < min(intval(ceil($trackCount / App\Track::$chunkSize)), $pageNumber + 5); $i++)
                @php($link = request()->fullUrlWithQuery(['page' => $i]))
                <a class="badge @if($i === $pageNumber) badge-info @else badge-secondary @endif" href="{{ $link }}">{{ $i + 1 }}</a>
            @endfor
            @if(($pageNumber + 5) < intval(ceil($trackCount / App\Track::$chunkSize)))
                <a class="badge badge-secondary disabledAnchor" href="#">...</a>
            @endif
        </div>
    </div>
    @endif
@else
    @if(!isset($users) || (isset($users) && count($users) == 0))
        <div class="mt-3 row align-items-center justify-content-center">
            <div class="col-12 text-center">
                <h4 class="text-black-50 boldText">Sembra che qui non ci sia nulla...</h4>
            </div>
        </div>
        <div class="row align-items-center justify-content-center">
            <div class="col-12 col-md-6 text-center">
                <img class="img-fluid" src="{{ asset("/images/tumbleweed.gif") }}">
            </div>
        </div>
    @endif
@endif