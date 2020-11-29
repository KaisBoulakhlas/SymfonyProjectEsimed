/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import 'bootstrap';
import $ from 'jquery';

require('bootstrap');
require('jquery');

$(document).ready(function(){
    
        $('.advertinfo').click(function(){
            let advertContent = $(this).data('content');
            // AJAX request
            $.ajax({
                type: 'get',
                success: function(){
                    $('.modal-body').html(advertContent);
                    // Display Modal
                    $('#exampleModal').modal('show');
                }
            });
        });
});
// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

console.log('Hello Webpack Encore! Edit me in assets/app.js');
