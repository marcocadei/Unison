<!doctype html>
<html lang="it">
    <head>
        <!-- Meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Token usato da Laravel per proteggere l'utente rispetto a determinati tipi di attacchi -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Fonts -->
        <link href='//fonts.googleapis.com/css?family=Montserrat:thin,extra-light,light,regular,medium,bold,100,200,300,400,500,600,700,800' rel='stylesheet' type='text/css'>

        <!-- Fogli di stile -->
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}"> <!-- Modificato con i colori del tema Pulse -->
        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Nostri CSS -->
        <link rel="stylesheet" href="{{asset('/css/style.css')}}">

        <!-- Script (1) -->
        <!-- FontAwesome -->
        <script defer src="https://use.fontawesome.com/releases/v5.6.3/js/all.js" integrity="sha384-EIHISlAOj4zgYieurP0SdoiBYfGJKkgWedPHH4jCzpCXLmzVsw1ouK59MuUtP4a1" crossorigin="anonymous"></script>

        <!-- File specifici della pagina -->
        @yield('script_header')

        <!-- Per altri script vedi in coda al body -->

        <title>
            @yield('title')
        </title>

    </head>
    <body class="generalFont bg-light @yield('body_classes')">
        @include('layouts.navbar')
        @yield('content')
        @if(!isset($dropFooter) || !$dropFooter)
            @include('layouts.footer')
        @endif
        <!-- Script (2) -->
        <!-- Bootstrap: jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"
                integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
                crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"
                integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ"
                crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"
                integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm"
                crossorigin="anonymous"></script>

        <!-- Searchbar -->
        <script type="text/javascript" src="{{asset('/js/searchbarCheck.js')}}"></script>

        <!-- Sezione per script specifici della pagina -->
        @yield('script_footer')
    </body>
</html>
