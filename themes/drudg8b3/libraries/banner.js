(function ($) {

$('.carousel[data-type="multi"] .item').each(function() {
  var next = $(this).next();
  if (!next.length) {
    next = $(this).siblings(':first');
  }
  next.children(':first-child').clone().appendTo($(this));

  for (var i = 0; i < 2; i++) {
    next = next.next();
    if (!next.length) {
      next = $(this).siblings(':first');
    }

    next.children(':first-child').clone().appendTo($(this));
  }
});


$('#playButtonbanner').addClass('not-show');

  $('#playButtonbanner').click(function () {
    $('#carouselBanner').carousel('cycle');
    $('#playButtonbanner').addClass('not-show');
    $('#pauseButtonbanner').removeClass('not-show');
  });
  $('#pauseButtonbanner').click(function () {
    $('#carouselBanner').carousel('pause');
    $('#pauseButtonbanner').addClass('not-show');
    $('#playButtonbanner').removeClass('not-show');
  });


  }(jQuery));
