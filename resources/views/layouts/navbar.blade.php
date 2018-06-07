<nav class="navbar navbar-expand-lg navbar-dark bg-secondary fixed-top">
    <!-- Non fa impostare la grangezza -->
    <!--<a class="navbarIcon mr-3" href="login.html">-->
    <!--<img src="./images/diapasonBiancoDefinitivo.png" alt="Logo" class="img-fluid">-->
    <!--</a>-->
    <a class="navbar-brand" href="#">
        <!-- FIXME Sistemare dimensione immagine logo -->
        <img src="images/bigLogo.png" width="60" height="30" alt="Logo Unison">
    </a>
    <div class="d-flex flex-row order-0 order-lg-3">
        <ul class="navbar-nav flex-row">
            <li class="nav-item"><a class="nav-link px-2" href="#"><span class="fas fa-cogs"></span></a></li>
            <li class="nav-item"><a class="nav-link px-2" href="#"><span class="fas fa-user"></span></a></li>
        </ul>
        <button class="navbar-toggler btn-outline-primary burgerFocus" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="#">Iscriviti</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Accedi</a>
            </li>
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
                <!--<div class="dropdown">-->
                <!--<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
                <!--Dropdown button-->
                <!--</button>-->
                <!--<div class="dropdown-menu" aria-labelledby="navbarDropdown">-->
                <!--<a class="dropdown-item" href="#">Artisti</a>-->
                <!--<a class="dropdown-item" href="#">Brani</a>-->
                <!--<div class="dropdown-divider"></div>-->
                <!--<a class="dropdown-item" href="#">Something else here</a>-->
                <!--</div>-->
                <!--</div>-->
            </li>
        </ul>
        <form class="my-auto mx-2 d-inline w-75">
            <div class="input-group-append my-0">
                <input class="my-0 form-control mr-2 flex-fill" type="search" placeholder="Cerca..." aria-label="Cerca">
                <span class="input-group-append">
                            <button class="btn btn-outline-light my-2 my-sm-0" type="submit"><span class="fas fa-search"></span></button>
                        </span>
            </div>
        </form>

    </div>
</nav>