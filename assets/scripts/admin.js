(function($){
  // sortable icons
    $(window).load(function(){
        $( "#rlssb-sortable" ).sortable({
            axis: "x", 
            cursor: "move", 
            opacity: 0.5,
            stop: function( event, ui ) {
                var arrayOfIds = $.map($("#rlssb-sortable span a"), function(n, i){ return n.id; });
                $( "#rlssb-sort-order" ).val(arrayOfIds);
            }
        });
        $( "#rlssb-sortable" ).disableSelection();
   
        
    });
})(jQuery);
(function($){
  // show hide color picker
  $(window).load(function(){
        $('#rlssb-color-chooser').on('change', function(){
          if(this.value === 'custom'){
              $('.rlssb-color-pickers').show();
          } else {
              $('.rlssb-color-pickers').hide();
          }
        });
        $('.active_networks').on('change', function(){
          $('#rlssb-sortable .' + $(this).val()).toggleClass('rlssb-hidden');
          var has_networks = false;
          $('.active_networks').each(function(){
            if( $(this).attr('checked') === 'checked' ){
              has_networks = true;
            }
            if( has_networks === false ){
              $('.rlssb-order-helper.no-networks').show();
              $('.rlssb-order-helper.with-networks').hide();
            } else {
              $('.rlssb-order-helper.no-networks').hide();
              $('.rlssb-order-helper.with-networks').show();

              
            }
          });
        });
  });
})(jQuery);
(function($){
  // confirm reset button
  $(window).load(function(){
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