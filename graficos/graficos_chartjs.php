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
    //  echo 'sql motivos:<br>' . $sql ;


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

    // var_dump( $chart );

    // print_r( $chart );
    return  $chart ; 
}


// /**Verificação de chamados por Tecnicos  */
function verifica_tecnico(){
    include('../conexao.php');

    $hoje = date('Y-m-d');
    $trintaAntes = date('Y-m-d', strtotime('-30 days'));

    $sql_todos  = "SELECT  Usuario_Atendimento	, COUNT(*) as Total_Por_Tecnico FROM chamado
                    WHERE DH_Chamado >= '".$trintaAntes." 00:00:01' 
                    AND DH_Chamado <= '".$hoje." 23:59:59' ";
   // $sql_finalizado = " AND Finalizado='S' ";
    $sql_group = "  GROUP BY Usuario_Atendimento ORDER BY Total_Por_Tecnico desc	 ";
    $sql =  $sql_todos .   $sql_group . ";"; 
    //echo '<br>' . $sql ;

    $nome = array();
    $chart = array();
  
    $cores = array(
        'rgba(255, 99, 132, 1)',                'rgba(54, 162, 235, 1)',                'rgba(255, 206, 86, 1)',                'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',               'rgba(255, 159, 64, 1)',                'rgba(255, 99, 132, 1)',                'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',                'rgba(75, 192, 192, 1)',                'rgba(153, 102, 255, 1)',               'rgba(255, 159, 64, 1)'
    );

    $tc = 0;
    $consulta  = $conn->query( $sql );
    foreach( $consulta  as $row  ){
        if( $row['Usuario_Atendimento'] > 0){
            $nome["data"][] = $row['Total_Por_Tecnico'];

                /**Descobrir a descricao do motivo */
                $sql_desc_tecnico = 'SELECT Nome FROM usuario WHERE Id_Usuario="'.$row['Usuario_Atendimento'].'"';

                $result = $conn->query( $sql_desc_tecnico  );    
                $resultado = $result->fetch(PDO::FETCH_OBJ);
                
                $nome["label"][] =  $resultado->Nome ;
                $nome["backgroundColor"][] = $cores[$tc];


                $chart['labels'] = $nome["label"];
                $chart['data'] = $nome["data"];
                $chart['backgroundColor'] = $nome["backgroundColor"];

        }
        $tc++;
       
    }

     //var_dump( $nome['data'] );

        //  var_dump( $chart );
    return  $chart ; 
}



function hora2nr($hora){
    $negativo = (substr($hora, 0, 1) == '-');

    $hora_arr = explode(':', $hora);
    $horas = ltrim($hora_arr[0], '-'); // Remove sinal negativo caso exista
    $minutos = isset($hora_arr[1]) ? $hora_arr[1] : 0;
    $segundos = isset($hora_arr[2]) ? $hora_arr[2] : 0;

    $nr = ($horas * 3600) + ($minutos * 60) + $segundos;

    if($negativo){
       $nr *= -1;
    }

    return $nr;
}





// /**Verificação de chamados por Tempo de Espera   */
function verifica_chamado_por_tempo_espera(){
    include('../conexao.php');

    $hoje = date('Y-m-d');
    $trintaAntes = date('Y-m-d', strtotime('-30 days'));

    $sql_todos  = "SELECT  Tempo_Espera	, COUNT(*) as Total_Por_Tempo_Espera FROM chamado
                    WHERE DH_Chamado >= '".$trintaAntes." 00:00:01' 
                    AND DH_Chamado <= '".$hoje." 23:59:59' ";
    $sql_group = "  GROUP BY Tempo_Espera ORDER BY Tempo_Espera	 ";
    $sql =  $sql_todos .   $sql_group . ";"; 

    $chart = array();

    $resultado = $conn->query( $sql );          
   
    /**Se aumentar os motivos de chamados aumentar as cores  */    
    $cores = array(
        'rgba(255, 99, 132, 1)',          'rgba(54, 162, 235, 1)',              'rgba(255, 206, 86, 1)',                'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',         'rgba(255, 159, 64, 1)',              'rgba(255, 99, 132, 1)',                'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',          'rgba(75, 192, 192, 1)',              'rgba(153, 102, 255, 1)',               'rgba(255, 159, 64, 1)'
    );

   
    $tempo = array();
    $tempo['< 1min'] = 0;
    $tempo['1m - 2m'] = 0; 
    $tempo['2m - 5m'] = 0 ;
    $tempo['5m - 10m'] = 0 ;
    $tempo['10m - 15m'] = 0 ;
    $tempo['> 15min'] = 0 ;

    foreach ( $resultado as $row  ){
          
        if( hora2nr( $row['Tempo_Espera'] ) <60){
            $tempo['< 1min'] += $row['Total_Por_Tempo_Espera']; //$menos_um_minuto;            
        }else if( (hora2nr( $row['Tempo_Espera'] ) >=60) &&  (hora2nr( $row['Tempo_Espera'] ) <120 ) ){
            $tempo['1m - 2m'] += $row['Total_Por_Tempo_Espera']; //entre 1 e 2 minutos
        }else if ( (hora2nr( $row['Tempo_Espera'] ) >=120) &&  (hora2nr( $row['Tempo_Espera'] ) <300 ) ){
            $tempo['2m - 5m'] += $row['Total_Por_Tempo_Espera']; //entre 1 e 2 minutos
        }else if ( (hora2nr( $row['Tempo_Espera'] ) >=300) &&  (hora2nr( $row['Tempo_Espera'] ) <600 ) ){
            $tempo['5m - 10m'] += $row['Total_Por_Tempo_Espera']; //entre  5 e 10  minutos
        }else if ( (hora2nr( $row['Tempo_Espera'] ) >=600) &&  (hora2nr( $row['Tempo_Espera'] ) <900 ) ){
            $tempo['10m - 15m'] += $row['Total_Por_Tempo_Espera']; //entre  5 e 10  minutos
        }else{
            $tempo['> 15min'] += $row['Total_Por_Tempo_Espera']; //entre  5 e 10  minutos              
        }
    }
    $i = 0 ;
        
    foreach( $tempo as $key => $value){
            $chart['labels'][] = $key;
            $chart['data'][] = $value;
            $chart['backgroundColor'][] = $cores[$i];
            $i++;
    } 
    
   // var_dump( $chart );
    return  $chart ; 
}






// /**Verificação de chamados por Horario   */
function verifica_chamado_por_horario(){
    include('../conexao.php');

    $cores = array(
        'rgba(255, 99, 132, 1)',        'rgba(54, 162, 235, 1)',        'rgba(255, 206, 86, 1)',        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',       'rgba(255, 159, 64, 1)',        'rgba(0,191,255, 1)',           'rgba(176,196,222,1)',
        'rgba(0,128,128,1)',            'rgba(107,142,35,1)',           'rgba(127,255,0,1)',            'rgba(218,165,32,1)',
        'rgba(210,105,30,1)',           'rgba(222,184,135,1)',          'rgba(123,104,238,1)',          'rgba(255,0,255,1)',
        'rgba(250,128,114,1)',          'rgba(255,215,0,1)',            'rgba(216,191,216,1)',          'rgba(175,238,238,1)'
    );

    $hoje = date('Y-m-d');
    $trintaAntes = date('Y-m-d', strtotime('-30 days'));
    $sql_todos  = "SELECT  DH_Chamado	, COUNT(*) as Total_Por_Horario FROM chamado
                    WHERE DH_Chamado >= '".$trintaAntes." 00:00:01' 
                    AND DH_Chamado <= '".$hoje." 23:59:59' ";
    // $sql_finalizado = " AND Finalizado='S' ";
    $sql_group = "  GROUP BY DH_Chamado ORDER BY DH_Chamado ";
    $sql =  $sql_todos .   $sql_group . ";"; 
  
    $chart = array();
  
    $tempo = array();
    $tempo['< 08:30h'] = 0 ;
    $tempo['08:30h-09:00h'] = 0 ; 
    $tempo['09:00h-09:30h'] = 0 ; 
    $tempo['09:30h-10:00h'] = 0 ; 
    $tempo['10:00h-10:30h'] = 0;
    $tempo['10:30h-11:00h'] = 0;
    $tempo['11:00h-11:30h'] = 0;
    $tempo['11:30h-12:00h'] = 0;
    $tempo['< 13:30h'] = 0;
    $tempo['13:30h-14:00h'] = 0;
    $tempo['14:00h-14:30h'] = 0;
    $tempo['14:30h-15:00h'] = 0; 
    $tempo['15:00h-15:30h'] = 0;
    $tempo['15:30h-16:00h'] = 0;
    $tempo['16:00h-16:30h'] = 0;
    $tempo['16:30h-17:00h'] = 0;
    $tempo['17:00h-17:30h'] = 0;   
    $tempo['> 17:30h'] = 0 ; 

    $resultado = $conn->query( $sql );
    
    foreach ( $resultado as $row  ){
        $hora_chamado = explode(" ", $row['DH_Chamado']);
        $hora = $hora_chamado[1] ;

           
            if( (hora2nr( $hora )) < hora2nr( '08:30:00' )){
                $tempo['< 08:30h'] += $row['Total_Por_Horario'];
            }
            else if(    (hora2nr( $hora ) >= hora2nr( '08:30:00' ) ) && (hora2nr( $hora ) < hora2nr( '09:00:00' ) ) ){               
                $tempo['08:30h-09:00h'] += $row['Total_Por_Horario'];                 
            }
            else if(    (hora2nr( $hora ) >= hora2nr( '09:00:00' ) ) && (hora2nr( $hora ) < hora2nr( '09:30:00' ) ) ){               
                $tempo['09:00h-09:30h'] += $row['Total_Por_Horario'];                 
            }
            else if(    (hora2nr( $hora ) >= hora2nr( '09:30:00' ) ) && (hora2nr( $hora ) < hora2nr( '10:00:00' ) ) ){               
                $tempo['09:30h-10:00h'] += $row['Total_Por_Horario'];                 
            }
            else if(    (hora2nr( $hora ) >= hora2nr( '10:00:00' ) ) && (hora2nr( $hora ) < hora2nr( '10:30:00' ) ) ){               
                $tempo['10:00h-10:30h'] += $row['Total_Por_Horario'];                 
            }            
            else if(    (hora2nr( $hora ) >= hora2nr( '10:30:00' ) ) && (hora2nr( $hora ) < hora2nr( '11:00:00' ) ) ){               
                $tempo['10:30h-11:00h'] += $row['Total_Por_Horario'];                 
            }
            else if(    (hora2nr( $hora ) >= hora2nr( '11:00:00' ) ) && (hora2nr( $hora ) < hora2nr( '11:30:00' ) ) ){               
                $tempo['11:00h-11:30h'] += $row['Total_Por_Horario'];                 
            }
            else if(    (hora2nr( $hora ) >= hora2nr( '11:30:00' ) ) && (hora2nr( $hora ) < hora2nr( '12:00:00' ) ) ){               
                $tempo['11:30h-12:00h'] += $row['Total_Por_Horario'];                 
            }
            else if(    (hora2nr( $hora ) >= hora2nr( '12:00:00' ) ) && (hora2nr( $hora ) < hora2nr( '13:30:00' ) ) ){               
                $tempo['< 13:30h'] += $row['Total_Por_Horario'];                 
            }
            else if(    (hora2nr( $hora ) >= hora2nr( '13:30:00' ) ) && (hora2nr( $hora ) < hora2nr( '14:00:00' ) ) ){               
                $tempo['13:30h-14:00h'] += $row['Total_Por_Horario'];                   
            }
            else if(    (hora2nr( $hora ) >= hora2nr( '14:00:00' ) ) && (hora2nr( $hora ) < hora2nr( '14:30:00' ) ) ){               
                $tempo['14:00h-14:30h'] += $row['Total_Por_Horario'];                   
            }
            else if(    (hora2nr( $hora ) >= hora2nr( '14:30:00' ) ) && (hora2nr( $hora ) < hora2nr( '15:00:00' ) ) ){               
                $tempo['14:30h-15:00h'] += $row['Total_Por_Horario'];                   
            }
            else if(    (hora2nr( $hora ) >= hora2nr( '15:00:00' ) ) && (hora2nr( $hora ) < hora2nr( '15:30:00' ) ) ){               
                $tempo['15:00h-15:30h'] += $row['Total_Por_Horario'];                   
            }
            else if(    (hora2nr( $hora ) >= hora2nr( '15:30:00' ) ) && (hora2nr( $hora ) < hora2nr( '16:00:00' ) ) ){               
                $tempo['15:30h-16:00h'] += $row['Total_Por_Horario'];                   
            }
            else if(    (hora2nr( $hora ) >= hora2nr( '16:00:00' ) ) && (hora2nr( $hora ) < hora2nr( '16:30:00' ) ) ){               
                $tempo['16:00h-16:30h'] += $row['Total_Por_Horario'];                   
            }
            else if(    (hora2nr( $hora ) >= hora2nr( '16:30:00' ) ) && (hora2nr( $hora ) < hora2nr( '17:00:00' ) ) ){               
                $tempo['16:30h-17:00h'] += $row['Total_Por_Horario'];                   
            }
            else if(    (hora2nr( $hora ) >= hora2nr( '17:00:00' ) ) && (hora2nr( $hora ) < hora2nr( '17:30:00' ) ) ){               
                $tempo['17:00h-17:30h'] += $row['Total_Por_Horario'];                   
            }                       
            else  if ( (hora2nr( $hora ) >= hora2nr( '17:30:00' )  )  ){
                $tempo['> 17:30h']    += $row['Total_Por_Horario'];  
                
            }
        }
      

        $i = 0 ;
        foreach( $tempo as $key => $value){
            $chart['labels'][] = $key;
            $chart['data'][] = $value;
            $chart['backgroundColor'][] = $cores[$i];
            $i++;
        }

        return  $chart ; 
}




// /**Verificação de chamados por Dias do Mes   */
function verifica_chamado_por_dias_mes(){
    include('../conexao.php');

    $hoje = date('Y-m-d');
    $trintaAntes = date('Y-m-d', strtotime('-30 days'));

    $sql_todos  = "SELECT  DH_Chamado	, COUNT(*) as Total_Por_Dias FROM chamado
                    WHERE DH_Chamado >= '".$trintaAntes." 00:00:01' 
                    AND DH_Chamado <= '".$hoje." 23:59:59' ";
    // $sql_finalizado = " AND Finalizado='S' ";
    $sql_group = "  GROUP BY DH_Chamado ORDER BY DH_Chamado ";
    $sql =  $sql_todos .   $sql_group . ";"; 

    $nome = array();
    $chart = array();

    /**Se aumentar os motivos de chamados aumentar as cores  */    
    $cores = array(
        'rgba(255, 99, 132, 1)',        'rgba(54, 162, 235, 1)',                'rgba(255, 206, 86, 1)',            'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',       'rgba(255, 159, 64, 1)',                'rgba(255, 99, 132, 1)',            'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',        'rgba(75, 192, 192, 1)',                'rgba(153, 102, 255, 1)',           'rgba(255, 159, 64, 1)'
    );
  
    $tempo = array();
    $tempo['< 05'] = 0 ;
    $tempo['05 a 10'] = 0 ; 
    $tempo['10 a 15'] = 0 ;
    $tempo['15 a 20'] = 0 ;
    $tempo['20 a 25'] = 0 ;
    $tempo['> 25'] = 0 ;

    $resultado = $conn->query( $sql );

    foreach ( $resultado as $row  ){
          
        $dh_chamado = explode(" ", $row['DH_Chamado']);
           
            $todos_dia = explode("-",  $dh_chamado[0]);
            $cada_dia =  $todos_dia[2];            

            if(  $cada_dia < 5 ){               
                $tempo['< 05']  += $row['Total_Por_Dias'];                 
            }
            else if(  ( $cada_dia  >= 5 ) &&   ( $cada_dia < 10 ) ){
                $tempo['05 a 10'] += $row['Total_Por_Dias']; 
            }else if ( ( $cada_dia  >= 10 ) &&   ( $cada_dia < 15 ) ){
                $tempo['10 a 15'] += $row['Total_Por_Dias'];                
            }
            else if ( ( $cada_dia  >= 15 ) &&   ( $cada_dia < 20 ) ){
                $tempo['15 a 20'] += $row['Total_Por_Dias']; 
            }
            else if ( ( $cada_dia  >= 20 ) &&   ( $cada_dia < 25 ) ){
                $tempo['20 a 25'] += $row['Total_Por_Dias'];               
            }            
            else  if ( ( $cada_dia  >= 25 )  ){
                $tempo['> 25']    += $row['Total_Por_Dias'];                
            }

       
    }
      
        $i = 0 ;
        foreach( $tempo as $key => $value){
            $chart['labels'][] = $key;
            $chart['data'][] = $value;
            $chart['backgroundColor'][] = $cores[$i];
            $i++;
        }


  
//     //var_dump( $nome['data'] );
//     // var_dump( $tempo );
//     //  var_dump( $chart );
    return  $chart ; 
}



function semana_do_ano($data ){
    $todos_dia = explode("-",  $data );
    $dia =  $todos_dia[2];   
    $mes =  $todos_dia[1];   
    $ano =  $todos_dia[0]; 

    $var=intval( date('z', mktime(0,0,0,$mes,$dia,$ano) ) / 7 ) + 1;
    
    return $var;
}



function dataf($data){
    if($data != ""){
      // $texto = fill_full($data[8].$data[9],2).'/'.fill_full($data[5].$data[6],2).'/'.fill_full($data[0].$data[1].$data[2].$data[3],4);
       $texto = ($data[8].$data[9]).'/'.($data[5].$data[6]).'/'.($data[0].$data[1].$data[2].$data[3]);
    }else{
       $texto = "00/00/0000";
    }

    // echo $texto ; 
    return $texto;
 }

 function dia_semana($jdr){
    $jd = ($jdr+2415019);
    return jddayofweek($jd,0);
}



// /**Verificação de chamados por Dias do Mes   */
function verifica_chamado_por_dias_semana(){
    include('../conexao.php');

    $hoje = date('Y-m-d');
    $trintaAntes = date('Y-m-d', strtotime('-30 days'));

    $sql_todos  = "SELECT  DH_Chamado	, COUNT(*) as Total_Por_Semana  FROM chamado
                    WHERE DH_Chamado >= '".$trintaAntes." 00:00:01' 
                    AND DH_Chamado <= '".$hoje." 23:59:59' ";
    // $sql_finalizado = " AND Finalizado='S' ";
    $sql_group = "  GROUP BY DH_Chamado ORDER BY DH_Chamado ";
    $sql =  $sql_todos .   $sql_group . ";"; 

    $nome = array();
    $chart = array();

    /**Se aumentar os motivos de chamados aumentar as cores  */    
    $cores = array(
        'rgba(255, 99, 132, 1)',                'rgba(54, 162, 235, 1)',            'rgba(255, 206, 86, 1)',            'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',               'rgba(255, 159, 64, 1)',            'rgba(255, 99, 132, 1)',            'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',                'rgba(75, 192, 192, 1)',            'rgba(153, 102, 255, 1)',           'rgba(255, 159, 64, 1)'
    );
  
    $tempo = array();

    $resultado = $conn->query( $sql );          
    foreach( $resultado as $row ){
        $dh_chamado = explode(" ", $row['DH_Chamado']);
        $semana = semana_do_ano( $dh_chamado[0] ) ;
        if(   $semana == semana_do_ano( $dh_chamado[0] ) ){ 

            if(!isset($tempo[$semana])){
                   // $tempo[$semana]['Domingo'] = 0 ;
                    $tempo[$semana]['Segunda'] = 0 ;
                    $tempo[$semana]['Terça'] = 0 ; 
                    $tempo[$semana]['Quarta'] = 0 ;
                    $tempo[$semana]['Quinta'] = 0 ;
                    $tempo[$semana]['Sexta'] = 0 ;
                    $tempo[$semana]['Sabado'] = 0 ;
                   

            }
            if( !isset($label[$semana]) ){
                    $label[$semana]['inicio'] = '32/13/2099';
                    $label[$semana]['fim'] = '00/00/0000';

            }

                $inicio = dataf($dh_chamado[0]) ;
                if( $label[$semana]['inicio'] >  $inicio ){
                    $label[$semana]['inicio'] = $inicio ;

                }
                $fim = dataf($dh_chamado[0]) ;
                if( $label[$semana]['fim'] <   $fim ){
                    $label[$semana]['fim'] = $fim ;

                }

                $dia_semana2 =  dia_semana( date('w',strtotime(  $dh_chamado[0] ) ) )+1 ;
                //echo ' <br>  dia da semana com numero  '. $dia_semana2 . ' dia do mes '.  $dh_chamado[0];
                if(  $dia_semana2 == '1'    ){
                    //$dias['Segunda']  += $row->Total_Por_Semana ; 
                    //$tempo[$semana][1]  += $row->Total_Por_Semana ; 
                    //
                    // 
                    $tempo[$semana]['Segunda']  += $row['Total_Por_Semana'] ; 

                   
                   
                   // $tempo[$semana]['Segunda'] =  $dias['Segunda'] ; 

                    //$tempo[$semana]['Segunda']['Saldo'] = coloca_saldo( $semana , 'Segunda' ,$row->Total_Por_Semana , $tempo[$semana]['Segunda']['Saldo']  );


                    // $tempo[$semana] = alimenta_dias( $semana , $row->Total_Por_Semana , 'Segunda'  );


                }else if ( $dia_semana2 ==  '2' ){
                    // 
                    //
                    $tempo[$semana]['Terça']  += $row['Total_Por_Semana'] ; 
                   // $tempo[$semana][2]  += $row->Total_Por_Semana ; 


                   //$tempo[$semana] = alimenta_dias( $semana , $row->Total_Por_Semana , 'Terça' );
                } else if ( $dia_semana2 ==  '3'  ){
                    // 
                    //
                    $tempo[$semana]['Quarta']  += $row['Total_Por_Semana']; 
                    //$tempo[$semana] = alimenta_dias( $semana , $row->Total_Por_Semana , 'Quarta' );
                    //$tempo[$semana][3]  += $row->Total_Por_Semana ; 
                }else if ( $dia_semana2 ==  '4'  ){
                    // 
                    $tempo[$semana]['Quinta']  += $row['Total_Por_Semana'] ; 
                   // $tempo[$semana][4]  += $row->Total_Por_Semana ; 

                }else if ( $dia_semana2 ==  '5'  ){
                    //
                     $tempo[$semana]['Sexta']  += $row['Total_Por_Semana'] ; 
                    //$tempo[$semana][5]  += $row->Total_Por_Semana ; 
                }else if ( $dia_semana2 ==  '6'  ){
                    //
                     $tempo[$semana]['Sabado']  += $row['Total_Por_Semana'] ;
                    //  $label[$semana]['fim'] =dataf($dh_chamado[0]);
                    // echo ' <br> sabados: '. $dh_chamado[0] . ' ' .  $dia_semana2 ;
                   // $tempo[$semana][6]  += $row->Total_Por_Semana ;
                }else{
                    // 
                    $tempo[$semana]['Domingo']+= $row->Total_Por_Semana ;
                   // $label[$semana]['fim'] = datat($dh_chamado[0]);
                    //echo ' <br> sabados: '. $dh_chamado[0] . ' ' .  $dia_semana2 ;
                   // $tempo[$semana][0]+= $row->Total_Por_Semana ;
                }
            }
           
            
        }
      

        $lista = array();        
        foreach( $tempo as $key => $value){
             
            $lista[$key]= $value;
            $i = 0 ;
            foreach(  $lista as $key2 => $value2 ){
                $chart[$key2]['data'] = $value2;
                $chart[$key2]['backgroundColor'] = $cores[$i];
                $chart[$key2]['labels']=  $label[$key2]['inicio'] ;  //. '-' .$label[$key2]['fim']
                $i++;
              
            }            
        }      
 //   }

//    // print_r( $label );

//     //  print_r( $chart);
    return   $chart ; 
}



$resposta =  verifica_motivo();
$respostaTecnico = verifica_tecnico();
$respostaTempoEspera = verifica_chamado_por_tempo_espera();
$respostaHorario = verifica_chamado_por_horario();
$respostaDiasMes = verifica_chamado_por_dias_mes();
$respostaDiaSemana = verifica_chamado_por_dias_semana();

//  echo '<br> linha 108 ::: '. $data = json_encode( $resposta , JSON_NUMERIC_CHECK); 

// var_dump( $respostaDiaSemana  );
$valores = array();
$labels = array();
$i = 0 ; 
foreach( $respostaDiaSemana as $key => $value){
    $semana[$key] = $value ;
    $valores[$i] = $key;
    $labels[$i] = $value['labels'];
   
//    $valores['Semana'] = $key;

    // if(  $key == '11' ){
    //     $labels[$i] = '14/03 a 20/03/2022';
        

    // }else{
    //     $labels[$i]= 'teste '. $i;  
    // }
    $i++ ;

}



// for( $i = 0 ; $i<count( $semana ); $i++){
//    echo  ' 744inicio ::: '. $semana[$i]['inicio'] ;                  
   
        
// }



// for( $i = 0 ; $i<count( $valores ); $i++){
   
//              echo ' <br>linha 757 ' . json_encode( $semana[$valores[$i]]['labels'] , JSON_NUMERIC_CHECK);                    
//              echo ' linha 758 ' .json_encode( $semana[$valores[$i]]['data'] , JSON_NUMERIC_CHECK); 
           
//             echo ' linha 759 ' . json_encode( $semana[$valores[$i]]['backgroundColor'] , JSON_NUMERIC_CHECK); 
       

// }


// var_dump( $valores );
// var_dump( $labels );


 
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grafico Charts  </title>
    <link rel="stylesheet" href="../../plugins/bootstrap/css/bootstrap.min.css" />




</head>
<body>


<!--  -->
    <!--    **********************  Informacoes da pagina Original do Uchoa **************************   --> 
    <!-- gráficos -->    
    <h1>Graficos com chart js </h1>
<div class="row">
    <div class="col-md-6">
        <!-- AREA CHART -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Motivo do Chamado</h3>
            </div>
            <div class="box-body">
                <div class="chart">                
                    <canvas id="areaChart" style="height:400px;width: 400px;border: 1px solid blue;"></canvas>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
          <!-- /.box -->

        <!-- DONUT CHART -->
        <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">3. Chamados Por Horário </h3>
            </div>
            <div class="box-body">
                <div class="chart" > 
                <!--    style="position: relative; height:40vh; width:80vw"            -->
                    <canvas id="chartContainerHorario" style="height:400px;width: 400px; border: 1px solid rgb(153, 102, 255);"   ></canvas>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
          <!-- /.box -->




        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">5 . Chamados Por dias da Semana</h3>
                <!-- <div class="box-tools pull-right">   </div> -->
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="chartContainerDiaSemana" style="height:400px;width: 400px;border: 1px solid rgb(54, 162, 235);"></canvas>               
                </div>
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
                <h3 class="box-title">2 . Chamados Por Técnicos</h3>
                <!-- <div class="box-tools pull-right">   </div> -->
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="chartContainerTecnico" style="height:400px;width: 400px;border: 1px solid rgb(318, 206, 62);"></canvas>               
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->

        <!-- BAR CHART -->
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">4. Chamados por dias do mês </h3>
                <!-- <div class="box-tools pull-right"></div> -->
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="chartContainerDiaMes" style="height:400px;width: 400px; border: 1px solid rgb(235, 99 , 132);"></canvas>                
                </div>
            </div>  <!-- /.box-body -->
        </div>
          <!-- /.box -->




        <!-- BAR CHART -->
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">6. Tempo de Espera </h3>
                <!-- <div class="box-tools pull-right"></div> -->
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="chartContainerEspera" style="height:400px;width: 400px;border: 1px solid yellow ;"></canvas>                
                </div>
            </div>  <!-- /.box-body -->
        </div>
          <!-- /.box -->



          <div class="box box-success">
           
            <div class="box-body">
                <div class="chart">
                 
                    <canvas id="my" width="400" height="400" style="border:1px solid blue;"></canvas>             
                </div>
            </div>  <!-- /.box-body -->
        </div>
          <!-- /.box -->





    </div>
    <!-- /.col (RIGHT) -->



  


</div>
<!-- /.row -->
<!-- E:\Wamp\www\src\js\chartjs -->

<!-- https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js

https://cdnjs.com/libraries/Chart.js

<?php 

  for( $i = 0 ; $i <= count( $valores ); $i++){
            
    echo    json_encode( $semana[$valores[$i]]['labels'] , JSON_NUMERIC_CHECK);                       
    echo json_encode( $semana[$valores[$i]]['data'] , JSON_NUMERIC_CHECK); 
    echo json_encode( $semana[$valores[$i]]['backgroundColor'] , JSON_NUMERIC_CHECK); 
  }                 

?>
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








const ctx = document.getElementById('areaChart').getContext('2d');

// console.log(  <?php // echo json_encode( $resposta , JSON_NUMERIC_CHECK); ?> );

// console.log(  <?php  echo json_encode( $resposta['labels'] , JSON_NUMERIC_CHECK); ?> ); 
// console.log(  <?php  echo json_encode( $resposta['data'] , JSON_NUMERIC_CHECK); ?> );  
//  console.log(  <?php  echo json_encode( $resposta['backgroundColor'] , JSON_NUMERIC_CHECK); ?> );  
const myChart = new Chart(ctx, {
    
    type: 'bar',         
    data: {
        labels: <?php echo json_encode( $resposta['labels'] , JSON_NUMERIC_CHECK); ?>,
        datasets:[
            {
                data: <?php echo json_encode( $resposta['data'] , JSON_NUMERIC_CHECK); ?>,
                backgroundColor: <?php echo json_encode( $resposta['backgroundColor'] , JSON_NUMERIC_CHECK); ?>,
            }
        ],
       
    },
    options: {
        indexAxis: 'y',
        scales: {
            x: {
                beginAtZero: true
            }
        }, 
         legend: {
      display: false,
    },
    plugins: {
    legend: {
        display: false,
    }
    }
   
}
});


/**Chamado por Tecnico */

const ctx_tecnico = document.getElementById('chartContainerTecnico').getContext('2d');

// console.log(  <?php // echo json_encode( $resposta , JSON_NUMERIC_CHECK); ?> );

// console.log(  <?php // echo json_encode( $resposta['labels'] , JSON_NUMERIC_CHECK); ?> ); 
// console.log(  <?php // echo json_encode( $resposta['data'] , JSON_NUMERIC_CHECK); ?> );  
// console.log(  <?php // echo json_encode( $resposta['backgroundColor'] , JSON_NUMERIC_CHECK); ?> );  
const myChart_tecnico = new Chart(ctx_tecnico, {
    
    type: 'bar',         
    data: {
        labels: <?php echo json_encode( $respostaTecnico['labels'] , JSON_NUMERIC_CHECK); ?>,
        datasets:[
            {
                data: <?php echo json_encode( $respostaTecnico['data'] , JSON_NUMERIC_CHECK); ?>,
                backgroundColor: <?php echo json_encode( $respostaTecnico['backgroundColor'] , JSON_NUMERIC_CHECK); ?>,
            }
        ],
       
    },
    options: {
        indexAxis: 'y',
        scales: {
            x: {
                beginAtZero: true
            }
        }, 
         legend: {
      display: false,
    },
    plugins: {
    legend: {
        display: false,
    }
    }
   
}
});






/**Chamado por Tempo de Espera  */

const ctx_tempo_espera = document.getElementById('chartContainerEspera').getContext('2d');
const myChart_espera = new Chart(ctx_tempo_espera , {
    
    type: 'bar',         
    data: {
        labels: <?php  echo json_encode( $respostaTempoEspera['labels'] , JSON_NUMERIC_CHECK); ?>,
        datasets:[
            {
                data: <?php   echo json_encode( $respostaTempoEspera['data'] , JSON_NUMERIC_CHECK); ?>,
                backgroundColor: <?php  echo json_encode( $respostaTempoEspera['backgroundColor'] , JSON_NUMERIC_CHECK); ?>,
            }
        ],
       
    },
    options: {
        indexAxis: 'x',
        scales: {
            y: {
                beginAtZero: true
            }
        }, 
        legend: {
            display: false,
        },
        plugins: {
            legend: {
                display: false,
            }
        }
   
    }
});


// /** Chamados por Horario chartContainerHorario  */
const ctx_horario = document.getElementById('chartContainerHorario').getContext('2d');
// // chartContainerHorario.canvas.parentNode.style.height = '400px';
// // chartContainerHorario.canvas.parentNode.style.width = '400px';

// // chartContainerHorario.parentNode.style.height = '400px';
// // chartContainerHorario.parentNode.style.width = '400px';
// // chartContainerHorario.parentNode.style.align-items = 'center';

// // ctx_horario.parentNode.style.height = '400px';
// // ctx_horario.parentNode.style.width = '400px';

const myChart_horario = new Chart(ctx_horario , {
    
    type: 'bar',         
    data: {
        labels: <?php  echo json_encode( $respostaHorario['labels'] , JSON_NUMERIC_CHECK); ?>,
        datasets:[
            {
                data: <?php  echo json_encode( $respostaHorario['data'] , JSON_NUMERIC_CHECK); ?>,
                backgroundColor: <?php  echo json_encode( $respostaHorario['backgroundColor'] , JSON_NUMERIC_CHECK); ?>,
               // hoverOffset: 4,
            }
        ],
        // hoverOffset: 4
       
    },
    options: {
        indexAxis: 'y',
        scales: {
            x: {
                beginAtZero: true
            }
        }, 
        legend: {
            display: false,
        },
        plugins: {
            legend: {
                display: false,
            }
        }
   
    }
});



// <?php // echo json_encode( $respostaDiasMes['labels'] , JSON_NUMERIC_CHECK); ?>

// /** Chamados por Dias do mes chartContainerDiaMes  */
const ctx_dias_mes = document.getElementById('chartContainerDiaMes').getContext('2d');
const myChart_dias_mes = new Chart(ctx_dias_mes , {
    
    type: 'bar',         
    data: {
        labels: <?php  echo json_encode( $respostaDiasMes['labels'] , JSON_NUMERIC_CHECK); ?>,
        datasets:[
            {
                data: <?php  echo json_encode( $respostaDiasMes['data'] , JSON_NUMERIC_CHECK); ?>,
                backgroundColor: <?php  echo json_encode( $respostaDiasMes['backgroundColor'] , JSON_NUMERIC_CHECK); ?>,
               // hoverOffset: 4,
            }
        ],
        // hoverOffset: 4
       
    },
    options: {
        indexAxis: 'x',
        scales: {
            y: {
                beginAtZero: true
            }
        }, 
        legend: {
            display: false,
        },
        plugins: {
            legend: {
                display: false,
            }
        }
   
    }
});




// /** Chamados por Dias da Semanda o mes  chartContainerDiaSemana */
const ctx_dias_semana = document.getElementById('chartContainerDiaSemana').getContext('2d');
const myChart_dias_semana = new Chart(ctx_dias_semana , {
    
    type: 'line',         
    data: {

        labels: 
           [  "Segunda", "Terça" , "Quarta", "Quinta" , "Sexta", "Sabado" ] ,
        //    [  'Sábado',    'Segunda' ,'Terça','Quarta' ,'Quinta','Sexta']   
    
        datasets: [
            <?php
            
      
                for( $i = 0 ; $i<count( $valores ); $i++){
                ?>
                    { 
                        label: <?php  echo json_encode( $semana[$valores[$i]]['labels'] , JSON_NUMERIC_CHECK); ?>,                          
                        data:  <?php  echo json_encode( $semana[$valores[$i]]['data'] , JSON_NUMERIC_CHECK); ?>,
                        borderWidth:1 ,
                        borderColor:  <?php   echo json_encode( $semana[$valores[$i]]['backgroundColor'] , JSON_NUMERIC_CHECK); ?>,
                    },
                <?php
                }  
            ?>               
        ]
       
    },
    options: {
        indexAxis: 'x',
        scales: {
            y: {
                beginAtZero: true
            }
        }, 
        legend: {
            display: false,
        },
        plugins: {
            legend: {
                display: true,  //false,
                position: 'top', 
                align: 'center',
              
            }
        }
    }
});







   



</script>

</body>
</html>


  