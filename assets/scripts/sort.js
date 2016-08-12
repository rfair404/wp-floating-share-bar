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