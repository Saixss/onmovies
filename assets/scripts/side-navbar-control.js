import $ from 'jquery';

export function sideNavbarToggle() {
    let sidebar = $('#sidebar');
    let btnCollapse = $('#sidebarCollapse');

    btnCollapse.on('click', function () {
        sidebar.removeClass('hidden');

        setTimeout(function () {
            sidebar.toggleClass('active');
            btnCollapse.toggleClass('active');

            let active = '';

            if (sidebar.hasClass('active')) {
                active = 'active';
            }

            document.cookie = `sidenav=${active}`;
        }, 100)
    });
}

export function categoryDropdownToggle() {
    let dropdown = $('#genreSubmenu');

    dropdown.prev().on('click', function () {
        dropdown.toggleClass('collapse');
    });
}

export function filtersDropdownToggle() {
    let dropdown = $('#filtersSubmenu');

    dropdown.prev().on('click', function () {
        dropdown.toggleClass('collapse');
    });
}

export function ordersDropdownToggle() {
    let dropdownsParent = $('#filtersSubmenu');
    let dropdownToggle = dropdownsParent.find('.dropdown-toggle');
    dropdownToggle.each(function () {
        $(this).on('click', function () {
            $(this).next().toggleClass('collapse');
        });
    })
}