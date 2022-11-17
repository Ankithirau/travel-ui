/*=========================================================================================
    File Name: form-repeater.js
    Description: form repeater page specific js
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy HTML Admin Template
    Version: 1.0
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

$(function () {
  //'use strict';
var $i=2;
  // form repeater jquery
  $('.invoice-repeater, .repeater-default').repeater({
    show: function () {

      $(this).slideDown();

      $('.addGroup').removeClass('select2').addClass('select'+($i+1));

      $(this).find('span.selection').remove()

      $i=$i+1;

      $('.select'+$i).select2({});

      // Feather Icons
      if (feather) {
        feather.replace({ width: 14, height: 14 });
      }
    },
    hide: function (deleteElement) {
      if (confirm('Are you sure you want to delete this element?')) {
        $(this).slideUp(deleteElement);
      }
    }
  });
});
