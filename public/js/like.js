function toggleLike(btnLike, idLiked) {
    // ILLEGAL NEXT LEVEL CHECK ASAP
    //btnLike = $(btnLike).siblings()[0]
    //originalBtn = $(btnLike);
    likesSpan = $($(btnLike).children()[1]);
    //btnLike = $($(btnLike).children()[0]).children()[0];
    $(btnLike).addClass("disabledAnchor");
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
                // Modifica del numero dei like
                // let likesSpan = $(btnLike).next();
                // if (!likesSpan.text().match(/[a-z]/i)) {
                //     let likesCount = parseInt(likesSpan.text());
                //     let delta = $(btnLike).hasClass("buttonOn") ? -1 : +1;
                //     likesSpan.text(likesCount + delta);
                //
                //     if (delta == -1){
                //         $(originalBtn).removeClass("fas");
                //         $(originalBtn).addClass("far");
                //         alert("tolto");
                //     }
                //     if (delta == 1){
                //         $(originalBtn).removeClass("far");
                //         $(originalBtn).addClass("fas");
                //         alert("messo");
                //     }
                // }
                let likesCount = parseInt(likesSpan.text().replace ( /[^\d.]/g, '' ));
                let delta = $($($(btnLike).children()[0]).children()[0]).hasClass("buttonOn") ? -1 : +1;
                likesCount += delta
                likesSpan.text("Mi piace: " + likesCount);

                // if (delta == -1){
                //     $(btnLike).removeClass("fas fa-heart");
                //     $(btnLike).addClass("far fa-heart");
                //     $(btnLike).removeClass("buttonOn");
                //     alert("tolto");
                // }
                // if (delta == 1){
                //     $(btnLike).removeClass("far fa-heart");
                //     $(btnLike).addClass("fas fa-heart");
                //     $(btnLike).addClass("buttonOn");
                //     alert(btnLike.innerHTML);
                //     alert("messo");
                // }
                // $($($(btnLike).children()[0]).children()[0]).toggleClass("fa-heart fa-heart-broken");
                if($($($(btnLike).children()[0]).children()[0]).attr("data-prefix") == "fas")
                    $($($(btnLike).children()[0]).children()[0]).attr("data-prefix", "far")
                else
                    $($($(btnLike).children()[0]).children()[0]).attr("data-prefix", "fas")
                $($($(btnLike).children()[0]).children()[0]).toggleClass("buttonOn");
            }
        }, "json")
        .always(function () {
            $(btnLike).removeClass("disabledAnchor");
        });
}