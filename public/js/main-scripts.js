/*===================================================================
Portal Navigation
===========================================================*/

$(document).ready(function () {
  // Attach event listeners for collapse events once
  $('[data-bs-toggle="collapse"]').each(function () {
    var $btn = $(this);
    var $icon = $btn.find('i.fa-angle-right');
    var targetCollapse = $btn.data('bs-target');

    $(targetCollapse).on('show.bs.collapse', function () {
      $icon.addClass('rotate');
    });

    $(targetCollapse).on('hide.bs.collapse', function () {
      $icon.removeClass('rotate');
    });
  });

  // Fix for initial state
  $('[data-bs-toggle="collapse"]').each(function () {
    var $btn = $(this);
    var $icon = $btn.find('i.fa-angle-right');
    var targetCollapse = $btn.data('bs-target');

    if ($(targetCollapse).hasClass('show')) {
      $icon.addClass('rotate');
    } else {
      $icon.removeClass('rotate');
    }
  });
});
$(document).ready(function () {
  $('#sidebar').on('click', function () {
    $('.sidebar').toggleClass('w-120');
  });
});

$(document).ready(function () {
  // Close other collapsibles when one is opened
  $('.collapse').on('show.bs.collapse', function () {
    $('.collapse').not(this).collapse('hide');
  });

  // Automatically open the menu if an active link is inside
  $('.collapse').each(function () {
    if ($(this).find('a.active').length > 0) {
      $(this).collapse('show');
    }
  });
});