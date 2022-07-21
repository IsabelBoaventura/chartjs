<?php

/*define( 'MYSQL_HOST', 'localhost' );
define( 'MYSQL_USER', 'root' );
define( 'MYSQL_PASSWORD', '' );
define( 'MYSQL_DB_NAME', 'pdo_tutorial' );
$PDO = new PDO( 'mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_DB_NAME, MYSQL_USER, MYSQL_PASSWORD );*/

$db_host = 'localhost';
$db_name = 'simples_controle';
$db_user = 'root';
$db_pass_empresa = '';
$db_pass_casa = '';
//private $db_char = 'utf8';

//$conn = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . ' ',  $db_user,   $db_pass);

try {
    $conn = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name,  $db_user,   $db_pass_empresa);

    //$conn = new PDO('mysql:host=localhost;dbname=meuBancoDeDados', $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

 //    echo '<br> conexao realizada com sucesso com o banco  '. $db_name .' <br>';
} catch (PDOException $e) {
    echo '<br>Erro ao conectar com o banco de dados  <br>';
    echo 'ERROR: ' . $e->getMessage();
}


?>



