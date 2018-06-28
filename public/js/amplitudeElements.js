/*
    Contiene tutte le funzioni collegate al player audio e/o utilizzate da quest'ultimo
 */

/**********************************************************
    Funzioni di callback
*/

/*
    Nota: Le funzioni di callback sono definite in questo modo poiché in generale è necessario eseguire più funzioni
    all'attivazione di un evento, ma Amplitude consente di definire esclusivamente una singola funzione di callback
    per ciascun evento; in pratica, le funzioni definite di seguito sono solo wrapper che chiamano altre funzioni in
    modo che vengano tutte eseguite all'attivazione di un certo evento.
 */

/**
 * Funzione eseguita all'attivazione dell'evento "after_play".
 */
function afterPlayCallback() {
    // Spostamento dell'elemento puntina alla traccia attualmente in riproduzione.
    setThumbtackDestination();
    // (Ri-)attivazione del timer per l'aggiornamento del contatore riproduzioni.
    resumePlayCountTimer();
}

/**
 * Funzione eseguita all'attivazione dell'evento "song_change".
 */
function songChangeCallback() {
    // Attivazione/disattivazione dell'elemento testuale che visualizza il conteggio delle ore.
    toggleCurrentHoursSpan();
    // Reset del timer per l'aggiornamento del contatore riproduzioni.
    resetPlayCountTimer();
}

/**
 * Funzione eseguita all'attivazione dell'evento "after_pause".
 */
function afterPauseCallback() {
    // Disattivazione del timer per l'aggiornamento del contatore riproduzioni.
    pausePlayCountTimer();
}

/**********************************************************
    Conteggio delle riproduzioni
*/

/*
    [ Note sul conteggio delle riproduzioni ]

    Il conteggio delle riproduzioni funziona nel seguente modo:
    - quando l'utente avvia la riproduzione di un brano, viene avviato un timer la cui durata è pari ad una certa
        percentuale della lunghezza della traccia in riproduzione;
    - quando la traccia è messa in pausa il timer è congelato, e riprende a scorrere quando la riproduzione è
        riavviata;
    - quando l'utente cambia brano, il timer è resettato e la durata viene modificata in base alla lunghezza della
        nuova traccia in riproduzione.

    Si sottolinea che il conteggio delle riproduzioni NON tiene in alcun modo conto della porzione di traccia
    effettivamente ascoltata dall'utente. Ad esempio, per una traccia della durata di un minuto, se la soglia
    percentuale è impostata al 50%, la riproduzione dei primi 30 secondi del brano e la riproduzione "in loop" dei
    primi 3 secondi per 10 volte (senza mai cambiare traccia) avranno lo stesso effetto e il numero di riproduzioni
    sarà comunque incrementato di un'unità.

    Alla scadenza del timer, questo NON viene fermato, bensì riparte con la medesima durata. Questa è una scelta
    voluta, che comunque si traduce nella possibilità di creare "falsi conteggi" se l'utente continua ad ascoltare
    la stessa traccia senza mai cambiare brano. Ad esempio, se la soglia percentuale è impostata al 50%, ascoltare il
    brano nella sua interezza avrà l'effetto di incrementare di DUE unità il numero di riproduzioni, ascoltare il brano
    per due volte (senza cambiare traccia) avrà l'effetto di incrementare di QUATTRO unità il numero di riproduzioni,
    e così via.

    Infine: Il timer NON tiene conto dello stato attuale del caricamento del file audio (Amplitude non offre un
    sistema di callback anche per il buffering, quindi non è tecnicamente possibile); questo vuol dire che su
    connessioni lente, o comunque quando il file audio viene caricato ad una velocità inferiore a quella con cui la
    traccia è riprodotta (cioè quando la riproduzione "va a scatti"), il timer tiene conto anche del periodo di tempo
    in cui l'utente effettivamente non sta ascoltando la traccia dato che questa non si è ancora caricata.
 */

/**
 * Resetta il timer per l'aggiornamento del contatore riproduzioni.
 */
function resetPlayCountTimer() {
    /*
        Stop (e reset) del timer eventualmente attivo.
     */
    playCountTimer.stop();
    /*
        Calcolo della nuova durata del timer (soglia oltre la quale si incrementa il numero di riproduzioni).
        La percentuale della lunghezza della traccia che è necessario ascoltare prima che sia incrementato il numero
        di riproduzioni è dato dal valore della variabile "percentage".

        Nota: Nel calcolo del valore di "playCountThreshold" la durata viene moltiplicata per 1000 in quanto il timer
        richiede che sia specificata in millisecondi.
     */
    let percentage = 0.75;
    let currentSongDuration = Amplitude.getActiveSongMetadata().duration;
    let playCountThreshold = Math.floor((currentSongDuration * 1000) * percentage);
    /*
        Definizione del nuovo timer (che rimane fermo: verrà attivato quando si scatena l'evento "after_play").
     */
    playCountTimer = $.timer(updatePlayCount, playCountThreshold, false);
}

/**
 * Ferma il timer per l'aggiornamento del contatore riproduzioni.
 */
function pausePlayCountTimer() {
    playCountTimer.pause();
}

/**
 * Fa ripartire il timer per l'aggiornamento del contatore riproduzioni.
 */
function resumePlayCountTimer() {
    playCountTimer.play();
}

/**
 * Aggiorna il contatore delle riproduzioni.
 */
function updatePlayCount() {
    /*
        Richiama la pagina contenente la query che aggiorna il database aggiungendo 1 al numero di riproduzioni della
        traccia attualmente riprodotta dall'utente.
     */

    console.log("+1"); // FIXME da rimuovere

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.post("/listened/" + parseInt(Amplitude.getActiveSongMetadata().id), {}, function (data, status, xhr) {}, "json");
}

/**********************************************************
    Attivazione e disattivazione del pulsante di play globale e della navbar inferiore
*/

/**
 * Disabilita tutti i bottoni della navbar inferiore.
 * Nota: Lo slider del volume è comunque ancora modificabile, ma dato che non ci sono brani in riproduzione la modifica
 * del suo valore non ha alcun effetto.
 */
function disableMainPlayButton() {
    $("#audioNavButtons > li > a").addClass("disabledAnchor");
}


// TODO scrivere il codice per la riattivazione/ridisattivazione dei bottoni della navbar inferiore quando si aggiornano
// le tracce con Amplitude.bindNewElements