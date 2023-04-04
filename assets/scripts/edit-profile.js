import $ from 'jquery';

$(document).ready(function () {
    function changeImage() {
        $("#profile-picture").attr('src', URL.createObjectURL($("#user_profilePicture")[0].files[0]));
    }

    $("#user_profilePicture").on("change", changeImage);
})