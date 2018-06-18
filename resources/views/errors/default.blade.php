<!--
    Pagina di errore che è invocata per ogni possibile errore HTTP che si verifichi.
    Il codice di errore stampato non è fisso, ma varia in funzione dell'errore verificatosi.
    Questa view è invocata nel metodo render di \App\Exceptions\Handler.php
-->
<!doctype html>
<html lang="it">
    <head>
        <!-- Meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Fonts -->
        <link href='//fonts.googleapis.com/css?family=Montserrat:thin,extra-light,light,regular,medium,bold,100,200,300,400,500,600,700,800' rel='stylesheet' type='text/css'>

        <!-- Fogli di stile -->
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}"> <!-- Modificato con i colori del tema Pulse -->
        <!-- Nostri CSS -->
        <link rel="stylesheet" href="{{asset('/css/style.css')}}">

        <title>
            Errore {{ $exception->getStatusCode() }}
        </title>

    </head>
    <body>
        <div class="container md-12">
            <div class="row justify-content-center">
                <h1>
                    Ooops! Si è verificato un errore {{ $exception->getStatusCode() }}!
                </h1>
            </div>
            <div class="row justify-content-center">
                @php($names = array('Roscigno', 'Mastro Pitossi', 'Marco Cadei'))
                @php($selected = $names[rand(0, 2)])
                Abbiamo attivato&nbsp;<a target="_blank" href="https://www.google.com/search?q={{ $selected }}">{{ $selected }}</a>&nbsp;per risolvere il problema
            </div><br>
            <div class="row justify-content-center">
                <a href="{{ route('index') }}">
                    <button type="button" class="btn btn-primary">Ritorna alla pagina principale</button>
                </a>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.3.1.min.js"
                integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
                crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"
                integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ"
                crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"
                integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm"
                crossorigin="anonymous"></script>
    </body>
</html>