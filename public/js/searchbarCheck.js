$(document).ready(function () {
    let searchbar = $("nav form input[type=search]");
    searchbar.tooltip();
    // L'oggetto searchbar.next() non è altro che il bottone con la lente di ingrandimento (bottone submit della form).
    searchbar.next().click(validateQueryString);
});

function validateQueryString (event) {
    event.preventDefault();

    let searchbar = $("nav form input[type=search]");

    let queryString = searchbar.val();
    let asciiQueryString = queryString.replace(/[^\x20-\x7E\xC0-\xFF]/g, "");
    if (asciiQueryString !== queryString || asciiQueryString.trim().length <= 0) {
        // Attivazione del tooltip e focus forzato sull'elemento <input>.
        searchbar.tooltip('show');
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

function preloadSearchBar(text) {
    $("input[name=searchInput]").attr("value", text);
}