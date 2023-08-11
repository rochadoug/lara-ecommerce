/* globals Chart:false, feather:false */

$(function() {

  // Dashboard
    //(function () {
    //'use strict'

    var addChart = function(chartName, arrayValor, arrayQtd, arrayLabels) {
      try {
        // Graphs
        var ctx = $('#chart'+chartName);

        if (ctx.length == 0)
          return;

        // eslint-disable-next-line no-unused-vars
        var myChart = new Chart(ctx, {
          type: 'line',
          data: {
            labels: arrayLabels /*[
              'Sunday',
              'Monday',
              'Tuesday',
              'Wednesday',
              'Thursday',
              'Friday',
              'Saturday'
            ]*/,
            datasets: [{
              label: 'Valor',
              data: arrayValor /* [
                15339,
                21345,
                18483,
                24003,
                23489,
                24092,
                12034
              ] */,
              lineTension: 0,
              backgroundColor: 'transparent',
              borderColor: '#007bff',
              borderWidth: 4,
              pointBackgroundColor: '#007bff'
            }, {
              label: 'Quantidade',
              data: arrayQtd,
              lineTension: 0,
              backgroundColor: 'transparent',
              borderColor: '#00ff7b',
              borderWidth: 4,
              pointBackgroundColor: '#00ff7b'
            }]
          },
          options: {
            scales: {
              yAxes: [{
                ticks: {
                  suggestedMin: 0 // minimum will be 0, unless there is a lower value.
                  // OR //
                  //beginAtZero: true   // minimum value will be 0.
                  //beginAtZero: false
                }
              }]
            },
            legend: {
              display: false
            },
            tooltips: {
              //enabled: true,
              //mode: 'single',
              callbacks: {
                title: function(tooltipItem, data) {
                  return data['labels'][tooltipItem[0]['index']];
                },
                label: function(tooltipItem, data) {
                  var dsidx = tooltipItem['datasetIndex'],
                      idx   = tooltipItem['index'],
                      valor = data['datasets'][dsidx]['data'][idx];
                  if (dsidx == 0) {
                    return ' Valor: R$ '+strRepeat(' ', 12-valor.toString().length)+parseFloat(valor).formatMoney(2, ',', '.');
                  } else {
                    return ' Quantidade: '+valor;
                  }
                },
                afterLabel: function(tooltipItem, data) {
                  //var dataset = data['datasets'][0];
                  //var percent = Math.round((dataset['data'][tooltipItem['index']] / dataset["_meta"][0]['total']) * 100)
                  //return '(' + percent + '%)';
                }
              }
            }
          }
        });

        ctx.toggleClass('d-none');
      } catch (e) {
        console.log(e);
      }
    };

    var addChart2 = function(chartName, arrayData, arrayLabels) {
      try {
        // Graphs
        var ctx = $('#chart'+chartName),
            colorsData = ['#0000ff', '#00ff00', '#ff0000', '#00ffff', '#ffff00', '#ff00ff', '#bfff00', '#ff4000', '#00ff40', '#00bfff', '#4000ff', '#ff00bf'],
            dataObj,
            dataArray = [],
            colors = [];

        if (ctx.length == 0)
          return;

        for (var i=0; i<arrayData.length; i++) {
          dataObj = {
                      label: arrayData[i].label,
                      data: arrayData[i].data,
                      lineTension: 0,
                      backgroundColor: 'transparent',
                      borderColor: colorsData[(i%arrayData.length)],
                      borderWidth: 2,
                      pointBackgroundColor: colorsData[(i%arrayData.length)]
                    };
          dataArray[i] = dataObj;
        }

        // eslint-disable-next-line no-unused-vars
        var myChart = new Chart(ctx, {
          type: 'line',
          data: {
            labels: arrayLabels,
            datasets: dataArray
          },
          options: {
            scales: {
              yAxes: [{
                ticks: {
                  suggestedMin: 0 // minimum will be 0, unless there is a lower value.
                  // OR //
                  //beginAtZero: true   // minimum value will be 0.
                  //beginAtZero: false
                }
              }]
            },
            legend: {
              display: false
            },
            tooltips: {
              //enabled: true,
              //mode: 'single',
              callbacks: {
                title: function(tooltipItem, data) {
                  return data['datasets'][tooltipItem[0].datasetIndex].label+' ('+data['labels'][tooltipItem[0]['index']]+')';
                },
                label: function(tooltipItem, data) {
                  var dsidx = tooltipItem['datasetIndex'],
                      idx   = tooltipItem['index'],
                      valor = data['datasets'][dsidx]['data'][idx];
                  //if (dsidx == 0) {
                  if (chartName == 'Valores') {
                    return ' Valor: R$ '+strRepeat(' ', 12-valor.toString().length)+parseFloat(valor).formatMoney(2, ',', '.');
                  } else {
                    return ' Quantidade: '+valor;
                  }
                },
                afterLabel: function(tooltipItem, data) {
                  //var dataset = data['datasets'][0];
                  //var percent = Math.round((dataset['data'][tooltipItem['index']] / dataset["_meta"][0]['total']) * 100)
                  //return '(' + percent + '%)';
                }
              }
            }
          }
        });

        ctx.toggleClass('d-none');
      } catch (e) {
        console.log(e);
      }
    };

    try {
      var arrayLabels = $('#ArrayLabels').val(),
          inputs      = ['Aberto', 'Pago', 'Cancelado'],
          $inps       = $( '#'+inputs.join(', #')),
          inpsJson    = [],
          charts      = ['Valores', 'Quantidades'],
          chartsData;

      inpsJson = $.makeArray( $inps.map(function(idx, elm) {
        var json = JSON.parse(this.value);
        return {label: this.id, sum: json.sum, count: json.count };
      }) );

      arrayLabels = JSON.parse(arrayLabels);

      // Loop in Charts
      for (var x=0; x<charts.length; x++) {

        chartsData = [];

        // Loop in Inputs
        for (var idx in inpsJson) {

          var value = inpsJson[idx];

          if (gettype(value) == 'object' && $(value).length > 0 ) {
            if (charts[x] == 'Valores')
              chartsData.push({ label: value.label, data: value.sum})
            else if (charts[x] == 'Quantidades')
              chartsData.push({ label: value.label, data: value.count })
          }
        }

        console.log( charts[x], chartsData, arrayLabels );
        addChart2(charts[x], chartsData, arrayLabels );
      }
    } catch (e) {
      console.log(e);
    }

});//());


//0     #ff0000   rgb(255, 0, 0)      hsl(0, 100%, 50%)
//15    #ff4000   rgb(255, 64, 0)     hsl(15, 100%, 50%)
//30    #ff8000   rgb(255, 128, 0)    hsl(30, 100%, 50%)
//45    #ffbf00   rgb(255, 191, 0)    hsl(45, 100%, 50%)
//60    #ffff00   rgb(255, 255, 0)    hsl(60, 100%, 50%)
//75    #bfff00   rgb(191, 255, 0)    hsl(75, 100%, 50%)
//90    #80ff00   rgb(128, 255, 0)    hsl(90, 100%, 50%)
//105   #40ff00   rgb(64, 255, 0)     hsl(105, 100%, 50%)
//120   #00ff00   rgb(0, 255, 0)      hsl(120, 100%, 50%)
//135   #00ff40   rgb(0, 255, 64)     hsl(135, 100%, 50%)
//150   #00ff80   rgb(0, 255, 128)    hsl(150, 100%, 50%)
//165   #00ffbf   rgb(0, 255, 191)    hsl(165, 100%, 50%)
//180   #00ffff   rgb(0, 255, 255)    hsl(180, 100%, 50%)
//195   #00bfff   rgb(0, 191, 255)    hsl(195, 100%, 50%)
//210   #0080ff   rgb(0, 128, 255)    hsl(210, 100%, 50%)
//225   #0040ff   rgb(0, 64, 255)     hsl(225, 100%, 50%)
//240   #0000ff   rgb(0, 0, 255)      hsl(240, 100%, 50%)
//255   #4000ff   rgb(64, 0, 255)     hsl(255, 100%, 50%)
//270   #8000ff   rgb(128, 0, 255)    hsl(270, 100%, 50%)
//285   #bf00ff   rgb(191, 0, 255)    hsl(285, 100%, 50%)
//300   #ff00ff   rgb(255, 0, 255)    hsl(300, 100%, 50%)
//315   #ff00bf   rgb(255, 0, 191)    hsl(315, 100%, 50%)
//330   #ff0080   rgb(255, 0, 128)    hsl(330, 100%, 50%)
//345   #ff0040   rgb(255, 0, 64)     hsl(345, 100%, 50%)
//360   #ff0000   rgb(255, 0, 0)      hsl(0, 100%, 50%)

//#ff0000
//#ffff00
//#00ff00
//#00ffff
//#0000ff
//#ff00ff
//#ff4000
//#bfff00
//#00ff40
//#00bfff
//#4000ff
//#ff00bf