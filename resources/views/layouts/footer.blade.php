<footer class="footer font-weight-normal bg-primary pt-4">
    <div class="container text-center text-md-left">
        <div class="row text-center text-md-left mt-3 pb-3 text-light">
            <div class="col-sm-12 col-md-4 mx-auto mt-4 pl-3">
                <img class="img-fluid mx-auto" src="{{asset('images/bigLogo.png')}}" alt="Logo Unison">
            </div>
            <div class="col-sm-12 col-md-4 mx-auto mt-4 border-left pl-3">
                <h5 class="text-uppercase mb-4 font-weight-bold">Chi siamo</h5>
                <p>
                    Ascoltare, condividere e scoprire nuova musica non è mai stato così facile.<br>
                    Immergiti nel mondo Unison e vivi un'esperienza unica.
                </p>
            </div>
            <div class="col-sm-12 col-md-4 mx-auto mt-4 border-left pl-3">
                <h5 class="text-uppercase mb-4 font-weight-bold">Contatti</h5>
                <p><i class="fa fa-envelope mr-3"></i><a class="text-light" href="mailto:unison@altervista.org"><u>unison@altervista.org</u></a></p>
                @if(!isset($showLinkER) || !$showLinkER)
                    <h5 class="text-uppercase my-4 font-weight-bold">Social</h5>
                    <p><i class="fab fa-twitter mr-3"></i><a class="text-light" target="_blank" href="{{ "http://twitter.com/2018unison" }}"><u>Twitter</u></a></p>
                @else
                    <h5 class="text-uppercase my-4 font-weight-bold">Extra</h5>
                    <p><i class="fas fa-dice-three mr-3"></i><a class="text-light" target="_blank" href="{{ asset("/er_redirect.html") }}"><u>Lancia un dado</u></a></p>
                @endif
            </div>
        </div>
        <div class="row py-3 d-flex align-items-center text-light mt-3 pt-3 border-top">
            <div class="col-12">
                <p class="text-center text-md-left grey-text">© 2018 Copyright: <strong> Unison</strong></p>
            </div>
        </div>
    </div>
</footer>