maxLengthTitle = 64;
maxLengthDescription = 200;
trackChoosed = false;
// La scelta dell'immagine non è obbligatoria
imageChoosed = true;

$(document).ready(function () {
    // Dopo aver selezionato una foto aggiorno la textbox corrispondente
    // in modo che contenga il titolo della foto selezionata
    $("#photoMod").on('change',function(){
        photoName = getChooserName($(this));
        //replace the "Choose a file" label
        if (photoName.length > 0)
            $(this).next('.custom-file-label').html(photoName);
        else{
            $(this).next('.custom-file-label').html("Scegli file...");
        }

        // Codice javascript per recuperare le dimensioni dell'immagine
        // selezionata dall'utente
        let _URL = window.URL || window.webkitURL;
        let file, img;
        if ((file = this.files[0])) {
            img = new Image();
            img.onload = function () {
                if (this.width != this.height) {
                    $("#photoMod").addClass("is-invalid");
                    imageChoosed = false;
                }
                else {
                    $("#photoMod").removeClass("is-invalid");
                    imageChoosed = true;
                }
            };
            img.src = _URL.createObjectURL(file);
        }
        checkFileField($("#photoMod"), imageChoosed);
    });

    // Controllo sui campi titolo e descrizione
    $("#title").keyup(function(event) {checkTitle(event, this, maxLengthTitle)});
    $("#description").keyup(function(event) {checkDescription(event, this, maxLengthDescription)});

    // Prima di caricare la canzone controllo che tutti i campi siano compilati come richiesto
    $("#buttonModify").click(validateModify);

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
    nextPage &= checkFileField($("#photoMod"), imageChoosed);
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
        $.post("/checkSongExistence",
            {
                //file: "public/tracks/"+$("#title").val()+"_"+$("#userID").val()+trackName.substring(trackName.lastIndexOf("."))
                userID: $("#userID").val(),
                title: $("#title").val()
            }, function (data, status, xhr) {
                if (data.result) {
                    $("#mod").submit();
                }
                else {
                    $("#formModify").addClass("is-invalid");
                    // Se i dati inseriti erano sbagliati allora riabilito il bottone di caricamento
                    $("#buttonModify").attr("disabled", false);
                }
            }, "json");
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

function openDeleteModal (event) {
    event.preventDefault();

    $('#deleteModal').modal({
        keyboard: true
    });
}

function executeDelete() {
    $('#buttonDel').parent().submit();
}