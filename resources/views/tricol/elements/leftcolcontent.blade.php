@if (!auth()->check())
    <span>
        Attualmente non sei loggato e non è possibile mostrare la lista degli utenti che segui e che ti seguono.
    </span>
    <br>
    <a class="badge badge-pill badge-success" href="{{ route('login') }}">Accedi</a> per scoprire di più
    sui tuoi follower e followed.
@else
    <b><h6>FOLLOWED</h6></b>
    {{--@foreach($followed as $user)--}}
        {{--<a href="{{ url('/user/'.$user) }}">Prova</a>--}}
    {{--@endforeach--}}

    {{--<b><h6>FOLLOWER</h6></b>--}}
    {{--@foreach($follower as $user)--}}
        {{--<a href="{{ url('/user/'.$user) }}">Prova</a>--}}
    {{--@endforeach--}}
@endif
