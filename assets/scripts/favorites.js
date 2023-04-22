import $ from 'jquery';

$(document).ready(function () {
    let showMoreEl = $('#show-more');

    showMoreEl.on('click', function () {
        let elCount = $('#movies').children().length;

        fetch(`/api/user/favorites/${elCount}`, {
            method: 'GET',
            headers: {'Content-type': 'application/json'},
        })
            .then(response => response.json())
            .then(data => showFavorites(data))
            .catch(error => console.error(error));
    });

    function showFavorites(movies) {

        movies = JSON.parse(movies);

        movies.forEach(function (movie) {
            let movieId = movie.id;
            let movieUrlTitle = movie.urlTitle;
            let url = `/movie/${movieId}-${movieUrlTitle}`;
            let aEl = $('<a></a>').attr('href', url);

            let imgEl = $('<img>');
            imgEl.attr('src', movie.posterUrl);
            imgEl.attr('alt', movie.title);
            imgEl.addClass('movie-poster');

            aEl.append(imgEl);

            let movieEl = $('<div></div>');
            movieEl.addClass('movie');
            movieEl.append(aEl);

            let colEl = $('<div></div>');
            colEl.addClass('col-md-auto');
            colEl.append(movieEl);

            $('#movies').append(colEl);
        })
    }
})