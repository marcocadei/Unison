{{-- TODO Ancora da realizzare tutto, per ora solo testo placeholder --}}

<b><h6>ALCUNI LINK UTILI</h6></b>

<ul>
    <li class="mb-2">
        <a class="badge badge-pill badge-info" href="{{ url("/home") }}">Feed</a>
    </li>
    <li class="mb-2">
        <a class="badge badge-pill badge-info" href="/user/{{auth()->user()->username}}">Il tuo profilo</a>
    </li>
    <li class="mb-2">
        <a class="badge badge-pill badge-info" href="{{ url("/track/upload") }}">Carica una traccia</a>
    </li>
    <li class="mb-2">
        <a class="badge badge-pill badge-info" href="{{ url("/modify") }}">Impostazioni</a>
    </li>
    <li class="mb-2">
        <a class="badge badge-pill badge-info" href="mailto:unison@altervista.org">Contattaci</a>
    </li>
    <li class="mb-2">
        <a class="badge badge-pill badge-danger" href="{{ route('logout') }}">Esci</a>
    </li>
</ul>