<?php

include( '../conexao.php');

echo ' <br> dentro do fonte ...semana.php <br>';
//$dias = dias_do_intervalo( '20/02/2022' ,  '26/02/2022');
$dia_inicial  = '2022-01-03';

try{
    $situacao = 'A';
    $temp = array() ;
    $totalGeral = array();  
       
    $qtd_atendente = 0;
    $qtd_finalizados = 0;
    $total_dia = 0; 

    $consulta = " SELECT * FROM chamado ".
            " WHERE DH_Chamado >= '".$dia_inicial." 00:00:01'  ". 
            " AND DH_Chamado <= '".$dia_inicial." 23:59:59' ";                    
            // $resultado = consulta_sql($consulta);          
            // $total_dia  = consulta_num_registros( $resultado );
    $resultado = $conn->query( $consulta );

    foreach( $resultado as $row ){
        //var_dump( $row );
        $total_dia++;
        $chave = $row['Usuario_Atendimento'];                                  
        if( !isset(  $temp[$chave] ) ){
            $qtd_atendente++;
            if( $chave ==  $row['Usuario_Atendimento'] ){                 
                if(  $row['Finalizado'] == 'S'){
                    $qtd_finalizados++;
                }
                $temp[$chave]['Atendidos'] =  $qtd_atendente ;
                $data = explode( " " , $row['DH_Chamado'] )  ;
                $temp[$chave]['Dia'] = $data[0] ;
                $temp[$chave]['Atendente'] = $chave ;
                $temp[$chave]['Atendidos'] =  $qtd_atendente ;
                $temp[$chave]['Finalizados'] =  $qtd_finalizados ;
            }
            $temp[$chave]['Atendidos'] =  $qtd_atendente ;          
        }          
    }
    
    $totalGeral['Totais'] = $temp;

    echo '<br>';
    print_r("Encontradas   { $total_dia } no total do dia ".  $dia_inicial );
    echo '<br>';

    $totalGeral['Dia']  =  $dia_inicial  ;
    $totalGeral['TotalDia'] = $total_dia ;

           

        echo ' <br> linha 167 resultados: <br> ';
        var_dump( $temp );
        var_dump( $totalGeral );
} catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
}



?>