/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app_front.scss');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
const $ = require('jquery');
global.$ = global.jQuery = $;

require('popper.js');
require('bootstrap');
require('jquery-ui/ui/widgets/datepicker.js');

import Cookies from 'js-cookie';

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

//jQuery to collapse the navbar on scroll
$(window).scroll(function() {
    if ($(".navbar").offset().top > 50) {
      $(".menu").addClass("fixed");
    } else {
      $(".menu").removeClass("fixed");
    }
});

//DATEPICKER
$.datepicker.regional['fr'] = {clearText: 'Effacer', clearStatus: '',
    closeText: 'Fermer', closeStatus: 'Fermer sans modifier',
    prevText: '&lt;Préc', prevStatus: 'Voir le mois précédent',
    nextText: 'Suiv&gt;', nextStatus: 'Voir le mois suivant',
    currentText: 'Courant', currentStatus: 'Voir le mois courant',
    monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin',
    'Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
    monthNamesShort: ['Jan','Fév','Mar','Avr','Mai','Jun',
    'Jul','Aoû','Sep','Oct','Nov','Déc'],
    monthStatus: 'Voir un autre mois', yearStatus: 'Voir un autre année',
    weekHeader: 'Sm', weekStatus: '',
    dayNames: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
    dayNamesShort: ['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'],
    dayNamesMin: ['Di','Lu','Ma','Me','Je','Ve','Sa'],
    dayStatus: 'Utiliser DD comme premier jour de la semaine', dateStatus: 'Choisir le DD, MM d',
    dateFormat: 'dd/mm/yy', firstDay: 0, 
    initStatus: 'Choisir la date', isRTL: false};
 $.datepicker.setDefaults($.datepicker.regional['fr']);
$(document).ready(function() {
    var dateToday = new Date();
    $('.datepicker').datepicker({
        minDate: dateToday,
    });
});


// EXCEPTION CLOSURE
$(document).ready(function() {
    $('#exceptionalClosure').modal('show');
    $('.btn-exceptionclosure').click(function() {
        $('#exceptionalClosure').modal('hide');
    });
});

// COOKIES WEBSITE
$(document).ready(function() {
  var this_cookie = 'cookie_privacy';
  var current_cookie = Cookies.get(this_cookie);
  
  if(this_cookie != current_cookie){
      $('.rgpd').show();
      
      $('.btn-cookie').click(function() {
          Cookies.set(this_cookie, this_cookie, { expires: 30 });
          $('.rgpd').hide();
      });
  }
});
$( document ).ready(function() {
    $(".info-rgpd").after(function () {
        return '<a href="#" data-toggle="modal" data-target="#modal-rgpd"><i class="fa fa-info-circle"></i></a>';
    });
});


// sidebar
$(document).ready(function () {
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });
});
//scroll top
$(document).ready(function(){
	$(window).scroll(function () {
			if ($(this).scrollTop() > 50) {
				$('#back-to-top').fadeIn();
			} else {
				$('#back-to-top').fadeOut();
			}
		});
		// scroll body to 0px on click
		$('#back-to-top').click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 400);
			return false;
		});
});

/////////////////////////////////////////////////////
/////////////// Menu fixed, top scrolled////////////
///////////////////////////////////////////////////
'use strict';
    document.addEventListener("DOMContentLoaded", ev => {
    }, false);

    window.addEventListener("load", ev => {
    const
    header = document.querySelector("#siteWrapper header"),
    scrolled = ev => {
        let
        windowHeight = document.body.clientHeight,
                                                // Nombre de pixel dont le contenu d'élément a défilé vers le haut, soit du body , soit de l'élément
        currentScroll = document.body.scrollTop || document.documentElement.scrollTop;

        header.className = (currentScroll >= windowHeight - header.offsetHeight) ? "fixedMenuCarte" : "";
        };

        window.addEventListener("scroll", scrolled, false);
    }, false);


/////////////////////////////////////////////////////
/////////////// Tooltip bootstrap////////////
///////////////////////////////////////////////////
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})
/////////////////////////////////////////////////////
//// Display and hide Vins category to la-carte/////
///////////////////////////////////////////////////
// $('#vinsDuMaroc').collapse({
//     show: true
//     })

// $('#vinsDeFrance').collapse({
//     show: true
//     })

$(document).ready(function(){
    $("#btnFrance").on("click",function(){
    // $(".btnFrance").click(function(){
      $(".vinsDeFrance").toggleClass("hideVins");
    });
});

$(document).ready(function(){
    $("#btnFrance").on("click",function(){
    //$(".btnMaroc").click(function(){
      $(".vinsDuMaroc").toggleClass("hideVins");
    });
});

function rtn() {
    window.history.back();
 }
// $(document).ready(function() {
//     $("#titreBtnVins").on("click",function (){
//         if($("#titreBtnVins").val()=='Afficher')
//             $("#titreBtnVins").val('Cacher')
//         else if($("#titreBtnVins").val()=='Cacher')
//             $("#titreBtnVins").val('Afficher')
//     });
// });
