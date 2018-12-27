function toggleLike(btnLike, idLiked) {
    // Elemento che contiene annidati tutti gli altri span (icona del cuore, numero di likes, ...)
    $(btnLike).addClass("disabledAnchor");

    // Elemento che contiene il numero di likes della traccia
    // (Nota: se i like sono più di 1000, è una stringa che non contiene solo cifre ma anche lettere)
    let likesSpan = $($(btnLike).children()[2]);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.post("/like",
        {
            liked: idLiked
        },
        function (data, status, xhr) {
            if (data.result) {
                // Elemento che rappresenta l'icona di FontAwesome
                let heartSymbol = $($($(btnLike).children()[0]).children()[0]);

                // Modifica del numero dei like (solo se i like sono meno di 1000, altrimenti il valore rimane invariato)
                if (!likesSpan.text().match(/[a-z]/i)) {
                    let likesCount = parseInt(likesSpan.text());
                    let delta = heartSymbol.hasClass("buttonOn") ? -1 : +1;
                    likesSpan.text(likesCount + delta);
                }

                // Modifica dell'icona del cuore (si "riempie" o si "svuota" a seconda dell'azione effettuata)
                if (heartSymbol.attr("data-prefix") === "fas") {
                    heartSymbol.attr("data-prefix", "far");
                    likesSpan.siblings(".btn").attr("title", "Metti mi piace");
                }
                else {
                    heartSymbol.attr("data-prefix", "fas");
                    likesSpan.siblings(".btn").attr("title", "Togli mi piace");
                }

                // Aggiunta o rimozione della classe buttonOn per segnalare lo stato del like
                heartSymbol.toggleClass("buttonOn");
            }
        }, "json")
        .always(function () {
            $(btnLike).removeClass("disabledAnchor");
        });
}