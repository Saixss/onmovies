import $ from 'jquery';

export function sideNavbarToggle() {
    let sidebar = $('#sidebar');
    let btnCollapse = $('#sidebarCollapse');
    let isActive = sessionStorage.getItem('sidebar');

    if (isActive == null) {
        isActive = '';
    }

    sidebar.attr("class", isActive) ;
    btnCollapse.addClass(isActive) ;

    btnCollapse.on('click', function () {
        sidebar.toggleClass('active');
        btnCollapse.toggleClass('active');

        if (sidebar.attr("class") === undefined) {
            sidebar.attr("class", '');
        }

        sessionStorage.setItem('sidebar', sidebar.attr('class'));
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