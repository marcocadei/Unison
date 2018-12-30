maxLengthTitle = 64;
maxLengthDescription = 200;
// La scelta dell'immagine non è obbligatoria
imageChoosed = true;

$(document).ready(function () {

    let coverArtElement = $("form img");
    let originalCoverArtSrc = coverArtElement.attr("src");

    // Dopo aver selezionato una foto aggiorno la textbox corrispondente
    // in modo che contenga il titolo della foto selezionata
    $("#photoMod").on('change',function(){
        let photoName = getChooserName($(this));
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
            if ((file = this.files[0])) {
                let imageFormat = photoName.substring(photoName.lastIndexOf("."));
                let allowedFormat = [".jpg", ".jpeg", ".png"];

                if (allowedFormat.indexOf(imageFormat) != -1) {
                    img = new Image();
                    img.onload = function () {
                        if (this.width != this.height || this.width < 150 || this.height < 150) {
                            //$("#photoMod").addClass("is-invalid");
                            imageChoosed = false;
                            coverArtElement.attr("src", originalCoverArtSrc);
                        }
                        else {
                            $("#photoMod").removeClass("is-invalid");
                            imageChoosed = true;
                            coverArtElement.attr("src", img.src);
                        }
                    };
                    img.src = _URL.createObjectURL(file);
                }
                else{
                    //$("#photoMod").addClass("is-invalid");
                    imageChoosed = false;
                    coverArtElement.attr("src", img.src);
                }
            }
            else {
                $("#photoMod").removeClass("is-invalid");
                coverArtElement.attr("src", originalCoverArtSrc);
            }
            checkFileField($("#photoMod"), imageChoosed);
        }
        else {
            $("#photoMod").removeClass("is-invalid");
            imageChoosed = true;
            coverArtElement.attr("src", originalCoverArtSrc);
        }
    });

    // Controllo sui campi titolo e descrizione
    //$("#title").keyup(function(event) {checkTitle(event, this, maxLengthTitle)});
    //$("#description").keyup(function(event) {checkDescription(event, this, maxLengthDescription)});
    $("#title").focusin(function (event) {
        $("#title").removeClass("is-invalid");
        $("#formModify").removeClass("is-invalid");
    });
    $("#description").focusin(function (event) {
        $("#description").removeClass("is-invalid");
    });
    $("#photoMod").focusin(function (event) {
        $("#photoMod").removeClass("is-invalid");
    });

    // Prima di caricare la canzone controllo che tutti i campi siano compilati come richiesto
    $("#buttonModify").click(function (event) {
        event.preventDefault();
        checkDeletedPhoto($("#photoMod"), imageChoosed, event);
    });

    $("#buttonDel").click(openDeleteModal);
    $("#buttonDefDel").click(executeDelete);
});

function validateModify(event) {
    // Di default disabilito il submit della form, che effettuo solo dopo
    // che sia i controlli lato client che lato server sono stati superati
    event.preventDefault();

    // Rimuove il messaggio d'errore alla nuova pressione del tasto di submit.
    $("#formModify").removeClass("is-invalid");

    let nextPage = true;
    nextPage &= checkFileField($("#photoMod"), imageChoosed, false);
    nextPage &= checkTitle(event, $("#title"), maxLengthTitle);
    nextPage &= checkDescription(event, $("#description"), maxLengthDescription);

    // Se i controlli lato client hanno successo, prima di procedere alla pagina successiva devo
    // controllare che la canzone che si vuole caricare non sia già stata caricata in precedenza
    if(nextPage){
        // Disabilito il bottone di submit per evitare che la form sia trasmessa più di una volta
        $("#buttonModify").attr("disabled", true);
        // Ho dovuto aggiungere questa parte perché Laravel usa dei token nella form per proteggere
        // l'utente da determinati tipi di attacco
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let titleChanged = $("#title").val() != $("#originalTitle").val();
        if (/*titleChanged*/true) {
            let titleToSend = titleChanged ? $("#title").val() : '';

            $.post("/checkSongExistence",
                {
                    //file: "public/tracks/"+$("#title").val()+"_"+$("#userID").val()+trackName.substring(trackName.lastIndexOf("."))
                    userID: $("#userID").val(),
                    title: titleToSend
                }, function (data, status, xhr) {
                    if (data.result) {
                        // Prima di terminare l'upload controllo se la canzone è presente su spotify
                        if ($("#connectID").prop("checked"))
                            checkSpotify();
                        else
                            $("#mod").submit();
                    }
                    else {
                        $("#formModify").addClass("is-invalid");
                        // Se i dati inseriti erano sbagliati allora riabilito il bottone di caricamento
                        $("#buttonModify").attr("disabled", false);
                    }
                }, "json");
        }
        else {
            $("#mod").submit();
        }
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
            // console.log("trovata");
            // $("#spotifyID").val(oData.tracks.items[0].id);
            // $("#mod").submit();
        })
        .fail(function () {
            // Se c'è un errore con spotify (magari il servizio non è disponibile) allora memorizzo la canzone
            // senza alcuna associazione
            $("#mod").submit();
        })

}

function mostraInfoSpotify(data){
    // Mostro la finestra di spotify solo se ho trovato qualcosa e l'artista trovato è colui che sta
    // caricando la canzone, altrimenti non faccio nulla
    if(data.tracks.items.length > 0 && data.tracks.items[0].artists[0].name.includes($("#author").val())) {
        $("#spotifyID").val(data.tracks.items[0].id);
        $("#mod").submit();
    }
    else{
        $("#mod").submit();
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

// function checkFileField(field, choosed) {
//     if(!choosed)
//         field.addClass("is-invalid");
//     else
//         field.removeClass("is-invalid");
//
//     return choosed;
// }

// function checkFileField(field, choosed) {
//     if(!choosed)
//         field.addClass("is-invalid");
//     // Se la traccia/foto è selezionata
//     else{
//         // Ma la sua dimensione è zero (è stato rimosso o spostato) allora riporto un errore
//         if (typeof document.getElementById(field.attr("id")).files[0] !== 'undefined' && document.getElementById(field.attr("id")).files[0].size == 0) {
//             field.addClass("is-invalid");
//             choosed = false;
//         }
//         // Altrimenti è corretto
//         else
//             field.removeClass("is-invalid");
//     }
//
//     return choosed;
// }

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

function checkDeletedPhoto(field, choosed, event) {
    let result = null;
    input = document.getElementById(field.attr("id"));
    if (input.files.length > 0) {
        var file = input.files[0];
        var fr = new FileReader();

        fr.onload = function (e) {
            //alert("File is readable");
            checkFileField(field, choosed, false);
            validateModify(event);
        };
        fr.onerror = function (e) {
            if (e.target.error.name == "NotFoundError") {
                //alert("File deleted");
                result = checkFileField(field, choosed, true);
            }
        };
        fr.readAsText(file);
    } else {
        // no file choosen yet
        result =  checkFileField(field, choosed, false);
        validateModify(event);
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

function openDeleteModal (event) {
    event.preventDefault();

    $('#deleteModal').modal({
        keyboard: true
    });
}

function executeDelete() {
    $('#buttonDel').parent().submit();
}