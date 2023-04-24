import $ from 'jquery';

$(document).ready(function () {
    let showMoreEl = $('#show-more');

    async function getMovies(currElCount = 0){
        let elCount = Number($('#movies').children().length) + Number(currElCount);

        try {
            const response = await fetch(`/api/user/favorites/${elCount}`, {
                method: 'GET',
                headers: { 'Content-type': 'application/json' },
            });
            const data = await response.json();
            return data;
        } catch (error) {
            console.error(error);
            throw error;
        }
    }

    showMoreEl.on('click', showFavorites);

    async function removeButton(currElCount) {

        let data = await getMovies(currElCount);
        data = JSON.parse(data);

        if (data.length === 0) {
            showMoreEl.css('visibility', 'hidden');
        }
    }

    async function showFavorites() {

        let movies = await getMovies();
        movies = JSON.parse(movies);

        await removeButton(movies.length);

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