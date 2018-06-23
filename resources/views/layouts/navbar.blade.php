<nav class="navbar navbar-expand-md navbar-dark bg-secondary fixed-top navbarMinHeight">
    <a class="navbar-brand" href="/">
        <div class="navLogo container container-fluid h-100">
            &nbsp;
        </div>
    </a>
    <div class="d-flex flex-row order-0 order-md-3">
        <ul class="navbar-nav flex-row px-2 px-md-0 text-nowrap">
            @if(auth()->check())
                <li class="nav-item">
                    <a class="nav-link px-2" href="{{ route('modify') }}">
                        <span class="fas fa-cogs"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-2" href="{{ asset("/user/" . auth()->user()->username) }}">
                        <span class="fas fa-user"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-2" href="{{ route('logout') }}">
                        <span class="d-none d-sm-inline">Esci</span>
                        <span class="fas fa-sign-out-alt"></span>
                    </a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link px-2" href="{{ route('login') }}">
                        Accedi
                        <span class="fas fa-sign-in-alt"></span>
                    </a>
                </li>
            @endif
        </ul>
        <button class="navbar-toggler btn-outline-primary burgerFocus" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto text-nowrap">
            @if(!auth()->check())
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">Iscriviti</a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">Feed</a>
                </li>
            @endif
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Top Charts
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="#">Artisti</a>
                    <a class="dropdown-item" href="#">Brani</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </li>
        </ul>
        <div class="w-100 mx-0 mx-md-3">
            <form>
                <div class="input-group">
                    <input type="search" class="form-control" placeholder="Cerca...">
                    <div class="input-group-append">
                        <div class="btn btn-default btn-primary btn-outline-light"><span class="fas fa-search"></span></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</nav>