function executeFollow() {

    $("#buttonFollow").attr("disabled", true);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.post("/follow",
        {
            followed: parseInt($("#userID").text())
        },
        function (data, status, xhr) {
            $("#refreshing-container").load(location.href + " #refreshing-contained");
        }, "json");
};

function executeUnfollow() {

    $("#buttonUnfollow").attr("disabled", true);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.post("/unfollow",
        {
            unfollowed: parseInt($("#userID").text())
        },
        function (data, status, xhr) {
            $("#refreshing-container").load(location.href + " #refreshing-contained");
        }, "json");
};