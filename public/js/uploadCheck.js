maxLengthTitle = 64;
maxLengthDescription = 200;
trackChoosed = false;
// La scelta dell'immagine non è obbligatoria
imageChoosed = true;
let objectUrl;

$(document).ready(function () {
    // Dopo aver selezionato una traccia aggiorno la textbox corrispondente
    // in modo che contenga il titolo della traccia selezionata
    $("#trackSelect").on('change',function(){
        trackChoosed = true;
        trackName = getChooserName($(this));
        // Sostituisco la scritta "Scegli file..."
        if (trackName.length > 0)
            $(this).next('.custom-file-label').html(trackName);
        else {
            $(this).next('.custom-file-label').html("Scegli file...");
            trackChoosed = false;
        }
        if (trackName.length > 0) {
            let trackFormat = trackName.substring(trackName.lastIndexOf("."));
            let allowedFormat = [".mp3", ".m4a"];
            if (typeof this.files[0] !== 'undefined' && allowedFormat.indexOf(trackFormat) != -1) {
                //  Inizializzo il tag audio con il file caricato
                objectUrl = URL.createObjectURL(this.files[0]);
                $("#audio").prop("src", objectUrl);

                // Controllo che il file caricato non superi la dimensione massima consentita
                let maxFileSize = parseInt($("#maxFileSize").val());
                // Dato che la maxFileSize che mi ridà il server è in MB mentre this.files[0].size va in bit
                // per effettuare il confronto devo riportare i MB in bit
                if (this.files[0].size > maxFileSize * 1024 * 1024) {
                    //$("#trackSelect").addClass("is-invalid");
                    trackChoosed = false;
                }
                else {
                    $("#trackSelect").removeClass("is-invalid");
                    trackChoosed = true;
                }

                // Dopo aver selezionato la traccia propongo all'utente come titolo il nome
                // del file (senza il formato), ma è sempre possibile cambiarlo
                // Se il nome del file è più lungo di #maxLengthTitle allora mantengo solo i primi #maxLengthTitle
                // caratteri
                let ultimo_punto = trackName.lastIndexOf(".");
                $("#title").val((trackName.substring(0, ultimo_punto)).replace(/[^\x20-\x7E]/gi, '').substring(0, maxLengthTitle));
                checkFileField($("#trackSelect"), trackChoosed);
                checkTitle(null, $("#title"), maxLengthTitle);
            }
            else {
                //$("#trackSelect").addClass("is-invalid");
                trackChoosed = false;
            }
        }
        else {
            //$("#trackSelect").addClass("is-invalid");
            trackChoosed = false;
        }
    });

    // Dopo aver selezionato una foto aggiorno la textbox corrispondente
    // in modo che contenga il titolo della foto selezionata
    $("#photoSelect").on('change',function(){
        photoName = getChooserName($(this));
        //replace the "Choose a file" label
        if (photoName.length > 0)
            $(this).next('.custom-file-label').html(photoName);
        else{
            $(this).next('.custom-file-label').html("Scegli file...");
        }

        if (photoName.length > 0) {
            // Codice javascript per recuperare le dimensioni dell'immagine
            // selezionata dall'utente
            let _URL = window.URL || window.webkitURL;
            let file, img;
            if (file = this.files[0]) {
                let imageFormat = photoName.substring(photoName.lastIndexOf("."));
                let allowedFormat = [".jpg", ".jpeg", ".png"];

                if (allowedFormat.indexOf(imageFormat) != -1) {
                    img = new Image();
                    img.onload = function () {
                        if (this.width != this.height || this.width < 150) {
                            //$("#photoSelect").addClass("is-invalid");
                            imageChoosed = false;
                        }
                        else {
                            $("#photoSelect").removeClass("is-invalid");
                            imageChoosed = true;
                        }
                    };
                    img.src = _URL.createObjectURL(file);
                }
                else {
                    //$("#photoSelect").addClass("is-invalid");
                    imageChoosed = false;
                }
            }
            checkFileField($("#photoSelect"), imageChoosed);
        }
        else{
            $("#photoSelect").removeClass("is-invalid");
            imageChoosed = true;
        }
    });

    // Ottengo la durata della canzone e la metto in un elemento nasconto per trasmetterla al server
    $("#audio").on("canplaythrough", function(e) {
        var seconds = e.currentTarget.duration;
        $("#duration").val(parseInt(seconds));
        URL.revokeObjectURL(objectUrl);
    });

    // Controllo sui campi titolo e descrizione
    //$("#title").keyup(function(event) {checkTitle(event, this, maxLengthTitle)});
    //$("#description").keyup(function(event) {checkDescription(event, this, maxLengthDescription)});
    $("#title").focusin(function (event) {
        $("#title").removeClass("is-invalid");
        $("#formUpload").removeClass("is-invalid");
    });
    $("#description").focusin(function (event) {
        $("#description").removeClass("is-invalid");
    });
    $("#photoSelect").focusin(function (event) {
        $("#photoSelect").removeClass("is-invalid");
    });
    $("#trackSelect").focusin(function (event) {
        $("#trackSelect").removeClass("is-invalid");
    });

    // Prima di caricare la canzone controllo che tutti i campi siano compilati come richiesto
    //$("#buttonUpload").click(validateUpload);
    $("#buttonUpload").click(function (event) {
        event.preventDefault();
        checkDeletedTrack($("#trackSelect"), trackChoosed, event);
    });

});


function validateUpload(event, checkFile) {
    // Di default disabilito il submit della form, che effettuo solo dopo
    // che sia i controlli lato client che lato server sono stati superati
    event.preventDefault();

    // Rimuove il messaggio d'errore alla nuova pressione del tasto di submit.
    $("#formUpload").removeClass("is-invalid");

    let nextPage = true;
    // nextPage &= checkFile;
    nextPage &= checkFileField($("#trackSelect"), trackChoosed, false);
    nextPage &= checkFileField($("#photoSelect"), imageChoosed, false);
    nextPage &= checkTitle(event, $("#title"), maxLengthTitle);
    nextPage &= checkDescription(event, $("#description"), maxLengthDescription);

    // Se i controlli lato client hanno successo, prima di procedere alla pagina successiva devo
    // controllare che la canzone che si vuole caricare non sia già stata caricata in precedenza
    if(nextPage){
        // Disabilito il bottone di submit per evitare che la form sia trasmessa più di una volta
        $("#buttonUpload").attr("disabled", true);
        // Ho dovuto aggiungere questa parte perché Laravel usa dei token nella form per proteggere
        // l'utente da determinati tipi di attacco
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post("/checkSongExistence",
            {
                //file: "public/tracks/"+$("#title").val()+"_"+$("#userID").val()+trackName.substring(trackName.lastIndexOf("."))
                userID: $("#userID").val(),
                title: $("#title").val()
            }, function (data, status, xhr) {
                if (data.result) {
                    // Prima di terminare l'upload controllo se la canzone è presente su spotify
                    checkSpotify();
                }
                else {
                    $("#formUpload").addClass("is-invalid");
                    // Se i dati inseriti erano sbagliati allora riabilito il bottone di caricamento
                    $("#buttonUpload").attr("disabled", false);
                }
            }, "json");
    }
}

function checkSpotify(){
    // Con questo recupero il token
    var token = null;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.post("/spotify/token",
        {},
        function (data, status, xhr) {
            token = data.result;
            searchSong(token);
        },
        "json");
}

function searchSong(token){
    let artist = $("#author").val();
    let track = $("#title").val();

    // Questo rimuove l'header X-CSRF-TOKEN dalla richiesta ajax
    // Prima lo metto perché mi serve per ragioni di sicurezza imposte da Laravel
    // Ora lo tolgo perché spotify non si aspetta quell'header nella richiesta
    delete $.ajaxSettings.headers['X-CSRF-TOKEN'];
    //'https://api.spotify.com/v1/search?q=track:Numb%20artist:Linkin%20Park&type=track&limit=1'
    $.ajax({
        url: 'https://api.spotify.com/v1/search?q=track:' + track + '%20artist:' + artist + '&type=track&limit=1',
        headers: {
            Authorization: 'Bearer ' + token
        }
    })
        .then(function(oData) {
            mostraInfoSpotify(oData);
        })
        .fail(function () {
            // Se c'è un errore con spotify (magari il servizio non è disponibile) allora memorizzo la canzone
            // senza alcuna associazione
            $("#upload").submit();
        })

}

function mostraInfoSpotify(data){
    // Mostro la finestra di spotify solo se ho trovato qualcosa e l'artista trovato è colui che sta
    // caricando la canzone, altrimenti non faccio nulla
    if(data.tracks.items.length > 0 && data.tracks.items[0].artists[0].name.includes($("#author").val())) {
        // Recupero l'id dell'elemento che è stato premuto per uscire dalla finestra modale
        // in base al risultato che ottengo effettuo delle operazioni diverse
        $('#spotifyModal').on('hide.bs.modal', function (e) {
            var tmpid = $(document.activeElement).attr('id');
            // Se l'utente ha deciso di collegare la canzone allora devo aggiungere l'ID Spotify corretto
            if (tmpid == "collegaModal"){
                $("#spotifyID").val(data.tracks.items[0].id);
            }
            $("#upload").submit();
        });
        // Mostro la finestra modale, ma prima aggiungo le informazioni relative alla canzone
        $("#canzoneModale").append("<b>Canzone:</b> " + data.tracks.items[0].name)
        $("#artistaModale").append("<b>Artista:</b> " + data.tracks.items[0].artists[0].name)
        $("#albumModale").append("<b>Album:</b> " + data.tracks.items[0].album.name)
        $('#spotifyModal').modal({
            backdrop: 'static',
            keyboard: false
        });
    }
    else{
        $("#upload").submit();
        //document.getElementById("upload").submit();
    }
}


function checkTitle(event, field, maxLength) {

    const regex = /^[\x20-\x7E\xC0-\xFF]+$/;

    if (($(field).val().length == 0 || $(field).val().length > maxLength || !$(field).val().match(regex))
        && ((event!= null && event.keyCode != 9) || (event!= null && event.keyCode == 9 && $(field).val().length != 0)  || event == null)) {
        $(field).addClass("is-invalid");
        return false;
    }
    else {
        $(field).removeClass("is-invalid");
        return true;
    }
}

function checkDescription(event, field, maxLength) {

    const regex = /^[\x20-\x7E\xC0-\xFF]*$/;

    if (($(field).val().length > maxLength || !$(field).val().match(regex))
        && ((event!= null && event.keyCode != 9) || (event!= null && event.keyCode == 9 && $(field).val().length != 0)  || event == null)) {
        $(field).addClass("is-invalid");
        return false;
    }
    else {
        $(field).removeClass("is-invalid");
        return true;
    }
}

function checkFileField(field, choosed, deleted) {
    if(!choosed)
        field.addClass("is-invalid");
    // Se la traccia è selezionata
    else{
        // if (typeof document.getElementById(field.attr("id")).files[0] === 'undefined') {
        //     field.addClass("is-invalid");
        //     choosed = false;
        // }
        if (deleted){
            field.addClass("is-invalid");
            choosed = false;
        }
        // Altrimenti è corretto
        else {
            field.removeClass("is-invalid");
        }
    }

    return choosed;
}

// function checkFileField(field, choosed) {
//     if(!choosed)
//         field.addClass("is-invalid");
//     else
//         field.removeClass("is-invalid");
//
//     checkDeleted();
//     return choosed;
// }

function checkDeletedTrack(field, choosed, event) {
    let result = null;
    input = document.getElementById(field.attr("id"));
    if (input.files.length > 0) {
        var file = input.files[0];
        var fr = new FileReader();

        fr.onload = function (e) {
            //alert("File is readable");
            checkFileField(field, choosed, false);
            if (getChooserName($("#photoSelect")) != "")
                checkDeletedPhoto($("#photoSelect"), imageChoosed, event);
            else
                validateUpload(event);

        };
        fr.onerror = function (e) {
            if (e.target.error.name == "NotFoundError") {
                //alert("File deleted");
                result = checkFileField(field, choosed, true);
            }
        }
        fr.readAsText(file);
    } else {
        // no file choosen yet
        return checkFileField(field, choosed, false);
    }

}

function checkDeletedPhoto(field, choosed, event) {
    let result = null;
    input = document.getElementById(field.attr("id"));
    if (input.files.length > 0) {
        var file = input.files[0];
        var fr = new FileReader();

        fr.onload = function (e) {
            //alert("File is readable");
            checkFileField(field, choosed, false);
            validateUpload(event);
        };
        fr.onerror = function (e) {
            if (e.target.error.name == "NotFoundError") {
                //alert("File deleted");
                result = checkFileField(field, choosed, true);
            }
        }
        fr.readAsText(file);
    } else {
        // no file choosen yet
        return checkFileField(field, choosed, false);
    }

}


/**
 * Funzione che mi consente di ottenere il nome del file selezionato
 * con il chooser di cui è specificato l'id
 * @param element Chooser di cui voglio ottenere il nome del file
 * @returns {string | *} La stringa che rappresenta il nome del file
 *          selezionato dall'utente tramite il chooser
 */
function getChooserName(element){
    // Ottengo il nome del file
    let fileName = element.val();
    let lastSlash = fileName.lastIndexOf("\\");
    fileName = fileName.substring(lastSlash  + 1);

    return fileName;
}