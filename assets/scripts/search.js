import $ from 'jquery';
import _ from 'lodash';

export function search() {
    let input = $("input[name='searchData']");
    let hasNoResults = $(".hasNoResults");

    const throttledSearch = _.throttle((value) => {
        $.ajax({
            method: 'POST',
            url: '/api/search',
            data: {'searchData': value},
            success: function (data) {
                showTitles(data);
            },
            error: function (error) {
                return error;
            }
        });
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