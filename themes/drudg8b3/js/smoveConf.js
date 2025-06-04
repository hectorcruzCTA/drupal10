
(function($) {
Drupal.behaviors.Smove = {
  attach: function (context, settings) {
    $('#main-container').once().addClass("animacion");
    $('#content2').once().addClass("animacion");
   	$('#content2').once().addClass("animacion");
   	$('#content3').once().addClass("animacion");
   	$('#content4').once().addClass("animacion");
   	$('#content5').once().addClass("animacion");
   	$('#content6').once().addClass("animacion");
   	$('#content7').once().addClass("animacion");
   	$('#content8').once().addClass("animacion");
   	$('#content9').once().addClass("animacion");
   	$('#content10').once().addClass("animacion");
   	$('#content11').once().addClass("animacion");
  	$('.animacion').smoove({offset:'20%'});
  	}
  };
  //anclas
  	$('#block-drudg8b3-main-menu a').click(function(e){
  			//evitar el eventos del enlace normal
  		var strAncla=$(this).attr('href'); //id del ancla
  			$('body,html').stop(true,true).animate({
  				scrollTop: $(strAncla).offset().top
  			},1000);
  	});


})(jQuery);





