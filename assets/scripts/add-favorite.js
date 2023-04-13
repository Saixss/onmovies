import $ from 'jquery';

$(document).ready(() => {
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
            success: function (data) {
                $('.favorite-icon').toggleClass('inactive');
                let addToFavoritesEl = $('#add-to-favorites-msg');
                addToFavoritesEl.html(data.htmlDisplayMessage);
                addToFavoritesEl.css('color', '#90EE90');
                setTimeout(function () {
                    addToFavoritesEl.css('color', '');
                }, 5000);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown)
            }
        });
    }

    function getMovieId() {
        let url = window.location.href.split('/');
        let urlParams = url[url.length - 1];

        let movieId = urlParams.split('-')[0];

        return movieId;
    }
})