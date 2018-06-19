/*
    Questo script è utilizzato per inizializzare/aggiornare l'oggetto Amplitude con i dati delle tracce audio.

    FONDAMENTALE: Prima di importare questo script nel file HTML deve SEMPRE essere stata precedentemente definita
    la variabile globale songsData!
 */

/**
 *  Variabile globale che contiene i dati con cui è inizializzato l'oggetto player audio Amplitude.
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
        "after_play": afterPlayCallback,
        "song_change": songChangeCallback,
        "after_pause": afterPauseCallback
    },
    "songs": songsData
};
Amplitude.init(amplitudeData);

/**
 * Variabile globale; timer utilizzato per l'aggiornamento del numero di riproduzioni di una traccia.
 * @type {Timer}
 */
let playCountTimer = $.timer(updatePlayCount, 999999999, false);

/**********************************************************
    Altre operazioni da eseguire in fase di inizializzazione
*/

/**
 * Se c'è almeno una tracca, il timer viene inizializzato in base alla durata della prima traccia in elenco
 * (ma non viene fatto partire).
 */
if (songsData.length > 0) {
    resetPlayCountTimer();
}

/**
 * Se non ci sono tracce, tutti i bottoni della navbar inferiore vengono disabilitati.
 */
if (songsData.length == 0) {
    disableMainPlayButton();
}