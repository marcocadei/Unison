<footer class="footer font-weight-normal bg-primary pt-4">
    <div class="container text-center text-md-left">
        <div class="row text-center text-md-left mt-3 pb-3 text-light">
            <div class="col-sm-12 col-md-4 mx-auto mt-4 border-left pl-3">
                <h1 class="text-uppercase mb-4 font-weight-bold">Unison</h1>
                <p>
                    Ascoltare, condividere e scoprire nuova musica non è mai stato così facile.<br>
                    Immergiti nel mondo Unison e vivi un'esperienza unica.
                </p>
            </div>
            <div class="col-sm-12 col-md-3 mx-auto mt-4 border-left pl-3">
                <h5 class="text-uppercase mb-4 font-weight-bold">Link utili</h5>
                <p><a class="text-light" href="{{ route('index') }}">Home Page</a></p>
                <p><a class="text-light" href="{{ route('top50') }}">Top Tracks</a></p>
                <p><a class="text-light" href="{{ route('register') }}">Registrati</a></p>
            </div>
            <div class="col-sm-12 col-md-5 mx-auto mt-4 border-left pl-3">
                <h5 class="text-uppercase mb-4 font-weight-bold">Contatti</h5>
                <p><i class="fa fa-envelope mr-3"></i><a class="text-white" href="mailto:unison@altervista.org">unison@altervista.org</a></p>
                <h5 class="text-uppercase my-4 font-weight-bold">Extra</h5>
                <p><i class="fas fa-dice-three mr-3"></i><a class="text-white" target="_blank" href="{{ asset("/er_redirect.html") }}">Lancia un dado</a></p>
            </div>
        </div>
        <div class="row py-3 d-flex align-items-center text-light mt-3 pt-3 border-top">
            <div class="col-12">
                <p class="text-center text-md-left grey-text">© 2018 Copyright: <a href="/" class="text-light"><strong> Unison</strong></a></p>
            </div>
        </div>
    </div>
</footer>