import $ from 'jquery';
import debounce from 'lodash.debounce';
import _ from 'lodash';

export function search() {
    let input = $("input[name='searchData']");

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
        if (inputValue === '') {
            $('.dropdown-menu').removeClass('show');
            return;
        }

        let dropdown = $('.dropdown-menu');
        console.log(movies);
        if (movies.length !== 0) {
            let list = dropdown.find('.list-autocomplete');

            list.empty();

            movies.forEach(function (movie) {
                let movieId = movie.id;
                let movieUrlTitle = movie.urlTitle;
                let movieTitle = movie.title;

                let aEl = $(`<a href=/movie/${movieId}-${movieUrlTitle}" class="dropdown-item">${movieTitle}</a>`);
                list.append(aEl);
            })

            dropdown.addClass('show');
        } else {
            $('.dropdown-menu').removeClass('show');
        }
    }
}