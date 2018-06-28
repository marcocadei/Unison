<h4>LINK UTILI</h4>
<ul>
    <li class="mb-2">
        <a class="badge badge-pill badge-primary" href="{{ url("/home") }}">Feed</a>
    </li>
    @if(auth()->check())
        <li class="mb-2">
            <a class="badge badge-pill badge-primary" href="/user/{{ auth()->user()->id }}">Il tuo profilo</a>
        </li>
    @endif
    <li class="mb-2">
        <a class="badge badge-pill badge-primary" href="{{ url("/top50") }}">Top tracks</a>
    </li>
    <li class="mb-2">
        <a class="badge badge-pill badge-primary" href="{{ url("/track/upload") }}">Upload</a>
    </li>
    <li class="mb-2">
        <a class="badge badge-pill badge-primary" href="{{ url("/modify") }}">Impostazioni</a>
    </li>
    <li class="mb-2">
        <a class="badge badge-pill badge-primary" href="mailto:unison@altervista.org">Contattaci</a>
    </li>
    <li class="mb-2">
        <a class="badge badge-pill badge-primary" href="{{ url("/") }}">Home page</a>
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