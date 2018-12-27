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
                let intestazione = $("<span class='smallText'>I dati mostrati sono dei valori che Spotify associa a questa traccia.<br>" +
                                     "<a href='https://developer.spotify.com/documentation/web-api/reference/tracks/get-audio-features#audio-features-object' target='_blank'>Clicca qui per ulteriori informazioni <i class=\"fas fa-external-link-alt\"></i></a></span>");
                let dati = $("<ul class='smallText m-0'></ul>");
                // Spiegazione dei vari campi:
                // https://developer.spotify.com/documentation/web-api/reference/tracks/get-audio-features
                // L'acousticness è la presenza di suoni "naturali" (piano, chitarra, voce), se ci sono
                // tanti suono elettronici è bassa
                dati.append("<li><b>Acousticness: </b>" + oData.acousticness + "</li>");
                dati.append("<li><b>Danceability: </b>" + oData.danceability + "</li>");
                dati.append("<li><b>Energy: </b>" + oData.energy + "</li>");
                dati.append("<li><b>Liveness: </b>" + oData.liveness + "</li>");
                dati.append("<li><b>Loudness: </b>" + oData.loudness + "</li>");
                // Alta se ci sono tante parole, bassa altrimenti
                dati.append("<li><b>Spechiness: </b>" + oData.speechiness + "</li>");
                dati.append("<li><b>Instrumentalness: </b>" + oData.instrumentalness + "</li>");

                let spot = intestazione.add(dati)
                $($(element).attr("href")).find("span").replaceWith(spot);

                // La utilizzo per indicare che ho già scaricato i dati
                $(element).addClass("retrieved");
            })
            .fail(function () {
                // Se c'è un errore con spotify (magari il servizio non è disponibile) allora memorizzo la canzone
                // senza alcuna associazione
                let dati = $("<span class='smallText'></span>");
                dati.append("C'è stato un errore e non è attualmente possibile recuperare i dati.");
                $($(element).attr("href")).find("span").replaceWith(dati);
            })
    }
}
