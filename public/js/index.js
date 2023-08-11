$(function(){

  $('#orderby').on('change', function() {
    var $this = $(this),
        $form = $this.closest('form');
    $form.submit();
  });

});
