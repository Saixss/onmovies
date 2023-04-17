import $ from 'jquery';
import _ from 'lodash';

export function search() {
    let input = $("input[name='searchData']");
    let hasNoResults = $(".hasNoResults");

    const throttledSearch = _.throttle((value) => {
        fetch('/api/search', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'searchData=' + encodeURIComponent(value)
        })
            .then(response => response.json())
            .then(data => showTitles(data))
            .catch(error => console.error(error));
    }, 500);

    input.on('input propertyChange', () => {

        let inputValue = input.val();
        throttledSearch(inputValue);
    });

    function showTitles(movies) {
        let inputValue = input.val();
        let dropdown = $('.dropdown-menu');
        let list = dropdown.find('.list-autocomplete');
        if (inputValue === '') {
            dropdown.removeClass('show');
            return;
        }

        if (movies.length !== 0) {

            list.empty();
            console.log(movies);
            movies.forEach(function (movie) {
                let movieId = movie.id;
                let movieUrlTitle = movie.urlTitle;
                let movieTitle = movie.title;

                let aEl = $(`<a href=/movie/${movieId}-${movieUrlTitle}" class="dropdown-item">${movieTitle}</a>`);
                list.append(aEl);
            })

            dropdown.addClass('show');
            hasNoResults.hide();
        } else {
            hasNoResults.show();
            list.empty();
        }
    }
}