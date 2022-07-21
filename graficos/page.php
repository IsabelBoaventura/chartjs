<?php

$origem = origem();
//require ($origem.'includes/graficos/libs/conexao.php' );



function chamadosTotais(){
    /** Descobrir o Total de chamados 
     * neste momento usando os ultimos 30 dias como base
     */
    // $origem = origem();
    // require ($origem.'includes/graficos/libs/conexao.php' );

    $hoje = date('Y-m-d');
    $trintaAntes = date('Y-m-d', strtotime('-30 days'));

    $sql_todos  = "SELECT * FROM chamado
                WHERE DH_Chamado >= '".$trintaAntes." 00:00:01' 
                AND DH_Chamado <= '".$hoje." 23:59:59' ";
    

    /**
     * Funcoes em PDOO - Reativar quando estiver no MVC
     * $resultados = $conn->query( $sql_todos . ";" );
     * $totalPorPeriodo = $resultados->rowCount();     * 
     *  */ 

    $conTodos = $sql_todos;
    $resTodos = consulta_sql( $conTodos);
    $regTodos = consulta_ler_objeto( $resTodos );
    $totalPorPeriodo = consulta_num_registros( $resTodos );
    consulta_limpa( $resTodos );

    $dataApresentaDe = date('d/m/Y', strtotime('-30 days'));
    $dataApresentaAte = date('d/m/Y'); 
    $mensagem = 'De: ' . $dataApresentaDe . ' ate '. $dataApresentaAte ;

    $retorno =  $mensagem .";".$totalPorPeriodo . ";" ;    
    
    return  $retorno  ;
}


  
function chamadosFinalizados(){
    /**Descobrir o total de chamados finalizados 
     * Neste momento sendo usado os ultimos 30 dias 
    */
    $hoje = date('Y-m-d');
    $trintaAntes = date('Y-m-d', strtotime('-30 days'));

    $sql_todos  = "SELECT * FROM chamado
                WHERE DH_Chamado >= '".$trintaAntes." 00:00:01' 
                AND DH_Chamado <= '".$hoje." 23:59:59' ";
    $sql_finalizados = " AND Finalizado = 'S' ";

    $conFinalizados = $sql_todos . $sql_finalizados .";" ;
    $resFinalizados = consulta_sql($conFinalizados);
    $regFinalizados = consulta_ler_objeto($resFinalizados);
    $totalPorPeriodoFinalizados = consulta_num_registros( $resFinalizados );
    consulta_limpa($resFinalizados);    

    /*$resultados = $conn->query( $sql_todos . $sql_finalizados . ";" );
    $totalPorPeriodoFinalizados = $resultados->rowCount();*/    
    return $totalPorPeriodoFinalizados  ;

}


function tipoDeChamado(){
    /** Descobrir o tipo de Chamado  - Descricao do Motivo */
    
    $respostasTotais =  chamadosTotais();
    //echo '<br> linha 237 '. $respostasTotais ;
    $respostas = explode(";", $respostasTotais);
    $totalTrintaDias = $respostas[1];
    

    $hoje = date('Y-m-d');
    $trintaAntes = date('Y-m-d', strtotime('-30 days'));
    $sql_todos  = "SELECT * FROM chamado
                WHERE DH_Chamado >= '".$trintaAntes." 00:00:01' 
                AND DH_Chamado <= '".$hoje." 23:59:59'  ";

    $sql_primeiro_chamado = "SELECT * FROM chamado
                              WHERE DH_Chamado >= '".$trintaAntes." 00:00:01' 
                              ORDER BY DH_Chamado 
                              LIMIT 1";
                              
    $conPimeiro =  $sql_primeiro_chamado; 
    $resPrimeiro = consulta_sql(  $conPimeiro );
    $regPrimeiro = consulta_ler_objeto($resPrimeiro );
    consulta_limpa($resPrimeiro );
    $primeiro = $regPrimeiro->Id;     
    /*
    PDO: $resultados = $conn->query( $sql_primeiro_chamado . ";" );
    $temp = array();  
    while($row = $resultados->fetch(PDO::FETCH_ASSOC)) {
        // Por enquanto iremos pegar apenas o primeiro n�mero, sem salvar nada em tabelas tempor�rias
        //pois agora � s� um percentual dos que mais abrem chamados
        $chave = $row['Id'];
        $temp[$chave]['ID'] = $row['Id'];
        $temp[$chave]['Data'] = substr( $row['DH_Chamado'], 0, 10);      

        $primeiro = $row['Id'];         
    } 
    */
 

    /**
     * 
     * Descobriu quem  � o primeiro agora vamos fazer pesquisar por motivos 
     * SQL para pegar as informa��es
     * Totais por motivo 
     * 
     * SELECT `Codigo_Motivo`, COUNT(*)
     * FROM chamado_motivo
     * WHERE `Id_Chamado` >= 24828
     * and `Situacao` = 'A'
     * GROUP BY `Codigo_Motivo`
     * 
     */

    $sql_motivo = "SELECT Codigo_Motivo, COUNT(*) as Total_Motivo
                  FROM chamado_motivo
                  WHERE Id_Chamado >= ".$primeiro."
                  AND Situacao = 'A'
                  GROUP BY Codigo_Motivo ";
    $conMotivo  =  $sql_motivo ;
    $resMotivo = consulta_sql($conMotivo );
    $regMotivo = consulta_ler_objeto( $resMotivo );
    $total = consulta_num_registros( $resMotivo  );
    consulta_limpa( $resMotivo );
    $porcentagem =  ($regMotivo->Total_Motivo  /   $totalTrintaDias  ) * 100 ;
    $porcentagem =  round( $porcentagem ); //Resultado: 3
   

    /**Apresentacao do resultado  
     *  $resultadoSegundaSQL = $conn->query(  $sql_motivo  . ";" );
    while($rowSegundaSQL = $resultadoSegundaSQL->fetch(PDO::FETCH_ASSOC)) {
      // Por enquanto iremos pegar apenas o primeiro n�mero, sem salvar nada em tabelas tempor�rias
       //pois agora � s� um percentual dos que mais abrem chamados
        
        $chave = $row['Id'];
        $temp[$chave]['ID'] = $row['Id'];
        $temp[$chave]['Data'] = substr( $row['DH_Chamado'], 0, 10); 
     
      

        $chave = $rowSegundaSQL['Codigo_Motivo'];
        $temp[$chave]['Motivo'] = $chave;
        $temp[$chave]['Total'] = $rowSegundaSQL['Total_Motivo'];
    } // fecha while

    */

   



    // var_dump( $temp );

    $echo  = ' Motivo de maior chamado '. $regMotivo->Codigo_Motivo;
    $echo  .= ' <br>Com o Total de '. $regMotivo->Total_Motivo . ' chamados ';
    $echo  .= ' <br> '. $porcentagem .'%';
    $motivo =  $regMotivo->Codigo_Motivo .";". $regMotivo->Total_Motivo . ";" .  $porcentagem ."; Todos os echos:" . $echo ;

    //var_dump ( $respostaTotais  );
    $retornaDaFuncao = ' mandando resposta ';

    return $porcentagem .";". $motivo ;
}



  
function motivoTipoDeChamado(){
    /** Descobrir a Descri��o do chamado  */

    $respostaDaFuncao = tipoDeChamado();  
    
    //var_dump( $respostaDaFuncao   );
    $respostas = explode(";",  $respostaDaFuncao);
    $motivoChamado  = $respostas[1];

    $sql_TipoMotivo  = "SELECT Descricao FROM motivo
                WHERE Codigo_Motivo =".$motivoChamado."  LIMIT 1 ";

    $conTipoMotivo =  $sql_TipoMotivo ; 
    $resTipoMotivo = consulta_sql( $conTipoMotivo );
    $regTipoMotivo = consulta_ler_linha( $resTipoMotivo );
    $total = consulta_num_registros( $resTipoMotivo  );
    consulta_limpa( $resTipoMotivo );  
    $respostaMotivo  = $regTipoMotivo[0];    

   
    /**
      * $resultados = $conn->query( $sql_motivo . ";" );
      * $temp = array();
        while($row = $resultados->fetch(PDO::FETCH_ASSOC)) {
        //Por enquanto iremos pegar apenas o primeiro n�mero, sem salvar nada em tabelas tempor�rias
       * pois agora � s� um percentual dos que mais abrem chamados
       * 
       * $chave = $row['Id'];
       * $temp[$chave]['ID'] = $row['Id'];
       * $temp[$chave]['Data'] = substr( $row['DH_Chamado'], 0, 10); 
       * 
      $respostaMotivo  = $row['Descricao'];
         
    } // fecha while
      * */

 


    //var_dump ( $respostaTotais  );
    $retornaDaFuncao = ' descricao_do_tipo_do_chamado ';

    return $respostaMotivo   ;
}


function clienteDosChamados(){
   


    $hoje = date('Y-m-d');
    $trintaAntes = date('Y-m-d', strtotime('-30 days'));

    $consulta  = "SELECT  Pessoa, Nome , COUNT(*) as Total_Por_Cliente  FROM chamado
                WHERE DH_Chamado >= '".$trintaAntes." 00:00:01' 
                AND DH_Chamado <= '".$hoje." 23:59:59' 
                GROUP BY  Pessoa ORDER BY Total_Por_Cliente  DESC, Pessoa ";

            
    $resultado = consulta_sql( $consulta);
    $registro = consulta_ler_objeto($resultado);
               
    consulta_limpa( $resultado );  


    $respostaMotivo = $registro->Nome . ' ;  '. $registro->Total_Por_Cliente ;

    return $respostaMotivo   ;

}




    /**
     * LEMBRETE 
     * 
     * 
     *  $conClienteDados = 'SELECT Identificador, Any_Desk, Usuario, Nome_Computador, OS, '.
                               ' Processador_Arquitetura, Processador_Identificador, '.
                               ' IP, Memoria_Tamanho, Disco_C_Tamanho '.
                               ' FROM cliente_estacao '.
                               ' WHERE Id = "'.$registro->Id_Cliente_Estacao.'"';
            $resClienteDados = consulta_sql($conClienteDados);
            $regClienteDados = consulta_ler_objeto($resClienteDados);
            $total = consulta_num_registros( $resClienteDados );
            consulta_limpa($resClienteDados);
     */

    

   







  







?>