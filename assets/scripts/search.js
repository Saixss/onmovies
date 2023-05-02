import $ from 'jquery';
import _ from 'lodash';

export function search() {
    let input = $("input[name='searchData']");
    let hasNoResults = $(".hasNoResults");
    let dropdown = $('.dropdown-menu');
    let list = dropdown.find('.list-autocomplete');

    const fetchData = _.debounce((input) => {

        let inputValue = input.val().trim();

        if (!inputValue) {
            dropdown.removeClass('show');
            return;
        }

        fetch('/api/search', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'searchData=' + encodeURIComponent(inputValue)
        })
            .then(response => response.json())
            .then(data => showTitles(data))
            .catch(error => console.error(error));
    }, 300);

    input.on('input propertyChange', function () {
        fetchData($(this));
    });

    function showTitles(movies) {

        if (!input.val()) {
            dropdown.removeClass('show');
            return;
        }

        dropdown.addClass('show');

        if (movies.length !== 0) {

            list.empty();

            movies.forEach(function (movie) {
                let movieId = movie.id;
                let movieUrlTitle = movie.urlTitle;
                let movieTitle = movie.title;

                let aEl = $(`<a href=/movie/${movieId}-${movieUrlTitle}" class="dropdown-item" style="overflow: hidden; width: 340px">${movieTitle}</a>`);
                list.append(aEl);
            })

            hasNoResults.hide();
        } else {
            hasNoResults.show();
            list.empty();
        }
    }
}