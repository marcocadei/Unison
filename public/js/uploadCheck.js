maxLengthTitle = 64;
maxLengthDescription = 200;
trackChoosed = false;
// La scelta dell'immagine non è obbligatoria
imageChoosed = true;


$(document).ready(function () {
    // Dopo aver selezionato una traccia aggiorno la textbox corrispondente
    // in modo che contenga il titolo della traccia selezionata
    $("#trackSelect").on('change',function(){
        trackChoosed = true;
        trackName = getChooserName($(this));
        //replace the "Choose a file" label
        if (trackName.length > 25)
            $(this).next('.custom-file-label').html(trackName.substr(0, 23)+"...");
        else if (trackName.length < 25 && trackName.length > 0)
            $(this).next('.custom-file-label').html(trackName);
        else {
            $(this).next('.custom-file-label').html("Scegli file...");
            trackChoosed = false;
        }

        // Dopo aver selezionato la traccia propongo all'utente come titolo il nome
        // del file (senza il formato), ma è sempre possibile cambiarlo
        let ultimo_punto = trackName.lastIndexOf(".");
        $("#title").val(trackName.substring(0, ultimo_punto));
        checkFileField($("#trackSelect"), trackChoosed);
        checkTitle(null, $("#title"), maxLengthTitle);
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
            imageChoosed;
        }

        // Codice javascript per recuperare le dimensioni dell'immagine
        // selezionata dall'utente
        let _URL = window.URL || window.webkitURL;
        let file, img;
        if ((file = this.files[0])) {
            img = new Image();
            img.onload = function () {
                if (this.width != this.height) {
                    $("#photoSelect").addClass("is-invalid");
                    imageChoosed = false;
                }
                else {
                    $("#photoSelect").removeClass("is-invalid");
                    imageChoosed = true;
                }
            };
            img.src = _URL.createObjectURL(file);
        }
        checkFileField($("#photoSelect"), imageChoosed);
    });

    // Controllo sui campi titolo e descrizione
    $("#title").keyup(function(event) {checkTitle(event, this, maxLengthTitle)});
    $("#description").keyup(function(event) {checkDescription(event, this, maxLengthDescription)});

    // Prima di caricare la canzone controllo che tutti i campi siano compilati come richiesto
    $("#buttonUpload").click(validateUpload);
});


function validateUpload(event) {
    // Di default disabilito il submit della form, che effettuo solo dopo
    // che sia i controlli lato client che lato server sono stati superati
    event.preventDefault();

    // Rimuove il messaggio d'errore alla nuova pressione del tasto di submit.
    $("#formUpload").removeClass("is-invalid");

    let nextPage = true;
    nextPage &= checkFileField($("#trackSelect"), trackChoosed);
    nextPage &= checkFileField($("#photoSelect"), imageChoosed);
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
                file: "public/tracks/"+$("#title").val()+"_"+$("#author").val()+trackName.substring(trackName.lastIndexOf("."))
            }, function (data, status, xhr) {
                if (data.result) {
                    // Prima di terminare l'upload controllo se la canzone è presente su spotify
                    checkSpotify();
                    // L'upload lo faccio solo dopo che l'utente ha premuto uno dei due
                    // tasti della finestra modale (nel caso in cui essa sia mostrata)
                    //$("#upload").submit();
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
    // Questo rimuove l'header X-CSRF-TOKEN dalla richiesta ajax
    // Prima lo metto perché mi serve per ragioni di sicurezza imposte da Laravel
    // Ora lo tolgo perché spotify non si aspetta quell'header nella richiesta
    delete $.ajaxSettings.headers['X-CSRF-TOKEN'];
}

function searchSong(token){
    // var song = 'Moondust';
    // var artist = 'James%20Young';
    // Gli spazi vanno sostituiti con %20 nell'url di richiesta
    let track = $("#title").val().replace(" ", "%20");
    //var token = data;
    $.ajax({
        url: 'https://api.spotify.com/v1/search?type=track&query=' + track + '&limit=1',
        headers: {
            Authorization: 'Bearer ' + token
        }
    })
        .then( function(oData) {
            //console.log(oData.tracks.items[0].uri);
            // console.log(oData);
            mostraInfoSpotify(oData);
        })
}

function mostraInfoSpotify(data){
    // Mostro la finestra di spotify solo se ho trovato qualcosa,
    // altrimenti non faccio nulla
    if(data.tracks.items.length > 0) {
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
    }
}


function checkTitle(event, field, maxLength) {

    const regex = /^[\x20-\x7E]+$/;

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

    const regex = /^[\x20-\x7E]*$/;

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

function checkFileField(field, choosed) {
    if(!choosed)
        field.addClass("is-invalid");
    else
        field.removeClass("is-invalid");

    return choosed;
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