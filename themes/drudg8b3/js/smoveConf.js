(function ($, Drupal) {
  Drupal.behaviors.smoveConf = {
    attach: function (context, settings) {
      // Ejecutar sólo una vez al cargar (marcamos en <body>)
      if (!$('body').data('smove-initialized')) {
        $('body').data('smove-initialized', true);

        // Añadir clase animación a todos los contenedores
        $('#main-container, #content2, #content3, #content4, #content5, #content6, #content7, #content8, #content9, #content10, #content11')
          .addClass('animacion');

        // Inicializar smove
        $('.animacion', context).smoove({ offset: '20%' });

        // Scroll suave para anclas del menú principal
        $('#block-drudg8b3-main-menu a').on('click', function (e) {
          var hash = this.hash || $(this).attr('href');
          var $target = $(hash);
          if ($target.length) {
            e.preventDefault();
            $('html, body').animate({ scrollTop: $target.offset().top }, 1000);
          }
        });
      }
    }
  };
})(jQuery, Drupal);
