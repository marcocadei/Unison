/*
    Questo script è utilizzato per inizializzare/aggiornare l'oggetto Amplitude con i dati delle tracce audio.

    FONDAMENTALE: Prima di importare questo script nel file HTML deve SEMPRE essere stata precedentemente definita
    la variabile globale songsData!
 */

let amplitudeData = {
    "autoplay": false, // Al caricamento della pagina la riproduzione non parte in automatico
    "default_album_art": "/dbres/trackthumbs/trackdefault.png",
    "debug": "true",
    "volume": 50,
    "volume_increment": 5,
    "volume_decrement": 5,
    "bindings": {
        "32": "play_pause", // Con il tasto spazio si avvia o si interrompe la riproduzione
        "37": "prev", // Con il tasto freccia sinistra si torna alla traccia precedente
        "39": "next", // Con il tasto freccia destra si va alla traccia successiva
        "83": "shuffle" // Con il tasto S si attiva/disattiva la modalità casuale
    },
    "callbacks": {
        /*
            Ogni volta che viene avviata una traccia viene aggiornata di conseguenza la destinazione dell'elemento
            puntina in modo che punti al contenitore della traccia attualmente in riproduzione.
         */
        "after_play": setThumbtackDestination,
        "song_change": toggleCurrentHoursSpan
    },
    "songs": songsData
};
Amplitude.init(amplitudeData);

/**********************************************************
    Attivazione e disattivazione del pulsante di play globale
*/

/**
 * Se non ci sono tracce, tutti i bottoni della navbar inferiore vengono disabilitati.
 */
if (songsData.length == 0) {
    disableMainPlayButton();
}

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