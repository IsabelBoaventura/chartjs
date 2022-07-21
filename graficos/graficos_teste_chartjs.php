<?php


echo ' <br> dentro do fonte ... src / auxiliares / graficos / graficos_chartjs.php ';
function verifica_motivo(){

    include('../conexao.php');

    $hoje = date('Y-m-d');
    $trintaAntes = date('Y-m-d', strtotime('-30 days'));

    $sql_todos  = "SELECT  Codigo_Motivo, COUNT(*) as Total_Por_Motivo FROM chamado
                    WHERE DH_Chamado >= '".$trintaAntes." 00:00:01' 
                    AND DH_Chamado <= '".$hoje." 23:59:59' ";

    $sql_finalizado = " AND Finalizado='S' ";
    $sql_group = "  GROUP BY Codigo_Motivo   ORDER BY Total_Por_Motivo DESC ";

    $sql =  $sql_todos . $sql_finalizado .  $sql_group . ";"; 
    //  
    echo 'sql motivos:<br>' . $sql ;


    $nome = array();
    $chart = array();

    // $resultado = consulta_sql( $sql );          
    // $total_chamado  = consulta_num_registros( $resultado );
    //echo '<br> totais de linhas ' . $total_chamado ;

    /**Se aumentar os motivos de chamados aumentar as cores  */    
    $cores = array(
        'rgba(255, 99, 132, 1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)',
        'rgba(255, 99, 132, 1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
    );

     

    $resultado = $conn->query( $sql );
    $tc = 0; 
    foreach( $resultado as $row ){
        //echo ' <br>  linha 52::: '. $row['Total_Por_Motivo'];

   

    
            
            $nome["data"][] = $row['Total_Por_Motivo'];

            /**Descobrir a descricao do motivo */
            //echo ' <br> linha 67::: ' ;
            $sql_desc_motivo = 'SELECT Descricao FROM motivo WHERE Codigo_Motivo="'.$row['Codigo_Motivo'].'"';


            $result = $conn->query( $sql_desc_motivo );
    
            $resultado = $result->fetch(PDO::FETCH_OBJ);
           // echo ' linha 73:: '.  $resultado->Descricao;
        
            $nome["label"][] = $resultado->Descricao ;
           
            
           
            $nome["backgroundColor"][] = $cores[$tc];


            $chart['labels'] = $nome["label"];
            $chart['data'] = $nome["data"];
            $chart['backgroundColor'] = $nome["backgroundColor"];

            $tc++;
           
      
    }

    //
     var_dump( $chart );

    // print_r( $chart );
    return  $chart ; 
}



 
?>

<!-- 
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grafico Charts  </title> -->
    <link rel="stylesheet" href="../../plugins/bootstrap/css/bootstrap.min.css" />









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
                    <canvas id="my" style="height:400px;width: 400px;"></canvas>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
          <!-- /.box -->

       


    </div>
    <!-- /.col (RIGHT) -->



  


</div>
<!-- /.row -->
<!-- E:\Wamp\www\src\js\chartjs -->

<!-- https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js

https://cdnjs.com/libraries/Chart.js



E:/wamp/www/src/js/chartjs/chart.min.js -->
      
<!-- <script src="https://cdnjs.com/libraries/Chart.js"></script>  -->

<script src="js/chart.min.js"></script>


<script>
const ctx1 = document.getElementById('my');
const myChart1 = new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
        datasets: [{
            label: '# of Votes',
            data: [12, 19, 3, 5, 2, 3],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});







   



</script>



  