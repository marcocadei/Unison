{{-- TODO Ancora da realizzare tutto, per ora solo testo placeholder --}}

<b><h6>ALCUNI LINK UTILI</h6></b>

<ul>
    <li class="mb-2">
        <a class="badge badge-pill badge-secondary" href="{{ url("/home") }}">Feed</a>
    </li>
    @if(auth()->check())
        <li class="mb-2">
            <a class="badge badge-pill badge-secondary" href="/user/{{ auth()->user()->id }}">Il tuo profilo</a>
        </li>
    @endif
    <li class="mb-2">
        <a class="badge badge-pill badge-secondary" href="{{ url("/top50") }}">Top tracks</a>
    </li>
    <li class="mb-2">
        <a class="badge badge-pill badge-secondary" href="{{ url("/track/upload") }}">Carica una traccia</a>
    </li>
    <li class="mb-2">
        <a class="badge badge-pill badge-secondary" href="{{ url("/modify") }}">Impostazioni</a>
    </li>
    <li class="mb-2">
        <a class="badge badge-pill badge-secondary" href="mailto:unison@altervista.org">Contattaci</a>
    </li>
    <li class="mb-2">
        <a class="badge badge-pill badge-secondary" href="{{ url("/") }}">Home page</a>
    </li>
    @if(auth()->check())
        <li class="mb-2">
            <a class="badge badge-pill badge-danger" href="{{ route('logout') }}">Esci</a>
        </li>
    @else
        <li class="mb-2">
            <a class="badge badge-pill badge-success" href="{{ route('register') }}">Registrati</a>
        </li>
    @endif
</ul>
<h4>UNISON</h4>
<small>
    Ascoltare, condividire e scoprire nuova musica non è mai stato così facile.
    Immergiti nel mondo Unison e vivi un'esperienza unica.
</small>