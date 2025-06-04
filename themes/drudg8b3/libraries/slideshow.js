(function ($) {
  $('#playButtonviews-bootstrap-slideshow-principal-block-1').addClass('not-show');

  $('#playButtonviews-bootstrap-slideshow-principal-block-1').click(function () {
    $('#views-bootstrap-slideshow-principal-block-1').carousel('cycle');
    $('#playButtonviews-bootstrap-slideshow-principal-block-1').addClass('not-show');
    $('#pauseButtonviews-bootstrap-slideshow-principal-block-1').removeClass('not-show');
  });
  $('#pauseButtonviews-bootstrap-slideshow-principal-block-1').click(function () {
    $('#views-bootstrap-slideshow-principal-block-1').carousel('pause');
    $('#pauseButtonviews-bootstrap-slideshow-principal-block-1').addClass('not-show');
    $('#playButtonviews-bootstrap-slideshow-principal-block-1').removeClass('not-show');
  });



  }(jQuery));
