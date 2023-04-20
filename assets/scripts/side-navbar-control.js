import $ from 'jquery';

export function sideNavbarToggle() {
    let sidebar = $('#sidebar');
    let btnCollapse = $('#sidebarCollapse');

    let width = screen.width;

    if (width <= 768) {
        document.cookie = `sidenav=active;path=/`;
    }

    btnCollapse.on('click', function () {
        sidebar.removeClass('hidden');

        setTimeout(function () {
            sidebar.toggleClass('active');
            btnCollapse.toggleClass('active');

            let active = '';

            if (sidebar.hasClass('active')) {
                active = 'active';
            }

            if (width > 768) {
                document.cookie = `sidenav=${active};path=/`;
            } else {
                document.cookie = `sidenav=active;path=/`;
            }

            function removeCookie(sKey, sPath, sDomain) {
                document.cookie = encodeURIComponent(sKey) +
                    "=; expires=Thu, 01 Jan 1970 00:00:00 GMT" +
                    (sDomain ? "; domain=" + sDomain : "") +
                    (sPath ? "; path=" + sPath : "");
            }
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