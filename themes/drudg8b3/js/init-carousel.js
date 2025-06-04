(function ($, Drupal) {
  console.log('INIT-CAROUSEL: Start of script');
  console.log('INIT-CAROUSEL: typeof $ is', typeof $);
  console.log('INIT-CAROUSEL: $ === jQuery is', $ === jQuery);
  console.log('INIT-CAROUSEL: $.fn.jquery is', $.fn.jquery); // Versión de jQuery
  console.log('INIT-CAROUSEL: typeof $.fn.once is', typeof $.fn.once); // ¿"function" o "undefined"?
  console.log('INIT-CAROUSEL: typeof Drupal is', typeof Drupal);

  Drupal.behaviors.initBootstrapCarousel = {
    attach: function (context, settings) {
      console.log('INIT-CAROUSEL attach: Called. typeof $.fn.once is', typeof $.fn.once); // ¿"function" o "undefined"?
      if (typeof $.fn.once === 'function') {
        $('.carousel', context).once('initCarousel').carousel({
          interval: 5000,
          pause: 'hover',
          wrap: true
        });
        console.log('INIT-CAROUSEL: .carousel() called via .once()');
      } else {
        console.error('init-carousel.js: FATAL - $.fn.once is not a function right before calling it!');
      }
    }
  };
  console.log('INIT-CAROUSEL: Behavior defined');
})(jQuery, Drupal);
