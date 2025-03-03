<?php
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
    <script src="https://kit.fontawesome.com/2495680ceb.js" crossorigin="anonymous"></script>
    <!-- Link para CSS específico -->
    <link rel="stylesheet" href="../css/estilo.css" type="text/css">


    <title>Chuleta Quente - Login - reservas</title>
</head>

<body>
    <a href="../index.php">Voltar para a Página Inicial</a>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <main class="container">
        <section>
            <article>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
                        <h1 class="breadcrumb text-info text-center">Faça seu login-reserva</h1>
                        <div class="thumbnail">
                            <p class="text-info text-center" role="alert">
                                <i class="fas fa-users fa-10x"></i>
                            </p>
                            <br>
                            <div class="alert alert-info" role="alert">
                                <form action="login.php" name="form_login" id="form_login" method="POST" enctype="multipart/form-data">
                                    <label for="login_usuario">Login:</label>
                                    <p class="input-group">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-user text-info" aria-hidden="true"></span>
                                        </span>
                                        <input type="text" name="login" id="login" class="form-control" autofocus required autocomplete="off" placeholder="Digite seu login.">
                                    </p>
                                    <label for="senha">Senha:</label>
                                    <p class="input-group">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-qrcode text-info" aria-hidden="true"></span>
                                        </span>
                                        <input type="password" name="senha" id="senha" class="form-control" required autocomplete="off" placeholder="Digite sua senha.">
                                    </p>
                                    <p class="text-right">
                                        <input type="submit" value="Entrar" class="btn btn-primary">
                                    </p>
                                    <?php
                                    if (isset($_SESSION['nivel_usuario']) && $_SESSION['nivel_usuario'] == 'admin') {
                                        echo '<p class="text-right"><button type="button" class="btn btn-primary" onclick="window.location.href=\'reserva_cliente.php\'">Reservar (Admin)</button></p>';
                                    } else {
                                        echo '<p class="text-right"><button type="button" class="btn btn-primary" onclick="window.location.href=\'reserva_cliente.php\'">Reservar</button></p>';
                                    }
                                    ?>
                                </form>
                                <p class="text-center">
                                    <small>
                                        <br>
                                        Caso não faça uma escolha em 30 segundos será redirecionado automaticamente para página inicial.
                                    </small>
                                </p>
                            </div><!-- fecha alert -->
                        </div><!-- fecha thumbnail -->
                    </div><!-- fecha dimensionamento -->
                </div><!-- fecha row -->
            </article>
        </section>
    </main>


    <!-- Link arquivos Bootstrap js -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
</body>

</html>