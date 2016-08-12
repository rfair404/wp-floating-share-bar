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