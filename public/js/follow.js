function executeFollow() {
    follow(true);
}

function executeUnfollow() {
    follow(false);
}

/**
 * Esegue il follow o l'unfollow a seconda del valore del parametro.
 * @param actionIsFollow Se posto uguale a <tt>true</tt>, indica di eseguire il follow; in caso contrario, indica di
 * eseguire l'unfollow.
 */
function follow(actionIsFollow) {
    let button = $("#buttonFollow");

    // Si disabilita il bottone finché non arriva una risposta.
    button.attr("disabled", true);

    let target = actionIsFollow ? "/follow" : "/unfollow";

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.post(target,
        {
            targetId: parseInt($("#userID").text())
        },
        function (data, status, xhr) {
            if (data.result) {
                // Modifica dell'aspetto del bottone
                button.toggleClass("btn-primary");
                button.toggleClass("btn-outline-primary");
                let newButtonText = actionIsFollow ? "Smetti di seguire" : "Segui";
                button.text(newButtonText);

                // Modifica del numero dei follower
                // NB: Questa operazione viene eseguita SOLO se il numero dei follower è inferiore a 1000!
                let followersSpan = button.parents(".row").prev().find("div:first-child > span:last-child");
                if (!followersSpan.text().match(/[a-z]/i)) {
                    let followersCount = parseInt(followersSpan.text());
                    let delta = actionIsFollow ? +1 : -1;
                    followersSpan.text(followersCount + delta);
                }

                // Modifica della funzione associata al bottone
                let newFunction = actionIsFollow ? "executeUnfollow" : "executeFollow";
                button.attr("onclick", newFunction + "()");

                // Modifica dell'elenco dei followed
                if (actionIsFollow && $("#followedList ul").length == 0)
                    $("#followedList span").replaceWith("<ul class='pl-2 my-auto'></ul>");
                if (actionIsFollow) $("#followedList ul").append($("<li><a style='word-break: break-all' href='/user/"+$("#userID").text().trim()+"'>"+$("#username").text()+"</a></li>"));
                else{
                    let username = $("#username").text().trim();
                    $("#followedList li:contains("+username+")").remove();
                    if ($("#followedList li").length == 0)
                        $("#followedList ul").replaceWith("<span class='text-left smallText'>Non segui nessuno attualmente.</span>")
                }
            }

            button.attr("disabled", false);
        }, "json")
        .always(function () {
            // A prescindere dal tipo di risposta ricevuta, il bottone viene sempre riattivato
            button.attr("disabled", false);
        });
}