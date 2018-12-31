$(document).ready(function () {
    let searchbar = $("nav form input[type=search]");
    searchbar.tooltip({container: 'body'});
    searchbar.tooltip('disable');
    // L'oggetto searchbar.next() non è altro che il bottone con la lente di ingrandimento (bottone submit della form).
    searchbar.next().click(validateQueryString);
    searchbar.on('input', disableTooltip);
});

function validateQueryString(event) {
    event.preventDefault();

    let searchbar = $("nav form input[type=search]");

    let queryString = searchbar.val();
    let asciiQueryString = queryString.replace(/[^\x20-\x7E\xC0-\xFF]/g, "");
    if (asciiQueryString !== queryString || asciiQueryString.trim().length <= 0) {
        // Il tooltip viene attivato solo se non è già visualizzato.
        if (searchbar.attr("aria-describedby") === "" || searchbar.attr("aria-describedby") == null) {
            // Attivazione del tooltip.
            enableTooltip();
        }
        // Focus forzato sull'elemento <input>.
        searchbar.focus();
    }
    else {
        asciiQueryString = asciiQueryString.replace(/\s\s+/g, ' ');
        searchbar.val(asciiQueryString);

        searchbar.blur();
        // L'oggetto searchbar.parent().parent() è l'intera form.
        searchbar.parent().parent().submit();
    }
}

function enableTooltip() {
    let searchbar = $("nav form input[type=search]");
    searchbar.tooltip('enable');
    searchbar.tooltip('show');
}

function disableTooltip() {
    let searchbar = $("nav form input[type=search]");
    searchbar.tooltip('hide');
    searchbar.tooltip('disable');
}

function preloadSearchBar(text) {
    $("input[name=searchInput]").attr("value", text);
}