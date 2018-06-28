@if (!auth()->check())
    <span>
        Attualmente non sei loggato e non è possibile mostrare la lista degli utenti che segui e che ti seguono.
    </span>
    <br>
    <a class="badge badge-pill badge-success" href="{{ route('login') }}">Accedi</a> per scoprire di più
    sui tuoi follower e followed.
@else
    <p>
        <b>
            <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                FOLLOWED
            </a>
        </b>
    </p>
    <div class="collapse" id="collapseExample">
        <div class="card card-body">
            <ul style="word-break: break-all">
                <a href="#">
                    <li>
                        Prova utente con nome moltoooooooooooooooooooooooooooooooooo lungo
                    </li>
                </a>
            </ul>
            <ul style="word-break: break-all">
                <a href="#">
                    <li>
                        Prova utente con nome moltoooooooooooooooooooooooooooooooooo lungo
                    </li>
                </a>
            </ul>
        </div>
    </div>
    {{--@foreach($followed as $user)--}}
        {{--<a href="{{ url('/user/'.$user) }}">Prova</a>--}}
    {{--@endforeach--}}

    {{--<b><h6>FOLLOWER</h6></b>--}}
    {{--@foreach($follower as $user)--}}
        {{--<a href="{{ url('/user/'.$user) }}">Prova</a>--}}
    {{--@endforeach--}}
@endif
