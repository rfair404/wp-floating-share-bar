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
        });
  });
})(jQuery);