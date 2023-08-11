$(function() {

  var hostname = $('meta[name=host]').attr('content'),
      $form = $('#updCart'),
      interval;

  function Calculatotal(val) {
    var $totalValor = $('#totalValor'),
        totalValor  = $totalValor.text().replace('.', '').replace(',', '.');

    try {
      totalValor = parseFloat(totalValor);
    } catch (e) {
      console.log(e);
      totalValor = 0;
    }

    val = Math.roundPrecision(val, 2);
    totalValor += val;
    totalValor = Math.roundPrecision(totalValor, 2);

    $totalValor.text(totalValor.formatMoney(2, ',', '.'));
  }

  $('.btnRemove').on('click', function() {
    var $this   = $(this),
        $tr     = $this.closest('tr'),
        $inpQtd = $tr.find(':input[name*=quantidade]'),
        $valor  = $tr.find(':input[name*=valor]'),
        qtd     = $inpQtd.val(),
        valor   = $valor.val().replace(/[^0-9.]/, '');

    try {
      valor = Math.roundPrecision(parseFloat(valor), 2);
    }
    catch (e) {
      console.log(e);
      valor = 0;
    }

    qtd = parseInt(qtd);
    valor = qtd*valor;
    Calculatotal(-valor);

    $inpQtd.val(0);
    $tr.hide();
  });

  $(':input[name*=quantidade]').on('change', function(e) {
    var $this   = $(this),
        $tr     = $this.closest('tr'),
        $inpQtd = $tr.find(':input[name*=quantidade]'),
        $valor  = $tr.find(':input[name*=valor]'),
        $subTotal = $tr.find('.subTotal'),
        qtd     = parseInt($inpQtd.val()),
        valor   = $valor.val().replace(/[^0-9.]/, ''),
        oldQtd  = parseInt($this.data('oldvalue'));

    $this.data('oldvalue', qtd);

    try {
      valor = Math.roundPrecision(parseFloat(valor), 2);
    }
    catch (e) {
      console.log(e);
      valor = 0;
    }

    var subTotal = qtd*valor;
    $subTotal.text( subTotal.formatMoney(2, ',', '.') );

    if (oldQtd > qtd)
      Calculatotal(-valor);
    else
      Calculatotal(valor);
  });

  interval = setInterval(function() {
    var timers = $('.elapsedTime');

    if (timers.length == 0)
      clearInterval(interval);

    timers.each(function(idx, elm) {
      var $elm = $(elm),
          time = $elm.text().split(':'),
          min  = parseInt(time[0]),
          sec  = parseInt(time[1]);
      if (sec >= 59) {
        sec = 0;
        min++;
      } else {
        sec++;
      }

      if (min>=10)
        $elm.text('PEDIDO SENDO CANCELADO').removeClass('elapsedTime');
      else
        $elm.text(padLeft(min, 2, '0')+':'+padLeft(sec, 2, '0'));
    });
  }, 1000);
});