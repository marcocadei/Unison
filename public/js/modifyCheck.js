maxLength = 64;
maxLengthBio = 200;
// La scelta dell'immagine non è obbligatoria
imageChosen = true;

/**
 * Associo agli elementi della form il gestore degli eventi che si occupa di controllare
 * che i diversi campi siano stati riempiti in modo appropriato
 */
$(document).ready(function () {

    let profilePicElement = $("form img");
    let originalProfilePicSrc = profilePicElement.attr("src");

    // Dopo aver selezionato una foto aggiorno la textbox corrispondente
    // in modo che contenga il titolo della foto selezionata
    $("#photoMod").on('change', function(){
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
                            $("#photoMod").addClass("is-invalid");
                            imageChosen = false;
                            profilePicElement.attr("src", originalProfilePicSrc);
                        }
                        else {
                            $("#photoMod").removeClass("is-invalid");
                            imageChosen = true;
                            profilePicElement.attr("src", img.src);
                        }
                    };
                    img.src = _URL.createObjectURL(file);
                }
                else {
                    $("#photoMod").addClass("is-invalid");
                    imageChosen = false;
                }
            }
            else {
                profilePicElement.attr("src", originalProfilePicSrc);
            }
            checkFileField($("#photoMod"), imageChosen);
        }
        else {
            $("#photoMod").removeClass("is-invalid");
            imageChosen = true;
            profilePicElement.attr("src", originalProfilePicSrc);
        }
    });
    $("#emailMod").keyup(function(event) {checkEmail(event, this)});
    // Questo handler serve perché se mentre si digita l'email compaiono i suggerimenti del browser
    // e si seleziona una voce con le frecce e poi si preme tab, su firefox l'evento keyup non si
    // scatena, mentre su chrome va
    $("#emailMod").focusout(function(event) {checkEmail(event, this)});
    $("#usernameMod").keyup(function(event) {checkUser(event, this)});
    $("#passwordMod").keyup(function(event) {checkPassword(event, this)});
    $("#repeatPasswordMod").keyup(function(event) {checkRepeatPassword(event, this);});
    $("#bioMod").keyup(function(event) {checkBio(event, this, maxLengthBio)});

    $("#buttonMod").click(validateModify);

    $("#buttonDel").click(openDeleteModal);
    $("#buttonDefDel").click(executeDelete);

    // $("#SU").submit(function () {
    //     $("#buttonSU").attr("disabled", true);
    //     return true;
    // });
});


/**
 * Premendo il bottone di modifica si controlla che i campi siano stati correttamente compilati
 *
 * @param event event L'evento di submit, il quale mi serve perché se i campi non risultano correttamente
 *        compilati allora l'utente non deve poter procedere alla pagina successiva
 */
function validateModify(event) {
    // Di default disabilito il submit della form, che effettuo solo dopo
    // che sia i controlli lato client che lato server sono stati superati
    event.preventDefault();

    $("#formMod").removeClass('is-invalid');

    let nextPage = true;

    // Controllo lato client sui campi della form
    // nextPage &= checkFileField($("#photoMod"), imageChosen);
    nextPage &= checkEmail(event, $("#emailMod"));
    nextPage &= checkUser(event, $("#usernameMod"));
    nextPage &= checkPassword(event, $("#passwordMod"));
    nextPage &= checkRepeatPassword(event, $("#repeatPasswordMod"));
    nextPage &= checkBio(event, $("#bioMod"), maxLengthBio);
    nextPage &= checkFileField($("#photoMod"), imageChosen);


    // Se i controlli lato client hanno successo, prima di procedere alla pagina successiva devo
    // controllare che le credenziali non siano già presenti nel db
    if(nextPage) {
        // Disabilito il bottone di submit per evitare che la form sia trasmessa più di una volta
        $("#buttonMod").attr("disabled", true);
        // Ho dovuto aggiungere questa parte perché Laravel usa dei token nella form per proteggere
        // l'utente da determinati tipi di attacco

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let emailChanged = $("#emailMod").val() != $("#originalEmailMod").val();
        let usernameChanged = $("#usernameMod").val() != $("#originalUsernameMod").val();
        if (emailChanged || usernameChanged) {
            let emailToSend = emailChanged ? $("#emailMod").val() : '';
            let usernameToSend = usernameChanged ? $("#usernameMod").val() : '';
            $.post("/checkNewUserCredentials",
                {
                    username: usernameToSend,
                    email: emailToSend
                }, function (data, status, xhr) {
                    if (data.result) {
                        $("#mod").submit();
                    }
                    else {
                        $("#formMod").addClass("is-invalid");
                        // Se i dati inseriti erano sbagliati allora riabilito il bottone di submit
                        $("#buttonMod").attr("disabled", false);
                    }
                }, "json");
        }
        else {
            $("#mod").submit();
        }
    }

}


/**
 *  Funzione che controlla che nel campo email della form sia stato inserito una email corretta.
 *  In particolare viene controllato che l'input sia della forma:
 *  testo@testo.testo
 * @param email, il valore del campo email, che viene verificato
 * @returns {boolean} true se il valore è valido, false altrimenti
 */
function checkEmail(event, email){
    const emailRegex = /\S+@\S+\.\S+/;

    if (($(email).val().length == 0 || $(email).val().length > maxLength || !$(email).val().match(emailRegex))
        && ((event!= null && event.keyCode != 9) || (event!= null && event.keyCode == 9 && $(email).val().length != 0)  || event == null)) {
        $(email).addClass("is-invalid");
        return false;
    }
    else {
        $(email).removeClass("is-invalid");
        return true;
    }
}


/**
 *  Funzione che controlla che nel campo username della form sia stato inserito un valore corretto,
 *  cioé una combinazione di lettere e numeri
 * @param user, il valore del campo user, che viene verificato
 * @returns {boolean} true se il valore è valido, false altrimenti
 */
function checkUser(event, user) {

    const userRegex = /^[\x20-\x7E]+$/;

    if (($(user).val().length == 0 || $(user).val().length > maxLength || !$(user).val().match(userRegex))
        && ((event!= null && event.keyCode != 9) || (event!= null && event.keyCode == 9 && $(user).val().length != 0)  || event == null)) {
        $(user).addClass("is-invalid");
        return false;
    }
    else {
        $(user).removeClass("is-invalid");
        return true;
    }
}

/**
 *  Funzione che controlla che nel campo password della form sia stato inserito un valore corretto,
 *  cioé una combinazione di almeno 8 caratteri, contenenti almeno una lettera minuscola, una lettera
 *  maiuscola, un numero e un carattere speciale
 * @param pwd, il valore del campo password, che viene verificato
 * @returns {boolean} true se il valore è valido, false altrimenti
 */
function checkPassword(event, pwd) {
    //cifra   //minuscola //maiuscola //simbolo //almeno 8 dei caratteri che accetto
    const passwordRegex = /^(?=.*[\d])(?=.*[A-Z])(?=.*[a-z])(?=.*[\W_])[\x21-\x7E]{8,}$/;
    if ($(pwd).val().length >= 1) {
        if (($(pwd).val().length > maxLength || !$(pwd).val().match(passwordRegex))
            && ((event != null && event.keyCode != 9) || (event != null && event.keyCode == 9 && $(pwd).val().length != 0) || event == null)) {
            $(pwd).addClass("is-invalid");
            return false;
        }
        else {
            $(pwd).removeClass("is-invalid");
            return true;
        }
    }
    else {
        $(pwd).removeClass("is-invalid");
        return true;
    }

}

/**
 * Funzione che controlla che nel campo repeatPassword sia stato inserito un valore uguale a quello nel campo password
 * @param repwd, il valore del campo repeatPassword, che viene verificato
 * @returns {boolean}, true se il valore è valido, false altrimenti
 */
function checkRepeatPassword(event, repwd) {
    if ($(repwd).val().length >= 1) {
        if (($(repwd).val().length > maxLength || $(repwd).val() != $("#passwordMod").val())
            && ((event != null && event.keyCode != 9) || (event != null && event.keyCode == 9 && $(repwd).val().length != 0) || event == null)) {
            $(repwd).addClass("is-invalid");
            return false;
        }
        else {
            $(repwd).removeClass("is-invalid");
            return true;
        }
    }
    else {
        $(repwd).removeClass("is-invalid");
        return true;
    }
}

/**
 *  Funzione che controlla che nel campo bio della form sia stato inserito un valore corretto.
 *  In particolare viene controllato che l'input non superi una certa lunghezza
 * @param bio, il valore del campo bio, che viene verificato
 * @returns {boolean} true se il valore è valido, false altrimenti
 */
function checkBio(event, bio, maxLenghtBio) {
    if (($(bio).val().length > maxLengthBio)
        && ((event!= null && event.keyCode != 9) || (event!= null && event.keyCode == 9 && $(user).val().length != 0)  || event == null)) {
        $(bio).addClass("is-invalid");
        return false;
    }
    else {
        $(bio).removeClass("is-invalid");
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
function checkFileField(field, choosed) {
    if(!choosed)
        field.addClass("is-invalid");
    // Se la traccia/foto è selezionata
    else{
        // Ma la sua dimensione è zero (è stato rimosso o spostato) allora riporto un errore
        if (typeof document.getElementById(field.attr("id")).files[0] !== 'undefined' && document.getElementById(field.attr("id")).files[0].size == 0) {
            field.addClass("is-invalid");
            choosed = false;
        }
        // Altrimenti è corretto
        else
            field.removeClass("is-invalid");
    }

    return choosed;
}

/**
 * Funzione che mi consente di ottenere il nome del file selezionato
 * con il chooser di cui è specificato l'id
 * @param element Chooser di cui voglio ottenere il nome del file
 * @returns {string | *} La stringa che rappresenta il nome del file
 *          selezionato dall'utente tramite il chooser
 */
function getChooserName(element) {
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