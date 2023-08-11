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

  $(document).on('click', '.btnRemove', {}, function() {
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

  $(document).on('change', ':input[name*=quantidade]', {}, function(e) {
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

  $('#clienteNome').autoComplete({
    source: function(term, response) {
      try { xhr.abort(); } catch(e) {}
      xhr = $.ajax({
        type: 'GET',
        url: '/admin/ajax/clientes/',
        async: false,
        data: {cliente : term },
        success: function(data) { response(data); },
        error: function(a,b,c) { console.log(a,b,c); }
      });
    },
    renderItem: function (item, search){
      // escape special characters
      search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
      var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
      return '<div class="autocomplete-suggestion" data-autocomplete-result=\'' + JSON.stringify(item) + '\' data-val="' + item.nome + '">' + item.nome.replace(re, "<b>$1</b>") + '</div>';
    },
    onSelect: function(ev, item, element) {
      //console.log(item, element, element.data());
      var result = element.data('autocompleteResult');
      if ( result != undefined )
        $(':input[name=cliente]').val(result.id)
      else {
        $(':input[name=cliente]').val('');
        element.val('');
      }
    }
  });

  $('#produtoNome').autoComplete({
    source: function(term, response) {
      try { xhr.abort(); } catch(e) {}
      xhr = $.ajax({
        type: 'GET',
        url: '/admin/ajax/produtos/',
        async: false,
        data: {produto : term },
        success: function(data) { response(data); },
        error: function(a,b,c) { console.log(a,b,c); }
      });
    },
    renderItem: function (item, search){
      // escape special characters
      search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
      var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
      return '<div class="autocomplete-suggestion" data-autocomplete-result=\'' + JSON.stringify(item) + '\' data-val="' + item.nome + '">' + item.nome.replace(re, "<b>$1</b>") + '</div>';
    },
    onSelect: function(ev, item, element) {
      //console.log(item, element, element.data());
      var result = element.data('autocompleteResult');

      if ( result != undefined ) {

        var $inpProdQntd  = $(':input[name*="'+result.id+'[quantidade]"]'),
            inpProdExists = (!!$inpProdQntd.length),
            $table        = $('#card-cadastro table'),
            $tBody        = $table.find('tbody');

        if (!inpProdExists) {
          $tBody.append(''+
                        '        <tr class="border-bottom">'+
                        '            <td data-th="Product">'+
                        '                <div class="row">'+
                        '                    <div class="col-auto hidden-xs"><img src="/storage/'+(result.imagem).replace('public', '')+'" alt="..." width="40" class="img-fluid rounded"/></div>'+
                        '                    <div class="col"><h6 class="m-1">'+result.nome+'</h6></div>'+
                        '                </div>'+
                        '            </td>'+
                        '            <td data-th="Price">R$ '+formatMoney(result.valor)+'</td>'+
                        '            <td data-th="Quantity">'+
                        '                <input name="'+result.id+'[quantidade]" type="number" class="form-control text-center" value="1" data-oldValue="1" autocomplete="off">'+
                        '                <input name="'+result.id+'[valor]" type="hidden" value="'+result.valor+'" autocomplete="off">'+
                        '            </td>'+
                        '            <td class="actions" data-th="">'+
                        '                <a href="javascript: void(0);" data-product="'+result.id+'" class="btnRemove btn btn-danger btn-sm mx-1"><i class="fas fa-trash-alt"></i></button>'+
                        '            </td>'+
                        '            <td data-th="Subtotal" class="text-center">R$ <span class="subTotal">'+formatMoney(result.valor)+'</span></td>'+
                        '        </tr>'
          );

          Calculatotal(result.valor);
        }

        $('#produtoNome').val('');
      }
    }
  });

});