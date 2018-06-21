<nav class="navbar navbar-expand-md navbar-light bg-light fixed-bottom navbarMinHeight border-primary border-top">
    <div class="d-flex flex-row order-0 w-100 pr-2">
        <div class="d-flex flex-row order-0">
            <ul class="navbar-nav flex-row" id="audioNavButtons">
                <li class="nav-item"><a class="nav-link px-2 cursorPointer">
                    <span class="amplitude-prev">
                        <span class="fas fa-chevron-left"></span>
                    </span>
                </a></li>
                <li class="nav-item"><a class="nav-link px-2 cursorPointer">
                    <span class="amplitude-play-pause" amplitude-main-play-pause="true">
                    </span>
                </a></li>
                <li class="nav-item"><a class="nav-link px-2 cursorPointer">
                    <span class="amplitude-next">
                        <span class="fas fa-chevron-right"></span>
                    </span>
                </a></li>
                <li class="nav-item"><a class="nav-link px-2 cursorPointer">
                    <span class="amplitude-shuffle">
                        <span class="fas fa-random"></span>
                    </span>
                </a></li>
                <li class="nav-item d-none"><a class="nav-link px-2 cursorPointer">
                    <span class="amplitude-repeat">
                        <span class="fas fa-redo"></span>
                    </span>
                </a></li>
                <li class="nav-item"><a class="nav-link px-2 cursorPointer" id="thumbtack">
                    <span class="fas fa-thumbtack"></span>
                </a></li>
                <li class="nav-item"><a class="nav-link px-2 cursorPointer">
                    <span class="amplitude-mute"></span>
                </a></li>
            </ul>
        </div>
        <div class="d-flex flex-row w-100 my-auto">
            <div class="align-items-center w-100">
                <input type="range" class="d-block amplitude-volume-slider w-100"/>
            </div>
        </div>
    </div>

    <div class="collapse navbar-collapse order-last w-100">
        <ul class="navbar-nav mr-2 text-nowrap">
            <li class="nav-item d-none"><a class="nav-link">
                <span class="smallText" amplitude-song-info="name" amplitude-main-song-info="true">Current Track Name</span>
            </a></li>
            <li class="nav-item text-monospace">
                <span class="d-none" id="mainCurrentHours"><span class="amplitude-current-hours" amplitude-main-current-hours="true"></span>:</span><span class="amplitude-current-time" amplitude-main-current-time="true"></span>
            </li>
        </ul>
        <div class="w-100 mx-0 pr-2">
            <form>
                <div class="position-relative">
                    <progress id="amplitude-main-progress" class="amplitude-song-played-progress over w-100" amplitude-main-song-played-progress="true"></progress>
                    <progress id="song-buffered-progress-0" class="amplitude-buffered-progress under w-100" value="0"></progress>
                </div>
            </form>
        </div>
    </div>
</nav>