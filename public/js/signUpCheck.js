/**
  * Associo agli elementi della form il gestore degli eventi che si occupa di controllare
  * che i diversi campi siano stati riempiti in modo appropriato
  */
$(document).ready(function () {
    $("#emailSU").keyup(function() {checkEmail(this)});
    $("#emailSU").focusout(function() {checkEmail(this)});
    $("#usernameSU").keyup(function() {checkUser(this)});
    $("#passwordSU").keyup(function() {checkPassword(this)});
    $("#repeatPasswordSU").keyup(function() {checkRepeatPassword(this);});

    $("#buttonSU").click(validateSignUp);
});

$(document).ready(function () {
    $("#SU").submit(function () {
        $("#buttonSU").attr("disabled", true);
        return true;
    });
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
    nextPage &= checkEmail($("#emailSU"));
    nextPage &= checkUser($("#usernameSU"));
    nextPage &= checkPassword($("#passwordSU"));
    nextPage &= checkRepeatPassword($("#repeatPasswordSU"));

    // Se i controlli lato client hanno successo, prima di procedere alla pagina successiva devo
    // controllare che le credenziali non siano già presenti nel db
    if(nextPage) {
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
                else $("#formSU").addClass("is-invalid");
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
function checkEmail(email){
    const emailRegex = /\S+@\S+\.\S+/;

    if ($(email).val().length == 0) {
        $(email).addClass("is-invalid");
        return false;
    }
    else if($(email).val().length > maxLength){
        $(email).addClass("is-invalid");
        return false;
    }
    else if (!$(email).val().match(emailRegex) && $(email).val().length > 0) {
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
function checkUser(user) {

    const userRegex = /^[a-zA-Z0-9]+$/;

    if ($(user).val().length == 0) {
        $(user).addClass("is-invalid");
        return false;
    }
    else if($(user).val().length > maxLength){
        $(user).addClass("is-invalid");
        return false;
    }
    else if(!$(user).val().match(userRegex) && $(user).val().length > 0) {
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
function checkPassword(pwd) {

   const passwordRegex = /^(?=.*[\d])(?=.*[A-Z])(?=.*[a-z])(?=.*[!@#$%^&*])[\w!@#$%^&*]{8,}$/;

    if ($(pwd).val().length == 0) {
        $(pwd).addClass("is-invalid");
        return false;
    }
    else if($(pwd).val().length > maxLength){
        $(pwd).addClass("is-invalid");
        return false;
    }
    else if(!$(pwd).val().match(passwordRegex) && $(pwd).val().length > 0) {
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
function checkRepeatPassword(repwd) {
    if ($(repwd).val().length == 0) {
        $(repwd).addClass("is-invalid");
        return false;
    }
    else if($(repwd).val() != $("#passwordSU").val() && $(repwd).val().length > 0) {
        $(repwd).addClass("is-invalid");
        return false;
    }
    else {
        $(repwd).removeClass("is-invalid");
        return true;
    }
}