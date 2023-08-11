//----- Instantiate Datepickers  -----//
  $(function() {
    if ($.datepicker) {
      $.datepicker.setDefaults({
        showButtonPanel: true,
        showOn: "focus",
        dateFormat: "dd/mm/yy"
      });

      $('.calendar').datepicker();
      $('.calendar').mask('00/00/0000');
    }
  });
// ------------------- //

//----- Function Format Date -----//
  /*
   * Função para retornar Data no formato dd/mm/YYYY
   * @date Data a ser formatada
   * @separate Separador do Dia Mes Ano
   * @by Pyetro Costa
   */
  var formatDate = function(date, separate, delimiter) {
    if (!date || date==undefined || date=='')
      return '';
    date = date.substr(0, 10);
    separate = (separate==undefined)?'-':separate;
    delimiter = (delimiter==undefined)?'/':delimiter;
    date = date.split(separate);
    return date[2]+delimiter+date[1]+delimiter+date[0];
  };
// ------------------- //

//----- Function Format Money -----//
  /**
   * Input <number>
   * Output <string>
   * Função a partir de um objeto Number, para retornar Moeda no formato desejado
   * @c Quantidade de casas decimais
   * @d Separador de decimais
   * @t Separador de Milhares
   * Ex: var aNum = 123.45, fmtNum = aNum.formatMoney(2, ',', '.'); console.log( fmtNum ); // Output: '123,45'
   *
   */
  Number.prototype.formatMoney = function(c, d, t){
    var n = this, c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? ',' : d, t = t == undefined ? '.' : t, s = n < 0 ? '-' : '', i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + '', j = (j = i.length) > 3 ? j % 3 : 0;
     return s + (j ? i.substr(0, j) + t : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : '');
  };

  /*
   * Função passando o número como argumento para retornar Moeda no formato desejado
   * @n Número a ser formatado
   * @c Quantidade de casas decimais
   * @d Separador de decimais
   * @t Separador de Milhares
   * Ex: var fmtNum = formatMoney(123.45, 2, ',', '.'); console.log( fmtNum ); // Output: '123,45'
   */
  var formatMoney = function (n, c, d, t) {
    return Number.prototype.formatMoney.call(n, c, d, t);
  };
// ------------------- //

//----- Máscaras e Validações para várias opções -----//

  /* ----- Máscaras ------ */

    // Mascara de CPF e CNPJ
    var CpfCnpjMaskBehavior = function (val) { return val.replace(/\D/g, '').length <= 11 ? '000.000.000-009' : '00.000.000/0000-00'; },
         CpfCnpjOptions = { onKeyPress: function(val, e, field, options) { field.mask(CpfCnpjMaskBehavior.apply({}, arguments), options); } };

    // Função que retorna uma string de CPF/CNPJ com máscara ou sem máscara
    var formatCpfCnpj = function (val, removeMask) {
      removeMask = (removeMask == undefined ? false : removeMask);
      // Verifica se é para inserir máscara no CPF/CNPJ ou removê-la
      if (!removeMask) {
        if (val.length == 11)
          val = val.substring(0, 3)+'.'+val.substring(3, 6)+'.'+val.substring(6, 9)+'-'+val.substring(9, 11);
        else if (val.length == 14)
          val = val.substring(0, 2)+'.'+val.substring(2, 5)+'.'+val.substring(5, 8)+'/'+val.substring(8, 12)+'-'+val.substring(12, 14);

      } else {
        val = val.replace(/\D/g, '');
      }

      return val;
    }

    // Mascara para Dinheiro e Decimais
    // Fonte: https://github.com/igorescobar/jQuery-Mask-Plugin/issues/527 point to >>> http://jsfiddle.net/c6qj0e3u/11/
    // Editado por: Pyetro >>> http://jsfiddle.net/c6qj0e3u/15/
    // Nova funcionalidade: Verifica valor do campo de é 0,00 e pressiona Backspace, limpa o campo
    var MoneyOpts = {
      reverse:true,
      maxlength: false,
      placeholder: '0,00',
      onKeyPress: function(v, ev, curField, opts) {
        var mask = curField.data('mask').mask,
            decimalSep = (/0(.)00/gi).exec(mask)[1] || ',';
        if (curField.data('mask-isZero') && curField.data('mask-keycode') == 8)
          $(curField).val('');
        else if (v) {
          // remove previously added stuff at start of string
          v = v.replace(new RegExp('^0*\\'+decimalSep+'?0*'), ''); //v = v.replace(/^0*,?0*/, '');
          v = v.length == 0 ? '0'+decimalSep+'00' : (v.length == 1 ? '0'+decimalSep+'0'+v : (v.length == 2 ? '0'+decimalSep+v : v));
          $(curField).val(v).data('mask-isZero', (v=='0'+decimalSep+'00'));
        }
      }
    };

    // Mascara para Dinheiro e Decimais com Prefixo e Sinal negativo
    // Font base: https://github.com/igorescobar/jQuery-Mask-Plugin/issues/670 point to http://jsfiddle.net/c6qj0e3u/15/
    // Edited by: Pyetro
    // New feature: Check if field value is "0,00" and Backspace was pressed, so clean Val
    // Accept decimals "." or ","
    var MoneyOptsMinus = {
      reverse:true,
      maxlength: false,
      placeholder: '0,00',
      byPassKeys: [9, 16, 17, 18, 35, 36, 37, 38, 39, 40, 46, 91],
      eventNeedChanged: false,
      onKeyPress: function(v, ev, curField, opts) {
        var mask = curField.data('mask').mask,
            decimalSep = (/0(.)00/gi).exec(mask)[1] || ',';

        opts.prefixMoney = typeof (opts.prefixMoney) != 'undefined' ? opts.prefixMoney : '';

        // Get Key pressed and Apply Minus signal or not
        var key = curField.data('mask-key');
        var minus = (typeof (curField.data('mask-minus-signal')) == 'undefined' ? false : curField.data('mask-minus-signal'));

        if ( ['-', '+'].indexOf(key) >= 0) {
            curField.val((opts.prefixMoney)+(key == '-' ? key+v : v.replace(/^-?/, '')));
            curField.data('mask-minus-signal', key == '-');
            return;
        }

        // Check value of the field
        if (curField.data('mask-isZero') && curField.data('mask-keycode') == 8)
          $(curField).val('');
        else if (v) {
          // remove previously added stuff at start of string
          v = v.replace(new RegExp('^-?'), '');
          v = v.replace(new RegExp('^0*\\'+decimalSep+'?0*'), ''); //v = v.replace(/^0*,?0*/, '');
          v = v.length == 0 ? '0'+decimalSep+'00' : (v.length == 1 ? '0'+decimalSep+'0'+v : (v.length == 2 ? '0'+decimalSep+v : v));
          curField.val((opts.prefixMoney)+(minus?'-':'')+v).data('mask-isZero', (v=='0'+decimalSep+'00'));
        }
      }
    };

    // Mascara para Dinheiro e Decimais com Prefixo e Sinal negativo
    var MoneyOptsPrefix = {};
    MoneyOptsPrefix = $.extend(true, {}, MoneyOptsPrefix, MoneyOptsMinus);
    MoneyOptsPrefix.prefixMoney = 'R$ ';

    // Mascara de telefone móvel e fixo
    var phone_mob_mask = function (val) { return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009'; },
        phone_mob_opt = { onKeyPress: function(val, e, field, options) { field.mask(phone_mob_mask.apply({}, arguments), options); } },
        phone_mask = '(00) 0000-0000';

    // Mascara de telefone de SP
    var formatPhone = function (val) {
      if (val !== '' && val !== null) {
        val = val.replace(/\D+/g, '');

        if (val.length == 8) // Telefone fixo sem DDD
          return val.substr(0, 4)+'-'+val.substr(-4);
        else if (val.length == 9) // Celular sem DDD
          return val.substr(0, 5)+'-'+val.substr(-4);
        else if (val.length == 10) // Telefone fixo com DDD
          return '('+val.substr(0, 2)+') '+val.substr(2, 4)+'-'+val.substr(-4);
        else if (val.length == 11) // Celular com DDD
          return '('+val.substr(0, 2)+') '+val.substr(2, 5)+'-'+val.substr(-4);
        else if (val.length == 12) // Telefone fixo com DDD e DDI do Brasil
          return '+'+val.substr(0, 2)+' ('+val.substr(2, 2)+') '+val.substr(4, 4)+'-'+val.substr(-4);
        else if (val.length == 13) // Celular com DDD e DDI do Brasil
          return '+'+val.substr(0, 2)+' ('+val.substr(2, 2)+') '+val.substr(4, 5)+'-'+val.substr(-4);
        else
          return '';
      } else
        return '';
    }
  // ---------------

  /* ----- Validações ------ */

    // Verifica se o texto é um email válido ou não
    var validateEmail = function ($email) {
        // var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        // Regex by (https://emailregex.com/)
        var emailReg = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return emailReg.test( $email );
    };

    // Valida o CPF digitado
    function ValidarCPF(cpf) {
      try {
        // Verifica se um número foi informado
        if (!cpf)
          return false;

        // Elimina possivel mascara
        cpf = cpf.replace(/\D/g, '');
        cpf = ('00000000000'+cpf).slice(-11);

        // Verifica se o numero de digitos informados é igual a 11
        if (cpf.length != 11)
          return false;

        // Verifica se nenhuma das sequências invalidas abaixo foi digitada. Caso afirmativo, retorna falso
        else if (cpf == '00000000000' || cpf == '11111111111' || cpf == '22222222222' || cpf == '33333333333' || cpf == '44444444444' ||
                 cpf == '55555555555' || cpf == '66666666666' || cpf == '77777777777' || cpf == '88888888888' || cpf == '99999999999')
          return false;

        // Calcula os digitos verificadores para verificar se o CPF é válido
        else {
          for (var t = 9; t < 11; t++) {
            for (var d = 0, c = 0; c < t; c++) {
              d += cpf[c] * ((t + 1) - c);
            }
            d = ((10 * d) % 11) % 10;
            if (cpf[c] != d) {
              return false;
            }
          }
          return true;
        }
      } catch (e) {
      } {
        return false;
      }
    }

    // Valida o CNPJ digitado
    function ValidarCNPJ(cnpj){
      try {
        if (!cnpj)
          return false;

        var valida = new Array(6,5,4,3,2,9,8,7,6,5,4,3,2),
            dig1= new Number,
            dig2= new Number;

        cnpj = cnpj.toString().replace(/\D/g, '');
        var digito = new Number(eval(cnpj.charAt(12)+cnpj.charAt(13)));

        for(i = 0; i<valida.length; i++){
                dig1 += (i>0? (cnpj.charAt(i-1)*valida[i]):0);
                dig2 += cnpj.charAt(i)*valida[i];
        }
        dig1 = (((dig1%11)<2)? 0:(11-(dig1%11)));
        dig2 = (((dig2%11)<2)? 0:(11-(dig2%11)));

        if(((dig1*10)+dig2) != digito)
          return false;
        else
          return true;
      }
      catch (e) {
        return false;
      }
    }
  // ---------------
// ------------------- //

//----- Function Str_Pad by: Kevin van Zonneveld, improved by: Michael White -----//
  var str_pad = function (input, padLength, padString, padType) {
    /**
     * discuss at: http://locutus.io/php/str_pad/
     * original by: Kevin van Zonneveld (http://kvz.io , http://kevin.vanzonneveld.net)
     * improved by: Michael White (http://getsprink.com)
     *    input by: Marco van Oort
     * bugfixed by: Brett Zamir (http://brett-zamir.me)
     *   example 1: str_pad('Kevin van Zonneveld', 30, '-=', 'STR_PAD_LEFT')
     *   returns 1: '-=-=-=-=-=-Kevin van Zonneveld'
     *   example 2: str_pad('Kevin van Zonneveld', 30, '-', 'STR_PAD_BOTH')
     *   returns 2: '------Kevin van Zonneveld-----'
     */
    try {

      if (!input || typeof input !== 'string' && typeof input !== 'number')
        throw { message: 'O valor a ser convertido não foi definido ou é inválido' };
      if (padLength == undefined || padLength == null || typeof padLength !== 'number')
        throw { message: 'O número de caracteres a ser preenchido não foi definido ou é inválido' };
      if (typeof padString !== 'string' && typeof padString !== 'number' && padString !== undefined)
        throw { message: 'O parâmetro StringPad é inválido' };

      var half = '',
          padToGo,
          _strPadRepeater = function (s, len) {
            var collect = ''
            while (collect.length < len) {
              collect += s
            }
            collect = collect.substr(0, len)
            return collect
          };
      input += '';
      padString = padString !== undefined ? padString : ' ';
      if (padType !== 'STR_PAD_LEFT' && padType !== 'STR_PAD_RIGHT' && padType !== 'STR_PAD_BOTH') {
        padType = 'STR_PAD_RIGHT';
      }
      if ((padToGo = padLength - input.length) > 0) {
        if (padType === 'STR_PAD_LEFT') {
          input = _strPadRepeater(padString, padToGo) + input
        } else if (padType === 'STR_PAD_RIGHT') {
          input = input + _strPadRepeater(padString, padToGo)
        } else if (padType === 'STR_PAD_BOTH') {
          half = _strPadRepeater(padString, Math.ceil(padToGo / 2))
          input = half + input + half
          input = input.substr(0, padLength)
        }
      }
      return input
    } catch (e) {
      console.log(e.message);
    }
  };
// ------------------- //

//----- Function PadLeft by: KooiInc -----//
  /*
   * @credits https://stackoverflow.com/a/5367656
   * original by: KooiInc
   */
  var padLeft = function (nr, n, str) {
    try {

      if (!nr&nr!=0 || typeof nr !== 'string' && typeof nr !== 'number') {
        throw { message: 'O valor a ser convertido não foi definido ou é inválido' };
      }
      if (n == undefined || n == null || typeof n !== 'number')
        throw { message: 'O número de caracteres a ser preenchido não foi definido ou é inválido' };
      if (typeof str !== 'string' && typeof str !== 'number' && str !== undefined)
        throw { message: 'O parâmetro StringPad é inválido' };

      return (parseFloat(nr) < 0 ? '-' : '') + Array(n-String(nr).length+1).join(str+''||' ')+nr;
    } catch (e) {
      console.log(e);
    }
  };

  Number.prototype.padLeft = function (n, str) {
    try {
      console.log('Number', this, typeof this);
      if (!this || (typeof this !== 'string' && typeof this !== 'number'))
        throw { message: 'O valor a ser convertido não foi definido ou é inválido' };
      if (n == undefined || n == null || typeof n !== 'number')
        throw { message: 'O número de caracteres a ser preenchido não foi definido ou é inválido' };
      if (typeof str !== 'string' && typeof str !== 'number' && str !== undefined)
        throw { message: 'O parâmetro StringPad é inválido' };

      return (this < 0 ? '-' : '') + Array(n-String(Math.abs(this)).length+1).join(str+''||' ') + (Math.abs(this));
    } catch (e) {
      console.log(e);
    }
  };
// ------------------- //

//----- String Repeat -----//
  var strRepeat = function(str, times) {
     return (new Array(times + 1)).join(str);
  };

  String.prototype.repeat = function(times) {
    return strRepeat(this, times);
  };
// ------------------- //

//----- Gettype, show type of object -----//
  /*
   * Show the type of object using the prototype.tostring.call instead typeof
   * @obj = Object to get the type
   */
  var gettype = function (obj) {
    return {}.toString.call(obj).split(' ')[1].slice(0, -1).toLowerCase();
  };
// ------------------- //

//----- Gettype, show type of object -----//
  /*
   * Show the type of object using the prototype.tostring.call instead typeof
   * @obj = Object to get the type
   *
   * reference: https://developer.mozilla.org/pt-BR/docs/Web/JavaScript/Reference/Global_Objects/Math/round
   *
   *   Examples
   *   myNamespace.round(1234.5678, 1);  // 1234.6
   *   myNamespace.round(1234.5678, -1); // 1230
   *   myNamespace.round(1234.5678, 2);  // 1234.57
   *   myNamespace.round(1234.5678, -2); // 1200
   *   myNamespace.round(1234, 1);       // 1234
   *   myNamespace.round(1234, -1);      // 1230
   *   myNamespace.round(1234, 2);       // 1234
   *   myNamespace.round(1234, -2);      // 1200
   */
  //var myNamespace = {};

  (function(){
    if (!Math.roundPrecision) {
      //myNamespace.round = function(number, precision) {
      Math.roundPrecision = function(number, precision) {
        var factor = Math.pow(10, precision);
        var tempNumber = number * factor;
        var roundedTempNumber = Math.round(tempNumber);
        return roundedTempNumber / factor;
      };
    }
  })();
// ------------------- //

//----- Array Unique from Array.Prototype -----//
  /*
   * Remove duplicates values from array
   *
   * Reference: https://stackoverflow.com/a/1584377
   */
  Array.prototype.unique = function () {
      var a = this.concat();
      for(var i=0; i<a.length; ++i) {
          for(var j=i+1; j<a.length; ++j) {
              if(a[i] === a[j])
                  a.splice(j--, 1);
          }
      }
      return a;
  };
// ------------------- //

//----- Array Merge from Array Prototype  -----//
  /*
   * Concat two arrays and remove duplicates values from result
   *
   * Reference: https://stackoverflow.com/a/23080662
   */
  Array.prototype.merge = function (array) {
    return this.concat(array.filter(function (item) {
      return this.indexOf(item) < 0;
    }));
  };
// ------------------- //

//----- Default Operations  -----//
  $(function() {

    // Previne o evento padrão do click do elemento e retorna false;
    $(document).on('click keyup keypress', '[preventEvents]', {}, function(e) {
      if (e.type != 'click' && (e.which && e.which == 13)) {
        e.preventDefault();
        return false;
      }
      else if (e.type == 'click') {
        e.preventDefault();
        return false;
      }
    });

    // Eventos dos botões Salvar/Excluir
    //$(document).on('click', 'form [btn-type]', {}, function(e) {
    clickFormSaveDel = function(e) {
      var $this         = $(this),
          $form         = $this.parents('form').eq(0),
          data          = $this.data(),
          btnType       = data.btnType,
          confirmation  = data.confirmation,
          addActionId   = data.addActionId;

      // Se o elemento conter o attributo 'preventEvents' retorna falso e não executa o resto da função!
      if ($this.is('[preventEvents]')) {
        e.preventDefault();
        return false;
      }

      // Button Delete
      if(btnType == 'delete') {
        e.preventDefault();
        if (typeof confirmation == 'undefined' || (typeof confirmation == 'boolean' && confirmation !== false) || (typeof confirmation == 'string' && confirmation.length > 0)) {
          if (!confirm( confirmation || 'Deseja realmente excluir o registro?'))
            return false;
        }

        var hidden_input = $('<input>', {'name':'_method', 'type':'hidden', 'value':'delete'});
        if (addActionId)
          $form[0].action = $form[0].action+'/'+addActionId;
        $form.append(hidden_input).submit();
      }

      // Button Save
      if(btnType == 'put') {
        e.preventDefault();
        if (typeof (confirmation) == 'string' && confirmation.length > 0) {
          if (!confirm( confirmation))
            return false;
        }
        var hidden_input = $('<input>', {'name':'_method', 'type':'hidden', 'value':'put'});
        if (addActionId)
          $form[0].action = $form[0].action+'/'+addActionId;
        $form.append(hidden_input).submit();
      }
    };

    // Vinculação ( .on ) do evento Click com a função específica, feita de forma separada e atribuído separadamente para os botões Save e Delete
    // para permitir Desvinculação ( .off ) do evento click de um dos botões sem desvincular o evento do outro por engano.
    $(document).on('click', '#btn_save', {}, clickFormSaveDel);
    $(document).on('click', '#btn_delete', {}, clickFormSaveDel);

    // Esconde/Exibe elementos no click do elemento com os atributos
    $(document).on('click', '[data-hide], [data-show]', {}, function(e) {
      e.preventDefault();
      var $this = $(this),
          data  = $this.data(),
          hide  = data.hide,
          show  = data.show;

      if (data.hide) {
        $(hide).fadeOut(300);
      }

      if (data.show) {
        $(show).delay(300).fadeIn(300)
      }
    });

    // Retirado do código do Mask Plugin, para verificar se o Chrome tem a funcionalidade do input Event
    var eventSupported = function(eventName) {
      var el = document.createElement('div'), isSupported;
      eventName = 'on' + eventName;
      isSupported = (eventName in el);
      if ( !isSupported ) {
          el.setAttribute(eventName, 'return;');
          isSupported = typeof el[eventName] === 'function';
      }
      el = null;
      return isSupported;
    };

    // Definições globais para o jQuery Mask Plugin
    $.jMaskGlobals = {
      // old versions of chrome dont work great with input event
      useInput: !/Chrome\/[2-4][0-9]|SamsungBrowser/.test(window.navigator.userAgent) && eventSupported('input'),
      byPassKeys: [9, 16, 17, 18, 27, 36, 37, 38, 39, 40, 91],
      translation: {
        '0': {pattern: /\d/},
        '9': {pattern: /\d/, optional: true},
        '#': {pattern: /\d/, recursive: true},
        'A': {pattern: /[a-zA-Z0-9À-ÿ _-]/}, //Char(192) À até Char(255) ÿ // /[a-zA-Z0-9]/
        'S': {pattern: /[a-zA-ZÀ-ÿ _-]/},
        'C': {pattern: /[\x20-\x7E\x80-\xFF]/}, // Toda tabela Asc II
        'T': {pattern: /[\d-]/}
      }
    };

    /*$.jMaskGlobals = {
      addTranslation: {
      'A': {pattern: /[a-zA-Z0-9À-ÿ _-]/}, //Char(192) À até Char(255) ÿ // /[a-zA-Z0-9]/
      'S': {pattern: /[a-zA-ZÀ-ÿ _-]/},
      'C': {pattern: /[\x20-\x7E\x80-\xFF]/} // Toda tabela Asc II
      }
    };*/

    // Mascara padrão definidos pela classe no HTML
    // Define uma mascara simples de digitos, string ou alpha com o max Length desejado
    $(':input[class*=mask_dig_], :input[class*=mask_digtr_], :input[class*=mask_alpha_], :input[class*=mask_str_], :input[class*=mask_ascii_]').each(function(i, e) {
      var $this   = $(this),
          $class  = $this.attr('class'),
          $regex  = (/mask_(dig|digtr|alpha|str|ascii)\_(\d+)/gi).exec($class);
          $digits = parseInt($regex[2] || 0),
          $type   = ($regex[1] == 'dig' ? '0' : ($regex[1] == 'alpha' ? 'A' : ($regex[1] == 'str' ? 'S' : ($regex[1] == 'ascii' ? 'C' : ($regex[1] == 'digtr' ? 'T' : '' )))));
          $this.mask( strRepeat($type, $digits) );
      // console.log($this,strRepeat($type, $digits));
    });

    // Valores monetários e decimais
    $('.money').mask('#.##0,00', MoneyOpts);

    // Valores monetários e decimais
    $('.moneyNegative').mask('#.##0,00', MoneyOptsMinus);

    // Mascara para CPF/CNPJ
    $('.cpf').mask('000.000.000-00');
    $('.cnpj').mask('00.000.000/0000-00');
    $('.cpf_cnpj').mask(CpfCnpjMaskBehavior, CpfCnpjOptions);

    // Mascara para telefone
    $('.telefone').mask(phone_mob_mask, phone_mob_opt);
  });
// ------------------- //
