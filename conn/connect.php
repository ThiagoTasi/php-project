<?php
// $host = "localhost";
// $database = "tinsphpdb01";
// $user = "root";
// $pass = "";
// $charset = "utf8";
// $port = "3306";
 
 
// try {
//     // lembre dessa variável quando usar um comando SQL no PHP
//     $conn = new mysqli($host,$user,$pass,$database,$port);
//     mysqli_set_charset($conn, $charset);
// }catch (Throwable $th){
//     die ("Atenção rolou um ERRO, Cara!".$th);
// }
 

// Dados de conexão com o banco
$host = 'localhost';  // Ou o seu servidor de banco de dados
$dbname = 'tinsphpdb01'; // Substitua pelo nome correto do seu banco de dados
$username = 'root'; // Usuário do banco de dados
$password = ''; // Senha do banco de dados
$charset = "utf8";
$port = "3306";

try {
    // Conectando ao banco de dados usando PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Ativar exceções
} catch (PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage();
    exit;
}
?>
