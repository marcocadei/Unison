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
            $(btnLike).toggleClass("buttonOn");
        }, "json");
};