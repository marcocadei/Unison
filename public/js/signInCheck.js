/**
 * Associo agli elementi della form il gestore degli eventi che si occupa di controllare
 * che i diversi campi siano stati riempiti in modo appropriato
 */
$(document).ready(function () {
    $("#usernameSI").keyup(function(event) {checkFieldSI(event, this)});
    $("#passwordSI").keyup(function(event) {checkFieldSI(event, this)});
    // Perché l'evento è sul click del bottone e non sul submit della form?
    // Perché in validateLogin di default disabilito il comportamento del submit e solo
    // se le credenziali sono corrette allora faccio il submit della form. Quindi se qui
    // aggiungessi un event handler per il submit entrerei in un loop
    $("#buttonSI").click(validateLogin);

    // $("#SI").submit(function () {
    //     $("#buttonSI").attr("disabled", true);
    //     return true;
    // });
});


/**
 * Premuto sul bottone accedi controllo che i campi siano stati correttamente compilati
 *
 * @param event event L'evento di submit, il quale mi serve perché se i campi non risultano correttamente
 *        compilati allora l'utente non deve poter procedere alla pagina successiva
 */
function validateLogin(event){
    // Di default disabilito il submit della form, che effettuo solo dopo
    // che sia i controlli lato client che lato server sono stati superati
    event.preventDefault();

    let nextPage = true;
    // L'input hidden viene usato per mostrare il messaggio di errore nel caso in cui le credenziali inserite
    // siano errate (quindi a seguito di una verifica lato server), perciò non deve essere considerato in questi
    // controlli lato client
    $("#SI input[type != hidden]").each(function () {
         nextPage &= checkFieldSI(null, this);
    });

    // Se i controlli lato client hanno successo, prima di procedere alla pagina successiva devo
    // controllare che le credenziali corrispondano a quelle di un utente precedentemente registrato
    if(nextPage) {
        // Disabilito il bottone di submit per evitare che la form sia trasmessa più di una volta
        $("#buttonSI").attr("disabled", true);
        // Ho dovuto aggiungere questa parte perché Laravel usa dei token nella form per proteggere
        // l'utente da determinati tipi di attacco
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post("/checkUserCredentials",
            {
                username: $("#usernameSI").val(),
                password: md5($("#passwordSI").val())
            }, function (data, status, xhr) {
                if (data.result)
                    $("#SI").submit();
                else {
                    $("#formSI").addClass("is-invalid");
                    // Se i dati inseriti erano sbagliati allora riabilito il bottone di submit
                    $("#buttonSI").attr("disabled", false);
                }
            }, "json");
    }

}

/**
 * Funzione che implementa la logica di validazione dell'elemento passato
 *
 * @param el L'elemento che si vuole validare
 * @returns {boolean} true se l'elemento è stato correttamente validato, false altrimenti
 */
function checkFieldSI(event, el) {
    if (($(el).val().length == 0 || $(el).val().length > maxLength) && ((event!= null && event.keyCode != 9) || event == null)) {
        $(el).addClass("is-invalid");
        return false;
    }
    else {
        $(el).removeClass("is-invalid");
        return true;
    }
}