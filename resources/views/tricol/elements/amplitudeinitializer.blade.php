{{--
    Inizializzazione dell'oggetto Amplitude con i dati delle tracce audio.
    L'inizializzazione vera e propria viene eseguita dal file /public/js/amplitudeInitializer.js; qui è presente
    una singola istruzione che permette di trasferire il contenuto di una variabile PHP (il cui contenuto è
    recuperato dal database) in una variabile JS.
--}}

<script>
    /*
        Questa istruzione serve a caricare nella variabile globale JS songsData (di tipo stringa) il contenuto della
        variabile PHP $songs (sotto forma di JSON).

        Nota 1: Viene segnalato un errore di sintassi ma l'istruzione è corretta e funziona!
        Nota 2: Viene utilizzata la parentesi con punti esclamativi anziché la doppia parentesi per evitare il
        passaggio per la funzione htmlspecialchars (altrimenti ad esempio gli apici verrebbero trasformati in &quot; e
        così via).
     */
    let songsData = {!! json_encode($songs) !!};

</script>
<script type="text/javascript" src="{{asset("js/amplitudeInitializer.js")}}"></script>