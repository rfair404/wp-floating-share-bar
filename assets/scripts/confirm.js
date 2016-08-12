(function($){
  // confirm reset button
  $(window).load(function(){
      console.log('okay what up here');
        $('#rlssb-reset').on( 'click', function(e){
        if( confirm( rlssb_admin_messages.confirm_reset ) ){
            return;
        }
        else{
            event.preventDefault(); // cancel default behavior
        }
    });
  });
})(jQuery);