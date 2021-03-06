/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app_back.scss');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
const $ = require('jquery');
global.$ = global.jQuery = $;

require('popper.js');
require('bootstrap');

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

// SIDEBAR HIDE
$(document).ready(function () {
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });

    if($(window).width() < 1092){
        $('#sidebar').toggleClass('active');
    } 
});

//AFFICHAGE DES NOM DES FICHIER UPLOADE
$(document).ready(function(){
    var id = $('.custom-file input').attr('id');
    $('#'+id).on('change',function(){
        var fileName = $(this).val();
        fileName = fileName.replace('C:\\fakepath\\', " ");
        $(this).next('.custom-file-label').html(fileName);
    });
});
