<?php

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootsstrap.min.css">
    <link rel="stylesheet" href="css/estilo.css">
    <title>Chuleta Quente Churrascaria</title>
</head>

<body class="fundofixo">
    <!-- Area de menu -->
    <?php include 'menu_publico.php' ?>
    <a name="home">&</a>
    <main class="container">
        <!-- Area de carousel -->
        <?php include 'carousel.php' ?>
        <!-- Area de destaque -->
        <a class="pt-6" name="destaques">&nbsp;</a>
        <?php include 'produtos_destaque.php'; ?>
        <!-- Area geral de produtos -->
        <a class="pt-6" name="produtos">&nbsp;</a>
        <?php include 'produtos_geral.php'; ?>
        <!-- rodapé -->
        <footer class="panel-footer" style="background:none;">
            <?php include 'rodape.php'; ?>
            <a name="contato"></a>
        </footer>
    </main>

</body>

</html>