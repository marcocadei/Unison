@if(auth()->check())
    <input type="hidden" id="loggedUserID" value="{{ auth()->user()->id }}">
    {{--<p>--}}
        {{--<b>--}}
            {{--<a class="btn btn-primary" data-toggle="collapse" href="#followedList" aria-expanded="false" aria-controls="followedList" id="buttonFollowed">--}}
                {{--FOLLOWED--}}
            {{--</a>--}}
        {{--</b>--}}
    {{--</p>--}}
    {{--<div class="collapse" id="followedList">--}}
        {{--<div class="card card-body">--}}
            {{--<span> Carico i followed... </span>--}}
        {{--</div>--}}
    {{--</div>--}}
    <p>
        <b>
            <a class="btn btn-primary" data-toggle="collapse" href="#followerList" aria-expanded="false" aria-controls="followerList" id="buttonFollower">
                FOLLOWERS
            </a>
        </b>
    </p>
    <div class="collapse" id="followerList">
        <div class="card card-body">
            <span> Carico i followers... </span>
        </div>
    </div>
@else
    <h4>UNISON</h4>
    {{--<small>--}}
        {{--Ascoltare, condividere e scoprire nuova musica non è mai stato così facile.--}}
        {{--Immergiti nel mondo Unison e vivi un'esperienza unica.--}}
    {{--</small>--}}
    <p class="text-left">
        Ascoltare, condividere e scoprire nuova musica non è mai stato così facile.
        Immergiti nel mondo Unison e vivi un'esperienza unica.
    </p>
@endif