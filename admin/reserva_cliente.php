<?php
die("teste"); // Adicione esta linha no topo do arquivo
include '../conn/connect.php';

session_start();

if (!isset($_SESSION['login_usuario'])) {
    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/2495680ceb.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/estilo.css" type="text/css">
    <title>Chuleta Quente - Reserva</title>
</head>

<body>
    <main class="container">
        <section>
            <article>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
                        <h1 class="breadcrumb text-info text-center">Faça sua reserva</h1>
                        <div class="thumbnail">
                            <p class="text-info text-center" role="alert">
                                <i class="fas fa-user-plus fa-10x"></i>
                            </p>
                            <br>
                            <div class="alert alert-info" role="alert">
                                <form action="cliente_insert.php" name="form_cadastro" id="form_cadastro" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="acao" value="reserva">
                                    <label for="nome">Nome:</label>
                                    <p class="input-group">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-user text-info" aria-hidden="true"></span>
                                        </span>
                                        <input type="text" name="nome" id="nome" class="form-control" autofocus required autocomplete="off" placeholder="Digite seu nome">
                                    </p>
                                    <label for="cpf">CPF:</label>
                                    <p class="input-group">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-id-card text-info glyphicon glyphicon-credit-card" aria-hidden="true"></span>
                                        </span>
                                        <input type="text" name="cpf" id="cpf" class="form-control" required autocomplete="off" placeholder="Digite seu CPF">
                                    </p>
                                    <label for="email">Email:</label>
                                    <p class="input-group">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-envelope text-info" aria-hidden="true"></span>
                                        </span>
                                        <input type="email" name="email" id="email" class="form-control" required autocomplete="off" placeholder="Digite seu email">
                                    </p>
                                    <p class="text-right">
                                        <input type="submit" value="Reservar" class="btn btn-primary">
                                    </p>
                                    <br>
                                    <p class="text-right">
                                        <button type="button" class="btn btn-primary" onclick="window.location.href='reserva_cliente.php'"></button>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </section>
    </main>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
</body>

</html>