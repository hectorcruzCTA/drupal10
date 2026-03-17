(function ($) {
  Drupal.behaviors.ListonBlock = {
    attach: function (context, settings) {
        // Donde queremos cambiar el tamaño de la fuente
      var donde = $('body');
      var state = 0;
      var sizeFuenteOriginal = donde.css('font-size');
       // Resetear Font Size
     $(".resetearFont").once().click(function(){
            donde.css('font-size', sizeFuenteOriginal);
            state = 0;
            $("body").removeClass("accessibility-invert-image");
            $("body").removeClass("accessibility-contrast-image");
            $("body").removeClass("accessibility-sepia-image");
      });
        // Aumentar Font Size
      $(".aumentarFont").once().click(function(){
        if(state <= 2){
            var sizeFuenteActual = donde.css('font-size');
            var sizeFuenteActualNum = parseFloat(sizeFuenteActual, 10);
            var sizeFuenteNuevo = sizeFuenteActualNum*1.2;
            donde.css('font-size', sizeFuenteNuevo);
            state = state + 1;
            return false;

        }

      });
      // Disminuir Font Size
      $(".disminuirFont").once().click(function(){
        if(state >= -1){
            var sizeFuenteActual = donde.css('font-size');
            var sizeFuenteActualNum = parseFloat(sizeFuenteActual, 10);
            var sizeFuenteNuevo = sizeFuenteActualNum*0.8;
            donde.css('font-size', sizeFuenteNuevo);
            state = state - 1;
            return false;
        }
      });

     $("#accessibility-invert").once().click(function(){
        $("body").toggleClass("accessibility-invert-image");
        $("body").removeClass("accessibility-contrast-image");
        $("body").removeClass("accessibility-sepia-image");
      });
     $("#accessibility-contrast").once().click(function(){
        $("body").toggleClass("accessibility-contrast-image");
        $("body").removeClass("accessibility-sepia-image");
        $("body").removeClass("accessibility-invert-image");
      });
     $("#accessibility-sepia").once().click(function(){
        $("body").toggleClass("accessibility-sepia-image");
        $("body").removeClass("accessibility-invert-image");
        $("body").removeClass("accessibility-contrast-image");
      });

     function invertHex(hex) {
       return (Number(`0x1${hex}`) ^ 0xFFFFFF).toString(16).substr(1).toUpperCase()
      }

    }
  }
}(jQuery));
