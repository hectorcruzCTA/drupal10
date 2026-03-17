(function ($) {

  Drupal.behaviors.udg_liston = {
    attach: function (context, settings) {
      $('#playButtonVSlide').addClass('not-show');

      $('#carousel-udg').carousel({
        interval:10000,
      });

      $('#playButtonVSlide').click(function () {
        $('#carousel-udg').carousel('cycle');
        $('#playButtonVSlide').addClass('not-show');
        $('#pauseButtonVSlide').removeClass('not-show');

      });
      $('#pauseButtonVSlide').click(function () {
        $('#carousel-udg').carousel('pause');
        $('#pauseButtonVSlide').addClass('not-show');
        $('#playButtonVSlide').removeClass('not-show');
      });
    }
  }



}(jQuery));
