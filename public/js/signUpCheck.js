/**
  * Associo agli elementi della form il gestore degli eventi che si occupa di controllare
  * che i diversi campi siano stati riempiti in modo appropriato
  */
$(document).ready(function () {
    $("#emailSU").keyup(function(event) {checkEmail(event, this)});
    // Questo handler serve perché se mentre si digita l'email compaiono i suggerimenti del browser
    // e si seleziona una voce con le frecce e poi si preme tab, su firefox l'evento keyup non si
    // scatena, mentre su chrome va
    $("#emailSU").focusout(function(event) {checkEmail(event, this)});
    $("#usernameSU").keyup(function(event) {checkUser(event, this)});
    $("#passwordSU").keyup(function(event) {checkPassword(event, this)});
    $("#repeatPasswordSU").keyup(function(event) {checkRepeatPassword(event, this);});

    $("#buttonSU").click(validateSignUp);

    // $("#SU").submit(function () {
    //     $("#buttonSU").attr("disabled", true);
    //     return true;
    // });
});


/**
 * Premendo il bottone di registrazione si controlla che i campi siano stati correttamente compilati
 *
 * @param event event L'evento di submit, il quale mi serve perché se i campi non risultano correttamente
 *        compilati allora l'utente non deve poter procedere alla pagina successiva
 */
function validateSignUp(event) {
    // Di default disabilito il submit della form, che effettuo solo dopo
    // che sia i controlli lato client che lato server sono stati superati
    event.preventDefault();

    let nextPage = true;

    // Controllo lato client sui campi della form
    nextPage &= checkEmail(event, $("#emailSU"));
    nextPage &= checkUser(event, $("#usernameSU"));
    nextPage &= checkPassword(event, $("#passwordSU"));
    nextPage &= checkRepeatPassword(event, $("#repeatPasswordSU"));


    // Se i controlli lato client hanno successo, prima di procedere alla pagina successiva devo
    // controllare che le credenziali non siano già presenti nel db
    if(nextPage) {
        // Disabilito il bottone di submit per evitare che la form sia trasmessa più di una volta
        $("#buttonSU").attr("disabled", true);
        // Ho dovuto aggiungere questa parte perché Laravel usa dei token nella form per proteggere
        // l'utente da determinati tipi di attacco
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post("/checkNewUserCredentials",
            {
                email: $("#emailSU").val(),
                username: $("#usernameSU").val()
            }, function (data, status, xhr) {
                if (data.result)
                    $("#SU").submit();
                else {
                    $("#formSU").addClass("is-invalid");
                    // Se i dati inseriti erano sbagliati allora riabilito il bottone di submit
                    $("#buttonSU").attr("disabled", false);
                }
            }, "json");
    }

}

// Lunghezza massima dei campi
maxLength = 64;


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

    const userRegex = /^[a-zA-Z0-9]+$/;

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

    if (($(pwd).val().length == 0 || $(pwd).val().length > maxLength || !$(pwd).val().match(passwordRegex))
        && ((event!= null && event.keyCode != 9) || (event!= null && event.keyCode == 9 && $(pwd).val().length != 0) || event == null)) {
        $(pwd).addClass("is-invalid");
        return false;
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
    if (($(repwd).val().length == 0 || $(repwd).val().length > maxLength || $(repwd).val() != $("#passwordSU").val())
        && ((event!= null && event.keyCode != 9) || (event!= null && event.keyCode == 9 && $(repwd).val().length != 0)  || event == null)) {
        $(repwd).addClass("is-invalid");
        return false;
    }
    else {
        $(repwd).removeClass("is-invalid");
        return true;
    }
}