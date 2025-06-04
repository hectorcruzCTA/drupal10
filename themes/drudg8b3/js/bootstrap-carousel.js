(function ($, Drupal) {
  Drupal.behaviors.bootstrapCarousel = {
    attach: function (context, settings) {
      $('.carousel', context).once('bootstrapCarousel').carousel({
        interval: 5000,
        pause: 'hover',
        wrap: true
      });
    }
  };
})(jQuery, Drupal);
