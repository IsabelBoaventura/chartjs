<?php

echo ' <br> dentro do fonte ...motivo.php <br> src / auxiliares / graficos / graficos_pagina_inicial.php ';
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

    $temp = array();
    $nome = array();

    $resultado = $conn->query( $sql );
    $tc = 0; 
    foreach( $resultado as $row ){
        $nome[$tc]["y"] = $row['Total_Por_Motivo'];
        /**Descobrir a descricao do motivo */
        $sql_desc_motivo = 'SELECT Descricao FROM motivo WHERE Codigo_Motivo="'.$row['Codigo_Motivo'].'"';
        $result = $conn->query( $sql_desc_motivo );
    
        $resultado = $result->fetch(PDO::FETCH_OBJ);
        $resultado->Descricao;
    
        $nome[$tc]["label"] = $resultado->Descricao ;
        
        $tc++;
    }
    $temp = $nome;
   
    //var_dump(  $temp );

    return  $temp; 

}



$resposta =  verifica_motivo();

// var_dump( $resposta );
// echo ' <br> linha 72 ' . json_encode($resposta , JSON_NUMERIC_CHECK); 
// echo ' <br> linha 74 ' . json_encode($resposta , JSON_NUMERIC_CHECK);
 
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grafico Pagina  Inicial </title>
    <link rel="stylesheet" href="../../plugins/bootstrap/css/bootstrap.min.css" />

  
<script>
window.onload = function() {
 
    var chart_motivo = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        theme: "light2",
        title:{
            text: "Motivos de Chamados"
        },
        axisY: {
            title: ""
        },
        data: [{
            type: "bar",
            yValueFormatString: "#,##0.## ",
            dataPoints: <?php echo json_encode($resposta , JSON_NUMERIC_CHECK); ?>
        }]
    });
    chart_motivo.render();


    var chart_plantao = new CanvasJS.Chart("chartContainerPlantao", {
        animationEnabled: true,
        theme: "light2",
        title:{
            text: "Plantão de Chamados"
        },
        axisY: {
            title: ""
        },
        data: [{
            type: "line",
            yValueFormatString: "#,##0.##",
            dataPoints: <?php echo json_encode($resposta , JSON_NUMERIC_CHECK); ?>
        }]
    });
    chart_plantao.render();


    var chart_espera = new CanvasJS.Chart("chartContainerEspera", {
        animationEnabled: true,
        theme: "light2",
        title:{
            text: "Tempo de Espera"
        },
        axisY: {
            title: ""
        },
        data: [{
            type: "spline",
            yValueFormatString: "#,##0.##",
            dataPoints: <?php echo json_encode($resposta , JSON_NUMERIC_CHECK); ?>
        }]
    });
    chart_espera.render();


    var chart_horario = new CanvasJS.Chart("chartContainerHorario", {
        animationEnabled: true,
        theme: "light2",
        title:{
            text: "Horário"
        },
        axisY: {
            title: ""
        },
        data: [{
            type: "pie",
            yValueFormatString: "#,##0.## ",
            dataPoints: <?php echo json_encode($resposta , JSON_NUMERIC_CHECK); ?>
        }]
    });
    chart_horario.render();
 
  }
</script>





</head>
<body>
  

<!--  -->
<!--    **********************  Informacoes da pagina Original do Uchoa **************************   --> 
<!-- gráficos -->    

<h1>Graficos com canvas js </h1>
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
                    <div id="chartContainer" style="height: 370px; width: 100%; border-style: 1px solid red; "></div> 
                </div>
            </div>  <!-- /.box-body -->
        </div>  <!-- /.box -->

        <!-- DONUT CHART -->
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Quantidade de Atendimentos</h3>
            </div>
            <div class="box-body">
                <!-- <canvas id="pieChart" style="height:250px"></canvas> -->
                <div id="chartContainerPlantao" style="height: 370px; width: 100%; "></div> 
            </div>  <!-- /.box-body -->
        </div>  <!-- /.box -->
    </div>  <!-- /.col (LEFT) -->
        
    
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
                <div id="chartContainerEspera" style="height: 370px; width: 100%;  "></div> 
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
                <div id="chartContainerHorario" style="height: 370px; width: 100%; "></div>
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






<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
  

</body>
</html>
