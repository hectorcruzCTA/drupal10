(function ($, Drupal) {
  Drupal.behaviors.menuToggle = {
    attach: function (context) {
      // Solo una vez por elemento
      $('.card-list .has-children', context)
        .once('menuToggle')
        .each(function () {
          var $li      = $(this);
          var $wrapper = $li.find('> .item-wrapper');
          var $submenu = $li.find('> .card-list--nested');
          var $btn     = $wrapper.find('.toggle-btn');

          // Aseguramos estado inicial
          $btn.attr('aria-expanded', 'false');
          $submenu.hide();

          // Unificamos el clic en todo el wrapper
          $wrapper.on('click', function (e) {
            e.preventDefault();
            // Alternamos la clase y el submenú
            var isOpen = $li.toggleClass('is-expanded').hasClass('is-expanded');
            $submenu.stop(true, true).slideToggle(200);
            // Actualizamos aria-expanded para accesibilidad
            $btn.attr('aria-expanded', isOpen ? 'true' : 'false');
          });
        });
    }
  };
})(jQuery, Drupal);
