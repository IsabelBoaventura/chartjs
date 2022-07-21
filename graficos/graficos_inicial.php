<?php

echo ' <br> dentro do fonte ...motivo.php <br> src / auxiliares / graficos / graficos_inicial.php ';
function verifica_motivo(){
    include('../conexao.php');

    $hoje = date('Y-m-d');
    $trintaAntes = date('Y-m-d', strtotime('-30 days'));

    $sql_todos  = "SELECT  Codigo_Motivo, COUNT(*) as Total_Por_Motivo FROM chamado
                    WHERE DH_Chamado >= '".$trintaAntes." 00:00:01' 
                    AND DH_Chamado <= '".$hoje." 23:59:59' ";
    $sql_finalizado = " AND Finalizado='S' ";
    $sql_group = "  GROUP BY Codigo_Motivo   ORDER BY Codigo_Motivo ";

    $sql =  $sql_todos . $sql_finalizado .  $sql_group . ";"; 


    //
    echo $sql ; 


    $temp = array();
     

    $resultado = $conn->query( $sql );
    $tc = 0; 
    foreach( $resultado as $row ){

   
            $nome[$tc]["y"] = $row['Total_Por_Motivo'];
            $nome[$tc]["label"] =  $row['Codigo_Motivo'];

            $tc++;
      
    }
    $temp = $nome;
   
    //
    var_dump(  $temp );

    return  $temp; 

}


$resposta =  verifica_motivo();

// var_dump( $resposta );
//
 echo json_encode($resposta , JSON_NUMERIC_CHECK); 
 
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grafico Inicial </title>
    <link rel="stylesheet" href="../../plugins/bootstrap/css/bootstrap.min.css" />




</head>
<body>
<!--  -->
    <!--    **********************  Informacoes da pagina Original do Uchoa **************************   --> 
     <!-- gráficos -->    
     <div class="row">
        <div class="col-md-6">
          <!-- AREA CHART -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Motivo do Chamado</h3>
            </div>
            <div class="box-body">
              <div class="chart">
                <!-- <canvas id="areaChart" style="height:250px"></canvas> -->
                <div id="chartContainer_echart" style="height: 370px; width: 100%; ">  </div> 
                <div id="teste" style="height: 370px; width: 100%; "> <p> teste </p> </div> 
                <p>teste 4</p>
                <div id="main4" style="width: 600px;height:400px;border:3px solid black;"></div>

              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- DONUT CHART -->
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Quantidade de Atendimentos</h3>
            </div>
            <div class="box-body">
              <!-- <canvas id="pieChart" style="height:250px"></canvas> -->
              <div id="chartContainerPlantao" style="height: 370px; width: 100%;border:2px solid red; "></div> 
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        </div>
        <!-- /.col (LEFT) -->
        <div class="col-md-6">
          <!-- LINE CHART -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Horario que mais teve chamado em espera</h3>

              <div class="box-tools pull-right">

              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <!-- <canvas id="lineChart" style="height:250px"></canvas> -->
                <div id="chartContainerEspera" style="height: 370px; width: 100%;border:2px solid blue; "></div> 
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- BAR CHART -->
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Plantao</h3>

              <div class="box-tools pull-right">

              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <!-- <canvas id="barChart" style="height:250px"></canvas>, -->
                <div id="chartContainerHorario" style="height: 370px; width: 100%;border: 2px solid green; "></div>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col (RIGHT) -->
      </div>
      <!-- /.row -->


      <!--    **********************  Informacoes da pagina Original do Uchoa **************************   -->
      
      
      <script src="https://cdn.jsdelivr.net/npm/echarts@5.3.3/dist/echarts.min.js" > </script>

      <script>


      
    var chart_motivo_echart = echarts.init( document.getElementById("chartContainer_echart"), null,  {
        option = {
            dataset: {
                source: [
                        ['score', 'amount', 'product'],
                        [89.3, 58212, 'Matcha Latte'],
                        [57.1, 78254, 'Milk Tea'],
                        [74.4, 41032, 'Cheese Cocoa'],
                        [50.1, 12755, 'Cheese Brownie'],
                        [89.7, 20145, 'Matcha Cocoa'],
                        [68.1, 79146, 'Tea'],
                        [19.6, 91852, 'Orange Juice'],
                        [10.6, 101852, 'Lemon Juice'],
                        [32.7, 20112, 'Walnut Brownie']
                ]
            },
            grid: { containLabel: true },
            xAxis: { name: 'amount' },
            yAxis: { type: 'category' },
            visualMap: {
                orient: 'horizontal',
                left: 'center',
                min: 10,
                max: 100,
                text: ['High Score', 'Low Score'],
                // Map the score column to color
                dimension: 0,
                inRange: {
                    color: ['#65B581', '#FFCE34', '#FD665F']
                }
            },
            series: [
                {
                    type: 'bar',
                    encode: {
                        // Map the "amount" column to X axis.
                        x: 'amount',
                        // Map the "product" column to Y axis
                        y: 'product'
                    }
                }
            ]
        }


    
    });

    /*
window.onload = function() {
 
    var chart_motivo = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        theme: "light2",
        title:{
            text: "Motivos de Chamados( Ultimos 30 dias)"
        },
        axisY: {
            title: ""
        },
        data: [{
            type: "bar",
            yValueFormatString: "#,##0.## tonnes",
            dataPoints: <?php //echo json_encode($resposta , JSON_NUMERIC_CHECK); ?>
        }]
    });
    chart_motivo.render();


    var chart_plantao = new CanvasJS.Chart("chartContainerPlantao", {
        animationEnabled: true,
        theme: "light2",
        title:{
            text: "Plantao de Chamados( Ultimos 30 dias)"
        },
        axisY: {
            title: ""
        },
        data: [{
            type: "line",
            yValueFormatString: "#,##0.## tonnes",
            dataPoints: <?php //echo json_encode($resposta , JSON_NUMERIC_CHECK); ?>
        }]
    });
    chart_plantao.render();


    var chart_espera = new CanvasJS.Chart("chartContainerEspera", {
        animationEnabled: true,
        theme: "light2",
        title:{
            text: "Tempo de Espera ( Ultimos 30 dias)"
        },
        axisY: {
            title: ""
        },
        data: [{
            type: "spline",
            yValueFormatString: "#,##0.## tonnes",
            dataPoints: <?php //echo json_encode($resposta , JSON_NUMERIC_CHECK); ?>
        }]
    });
    chart_espera.render();


    var chart_horario = new CanvasJS.Chart("chartContainerHorario", {
        animationEnabled: true,
        theme: "light2",
        title:{
            text: "Horario ( Ultimos 30 dias)"
        },
        axisY: {
            title: ""
        },
        data: [{
            type: "pie",
            yValueFormatString: "#,##0.## tonnes",
            dataPoints: <?php // echo json_encode($resposta , JSON_NUMERIC_CHECK); ?>
        }]
    });
    chart_horario.render();
 
}*/



const colors = ['#5470C6', '#EE6666'];
      //este faz a comparação de dois anos 
      var myChart4= echarts.init(document.getElementById('main4'));
      var option4 = {

        color: colors,
        tooltip: {
                trigger: 'none',
                axisPointer: {
                type: 'cross'
                }
        },
  legend: {},
  grid: {
    top: 70,
    bottom: 50
  },
  xAxis: [
    {
      type: 'category',
      axisTick: {
        alignWithLabel: true
      },
      axisLine: {
        onZero: false,
        lineStyle: {
          color: colors[1]
        }
      },
      axisPointer: {
        label: {
          formatter: function (params) {
            return (
              'Precipitation  ' +
              params.value +
              (params.seriesData.length ? '：' + params.seriesData[0].data : '')
            );
          }
        }
      },
      // prettier-ignore
      data: ['2016-1', '2016-2', '2016-3', '2016-4', '2016-5', '2016-6', '2016-7', '2016-8', '2016-9', '2016-10', '2016-11', '2016-12']
    },
    {
      type: 'category',
      axisTick: {
        alignWithLabel: true
      },
      axisLine: {
        onZero: false,
        lineStyle: {
          color: colors[0]
        }
      },
      axisPointer: {
        label: {
          formatter: function (params) {
            return (
              'Precipitation  ' +
              params.value +
              (params.seriesData.length ? '：' + params.seriesData[0].data : '')
            );
          }
        }
      },
      // prettier-ignore
      data: ['2015-1', '2015-2', '2015-3', '2015-4', '2015-5', '2015-6', '2015-7', '2015-8', '2015-9', '2015-10', '2015-11', '2015-12']
    }
  ],
                yAxis: [
                    {
                    type: 'value'
                    }
                ],
                series: [
                    {
                    name: 'Precipitation(2015)',
                    type: 'line',
                    xAxisIndex: 1,
                    smooth: true,
                    emphasis: {
                        focus: 'series'
                    },
                    data: [
                        2.6, 5.9, 9.0, 26.4, 28.7, 70.7, 175.6, 182.2, 48.7, 18.8, 6.0, 2.3
                    ]
                    },
                    {
                        name: 'Precipitation(2016)',
                        type: 'line',
                        smooth: true,
                        emphasis: {
                            focus: 'series'
                        },
                        data: [
                            3.9, 5.9, 11.1, 18.7, 48.3, 69.2, 231.6, 46.6, 55.4, 18.4, 10.3, 0.7
                        ]
                    }
                ]

      };
      myChart4.setOption(option4);




</script>








<!-- -->
    <!-- <script src="../../js/echarts-5.3.3/dist/echarts.js"></script>  -->
    <!-- <script src="https://www.jsdelivr.com/package/npm/echarts/echarts.js" > </script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/echarts@5.3.3/dist/echarts.min.js" > </script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/echarts@5.3.3/dist/echarts.min.js" > </script> -->
    <!-- At https://www.jsdelivr.com/package/npm/echarts select dist/echarts.js, click and save it as echarts.js file. -->
  