$(document).ready(function () {
    $("#buttonFollow").click(executeFollow);
    $("#buttonUnfollow").click(executeUnfollow);
});

function executeFollow() {

    $("#buttonFollow").attr("disabled", true);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.post("/follow",
        {
            followed: (window.location.pathname).substring(window.location.pathname.lastIndexOf('/') + 1)
        },
        function (data, status, xhr) {
            $("#refreshing").load(location.href + " #refreshing");
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
            unfollowed: (window.location.pathname).substring(window.location.pathname.lastIndexOf('/') + 1)
        },
        function (data, status, xhr) {
            $("#refreshing").load(location.href + " #refreshing");
        }, "json");
};