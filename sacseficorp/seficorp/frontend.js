jQuery(function ($) {

  // Abrir modal
  $(document).on('click', '.open-modal', function (e) {
    e.preventDefault();
    var id = $(this).data('modal');
    $('#' + id).fadeIn(150);
  });

  // Cerrar con la X
  $(document).on('click', '.close-gc', function () {
    $(this).closest('.modal-gc').fadeOut(150);
  });

  // Cerrar al hacer click fuera
  $(document).on('click', '.modal-gc', function (e) {
    if ($(e.target).is('.modal-gc')) {
      $(this).fadeOut(150);
    }
  });

});
