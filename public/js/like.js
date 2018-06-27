function toggleLike(btnLike, idLiked) {

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
            // Modifica del numero dei like
            let likesSpan = $(btnLike).next();
            if (!likesSpan.text().match(/[a-z]/i)) {
                let likesCount = parseInt(likesSpan.text());
                let delta = $(btnLike).hasClass("buttonOn") ? -1 : +1;
                likesSpan.text(likesCount + delta);
            }

            $(btnLike).toggleClass("buttonOn");
        }, "json");
};