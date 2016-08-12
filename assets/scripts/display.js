(function($){
  // floating sidebar
  $(window).load(function(){
        $('.rlssb-share-bar.caller-wp_print_footer_scripts').css('top', $(window).height()/4);
  });
})(jQuery);
(function($){
  // custom-colors
  $(window).load(function(){
    $('.rlssb-share-bar .button-style-custom .rlssb-button').css('background-color', rlssb_display_settings.background_color);
    $('.rlssb-share-bar .button-style-custom .rlssb-button').css('border-color', rlssb_display_settings.background_color );
    $('.rlssb-share-bar .button-style-custom .rlssb-button a').css('color', rlssb_display_settings.text_color);
    $('.rlssb-share-bar .button-style-custom .rlssb-button').hover( 
        function(){ 
            $(this).css('background-color', rlssb_display_settings.text_color );
            $("a", this).css('color', rlssb_display_settings.background_color );
        },function(){
            $(this).css('background-color', rlssb_display_settings.background_color );
            $("a", this).css('color', rlssb_display_settings.text_color );

        });
    $('.rlssb-share-bar .button-style-custom .rlssb-button a').css('color', rlssb_display_settings.text_color);
  });
})(jQuery);