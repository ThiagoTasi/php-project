<?php
include "acesso_com.php";
include '../conn/connect.php';
// inicia a verificação do Login

if ($_POST) {
    $login = $_POST['login'];
    $senha = $_POST['senha'];
    $loginResult = $pdo->query("select * from usuarios where login = '$login' and senha = md5('$senha')");
    $rowLogin = $loginResult->fetch(PDO::FETCH_ASSOC);
    // var_dump($rowLogin);
    // die();
    $numRow = $loginResult->rowCount();
    if (!isset($_SESSION)) {
        $sessaoAntiga = session_name('chulettaaa');
        session_start();
        $session_name_new = session_name();
    }
    if ($numRow > 0) {
        $_SESSION['login_usuario'] = $login;
        $_SESSION['nivel_usuario'] = $rowLogin['nivel'];
        $_SESSION['nome_da_sessao'] = session_name();
        if ($rowLogin['nivel'] == 'sup') {
            echo "<script>window.open('index.php','_self')</script>";
        } else {
            echo "<script>window.open('../cliente/index.php?cliente=" . $login . "','_self')</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estilo.css">
    <title>Área Administrativa - Chuleta Quente</title>
</head>
<body>
    <?php 
        // nav
        include "menu_adm.php";
        // container
        include "adm_options.php";
    ?>
</body>
</html>