/*
    Le funzionalità di questo script sono utilizzate in tutte e sole le pagine che hanno il layout "a tre colonne"
    (feed, pagina utente, eccetera).
 */

/**********************************************************
    Definizione variabili globali
 */

let viewportIsXL = false;
let leftCol;
let rightCol;
let middleCol;
/**
 * Bottone "torna su"
 */
let topButton;
/**
 * Elemento "puntina" della navbar inferiore; se premuto, scrolla all'elemento indicato da {@link thumbtackDestination}.
 */
let thumbtack;
/**
 * Destinazione dell'elemento puntina.
 */
let thumbtackDestination;
/**
 * Elemento invisibile utilizzato per saltare ad un'altra traccia audio.
 */
let skipper;

/**********************************************************
    Inizializzazione elementi
 */

$(document).ready(initizalizePageElements);

/**
 * Setta tutte le variabili globali definite in questo script.
 */
function initizalizePageElements() {
    if ($(window).width() > 1200) {
        viewportIsXL = true;
    }

    rightCol = $("#rightCol");
    leftCol = $("#leftCol");
    middleCol = $("#middleCol");
    $("#rightColToggler").click(toggleRightCol);
    $("#leftColToggler").click(toggleLeftCol);

    topButton = $('#backBtn');
    topButton.click(topFunction);
    initializeTopButton();
    $(window).scroll(toggleTopButton);

    thumbtack = $("#thumbtack");
    thumbtackDestination = middleCol;
    thumbtack.click(scrollToCurrentTrack);

    skipper = $("#skipper");
}

/**********************************************************
    Gestione colonne laterali
 */

/**
 * Attiva la colonna di sinistra chiudendo tutte le altre colonne eventualmente aperte.
 */
function toggleLeftCol() {
    if (!rightCol.hasClass("d-none")) {
        rightCol.toggleClass("d-none");
    }
    else {
        middleCol.toggleClass("d-none");
    }
    leftCol.toggleClass("d-none");
}

/**
 * Attiva la colonna di destra chiudendo tutte le altre colonne eventualmente aperte.
 */
function toggleRightCol() {
    if (!leftCol.hasClass("d-none")) {
        leftCol.toggleClass("d-none");
    }
    else {
        middleCol.toggleClass("d-none");
    }
    rightCol.toggleClass("d-none");
}

/*
    Quando si passa da una view piccola ad una view grande (in cui sono mostrate tutte e tre le colonne) le colonne
    precedentemente nascoste devono essere rese nuovamente visibili.
 */
$(window).resize(resetSideColumnsAtResize);

/**
 * Rende visibili o nasconde le colonne laterali quando viene ridimensionata la finestra.
 */
function resetSideColumnsAtResize() {
    if (viewportIsXL && $(window).width() < 1200) {
        viewportIsXL = false;
        hideSideColumns();
    }
    else {
        if (!viewportIsXL && $(window).width() > 1200) {
            viewportIsXL = true;
            showSideColumns();
        }
    }
}

/**
 * Nasconde le colonne laterali e mostra la colonna centrale.
 */
function hideSideColumns() {
    leftCol.addClass("d-none");
    rightCol.addClass("d-none");
    middleCol.removeClass("d-none");
}

/**
 * Mostra le colonne laterali e quella centrale.
 */
function showSideColumns() {
    leftCol.removeClass("d-none");
    rightCol.removeClass("d-none");
    middleCol.removeClass("d-none");
}

/**********************************************************
    Gestione top button e scrolling
 */

/**
 * Rende invisibile il bottone "torna su" al primo caricamento della pagina.
 */
function initializeTopButton() {
    topButton.hide();
    topButton.tooltip('hide');
    topButton.toggleClass("invisible");
}

/**
 * Mostra o nasconde il bottone "torna su" a seconda della posizione nella pagina corrente.
 */
function toggleTopButton() {
    if ($(window).scrollTop() > $(window).height()) {
        topButton.tooltip();
        topButton.fadeIn();
    }
    else {
        topButton.fadeOut();
        topButton.tooltip('hide');
    }
}

/**
 * Esegue l'animazione che riporta in cima alla pagina.
 */
function topFunction() {
    $('body, html').animate({scrollTop: 0}, 100);
}

/**********************************************************
    Gestione elemento "puntina" (scroll alla traccia attuale)
 */

/**
 * Imposta la destinazione della puntina al div che contiene la traccia attualmente in riproduzione.
 */
function setThumbtackDestination() {
    thumbtackDestination = $("#middleCol span.amplitude-playing:first").parents(".audioElementContainer");
}

/**
 * Esegue l'animazione che riporta alla traccia attualmente in riproduzione.
 */
function scrollToCurrentTrack() {
    $('body, html').animate({scrollTop: thumbtackDestination.position().top}, 'fast');
}

/**********************************************************
    Funzioni legate al player audio
*/

/*
    Impedisce che la pressione della barra spaziatrice faccia scrollare la pagina (è il comportamento di default, ma
    nelle pagine in cui c'è un player audio la barra spaziatrice è associata alla riproduzione).
 */
window.onkeydown = function(e) {
    return !(e.keyCode == 32);
};

/*
 * Aggiunge ad ogni barra relativa ad una traccia audio l'handler dell'evento onclick.
 */
$(document).ready(function() {
    var isChrome = !!window.chrome && !!window.chrome.webstore;
    var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
    var isBlink = (isChrome || isOpera) && !!window.CSS;

    /*
        Il metodo setSongPlayedPercentage non funziona nei browser Blink-based, quindi in questi ultimi viene
        semplicemente aggiunta la funzionalità che permette di avviare una traccia (dall'inizio) se si fa click
        sulla barra di una traccia diversa da quella attualmente in riproduzione.
     */
    if (!(isBlink || isOpera || isChrome)) {
        $('.amplitude-song-played-progress').on('click', function(e) {
            var offset = this.getBoundingClientRect();
            var x = e.pageX - offset.left;

            /**
             * Percentuale della traccia corrispondente al punto in cui si è cliccata la barra.
             * @type {number}
             */
            var percentage = (parseFloat(x) / parseFloat(this.offsetWidth));
            /**
             * Indice della traccia attualmente in riproduzione.
             * @type {number}
             */
            var activeIndex = Amplitude.getActiveIndex();
            /**
             * Indice della traccia corrispondente alla barra cliccata. Viene utilizzato substr(21) perchè ogni barra
             * ha un id pari a "song-played-progress-[numero]" e quindi il valore numerico inizia al ventunesimo
             * carattere.
             * @type {string}
             */
            var clickedIndex = this.id.substr(21);
            /**
             * Indice della traccia di destinazione: corrisponde sempre ad activeIndex a meno che non sia stata cliccata
             * la barra nella navbar inferiore, in qual caso la destinazione è la traccia attuale.
             * @type {string}
             */
            var targetIndex = this.id === "amplitude-main-progress" ? activeIndex : clickedIndex;

            if(activeIndex != targetIndex) {
                /*
                    Se la traccia di destinazione non è quella attuale bisogna simulare il click dell'elemento "skipper"
                    in modo da saltare ad una traccia differente (Amplitude non mette a disposizione un metodo
                    nativo per cambiare traccia).
                 */
                var trackLength = songsData[targetIndex].duration;
                var seconds = percentage * trackLength;
                skipper.attr("amplitude-song-index", targetIndex);
                skipper.attr("amplitude-location", seconds);
                skipper.click();
            }
            else {
                Amplitude.setSongPlayedPercentage(percentage * 100);
            }
        });
    }
    else {
        /*
            Versione ridotta per i browser Blink-based che permette solo di vviare una traccia dall'inizio se si fa
            click sulla barra di una traccia diversa da quella attualmente in riproduzione.
         */
        $('.amplitude-song-played-progress').on('click', function(e) {
            var activeIndex = Amplitude.getActiveIndex();
            var clickedIndex = this.id.substr(21);
            var targetIndex = this.id === "amplitude-main-progress" ? activeIndex : clickedIndex;

            if(activeIndex != targetIndex) {
                skipper.attr("amplitude-song-index", targetIndex);
                skipper.click();
            }
        });
    }
});