<?php
// insert.php
// Precisa da conexão
include "../conn/connect.php";
include "acesso_com.php";

// POST (SuperGLOBAL)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificando se os campos foram preenchidos
    if (isset($_POST['sigla']) && isset($_POST['rotulo'])) {
        $sigla = $_POST['sigla'];
        $rotulo = ($_POST['rotulo']); // Usando password_hash
 
        // Tentar executar a inserção
        try {
            // Define a consulta SQL para inserir os dados do usuário
            $sql = "INSERT INTO tinsphpdb01.tipos(sigla, rotulo) VALUES (:sigla, :rotulo)";
            // Prepara a consulta para execução
            $stmt = $pdo->prepare($sql);
            // Associa os parâmetros de forma segura
            $stmt->bindParam(':sigla', $sigla);
            $stmt->bindParam(':rotulo', $rotulo);
 
            if ($stmt->execute()) {
                echo "Tipos cadastrado com sucesso!";
                header('location: tipos_lista.php');
            } else {
                echo "Erro ao tipo.";
            }
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    } else {
        echo "Método de requisição inválido.";
    }
}
?>


<!-- html:5 -->
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>Tipos - Insere</title>
    <meta charset="UTF-8">
    <!-- Link arquivos Bootstrap CSS -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <!-- Link para CSS específico -->
    <link rel="stylesheet" href="../css/estilo.css" type="text/css">
</head>

<body>
    <?php include "menu_adm.php"; ?>
    <main class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-offset-3 col-sm-6 col-md-offset-4 col-md-4"><!-- dimensionamento -->
                <h2 class="breadcrumb text-info">
                    <a href="tipos_lista.php">
                        <button class="btn btn-info" type="button">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        </button>
                    </a>
                    Inserindo Tipos
                </h2>
                <div class="thumbnail">
                    <div class="alert alert-info">
                        <form action="tipos_insere.php" name="form_insere_tipos" id="form_insere_tipos" method="POST">
                            <!-- input tipos_inserir -->
                            <label for="login">Rotulo:</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                                </span>
                                <input type="text" name="rotulo" id="rotulo" autofocus maxlength="30" placeholder="Digite seu tipo." class="form-control" required autocomplete="off">
                            </div><!-- fecha input-group -->
                            <br>
                            <!-- fecha input login -->

                            <!-- input senha -->
                            <label for="senha">Sigla:</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-qrcode" aria-hidden="true"></span>
                                </span>
                                <input type="text" name="sigla" id="sigla" maxlength="8" placeholder="Digite a sigla." class="form-control" required autocomplete="off">
                            </div><!-- fecha input-group -->
                            <br>
                            <!-- fecha input senha -->

                            <!-- btn enviar -->
                            <input type="submit" value="Cadastrar" role="button" name="enviar" id="enviar" class="btn btn-block btn-info">
                        </form>
                    </div><!-- fecha alert -->
                </div><!-- fecha thumbnail -->
            </div><!-- Fecha dimensionamento -->
        </div><!-- Fecha row -->
    </main>

    <!-- Link arquivos Bootstrap js -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
</body>

</html>