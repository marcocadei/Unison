$(document).ready(function () {
    $("#followedList").on('shown.bs.collapse', retrieveFollowedUsers);
    $("#followerList").on('shown.bs.collapse', retrieveFollowerUsers);
})

function retrieveFollowedUsers(){
    if (!$("#followedList").hasClass("retrieved")) {
        $("#followedList").addClass("retrieved");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post("/followed",
            {
                id: $("#userID").val()
            },
            function (data, status, xhr) {
                followed = data.result;
                showFollowed(followed);
            },
            "json");
    }
}

function showFollowed(followed){
    if (followed.length == 0){
        $("#followedList span").replaceWith("<span>Non segui nessuno attualmente.</span>");
    }
    else{
        let followedList = $("<ul class='pl-2 my-auto'></ul>");
        for (let i = 0; i < followed.length; i++){
            let userLink = $("<li><a style='word-break: break-all' href='user/"+followed[i].id+"'>"+followed[i].name+"</a></li>");
            followedList.append(userLink);
        }
        $("#followedList span").replaceWith(followedList);

        // Classe che uso per indicare che ho già recuperato i followed
        $("#followedList").addClass("retrieved");
    }
}

function retrieveFollowerUsers(){
    if (!$("#followerList").hasClass("retrieved")) {
        $("#followerList").addClass("retrieved");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post("/follower",
            {
                id: $("#userID").val()
            },
            function (data, status, xhr) {
                followers = data.result;
                showFollowers(followers);
            },
            "json");
    }
}

function showFollowers(followers){
    if (followers.length == 0){
        $("#followerList span").replaceWith("<span>Non sei seguito da nessuno attualmente.</span>");
    }
    else{
        let followerList = $("<ul class='pl-2 my-auto'></ul>");
        for (let i = 0; i < followers.length; i++){
            let userLink = $("<li><a style='word-break: break-all' href='user/"+followers[i].id+"'>"+followers[i].name+"</a></li>");
            followerList.append(userLink);
        }
        $("#followerList span").replaceWith(followerList);

        // Classe che uso per indicare che ho già recuperato i followed
        $("#followerList").addClass("retrieved");
    }
}