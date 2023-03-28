import $ from 'jquery';

$(document).ready(function () {
    let favoriteBtn = $('.favorite-icon');

    let movieId = getMovieId();

    favoriteBtn.on('click', function () {
        saveFavorite();
    });

    function saveFavorite() {
        $.ajax({
            type: "POST",
            url: '/user/add-favorite/',
            data: {'movieId': movieId},
            success: function () {
                $('.favorite-icon').toggleClass('inactive');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown)
            }
        });
    }

    function getMovieId()
    {
        let url = window.location.href.split('/');
        let urlParams = url[url.length - 1];

        let movieId = urlParams.split('-')[0];

        return movieId;
    }
})
