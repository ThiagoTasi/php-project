<?php
session_start(); // Inicie a sessão no topo do arquivo
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/estilo.css">
    <title>Chuleta Quente Churrascaria</title>
</head>

<body class="fundofixo">
    <?php include 'menu_publico.php'; ?>
    <a name="home">&</a>
    <main class="container">
        <?php include 'carousel.php'; ?>
        <a class="pt-6" name="destaques">&nbsp;</a>
        <?php include 'produtos_destaque.php'; ?>
        <a class="pt-6" name="produtos">&nbsp;</a>
        <?php include 'produtos_geral.php'; ?>
        <section class="container text-center bg-info text-white p-4">
            <h2>Reserve sua Mesa e Ganhe Descontos Exclusivos!</h2>
            <p>Faça sua reserva para 5 pessoas ou mais e aproveite:</p>
            <ul>
                <li><strong>70% de desconto</strong> no rodízio do titular da reserva.</li>
                <li><strong>10% de desconto</strong> em todas as bebidas da mesa.</li>
            </ul>
            <?php
            if (isset($_SESSION['login_usuario'])) { // Verifica se o usuário está logado
                if (file_exists('admin/reserva_cliente.php')) { // Verifica se o arquivo existe
                    echo '<a href="admin/reserva_cliente.php" class="btn btn-warning btn-lg">Faça sua Reserva Agora!</a>';
                } else {
                    echo '<p class="text-danger">Arquivo admin/reserva_cliente.php não encontrado!</p>';
                }
            } else {
                echo '<a href="admin/login.php" class="btn btn-warning btn-lg">Faça Login para Reservar!</a>';
            }
            ?>
        </section>
        <footer class="panel-footer" style="background:none;">
            <?php include 'rodape.php'; ?>
            <a name="contato"></a>
        </footer>
    </main>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).on('ready', function() {
        $(".regular").slick({
            dots: true,
            infinity: true,
            slidesToShow: 3,
            slidesToScroll: 3
        });

    });
</script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick.min.js"></script>

</html>