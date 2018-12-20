@if (!auth()->check())
    {{--<span>--}}
        {{--Attualmente non sei loggato e non è possibile mostrare la lista degli utenti che segui e che ti seguono.--}}
    {{--</span>--}}
    <p class="text-left">
        Attualmente non sei loggato e non è possibile mostrare la lista degli utenti che segui e che ti seguono.
    </p>
    <br>
    <a class="badge badge-pill badge-success" href="{{ route('login') }}">Accedi</a> per scoprire di più
    sui tuoi follower e followed.
@else
    <input type="hidden" id="loggedUserID" value="{{ auth()->user()->id }}">
    <p>
        <b>
            <a class="btn btn-primary" data-toggle="collapse" href="#followedList" aria-expanded="false" aria-controls="followedList" id="buttonFollowed">
                FOLLOWED
            </a>
        </b>
    </p>
    <div class="collapse" id="followedList">
        <div class="card card-body">
            <span> Carico i followed... </span>
        </div>
    </div>

    {{--<p class="mt-5">--}}
        {{--<b>--}}
            {{--<a class="btn btn-primary" data-toggle="collapse" href="#followerList" aria-expanded="false" aria-controls="followerList" id="buttonFollower">--}}
                {{--FOLLOWERS--}}
            {{--</a>--}}
        {{--</b>--}}
    {{--</p>--}}
    {{--<div class="collapse" id="followerList">--}}
        {{--<div class="card card-body">--}}
            {{--<span> Carico i followers... </span>--}}
        {{--</div>--}}
    {{--</div>--}}
@endif
