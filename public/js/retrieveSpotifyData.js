let token;

$(document).ready(function () {
    // Con questo recupero il token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.post("/spotify/token",
        {},
        function (data, status, xhr) {
            token = data.result;
            //searchSong(token);
        },
        "json");
})

function retrieveData(element){
    // Questo rimuove l'header X-CSRF-TOKEN dalla richiesta ajax
    // Prima lo metto perché mi serve per ragioni di sicurezza imposte da Laravel
    // Ora lo tolgo perché spotify non si aspetta quell'header nella richiesta
    delete $.ajaxSettings.headers['X-CSRF-TOKEN'];
    // Se ho già recuperato i dati non li scarico una seconda volta
    if (!$(element).hasClass("retrieved")) {
        let id = $($(element).attr("href")).find("input").val();

        $.ajax({
            url: 'https://api.spotify.com/v1/audio-features/' + id,
            headers: {
                Authorization: 'Bearer ' + token
            }
        })
            .then(function (oData) {
                let dati = $("<ul></ul>");
                // Spiegazione dei vari campi:
                // https://developer.spotify.com/documentation/web-api/reference/tracks/get-audio-features/
                // L'acousticness è la presenza di suoni "naturali" (piano, chitarra, voce), se ci sono
                // tanti suono elettronici è bassa
                dati.append("<li><b>Acusticità: </b>" + oData.acousticness + "</li>");
                dati.append("<li><b>Danzabilità: </b>" + oData.danceability + "</li>");
                dati.append("<li><b>Energia: </b>" + oData.energy + "</li>");
                dati.append("<li><b>Vitalità: </b>" + oData.liveness + "</li>");
                dati.append("<li><b>Rumorisità: </b>" + oData.loudness + "</li>");
                // Alta se ci sono tante parole, bassa altrimenti
                dati.append("<li><b>Cantata: </b>" + oData.speechiness + "</li>");
                dati.append("<li><b>Strumentalità: </b>" + oData.instrumentalness + "</li>");

                $($(element).attr("href")).find("span").replaceWith(dati);

                // La utilizzo per indicare che ho già scaricato i dati
                $(element).addClass("retrieved");
            })
    }
}
