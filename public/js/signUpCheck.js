/**
  * Associo agli elementi della form il gestore degli eventi che si occupa di controllare
  * che i diversi campi siano stati riempiti in modo appropriato
  */
$(document).ready(function () {
    $("#emailSU").focusout(function() {checkField(this)});
    $("#usernameSU").focusout(function() {checkField(this)});
    $("#usernameSU").keyup(function() {checkUser(this)});
    //$("#passwordSU").focusout(function() {checkField(this)});
    $("#passwordSU").keyup(function() {checkPassword(this)});
    $("#repeatpasswordSU").focusout(function() {checkField(this)});
    $("#repeatPasswordSU").keyup(function() {checkRepeatPassword(this);});

    $("#buttonSU").click(validateSignUp);
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

    // L'input hidden viene usato per mostrare il messaggio di errore nel caso in cui le credenziali inserite
    // siano già presenti nel db (quindi a seguito di una verifica lato server), perciò non deve essere considerato
    // in questi controlli lato client
    $("#SU input[type != hidden]").each(function () {
        nextPage &= checkField(this);
    });

    // Non so se servirà in futuro
    //nextPage &= checkEmail($("#emailSU"));


    nextPage &= checkUser($("#usernameSU"));

    nextPage &= checkPassword($("#passwordSU"));

    nextPage &= checkRepeatPassword($("#repeatPasswordSU"));

    // Se anche i controlli lato client hanno successo, prima di procedere alla pagina successiva devo
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

/**
 * Funzione che controlla che i campi della form non siano vuoti
 * @param el, il campo della form che si verifica
 * @returns {boolean} true se il campo non è vuoto, false altrimenti
 */
function checkField(el) {
    if ($(el).val().length == 0) {
        $(el).addClass("is-invalid");
        return false;
    }
    else {
        $(el).removeClass("is-invalid");
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

    const $userRegex = /^[a-zA-Z0-9]+$/;

    if(!$(user).val().match($userRegex) && $(user).val().length > 0) {
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

   if(!$(pwd).val().match(passwordRegex) && $(pwd).val().length > 0) {
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
    if($(repwd).val() != $("#passwordSU").val() && $(repwd).val().length > 0) {
        $(repwd).addClass("is-invalid");
        return false;
    }
    else {
        $(repwd).removeClass("is-invalid");
        return true;
    }
}