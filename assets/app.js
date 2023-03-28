/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it

// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';
import './styles/movie_details.scss';
import './styles/login.scss';

// start the Stimulus application
import './bootstrap';

import './icons/icon_yellow-star.png';

import {
    categoryDropdownToggle,
    filtersDropdownToggle,
    ordersDropdownToggle,
    sideNavbarToggle,
} from "./scripts/side-navbar-control";

$(document).ready(function () {
    sideNavbarToggle();
    categoryDropdownToggle();
    filtersDropdownToggle();
    ordersDropdownToggle();
});



