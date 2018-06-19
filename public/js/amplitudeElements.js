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

    // TODO da fare!
    console.log("+1");
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